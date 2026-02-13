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
        $modules = DB::table('training_modules')->get();
        return view('admin.pages.module_management.module_master.index', compact('modules'));
    }

    /**
     * Show the form for creating a new module.
     */
    public function create()
    {
        return view('admin.pages.module_management.module_master.create');
    }

    /**
     * Store a newly created module in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'module_name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'version' => 'nullable|string|max:255',
            'total_sessions' => 'nullable|integer|min:1',
            'status' => 'required|in:Planned,In Progress,Completed',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'duration_days' => 'required|integer|min:1',
            'sequence' => 'nullable|integer|min:1',
            'mapped_domain' => 'nullable|boolean',
            'mapped_functional' => 'nullable|boolean',
            'mapped_behavioral' => 'nullable|boolean',
            'module_objectives' => 'nullable|string|max:1000',
            'prerequisites' => 'nullable|array',
            'topics' => 'nullable|array',
            'subtopics' => 'nullable|array'
        ]);

        DB::table('training_modules')->insert([
            'topic_id' => $request->topic_id ?? 0,
            'subtopic_id' => $request->subtopic_id ?? 0,
            'batch_id' => $request->batch_id ?? 0,
            'module_name' => $request->module_name,
            'module_code' => $request->module_code ?? 'MOD-' . date('YmdHis'),
            'subject' => $request->subject,
            'version' => $request->version,
            'total_sessions' => $request->total_sessions,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'duration_days' => $request->duration_days,
            'sequence' => $request->sequence ?? 0,
            'mapped_domain' => $request->mapped_domain ? 1 : 0,
            'mapped_functional' => $request->mapped_functional ? 1 : 0,
            'mapped_behavioral' => $request->mapped_behavioral ? 1 : 0,
            'module_objectives' => $request->module_objectives,
            'no_of_days' => $request->duration_days,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Module added successfully!');
    }

    /**
     * Show the form for editing the specified module.
     */
    public function edit($id)
    {
        $module = DB::table('training_modules')->where('id', $id)->first();
        return view('admin.pages.module_management.module_master.edit', compact('module'));
    }

    /**
     * Update the specified module in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'module_code' => 'required|string|max:50|unique:training_modules,module_code,'.$id,
            'module_name' => 'required|string|max:255',
            'module_type' => 'required|in:theory,practical,mixed',
            'duration' => 'required|string|max:100',
            'is_active' => 'required|boolean',
            'prerequisites' => 'nullable|string',
            'description' => 'nullable|string|max:1000'
        ]);

        DB::table('training_modules')->where('id', $id)->update([
            'module_code' => $request->module_code,
            'module_name' => $request->module_name,
            'module_type' => $request->module_type,
            'duration' => $request->duration,
            'is_active' => $request->is_active,
            'prerequisites' => $request->prerequisites,
            'description' => $request->description,
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Module updated successfully!');
    }

    /**
     * Remove the specified module from storage.
     */
    public function destroy($id)
    {
        DB::table('training_modules')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Module deleted successfully!');
    }

    /**
     * Get modules for API requests.
     */
    public function getModules()
    {
        $modules = DB::table('training_modules')->get();
        return response()->json($modules);
    }
}
