<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;









class ModuleController extends Controller
{
    /**
     * Display a listing of modules.
     */
  public function index()
{
    $modules = DB::table('modules')
        ->leftJoin('categories', 'modules.category_id', '=', 'categories.id')
        ->leftJoin('certifications', 'modules.certification_id', '=', 'certifications.id')
        ->leftJoin('module_totals', 'modules.id', '=', 'module_totals.module_id')
        ->select(
            'modules.id',
            'modules.subject',
            'modules.summary_title',
            'modules.doc_no',
            'modules.rev_no',
            'modules.date2',
            'categories.title as category_title',
            'certifications.iso_standard as certification_title',
            'module_totals.total_sessions'
        )
        ->orderBy('modules.id','desc')
        ->get();

    $allSubjectIds = [];
    foreach ($modules as $module) {
        $raw = (string) ($module->subject ?? '');
        if ($raw !== '' && preg_match('/^[0-9,\s]+$/', $raw)) {
            $ids = array_filter(array_map('trim', explode(',', $raw)));
            foreach ($ids as $id) {
                if (ctype_digit($id)) {
                    $allSubjectIds[] = (int) $id;
                }
            }
        }
    }

    $subjectMap = [];
    if (!empty($allSubjectIds)) {
        $subjectMap = DB::table('subject')
            ->whereIn('id', array_values(array_unique($allSubjectIds)))
            ->pluck('title', 'id')
            ->toArray();
    }

    foreach ($modules as $module) {
        $raw = (string) ($module->subject ?? '');
        if ($raw === '') {
            $module->subject_titles = '';
            continue;
        }

        // Legacy support: if it's not CSV of IDs, display as-is.
        if (!preg_match('/^[0-9,\s]+$/', $raw)) {
            $module->subject_titles = $raw;
            continue;
        }

        $ids = array_filter(array_map('trim', explode(',', $raw)));
        $titles = [];
        foreach ($ids as $id) {
            if (ctype_digit($id) && isset($subjectMap[(int) $id])) {
                $titles[] = $subjectMap[(int) $id];
            }
        }
        $module->subject_titles = implode(', ', $titles);
    }

    return view('admin.pages.module_management.module_master.index', compact('modules'));
}


public function create()
{
    $categories = DB::table('categories')
        ->orderBy('title', 'asc')
        ->get();

    $certifications = DB::table('certifications')
        ->orderBy('iso_standard', 'asc')
        ->get();

    $subjects = DB::table('subject')
        ->orderBy('title', 'asc')
        ->get();

    return view('admin.pages.module_management.module_master.create', compact('categories', 'certifications', 'subjects'));
}


    /**
     * Show the form for creating a new module.
     */
public function store(Request $request)
{
    \Log::info('========== MODULE STORE START ==========');
    \Log::info('Incoming Request:', $request->all());

    try {
        \Log::info('Validating request...');
        $request->validate([
            'summary_title' => 'required|string|max:255',
            'subject_ids' => 'required|array|min:1',
            'subject_ids.*' => 'integer',
            'category_id' => 'nullable|integer',
            'certification_id' => 'nullable|integer',
            'doc_no' => 'nullable|string|max:255',
            'rev_no' => 'required|string|max:20',
            'form_date' => 'required|date',
            'date2' => 'nullable|date',
            'mapped_competency' => 'required|in:Domain,Functional,Behavioral,All Competencies',
            'total_sessions' => 'required|integer|min:1',
            'no_of_days' => 'required|integer|min:1'
        ]);
        \Log::info('Validation passed');
    } catch (\Exception $e) {
        \Log::error('Validation error: '.$e->getMessage());
        return back()->with('error','Validation failed');
    }

    DB::beginTransaction();
    \Log::info('Transaction started');

    try {

        $subjectIds = array_map('intval', (array) $request->subject_ids);
        $subjectIds = array_values(array_unique(array_filter($subjectIds)));

        // Insert module
        $moduleId = DB::table('modules')->insertGetId([
            'summary_title'     => $request->summary_title,
            'subject'           => implode(',', $subjectIds),
            'category_id'       => $request->category_id,
            'certification_id'  => $request->certification_id,
            'doc_no'            => $request->doc_no,
            'rev_no'            => $request->rev_no,
            'form_date'         => $request->form_date,
            'date2'             => $request->date2,
            'mapped_competency' => $request->mapped_competency,
            'created_at'        => now(),
            'updated_at'        => now()
        ]);

        \Log::info('Module inserted. ID = '.$moduleId);

        // Insert totals (IMPORTANT: cast to int)
        DB::table('module_totals')->insert([
            'module_id'     => (int) $moduleId,
            'total_sessions' => (int) $request->total_sessions,
            'no_of_days'     => (int) $request->no_of_days,
            'created_at'    => now(),
            'updated_at'    => now()
        ]);

        \Log::info('module_totals inserted');

        DB::commit();
        \Log::info('========== MODULE STORE SUCCESS ==========');

        return redirect()->back()->with('success','Module created successfully');

    } catch (\Exception $e) {
        DB::rollBack();

        \Log::error('========== MODULE STORE FAILED ==========');
        \Log::error('Message: '.$e->getMessage());
        \Log::error('File: '.$e->getFile());
        \Log::error('Line: '.$e->getLine());

        return redirect()->back()->with('error','Save failed. Check logs.');
    }
}





