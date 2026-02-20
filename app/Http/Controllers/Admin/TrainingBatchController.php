<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainingBatchController extends Controller
{
    /**
     * Display a listing of the training batches.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get filter status from request
        $statusFilter = request('status', 'all');
        
        // Build query
        $query = DB::table('training_batches as tb')
            ->leftJoin('companies', 'tb.batch_code_company', '=', 'companies.company_id')
            ->leftJoin('designations', 'tb.batch_code_designation', '=', 'designations.designation_id')
            ->leftJoin('module_master', 'tb.module_master_id', '=', 'module_master.module_id')
            ->leftJoin('modules as m', 'module_master.module_id', '=', 'm.id')
            ->leftJoin('training_types', 'tb.training_id', '=', 'training_types.training_type_id')
           ->select(
    'tb.*',
    'companies.company_name',
    'm.summary_title',
    'designations.designation_name',
    'module_master.module_name as module_name',
    'training_types.training_type_name',
    'training_types.training_type_id'
);

        
        // Apply status filter if not 'all'
        if ($statusFilter !== 'all') {
            $query->where('tb.status', $statusFilter);
        }
        
        // Get batches
        $batches = $query->orderBy('tb.created_at', 'desc')->get();
        
        return view('admin.pages.training_batch_management.manage_batch.index', compact('batches', 'statusFilter'));
    }

    /**
     * Show the form for creating a new training batch.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get dropdown data
        $companies = DB::table('companies')->where('is_active', 1)->orderBy('company_name', 'asc')->get();
        $designations = DB::table('designations')->where('is_active', 1)->orderBy('designation_name', 'asc')->get();
       // Get modules for dropdown - simple approach
$modules = DB::table('modules')
    ->orderBy('summary_title', 'asc')
    ->get([
        'id',
        'summary_title'
    ]);


// Add summary_title from modules table if available
foreach ($modules as $module) {
    $module->module_id = $module->id; // Map id to module_id for compatibility
    $module->module_name = $module->summary_title; // Use summary_title as module_name
}

        $trainingTypes = DB::table('training_types')->where('is_active', 1)->orderBy('training_type_name', 'asc')->get();
        
        // Get venues data
        $venues = DB::table('venues')->orderBy('venue_name', 'asc')->get();
        
        // Get coordinators and managers - using all users for now since role column doesn't exist
        $coordinators = DB::table('users')
            ->orderBy('name', 'asc')
            ->get();
            
        $managers = DB::table('users')
            ->orderBy('name', 'asc')
            ->get();
        
        return view('admin.pages.training_batch_management.manage_batch.create', compact('companies', 'designations', 'modules', 'trainingTypes', 'venues', 'coordinators', 'managers'));
    }

    /**
     * Get batch count for specific combination
     */
    public function getBatchCount(Request $request)
    {
        $companyCode = $request->input('company_code');
        $designationCode = $request->input('designation_code');
        $trainingTypeCode = $request->input('training_type_code');
        
        // Count existing batches with same combination
        $batchCount = DB::table('training_batches')
            ->join('companies', 'training_batches.batch_code_company', '=', 'companies.company_id')
            ->join('designations', 'training_batches.batch_code_designation', '=', 'designations.designation_id')
            ->join('training_types', 'training_batches.training_id', '=', 'training_types.training_type_id')
            ->where('companies.company_code', $companyCode)
            ->where('designations.designation_code', $designationCode)
            ->where('training_types.training_type_code', $trainingTypeCode)
            ->whereYear('training_batches.created_at', date('Y'))
            ->count();
        
        return response()->json([
            'count' => $batchCount + 1 // +1 for the new batch
        ]);
    }

    /**
     * Show the form for editing the specified training batch.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $batch = DB::table('training_batches')->where('batch_id', $id)->first();
        
        if (!$batch) {
            return redirect()->route('admin.training_batch.index')
                ->with('error', 'Training batch not found');
        }
        
        // Get dropdown data
        $companies = DB::table('companies')->where('is_active', 1)->orderBy('company_name', 'asc')->get();
        $designations = DB::table('designations')->where('is_active', 1)->orderBy('designation_name', 'asc')->get();
        $modules = DB::table('modules')
            ->orderBy('summary_title', 'asc')
            ->get([
                'id',
                'summary_title'
            ]);
        $trainingTypes = DB::table('training_types')->where('is_active', 1)->orderBy('training_type_name', 'asc')->get();
        
        return view('admin.pages.training_batch_management.manage_batch.edit', compact('batch', 'companies', 'designations', 'modules', 'trainingTypes'));
    }

    /**
     * Store a newly created training batch in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Log incoming request data
        \Log::info('Training Batch Store - Incoming Request:', [
            'all_data' => $request->all(),
            'method' => $request->method(),
            'url' => $request->fullUrl()
        ]);

        try {
            $validated = $request->validate([
                'batch_name' => 'required|string|max:255',
                'batch_code' => 'required|string|unique:training_batches,batch_code',
                'description' => 'nullable|string',
                'batch_code_company' => 'required|integer|exists:companies,company_id',
                'batch_code_designation' => 'required|integer|exists:designations,designation_id',
                'batch_coordinator_id' => 'nullable|integer',
                'batch_manager_id' => 'nullable|integer',
                'venue' => 'required|integer',
                'module_master_id' => 'required|integer|exists:modules,id',
                'training_id' => 'required|integer|exists:training_types,training_type_id',
                'daily_hours' => 'required|string|max:10',
                'batch_type' => 'required|integer',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'instructor_id' => 'nullable|integer',
                'max_capacity' => 'nullable|integer|min:1',
                'status' => 'required|in:Planning,Active,Completed,Cancelled'
            ]);

            \Log::info('Training Batch Store - Validation Passed:', [
                'validated_data' => $validated
            ]);

            // Check if module_master_id exists in modules table
            $moduleCheck = DB::table('modules')->where('id', $validated['module_master_id'])->first();
            \Log::info('Training Batch Store - Module Check:', [
                'module_master_id' => $validated['module_master_id'],
                'module_exists' => $moduleCheck ? true : false,
                'module_data' => $moduleCheck
            ]);

            // Insert into database
            $batchId = DB::table('training_batches')->insertGetId([
                'batch_name' => $validated['batch_name'],
                'batch_code' => $validated['batch_code'],
                'description' => $validated['description'] ?? null,
                'batch_code_company' => $validated['batch_code_company'],
                'batch_code_designation' => $validated['batch_code_designation'],
                'batch_coordinator_id' => $validated['batch_coordinator_id'] ?? null,
                'batch_manager_id' => $validated['batch_manager_id'] ?? null,
                'venue' => $validated['venue'],
                'module_master_id' => $validated['module_master_id'],
                'topic_id' => 0, // Add default value for required field
                'subtopic_id' => 0, // Add default value for required field
                'training_id' => $validated['training_id'],
                'daily_hours' => $validated['daily_hours'],
                'batch_type' => $validated['batch_type'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'] ?? null,
                'instructor_id' => $validated['instructor_id'] ?? null,
                'max_capacity' => $validated['max_capacity'] ?? 50,
                'status' => $validated['status'],
                'created_by' => 1, // TODO: Get from authenticated user
                'created_at' => now(),
                'updated_at' => now(),
                'status_updated' => now()
            ]);

            \Log::info('Training Batch Store - Insert Success:', [
                'batch_id' => $batchId,
                'insert_data' => [
                    'batch_name' => $validated['batch_name'],
                    'batch_code' => $validated['batch_code'],
                    'module_master_id' => $validated['module_master_id'],
                    'status' => $validated['status']
                ]
            ]);

            return redirect()->route('admin.training_batch.index')
                ->with('success', 'Training batch created successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation errors
            \Log::error('Training Batch Store - Validation Error:', [
                'errors' => $e->validator->errors()->all(),
                'request_data' => $request->all(),
                'error_details' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Validation failed: ' . implode(', ', $e->validator->errors()->all()));
                
        } catch (\Exception $e) {
            // Log general errors
            \Log::error('Training Batch Store - General Error:', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating training batch: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified training batch in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'batch_name' => 'required|string|max:255',
            'batch_code' => 'required|string|max:50|unique:training_batches,batch_code,'.$id.',batch_id',
            'description' => 'nullable|string',
            'batch_code_company' => 'required|integer|exists:companies,company_id',
            'batch_code_designation' => 'required|integer|exists:designations,designation_id',
            'batch_coordinator_id' => 'nullable|integer',
            'batch_manager_id' => 'nullable|integer',
            'venue' => 'nullable|integer',
            'module_master_id' => 'required|integer|exists:modules,id',
            'training_id' => 'required|integer|exists:training_types,training_type_id',
            'daily_hours' => 'required|string|max:10',
            'batch_type' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'instructor_id' => 'nullable|integer',
            'max_capacity' => 'nullable|integer|min:1',
            'status' => 'required|in:Planning,Active,Completed,Cancelled'
        ]);

        // Update database
        DB::table('training_batches')->where('batch_id', $id)->update([
            'batch_name' => $validated['batch_name'],
            'batch_code' => $validated['batch_code'],
            'description' => $validated['description'],
            'batch_code_company' => $validated['batch_code_company'],
            'batch_code_designation' => $validated['batch_code_designation'],
            'batch_coordinator_id' => $validated['batch_coordinator_id'],
            'batch_manager_id' => $validated['batch_manager_id'],
            'venue' => $validated['venue'],
            'module_master_id' => $validated['module_master_id'],
            'training_id' => $validated['training_id'],
            'daily_hours' => $validated['daily_hours'],
            'batch_type' => $validated['batch_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'instructor_id' => $validated['instructor_id'],
            'max_capacity' => $validated['max_capacity'] ?? 50,
            'status' => $validated['status'],
            'updated_at' => now(),
            'status_updated' => now()
        ]);

        return redirect()->route('admin.training_batch.index')
            ->with('success', 'Training batch updated successfully!');
    }

    /**
     * Remove the specified training batch from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $deleted = DB::table('training_batches')->where('batch_id', $id)->delete();
            
            if ($deleted) {
                // Check if request is AJAX
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Training batch deleted successfully!'
                    ]);
                } else {
                    return redirect()->route('admin.training_batch.index')
                        ->with('success', 'Training batch deleted successfully!');
                }
            } else {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Training batch not found or could not be deleted'
                    ], 404);
                } else {
                    return redirect()->route('admin.training_batch.index')
                        ->with('error', 'Training batch not found or could not be deleted');
                }
            }
        } catch (\Exception $e) {
            \Log::error('Training Batch Delete Error: ' . $e->getMessage());
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting training batch: ' . $e->getMessage()
                ], 500);
            } else {
                return redirect()->route('admin.training_batch.index')
                    ->with('error', 'Error deleting training batch: ' . $e->getMessage());
            }
        }
    }

    /**
     * Display the specified training batch.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $batch = DB::table('training_batches as tb')
            ->leftJoin('companies', 'tb.batch_code_company', '=', 'companies.company_id')
            ->leftJoin('designations', 'tb.batch_code_designation', '=', 'designations.designation_id')
            ->leftJoin('topics', 'tb.module_master_id', '=', 'topics.topic_id')
            ->leftJoin('training_types', 'tb.training_id', '=', 'training_types.training_type_id')
            ->select(
                'tb.*',
                'companies.company_name',
                'designations.designation_name',
                'topics.topic as module_name',
                'training_types.training_type_name',
                'training_types.training_type_id'
            )
            ->where('tb.batch_id', $id)
            ->first();

        if (!$batch) {
            return response()->json([
                'success' => false,
                'message' => 'Training batch not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $batch
        ]);
    }

    /**
     * Get training batches data for AJAX requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getTrainingBatches(Request $request)
    {
        $statusFilter = $request->get('status', 'all');
        
        $query = DB::table('training_batches as tb')
            ->leftJoin('companies', 'tb.batch_code_company', '=', 'companies.company_id')
            ->leftJoin('designations', 'tb.batch_code_designation', '=', 'designations.designation_id')
            ->leftJoin('topics', 'tb.module_master_id', '=', 'topics.topic_id')
            ->leftJoin('training_types', 'tb.training_id', '=', 'training_types.training_type_id')
            ->select(
                'tb.*',
                'companies.company_name',
                'designations.designation_name',
                'topics.topic as module_name',
                'training_types.training_type_name',
                'training_types.training_type_id'
            );
        
        if ($statusFilter !== 'all') {
            $query->where('tb.status', $statusFilter);
        }
        
        $batches = $query->orderBy('tb.created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $batches
        ]);
    }
}
