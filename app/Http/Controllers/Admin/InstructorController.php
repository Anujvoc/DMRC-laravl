<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InstructorController extends Controller
{
    public function index()
    {
        $instructors = DB::table('instructors')
            ->orderBy('created_at', 'desc')
            ->get();

        $allSubjectIds = [];
        foreach ($instructors as $instructor) {
            $ids = array_filter(array_map('trim', explode(',', (string) ($instructor->subject ?? ''))));
            foreach ($ids as $id) {
                if (ctype_digit($id)) {
                    $allSubjectIds[] = (int) $id;
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

        foreach ($instructors as $instructor) {
            $ids = array_filter(array_map('trim', explode(',', (string) ($instructor->subject ?? ''))));
            $titles = [];
            foreach ($ids as $id) {
                if (ctype_digit($id) && isset($subjectMap[(int) $id])) {
                    $titles[] = $subjectMap[(int) $id];
                }
            }
            $instructor->subject_titles = implode(', ', $titles);
        }

        return view('admin.pages.instructors.index', compact('instructors'));
    }

    public function create()
    {
        $designations = DB::table('designations')
            ->where('is_active', 1)
            ->orderBy('designation_name', 'asc')
            ->get();

        $subjects = DB::table('subject')
            ->orderBy('title', 'asc')
            ->get();

        return view('admin.pages.instructors.create', compact('designations', 'subjects'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'employee_id' => 'required|string|max:255',
            'subject_ids' => 'required|array|min:1',
            'subject_ids.*' => 'integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $subjectIds = array_map('intval', (array) $request->subject_ids);
        $subjectIds = array_values(array_unique(array_filter($subjectIds)));

        DB::table('instructors')->insert([
            'name' => $request->name,
            'designation' => $request->designation,
            'employee id' => $request->employee_id,
            'subject' => implode(',', $subjectIds),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.master.instructors.index')
            ->with('success', 'Instructor created successfully!');
    }

    public function edit($id)
    {
        $instructor = DB::table('instructors')->where('id', $id)->first();

        if (!$instructor) {
            return redirect()->route('admin.master.instructors.index')
                ->with('error', 'Instructor not found');
        }

        $designations = DB::table('designations')
            ->where('is_active', 1)
            ->orderBy('designation_name', 'asc')
            ->get();

        $subjects = DB::table('subject')
            ->orderBy('title', 'asc')
            ->get();

        $selectedSubjectIds = array_filter(array_map('trim', explode(',', (string) ($instructor->subject ?? ''))));
        $selectedSubjectIds = array_values(array_filter($selectedSubjectIds, function ($id) {
            return ctype_digit((string) $id);
        }));

        return view('admin.pages.instructors.edit', compact('instructor', 'designations', 'subjects', 'selectedSubjectIds'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'employee_id' => 'required|string|max:255',
            'subject_ids' => 'required|array|min:1',
            'subject_ids.*' => 'integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $subjectIds = array_map('intval', (array) $request->subject_ids);
        $subjectIds = array_values(array_unique(array_filter($subjectIds)));

        DB::table('instructors')
            ->where('id', $id)
            ->update([
                'name' => $request->name,
                'designation' => $request->designation,
                'employee id' => $request->employee_id,
                'subject' => implode(',', $subjectIds),
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.master.instructors.index')
            ->with('success', 'Instructor updated successfully!');
    }

    public function destroy($id)
    {
        $deleted = DB::table('instructors')
            ->where('id', $id)
            ->delete();

        if ($deleted) {
            return redirect()->route('admin.master.instructors.index')
                ->with('success', 'Instructor deleted successfully!');
        }

        return redirect()->route('admin.master.instructors.index')
            ->with('error', 'Failed to delete instructor');
    }
}
