<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TrainingProgramController extends Controller
{
    /**
     * Display a listing of training programs.
     */
    public function index()
    {
        $programs = DB::table('training_program')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.pages.training_program.index', compact('programs'));
    }

    /**
     * Show the form for creating a new training program.
     */
    public function create()
    {
        return view('admin.pages.training_program.create');
    }

    /**
     * Store a newly created training program.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $programId = DB::table('training_program')->insertGetId([
            'title' => $request->title,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.training_program.index')
            ->with('success', 'Training program created successfully!');
    }

    /**
     * Show the form for editing the specified training program.
     */
    public function edit($id)
    {
        $program = DB::table('training_program')->where('id', $id)->first();
        
        if (!$program) {
            return redirect()->route('admin.training_program.index')
                ->with('error', 'Training program not found');
        }
        
        return view('admin.pages.training_program.edit', compact('program'));
    }

    /**
     * Update the specified training program.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updated = DB::table('training_program')
            ->where('id', $id)
            ->update([
                'title' => $request->title,
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.training_program.index')
            ->with('success', 'Training program updated successfully!');
    }

    /**
     * Remove the specified training program from storage.
     */
    public function destroy($id)
    {
        $deleted = DB::table('training_program')
            ->where('id', $id)
            ->delete();
            
        if ($deleted) {
            return redirect()->route('admin.training_program.index')
                ->with('success', 'Training program deleted successfully!');
        }
        
        return redirect()->route('admin.training_program.index')
            ->with('error', 'Failed to delete training program');
    }
}