    /**
     * Update the specified module in storage.
     */
public function edit($id)
{
    $module = DB::table('modules')
        ->leftJoin('module_totals','modules.id','=','module_totals.module_id')
        ->select(
            'modules.*',
            'module_totals.total_sessions',
            'module_totals.no_of_days'
        )
        ->where('modules.id',$id)
        ->first();

    if(!$module){
        return redirect()->back()->with('error','Module not found');
    }

    $categories = DB::table('categories')
        ->orderBy('title', 'asc')
        ->get();

    $certifications = DB::table('certifications')
        ->orderBy('iso_standard', 'asc')
        ->get();

    $subjects = DB::table('subject')
        ->orderBy('title', 'asc')
        ->get();

    $selectedSubjectIds = array_filter(array_map('trim', explode(',', (string) ($module->subject ?? ''))));
    $selectedSubjectIds = array_values(array_filter($selectedSubjectIds, function ($id) {
        return ctype_digit((string) $id);
    }));

    return view('admin.pages.module_management.module_master.edit', compact('module', 'categories', 'certifications', 'subjects', 'selectedSubjectIds'));
}


public function update(Request $request, $id)
{
    \Log::info("========== MODULE UPDATE START ==========");
    \Log::info("Module ID: ".$id);
    \Log::info("Incoming Data:", $request->all());

    try {

        \Log::info("Validating request...");

        $request->validate([
            'summary_title' => 'required|max:255',
            'subject_ids' => 'required|array|min:1',
            'subject_ids.*' => 'integer',
            'category_id' => 'nullable|integer',
            'certification_id' => 'nullable|integer',
            'doc_no' => 'nullable|string|max:255',
            'rev_no' => 'required|max:20',
            'form_date' => 'required|date',
            'date2' => 'nullable|date',
            'mapped_competency' => 'required|in:Domain,Functional,Behavioral,All Competencies',
            'total_sessions' => 'required|integer|min:1',
            'no_of_days' => 'required|integer|min:1'
        ]);

        \Log::info("Validation passed");

    } catch (\Exception $e) {
        \Log::error("VALIDATION FAILED: ".$e->getMessage());
        return back()->with('error','Validation failed. Check logs.');
    }

    DB::beginTransaction();
    \Log::info("Transaction started");

    try {

        $subjectIds = array_map('intval', (array) $request->subject_ids);
        $subjectIds = array_values(array_unique(array_filter($subjectIds)));

        \Log::info("Updating modules table...");

        $rows = DB::table('modules')->where('id',$id)->update([
            'summary_title' => $request->summary_title,
            'subject' => implode(',', $subjectIds),
            'category_id' => $request->category_id,
            'certification_id' => $request->certification_id,
            'doc_no' => $request->doc_no,
            'rev_no' => $request->rev_no,
            'form_date' => $request->form_date,
            'date2' => $request->date2,
            'mapped_competency' => $request->mapped_competency,
            'updated_at' => now()
        ]);

        \Log::info("Modules rows affected: ".$rows);

        \Log::info("Updating module_totals table...");

        $rows2 = DB::table('module_totals')->where('module_id',$id)->update([
            'total_sessions' => $request->total_sessions,
            'no_of_days' => $request->no_of_days,
            'updated_at' => now()
        ]);

        \Log::info("Totals rows affected: ".$rows2);

        DB::commit();
        \Log::info("Transaction committed");
        \Log::info("========== MODULE UPDATE SUCCESS ==========");

        return back()->with('success','Module updated successfully');

    } catch (\Exception $e) {

        DB::rollBack();

        \Log::error("========== MODULE UPDATE FAILED ==========");
        \Log::error("Message: ".$e->getMessage());
        \Log::error("File: ".$e->getFile());
        \Log::error("Line: ".$e->getLine());

        return back()->with('error','Update failed. Check logs.');
    }
}




