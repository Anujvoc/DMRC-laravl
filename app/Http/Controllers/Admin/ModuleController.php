<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModuleController extends Controller
{
    /**
     * Display a listing of modules.
     */
    public function index()
    {
        $modules = DB::table('module_master')->get();
        return view('admin.pages.module_management.module_master.index', compact('modules'));
    }

    /**
     * Show the form for creating a new module.
     */
    public function create()
    {
        $topics = DB::table('topics')->get();
        return view('admin.pages.module_management.module_master.create', compact('topics'));
    }

    /**
     * Store a newly created module in storage.
     */
public function store(Request $request)
{
    \Log::info('========== MODULE STORE START ==========');
    \Log::info('Incoming Request Data:', $request->all());

    $validated = $request->validate([
        'module_name' => 'required|string|max:255',
        'module_code' => 'nullable|string|max:100',
        'subject' => 'required|string|max:255',
        'version' => 'nullable|string|max:50',
        'total_sessions' => 'nullable|integer|min:1',
        'sequence' => 'nullable|integer|min:1',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'duration_days' => 'required|integer|min:1',
        'status' => 'required|in:Planned,In Progress,Completed',
        'mapped_domain' => 'nullable|boolean',
        'mapped_functional' => 'nullable|boolean',
        'mapped_behavioral' => 'nullable|boolean',
        'mapped_other' => 'nullable|boolean',
        'module_objectives' => 'nullable|string',
        'prerequisites' => 'nullable|array',
        'topics' => 'nullable|array',
        'subtopics' => 'nullable|array'
    ]);

    DB::beginTransaction();
    \Log::info('Transaction Started');

    try {
        // Step 1: Insert module into module_master
        $moduleId = DB::table('module_master')->insertGetId([
            'module_name' => $request->module_name,
            'module_code' => $request->module_code,
            'subject' => $request->subject,
            'version' => $request->version,
            'total_sessions' => $request->total_sessions,
            'sequence' => $request->sequence,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'duration_days' => $request->duration_days,
            'status' => $request->status,
            'mapped_domain' => $request->has('mapped_domain') ? 1 : 0,
            'mapped_functional' => $request->has('mapped_functional') ? 1 : 0,
            'mapped_behavioral' => $request->has('mapped_behavioral') ? 1 : 0,
            'mapped_other' => $request->has('mapped_other') ? 1 : 0,
            'module_objectives' => $request->module_objectives,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        \Log::info('Module Inserted Successfully', ['module_id' => $moduleId]);

        // Step 2: Save prerequisites
        if (!empty($request->prerequisites)) {
            foreach ($request->prerequisites as $prereqId) {
                if (!empty($prereqId)) {
                    DB::table('module_prerequisites')->insert([
                        'module_id' => $moduleId,
                        'prerequisite_module_id' => $prereqId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    \Log::info('Prerequisite Inserted', [
                        'module_id' => $moduleId,
                        'prerequisite_module_id' => $prereqId
                    ]);
                }
            }
        }

        // Step 3: Save topics and subtopics
        if (!empty($request->topics)) {
            foreach ($request->topics as $index => $topicId) {
                $subtopicId = $request->subtopics[$index] ?? null;

                if (!empty($topicId)) {
                    DB::table('module_topics')->insert([
                        'module_id' => $moduleId,
                        'topic_id' => $topicId,
                        'subtopic_id' => $subtopicId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    \Log::info('Topic Inserted', [
                        'module_id' => $moduleId,
                        'topic_id' => $topicId,
                        'subtopic_id' => $subtopicId
                    ]);
                }
            }
        }

        DB::commit();
        \Log::info('Transaction Committed Successfully');
        \Log::info('========== MODULE STORE END ==========');

        return redirect()->back()->with('success', 'Module added successfully!');
    } catch (\Exception $e) {
        DB::rollBack();

        \Log::error('MODULE STORE FAILED');
        \Log::error('Error Message: ' . $e->getMessage());
        \Log::error('Error Line: ' . $e->getLine());
        \Log::error('Error File: ' . $e->getFile());

        return redirect()->back()->with('error', 'Failed to add module. Check logs.');
        }
    }

    /**
     * Show the form for editing the specified module.
     */
   public function edit($module_id)
{
    try {
        \Log::info('Edit method called with module_id: ' . $module_id);

        $module = DB::table('module_master')->where('module_id', $module_id)->first();

        if (!$module) {
            return redirect()->back()->with('error', 'Module not found');
        }

        $prerequisites = DB::table('module_prerequisites')
            ->where('module_id', $module_id)
            ->pluck('prerequisite_module_id')
            ->toArray();

        $moduleTopics = DB::table('module_topics')
            ->where('module_id', $module_id)
            ->get()
            ->toArray();

        $topics = DB::table('topics')->get();

        return view('admin.pages.module_management.module_master.edit', compact(
            'module', 'prerequisites', 'moduleTopics', 'topics'
        ));
    } catch (\Exception $e) {
        \Log::error('Edit method error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error loading edit page: ' . $e->getMessage());
    }
}


    /**
     * Update the specified module in storage.
     */
    public function update(Request $request, $id)
    {
        \Log::info('========== MODULE UPDATE START ==========');
        \Log::info('Module ID:', ['id' => $id]);
        \Log::info('Incoming Request Data:', $request->all());

        $validated = $request->validate([
            'module_name' => 'required|string|max:255',
            'module_code' => 'nullable|string|max:100',
            'subject' => 'required|string|max:255',
            'version' => 'nullable|string|max:50',
            'total_sessions' => 'nullable|integer|min:1',
            'sequence' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'duration_days' => 'required|integer|min:1',
            'status' => 'required|in:Planned,In Progress,Completed',
            'mapped_domain' => 'nullable|boolean',
            'mapped_functional' => 'nullable|boolean',
            'mapped_behavioral' => 'nullable|boolean',
            'mapped_other' => 'nullable|boolean',
            'module_objectives' => 'nullable|string',
            'prerequisites' => 'nullable|array',
            'topics' => 'nullable|array',
            'subtopics' => 'nullable|array'
        ]);

        DB::beginTransaction();
        \Log::info('Transaction Started');

        try {
            // Step 1: Update module in module_master
            DB::table('module_master')->where('module_id', $id)->update([
                'module_name' => $request->module_name,
                'module_code' => $request->module_code,
                'subject' => $request->subject,
                'version' => $request->version,
                'total_sessions' => $request->total_sessions,
                'sequence' => $request->sequence,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'duration_days' => $request->duration_days,
                'status' => $request->status,
                'mapped_domain' => $request->has('mapped_domain') ? 1 : 0,
                'mapped_functional' => $request->has('mapped_functional') ? 1 : 0,
                'mapped_behavioral' => $request->has('mapped_behavioral') ? 1 : 0,
                'mapped_other' => $request->has('mapped_other') ? 1 : 0,
                'module_objectives' => $request->module_objectives,
                'updated_at' => now()
            ]);

            \Log::info('Module Updated Successfully');

            // Step 2: Delete existing prerequisites
            DB::table('module_prerequisites')->where('module_id', $id)->delete();

            // Step 3: Save new prerequisites
            if (!empty($request->prerequisites)) {
                foreach ($request->prerequisites as $prereqId) {
                    if (!empty($prereqId)) {
                        DB::table('module_prerequisites')->insert([
                            'module_id' => $id,
                            'prerequisite_module_id' => $prereqId,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }

            // Step 4: Delete existing topics
            DB::table('module_topics')->where('module_id', $id)->delete();

            // Step 5: Save new topics and subtopics
            if (!empty($request->topics)) {
                foreach ($request->topics as $index => $topicId) {
                    $subtopicId = $request->subtopics[$index] ?? null;

                    if (!empty($topicId)) {
                        DB::table('module_topics')->insert([
                            'module_id' => $id,
                            'topic_id' => $topicId,
                            'subtopic_id' => $subtopicId,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }

            DB::commit();
            \Log::info('Transaction Committed Successfully');
            \Log::info('========== MODULE UPDATE END ==========');

            return redirect()->back()->with('success', 'Module updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('MODULE UPDATE FAILED');
            \Log::error('Error Message: ' . $e->getMessage());
            \Log::error('Error Line: ' . $e->getLine());
            \Log::error('Error File: ' . $e->getFile());

            return redirect()->back()->with('error', 'Failed to update module. Check logs.');
        }
    }

    /**
     * Remove the specified module from storage.
     */
    public function destroy($module_id)
    {
        try {
            \Log::info('========== MODULE DELETE START ==========');
            \Log::info('Module ID:', ['module_id' => $module_id]);

            DB::beginTransaction();

            // Step 1: Delete module prerequisites
            $deletedPrereqs = DB::table('module_prerequisites')
                ->where('module_id', $module_id)
                ->delete();
            
            \Log::info('Prerequisites deleted:', ['count' => $deletedPrereqs]);

            // Step 2: Delete module topics
            $deletedTopics = DB::table('module_topics')
                ->where('module_id', $module_id)
                ->delete();
            
            \Log::info('Topics deleted:', ['count' => $deletedTopics]);

            // Step 3: Delete the main module
            $deletedModule = DB::table('module_master')
                ->where('module_id', $module_id)
                ->delete();
            
            \Log::info('Module deleted:', ['count' => $deletedModule]);

            DB::commit();
            \Log::info('========== MODULE DELETE END ==========');

            return redirect()->back()->with('success', 'Module deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('MODULE DELETE FAILED');
            \Log::error('Error Message: ' . $e->getMessage());
            \Log::error('Error Line: ' . $e->getLine());
            \Log::error('Error File: ' . $e->getFile());

            return redirect()->back()->with('error', 'Failed to delete module. Check logs.');
        }
    }
}
