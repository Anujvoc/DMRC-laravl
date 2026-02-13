<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainingTypeController extends Controller
{
    /**
     * Display a listing of the training types.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetch training types from database
        $trainingTypes = DB::table('training_types')->orderBy('created_at', 'desc')->get();
        
        return view('admin.pages.master.training_types.index', compact('trainingTypes'));
    }

    /**
     * Show the form for creating a new training type.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.master.training_types.create');
    }

    /**
     * Show the form for editing the specified training type.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $trainingType = DB::table('training_types')->where('training_type_id', $id)->first();
        
        if (!$trainingType) {
            return redirect()->route('admin.master.training_types')
                ->with('error', 'Training type not found');
        }
        
        return view('admin.pages.master.training_types.edit', compact('trainingType'));
    }

    /**
     * Store a newly created training type in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'training_type_name' => 'required|string|max:255',
            'training_type_code' => 'nullable|string|max:50|unique:training_types,training_type_code',
            'description' => 'nullable|string',
            'duration_days' => 'nullable|integer|min:1',
            'is_active' => 'required|boolean'
        ]);

        // Handle is_active - use the actual value from request
        $isActive = $request->input('is_active', 0);

        // Insert into database
        $trainingTypeId = DB::table('training_types')->insertGetId([
            'training_type_name' => $validated['training_type_name'],
            'training_type_code' => $validated['training_type_code'],
            'description' => $validated['description'],
            'duration_days' => $validated['duration_days'] ?? 1,
            'is_active' => $isActive,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('admin.master.training_types')
            ->with('success', 'Training type created successfully!');
    }

    /**
     * Update the specified training type in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'training_type_name' => 'required|string|max:255',
            'training_type_code' => 'nullable|string|max:50|unique:training_types,training_type_code,'.$id.',training_type_id',
            'description' => 'nullable|string',
            'duration_days' => 'nullable|integer|min:1',
            'is_active' => 'required|boolean'
        ]);

        // Handle is_active - use the actual value from request
        $isActive = $request->input('is_active', 0);

        // Update database
        DB::table('training_types')->where('training_type_id', $id)->update([
            'training_type_name' => $validated['training_type_name'],
            'training_type_code' => $validated['training_type_code'],
            'description' => $validated['description'],
            'duration_days' => $validated['duration_days'] ?? 1,
            'is_active' => $isActive,
            'updated_at' => now()
        ]);

        return redirect()->route('admin.master.training_types')
            ->with('success', 'Training type updated successfully!');
    }

    /**
     * Remove the specified training type from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Delete from database
        $deleted = DB::table('training_types')->where('training_type_id', $id)->delete();
        
        if ($deleted) {
            return redirect()->route('admin.master.training_types')
                ->with('success', 'Training type deleted successfully!');
        } else {
            return redirect()->route('admin.master.training_types')
                ->with('error', 'Training type not found or could not be deleted');
        }
    }

    /**
     * Display the specified training type.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // For now, return sample data
        // In a real application, you would fetch from database
        $trainingType = [
            'id' => $id,
            'name' => 'Sample Training Type',
            'code' => 'STT001',
            'description' => 'Sample training type description',
            'duration_days' => 5,
            'status' => 'active',
            'created_at' => '2024-01-01',
            'updated_at' => '2024-01-01'
        ];

        return response()->json([
            'success' => true,
            'data' => $trainingType
        ]);
    }

    /**
     * Get training types data for AJAX requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getTrainingTypes(Request $request)
    {
        // For now, return sample data
        // In a real application, you would fetch from database
        $trainingTypes = [
            [
                'id' => 1,
                'name' => 'Technical Training',
                'code' => 'TT001',
                'description' => 'Technical skills development training',
                'duration_days' => 10,
                'status' => 'active',
                'created_at' => '2024-01-15',
                'updated_at' => '2024-01-20'
            ],
            [
                'id' => 2,
                'name' => 'Soft Skills Training',
                'code' => 'ST002',
                'description' => 'Soft skills and communication training',
                'duration_days' => 3,
                'status' => 'active',
                'created_at' => '2024-01-10',
                'updated_at' => '2024-01-18'
            ],
            [
                'id' => 3,
                'name' => 'Leadership Training',
                'code' => 'LT001',
                'description' => 'Management and leadership skills training',
                'duration_days' => 7,
                'status' => 'inactive',
                'created_at' => '2024-01-05',
                'updated_at' => '2024-01-15'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $trainingTypes
        ]);
    }
}
