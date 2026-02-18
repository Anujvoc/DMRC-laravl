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
        ->leftJoin('module_totals', 'modules.id', '=', 'module_totals.module_id')
        ->select(
            'modules.id',
            'modules.subject',
            'modules.summary_title',
            'modules.module_doc_no',
            'modules.rev_no',
            'modules.date2',
            'categories.title as category_title',
            'module_totals.total_sessions'
        )
        ->orderBy('modules.id','desc')
        ->get();

    return view('admin.pages.module_management.module_master.index', compact('modules'));
}


public function create()
{
    $categories = DB::table('categories')
        ->orderBy('title', 'asc')
        ->get();

    return view('admin.pages.module_management.module_master.create', compact('categories'));
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
            'subject' => 'required|string|max:255',
            'category_id' => 'nullable|integer',
            'module_doc_no' => 'nullable|string|max:255',
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

        // Insert module
        $moduleId = DB::table('modules')->insertGetId([
            'summary_title'     => $request->summary_title,
            'subject'           => $request->subject,
            'category_id'       => $request->category_id,
            'module_doc_no'     => $request->module_doc_no,
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

    return view('admin.pages.module_management.module_master.edit', compact('module', 'categories'));
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
            'subject' => 'required|max:255',
            'category_id' => 'nullable|integer',
            'module_doc_no' => 'nullable|string|max:255',
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

        \Log::info("Updating modules table...");

        $rows = DB::table('modules')->where('id',$id)->update([
            'summary_title' => $request->summary_title,
            'subject' => $request->subject,
            'category_id' => $request->category_id,
            'module_doc_no' => $request->module_doc_no,
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


public function exportWord()
{
    Log::info('========== WORD EXPORT START ==========');

    try {

        if (!class_exists(\ZipArchive::class)) {
            Log::error('ZipArchive missing (PHP zip extension not enabled)');
            return back()->with('error', 'Word export requires the PHP "zip" extension (ZipArchive). Enable it in php.ini (extension=zip) and restart the server.');
        }

        // Step 1 – Fetch module
        Log::info('Fetching latest module...');
        $module = DB::table('modules')
            ->leftJoin('module_totals','modules.id','=','module_totals.module_id')
            ->select(
                'modules.*',
                'module_totals.total_sessions',
                'module_totals.no_of_days'
            )
            ->latest('modules.id')
            ->first();

        if(!$module){
            Log::error('No module found');
            return back()->with('error','No module found');
        }

        Log::info('Module loaded', (array)$module);

        // Step 2 – Fetch contents
        Log::info('Fetching module contents...');
        $contents = DB::table('module_contents')
            ->where('module_id',$module->id)
            ->get();

        Log::info('Contents count: '.count($contents));

        // Step 3 – Load template
        $templateCandidates = [
            storage_path('app/templates/QFF64.docx'),
            storage_path('app/templates/training_module.docx'),
        ];

        $templatePath = null;
        foreach ($templateCandidates as $candidate) {
            if (file_exists($candidate)) {
                $templatePath = $candidate;
                break;
            }
        }

        Log::info('Template selected: '.($templatePath ?? 'NONE'));

        if (!$templatePath) {
            $docPath = storage_path('app/templates/training_module.doc');
            if (file_exists($docPath)) {
                Log::error('Found .doc template but no .docx template');
                return back()->with('error', 'TemplateProcessor requires a .docx template. Please convert this file to .docx and save it as: storage/app/templates/training_module.docx');
            }

            Log::error('Template file missing!');
            return back()->with('error', 'Template file not found. Expected: storage/app/templates/QFF64.docx or storage/app/templates/training_module.docx');
        }

        $template = new TemplateProcessor($templatePath);
        Log::info('Template loaded');

        $templateVars = $template->getVariables();
        Log::info('Template variables', ['count' => count($templateVars), 'vars' => $templateVars]);

        $setIfExists = function (string $key, $value) use ($template, $templateVars) {
            if (in_array($key, $templateVars, true)) {
                $template->setValue($key, $value);
                return true;
            }
            return false;
        };

        // ===============================
        // Header fields
        // ===============================
        Log::info('Injecting header fields...');
        $setIfExists('docno', $module->module_doc_no ?? '');
        $setIfExists('subject', $module->subject ?? '');
        $setIfExists('summary', $module->summary_title ?? '');
        $setIfExists('revno', $module->rev_no ?? '');
        $setIfExists('date', $module->form_date ? date('d.m.Y', strtotime($module->form_date)) : '');
        $setIfExists('date2', $module->date2 ? date('d.m.Y', strtotime($module->date2)) : '');

        // ===============================
        // Competency
        // ===============================
        Log::info('Setting competency checkboxes...');
        $setIfExists('domain', $module->mapped_competency == 'Domain' ? '✔' : '');
        $setIfExists('functional', $module->mapped_competency == 'Functional' ? '✔' : '');
        $setIfExists('behavioral', $module->mapped_competency == 'Behavioral' ? '✔' : '');

        // ===============================
        // Totals
        // ===============================
        $setIfExists('totalsessions', $module->total_sessions ?? '');
        $setIfExists('days', $module->no_of_days ?? '');

        // ===============================
        // Table rows
        // ===============================
        Log::info('Generating training rows...');
        $requiredRowVars = ['sl', 'content', 'sessions', 'page'];
        $missingRowVars = array_values(array_diff($requiredRowVars, $templateVars));

        if (!empty($missingRowVars)) {
            Log::warning('Template row variables missing; skipping training rows', ['missing' => $missingRowVars]);
        } elseif (count($contents) === 0) {
            Log::info('No module contents; skipping training rows');
        } else {
            $template->cloneRow('sl', count($contents));

            foreach ($contents as $i => $row) {
                $n = $i + 1;
                $template->setValue("sl#$n", $n);
                $template->setValue("content#$n", $row->topic ?? '');
                $template->setValue("sessions#$n", $row->sessions ?? '');
                $template->setValue("page#$n", $row->page_no ?? '');
            }
        }

        // ===============================
        // Save file
        // ===============================
        $dir = storage_path('app/temp');
        if(!file_exists($dir)){
            mkdir($dir, 0777, true);
            Log::info('Temp directory created');
        }

        $fileName = 'DMRCA_QFF64_'.$module->module_doc_no.'.docx';
        $path = $dir.'/'.$fileName;

        Log::info('Saving file to '.$path);
        $template->saveAs($path);

        if(!file_exists($path)){
            Log::error('File not created after save');
            return back()->with('error','File generation failed');
        }

        Log::info('File created successfully');
        Log::info('========== WORD EXPORT SUCCESS ==========');

        return response()->download($path)->deleteFileAfterSend();

    } catch (\Throwable $e) {

        Log::error('========== WORD EXPORT FAILED ==========');
        Log::error('Message: '.$e->getMessage());
        Log::error('File: '.$e->getFile());
        Log::error('Line: '.$e->getLine());

        return back()->with('error','Export failed. Check logs.');
    }
}


}
