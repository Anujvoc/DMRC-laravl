<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    /**
     * Display a listing of the companies.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetch companies from database
        $companies = DB::table('companies')->orderBy('created_at', 'desc')->get();
        
        return view('admin.pages.master.companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new company.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.master.companies.create');
    }

    /**
     * Show the form for editing the specified company.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $company = DB::table('companies')->where('company_id', $id)->first();
        
        if (!$company) {
            return redirect()->route('admin.master.companies')
                ->with('error', 'Company not found');
        }
        
        return view('admin.pages.master.companies.edit', compact('company'));
    }

    /**
     * Store a newly created company in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_code' => 'nullable|string|max:50|unique:companies,company_code',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string',
            'is_active' => 'nullable|boolean'
        ]);

        // Handle is_active checkbox - if not checked, set to 0
        $isActive = $request->has('is_active') ? 1 : 0;

        // Insert into database
        $companyId = DB::table('companies')->insertGetId([
            'company_name' => $validated['company_name'],
            'company_code' => $validated['company_code'],
            'contact_person' => $validated['contact_person'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'website' => $validated['website'],
            'address' => $validated['address'],
            'is_active' => $isActive,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('admin.master.companies')
            ->with('success', 'Company created successfully!');
    }

    /**
     * Update the specified company in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_code' => 'nullable|string|max:50|unique:companies,company_code,'.$id.',company_id',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string',
            'is_active' => 'nullable|boolean'
        ]);

        // Handle is_active checkbox
        $isActive = $request->has('is_active') ? 1 : 0;

        // Update database
        DB::table('companies')->where('company_id', $id)->update([
            'company_name' => $validated['company_name'],
            'company_code' => $validated['company_code'],
            'contact_person' => $validated['contact_person'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'website' => $validated['website'],
            'address' => $validated['address'],
            'is_active' => $isActive,
            'updated_at' => now()
        ]);

        return redirect()->route('admin.master.companies')
            ->with('success', 'Company updated successfully!');
    }

    /**
     * Display the specified company.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // For now, return sample data
        // In a real application, you would fetch from database
        $company = [
            'id' => $id,
            'name' => 'Sample Company',
            'code' => 'SMP001',
            'contact_person' => 'John Doe',
            'email' => 'john@sample.com',
            'phone' => '+1-555-0123',
            'industry' => 'technology',
            'company_size' => 'medium',
            'website' => 'https://sample.com',
            'address' => '123 Sample St, City, State 12345',
            'description' => 'Sample company description',
            'status' => 'active',
            'created_at' => '2024-01-01',
            'updated_at' => '2024-01-01'
        ];

        return response()->json([
            'success' => true,
            'data' => $company
        ]);
    }

    /**
     * Remove the specified company from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
 public function destroy($id)
{
    // Delete from database
    $deleted = DB::table('companies')->where('company_id', $id)->delete();
    
    if ($deleted) {
        return redirect()->route('admin.master.companies')
            ->with('success', 'Company deleted successfully!');
    } else {
        return redirect()->route('admin.master.companies')
            ->with('error', 'Company not found or could not be deleted');
    }
}

    /**
     * Get companies data for AJAX requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCompanies(Request $request)
    {
        $companies = DB::table('companies')->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'companies' => $companies
        ]);
    }
}
