<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CertificationController extends Controller
{
    public function index()
    {
        $certifications = DB::table('certifications')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pages.certifications.index', compact('certifications'));
    }

    public function create()
    {
        return view('admin.pages.certifications.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'iso_standard' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::table('certifications')->insert([
            'iso_standard' => $request->iso_standard,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.master.certifications.index')
            ->with('success', 'Certification created successfully!');
    }

    public function edit($id)
    {
        $certification = DB::table('certifications')->where('id', $id)->first();

        if (!$certification) {
            return redirect()->route('admin.master.certifications.index')
                ->with('error', 'Certification not found');
        }

        return view('admin.pages.certifications.edit', compact('certification'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'iso_standard' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::table('certifications')
            ->where('id', $id)
            ->update([
                'iso_standard' => $request->iso_standard,
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.master.certifications.index')
            ->with('success', 'Certification updated successfully!');
    }

    public function destroy($id)
    {
        $deleted = DB::table('certifications')
            ->where('id', $id)
            ->delete();

        if ($deleted) {
            return redirect()->route('admin.master.certifications.index')
                ->with('success', 'Certification deleted successfully!');
        }

        return redirect()->route('admin.master.certifications.index')
            ->with('error', 'Failed to delete certification');
    }
}
