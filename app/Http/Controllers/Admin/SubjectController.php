<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
public function index()
{
    $subjects = DB::table('subject')
        ->orderBy('created_at', 'desc')
        ->get();

    foreach ($subjects as $subject) {

        // Get topics for this subject
        $topics = DB::table('topics')
            ->where('subject_id', $subject->id)
            ->orderBy('sort_order')
            ->get();

        foreach ($topics as $topic) {

            // Get subtopics for this topic
            $subtopics = DB::table('subtopics')
                ->where('topic_id', $topic->topic_id)
                ->orderBy('sort_order')
                ->get();

            $topic->subtopics = $subtopics;
        }

        $subject->topics = $topics;
    }

    return view('admin.pages.subjects.index', compact('subjects'));
}


    public function create()
    {
        return view('admin.pages.subjects.create');
    }

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

        DB::table('subject')->insert([
            'title' => $request->title,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.master.subjects.index')
            ->with('success', 'Subject created successfully!');
    }

    public function edit($id)
    {
        $subject = DB::table('subject')->where('id', $id)->first();

        if (!$subject) {
            return redirect()->route('admin.master.subjects.index')
                ->with('error', 'Subject not found');
        }

        return view('admin.pages.subjects.edit', compact('subject'));
    }

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

        DB::table('subject')
            ->where('id', $id)
            ->update([
                'title' => $request->title,
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.master.subjects.index')
            ->with('success', 'Subject updated successfully!');
    }

    public function destroy($id)
    {
        $deleted = DB::table('subject')
            ->where('id', $id)
            ->delete();

        if ($deleted) {
            return redirect()->route('admin.master.subjects.index')
                ->with('success', 'Subject deleted successfully!');
        }

        return redirect()->route('admin.master.subjects.index')
            ->with('error', 'Failed to delete subject');
    }
}
