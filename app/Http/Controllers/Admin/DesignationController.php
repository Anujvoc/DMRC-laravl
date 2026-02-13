<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DesignationController extends Controller
{
    /**
     * Display a listing of the designations.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetch designations from database
        $designations = DB::table('designations')->orderBy('created_at', 'desc')->get();
        
        return view('admin.pages.master.designations.index', compact('designations'));
    }

    /**
     * Show the form for creating a new designation.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.master.designations.create');
    }

    /**
     * Show the form for editing the specified designation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $designation = DB::table('designations')->where('designation_id', $id)->first();
        
        if (!$designation) {
            return redirect()->route('admin.master.designations')
                ->with('error', 'Designation not found');
        }
        
        return view('admin.pages.master.designations.edit', compact('designation'));
    }

    /**
     * Store a newly created designation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'designation_name' => 'required|string|max:255',
            'designation_code' => 'nullable|string|max:50|unique:designations,designation_code',
            'description' => 'nullable|string',
            'level_order' => 'nullable|integer|min:0',
            'is_active' => 'required|boolean'
        ]);

        // Handle is_active - use the actual value from request
        $isActive = $request->input('is_active', 0);

        // Insert into database
        $designationId = DB::table('designations')->insertGetId([
            'designation_name' => $validated['designation_name'],
            'designation_code' => $validated['designation_code'],
            'description' => $validated['description'],
            'level_order' => $validated['level_order'] ?? 0,
            'is_active' => $isActive,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('admin.master.designations')
            ->with('success', 'Designation created successfully!');
    }

    /**
     * Update the specified designation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'designation_name' => 'required|string|max:255',
            'designation_code' => 'nullable|string|max:50|unique:designations,designation_code,'.$id.',designation_id',
            'description' => 'nullable|string',
            'level_order' => 'nullable|integer|min:0',
            'is_active' => 'required|boolean'
        ]);

        // Handle is_active - use the actual value from request
        $isActive = $request->input('is_active', 0);

        // Update database
        DB::table('designations')->where('designation_id', $id)->update([
            'designation_name' => $validated['designation_name'],
            'designation_code' => $validated['designation_code'],
            'description' => $validated['description'],
            'level_order' => $validated['level_order'] ?? 0,
            'is_active' => $isActive,
            'updated_at' => now()
        ]);

        return redirect()->route('admin.master.designations')
            ->with('success', 'Designation updated successfully!');
    }

    /**
     * Remove the specified designation from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Delete from database
        $deleted = DB::table('designations')->where('designation_id', $id)->delete();
        
        if ($deleted) {
            return redirect()->route('admin.master.designations')
                ->with('success', 'Designation deleted successfully!');
        } else {
            return redirect()->route('admin.master.designations')
                ->with('error', 'Designation not found or could not be deleted');
        }
    }

    /**
     * Display the specified designation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // For now, return sample data
        // In a real application, you would fetch from database
        $designation = [
            'id' => $id,
            'name' => 'Sample Designation',
            'code' => 'SMP001',
            'description' => 'Sample designation description',
            'level_order' => 1,
            'status' => 'active',
            'created_at' => '2024-01-01',
            'updated_at' => '2024-01-01'
        ];

        return response()->json([
            'success' => true,
            'data' => $designation
        ]);
    }

    /**
     * Get designations data for AJAX requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getDesignations(Request $request)
    {
        // For now, return sample data
        // In a real application, you would fetch from database
        $designations = [
            [
                'id' => 1,
                'name' => 'Senior Developer',
                'code' => 'SD001',
                'description' => 'Senior software developer position',
                'level_order' => 3,
                'status' => 'active',
                'created_at' => '2024-01-15',
                'updated_at' => '2024-01-20'
            ],
            [
                'id' => 2,
                'name' => 'Junior Developer',
                'code' => 'JD001',
                'description' => 'Junior software developer position',
                'level_order' => 1,
                'status' => 'active',
                'created_at' => '2024-01-10',
                'updated_at' => '2024-01-18'
            ],
            [
                'id' => 3,
                'name' => 'Team Lead',
                'code' => 'TL001',
                'description' => 'Team leader position',
                'level_order' => 4,
                'status' => 'inactive',
                'created_at' => '2024-01-05',
                'updated_at' => '2024-01-15'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $designations
        ]);
    }
}