    /**
     * Remove the specified module from storage.
     */
public function destroy($id)
{
    DB::beginTransaction();
    try {

        DB::table('module_contents')->where('module_id',$id)->delete();
        DB::table('module_totals')->where('module_id',$id)->delete();
        DB::table('modules')->where('id',$id)->delete();

        DB::commit();
        return redirect()->back()->with('success','Module deleted');

    } catch (\Exception $e){
        DB::rollBack();
        return redirect()->back()->with('error',$e->getMessage());
    }
}


public function exportWord($id)
{
    Log::info("===== WORD EXPORT START for module $id =====");

    try {

        // Load module with relations
        $module = DB::table('modules')
            ->leftJoin('module_totals','modules.id','=','module_totals.module_id')
            ->leftJoin('categories','modules.category_id','=','categories.id')
            ->leftJoin('certifications','modules.certification_id','=','certifications.id')
            ->select(
                'modules.*',
                'module_totals.total_sessions',
                'module_totals.no_of_days',
                'categories.title as category_title',
                'certifications.iso_standard as certification_title'
            )
            ->where('modules.id',$id)
            ->first();

        if(!$module){
            Log::error("Module not found");
            return back()->with('error','Module not found');
        }

        Log::info("Module Loaded", (array)$module);

        // Load template
        $templatePath = storage_path('app/templates/training_module.docx');

        if(!file_exists($templatePath)){
            Log::error("Template not found: ".$templatePath);
            return back()->with('error','Template missing');
        }

        $template = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        // ===============================
        // FIRST PAGE – HEADER
        // ===============================
        $template->setValue('docno', $module->doc_no ?? 'QFF/DMRC Academy/MR/064');
        $template->setValue('academy', $module->academy_name ?? 'DMRC ACADEMY');
        $template->setValue('location', $module->location ?? 'Metro Train Depot, Shastri Park, Delhi-53');
        $template->setValue('formdate', $module->form_date ? date('d.m.Y',strtotime($module->form_date)) : '');
        $template->setValue('date2', $module->date2 ? date('d.m.Y',strtotime($module->date2)) : '');
        $template->setValue('revno', $module->rev_no ?? '');
        $template->setValue('iso', $module->certification_title ?? 'ISO 9001:2015');

        // ✅ CATEGORY FROM categories table
        $template->setValue('category', $module->category_title ?? '');

        // ✅ VERSION (using rev_no)
        $template->setValue('version', $module->rev_no ?? '');

        // ===============================
        // SUBJECT + SUMMARY
        // ===============================
        $subjectValue = (string) ($module->subject ?? '');

        if ($subjectValue !== '' && preg_match('/^[0-9,\s]+$/', $subjectValue)) {
            $ids = array_filter(array_map('trim', explode(',', $subjectValue)));
            $ids = array_map('intval',$ids);

            if(!empty($ids)){
                $titles = DB::table('subject')
                    ->whereIn('id',$ids)
                    ->pluck('title')
                    ->toArray();

                $subjectValue = implode(', ',$titles);
            }
        }

        $template->setValue('subject', $subjectValue);
        $template->setValue('summary', $module->summary_title);

        // ===============================
        // COMPETENCY
        // ===============================
        $template->setValue('domain',     $module->mapped_competency=='Domain'?'✔':'');
        $template->setValue('functional', $module->mapped_competency=='Functional'?'✔':'');
        $template->setValue('behavioral', $module->mapped_competency=='Behavioral'?'✔':'');

        // ===============================
        // TOTALS
        // ===============================
        $template->setValue('totalsessions', $module->total_sessions ?? '');
        $template->setValue('days', $module->no_of_days ?? '');

        // ===============================
        // SUBJECTS, TOPICS, AND SUBTOPICS
        // ===============================
        
        // Load subjects for this module
 // ===============================
// SUBJECT → TOPIC → SUBTOPIC (TABLE)
// ===============================

$subjectIds = [];
$topicIds = [];
$subtopicIds = [];

// Parse subject IDs from module
if(!empty($module->subject) && preg_match('/^[0-9,\s]+$/',$module->subject)){
    $subjectIds = array_map('intval', array_filter(explode(',', $module->subject)));
}

// Parse topic IDs from module (if separate field exists)
if(!empty($module->topic) && preg_match('/^[0-9,\s]+$/',$module->topic)){
    $topicIds = array_map('intval', array_filter(explode(',', $module->topic)));
}

// Parse subtopic IDs from module (if separate field exists)
if(!empty($module->subtopic) && preg_match('/^[0-9,\s]+$/',$module->subtopic)){
    $subtopicIds = array_map('intval', array_filter(explode(',', $module->subtopic)));
}

// Initialize variables
// ===============================
// SUBJECT → TOPIC → SUBTOPIC
// ===============================

// ===============================
// SUBJECT → TOPIC → SUBTOPIC
// ===============================

$row_no = '';
$subject_name = '';
$topic_name = '';
$subtopic_name = '';
$sessions = '';
$totalSessions = 0;

$rows = collect();
$subjectIds = [];

// Step 1: Detect if subject is numeric IDs or text
if (!empty($module->subject)) {

    // Case 1: subject contains numeric IDs
    if (preg_match('/^[0-9,\s]+$/', $module->subject)) {

        $subjectIds = array_map('intval', explode(',', $module->subject));

    } else {

        // Case 2: subject contains title text
        $subject = DB::table('subject')
            ->where('title', $module->subject)
            ->first();

        if ($subject) {
            $subjectIds[] = $subject->id;
        }
    }
}

// Step 2: Fetch topics + subtopics
if (!empty($subjectIds)) {

$rows = DB::table('topics')
    ->join('subject', 'topics.subject_id', '=', 'subject.id')
    ->leftJoin('subtopics', 'subtopics.topic_id', '=', 'topics.topic_id')
    ->select(
        'subject.title as subject_name',
        'topics.topic as topic_name',
        'subtopics.subtopic as subtopic_name',
        'subtopics.sessions'
    )
    ->get();

Log::info("TEST ROWS:", $rows->toArray());

}

// Step 3: Build data
if ($rows->count() > 0) {

    $rowNumbers = [];
    $subjectArr = [];
    $topicArr = [];
    $subtopicArr = [];
    $sessionArr = [];

    foreach ($rows as $index => $row) {

        $rowNumbers[] = $index + 1;
        $subjectArr[] = $row->subject_name;
        $topicArr[] = $row->topic_name;
        $subtopicArr[] = $row->subtopic_name;
        $sessionArr[] = $row->sessions;

        $totalSessions += (int) $row->sessions;
    }

    $row_no = implode("\n", $rowNumbers);
    $subject_name = implode("\n", $subjectArr);
    $topic_name = implode("\n", $topicArr);
    $subtopic_name = implode("\n", $subtopicArr);
    $sessions = implode("\n", $sessionArr);
}


// Now replace simple variables (can be anywhere in doc)

$template->setValue('row_no', $row_no);
$template->setValue('subject_name', $subject_name);
$template->setValue('topic_name', $topic_name);
$template->setValue('subtopic_name', $subtopic_name);
$template->setValue('sessions', $sessions);
$template->setValue('total_subtopic_sessions', $totalSessions);




// ===============================
// SAVE FILE
// ===============================
$cleanDocNo = preg_replace('/[^A-Za-z0-9_\-]/', '_', $module->doc_no);
$filename = 'QFF64_'.$cleanDocNo.'.docx';

$savePath = storage_path('app/temp/'.$filename);

if(!file_exists(storage_path('app/temp'))){
    mkdir(storage_path('app/temp'),0777,true);
}

$template->saveAs($savePath);

Log::info("Word created: ".$savePath);

return response()->download($savePath)->deleteFileAfterSend();

} // 👈 closes TRY block

catch(\Exception $e){
    Log::error("WORD EXPORT FAILED");
    Log::error($e->getMessage());
    Log::error($e->getFile()." ".$e->getLine());
    return back()->with('error','Export failed – check logs');
}

}



}
