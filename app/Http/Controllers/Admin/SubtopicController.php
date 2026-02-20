<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubtopicController extends Controller
{
    /**
     * Display a listing of the subtopics.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetch subtopics with topic names
        $subtopics = DB::table('subtopics')
            ->leftJoin('topics', 'subtopics.topic_id', '=', 'topics.topic_id')
            ->select('subtopics.*', 'topics.topic as topic_name')
            ->orderBy('subtopics.sort_order', 'asc')
            ->orderBy('subtopics.subtopic', 'asc')
            ->get();
        
        return view('admin.pages.module_management.subtopics.index', compact('subtopics'));
    }

    /**
     * Show the form for creating a new subtopic.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get topics for dropdown
        $topics = DB::table('topics')->where('status', 1)->orderBy('topic', 'asc')->get();
        
        return view('admin.pages.module_management.subtopics.create', compact('topics'));
    }

    /**
     * Show the form for editing the specified subtopic.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subtopic = DB::table('subtopics')->where('subtopic_id', $id)->first();
        
        if (!$subtopic) {
            return redirect()->route('admin.module.subtopics')
                ->with('error', 'Subtopic not found');
        }
        
        // Get topics for dropdown
        $topics = DB::table('topics')->where('status', 1)->orderBy('topic', 'asc')->get();
        
        return view('admin.pages.module_management.subtopics.edit', compact('subtopic', 'topics'));
    }

    /**
     * Store a newly created subtopic in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'topic_id' => 'required|integer|exists:topics,topic_id',
            'subtopic' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sessions' => 'nullable|integer|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|boolean'
        ]);

        // Handle status - use the actual value from request
        $status = $request->input('status', 0);

        // Insert into database
        $subtopicId = DB::table('subtopics')->insertGetId([
            'topic_id' => $validated['topic_id'],
            'subtopic' => $validated['subtopic'],
            'description' => $validated['description'],
            'sessions' => $validated['sessions'] ?? 0,
            'sort_order' => $validated['sort_order'] ?? 0,
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('admin.module.subtopics')
            ->with('success', 'Subtopic created successfully!');
    }

    /**
     * Update the specified subtopic in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'topic_id' => 'required|integer|exists:topics,topic_id',
            'subtopic' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sessions' => 'nullable|integer|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|boolean'
        ]);

        // Handle status - use the actual value from request
        $status = $request->input('status', 0);

        // Update database
        DB::table('subtopics')->where('subtopic_id', $id)->update([
            'topic_id' => $validated['topic_id'],
            'subtopic' => $validated['subtopic'],
            'description' => $validated['description'],
            'sessions' => $validated['sessions'] ?? 0,
            'sort_order' => $validated['sort_order'] ?? 0,
            'status' => $status,
            'updated_at' => now()
        ]);

        return redirect()->route('admin.module.subtopics')
            ->with('success', 'Subtopic updated successfully!');
    }

    /**
     * Remove the specified subtopic from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = DB::table('subtopics')->where('subtopic_id', $id)->delete();
        
        if ($deleted) {
            return redirect()->route('admin.module.subtopics')
                ->with('success', 'Subtopic deleted successfully!');
        } else {
            return redirect()->route('admin.module.subtopics')
                ->with('error', 'Subtopic not found or could not be deleted');
        }
    }

    /**
     * Display the specified subtopic.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subtopic = DB::table('subtopics')
            ->leftJoin('topics', 'subtopics.topic_id', '=', 'topics.topic_id')
            ->select('subtopics.*', 'topics.topic as topic_name')
            ->where('subtopics.subtopic_id', $id)
            ->first();

        if (!$subtopic) {
            return response()->json([
                'success' => false,
                'message' => 'Subtopic not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $subtopic
        ]);
    }

    /**
     * Get subtopics data for AJAX requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getSubtopics(Request $request)
    {
        $subtopics = DB::table('subtopics')
            ->leftJoin('topics', 'subtopics.topic_id', '=', 'topics.topic_id')
            ->select('subtopics.*', 'topics.topic as topic_name')
            ->orderBy('subtopics.sort_order', 'asc')
            ->orderBy('subtopics.subtopic', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $subtopics
        ]);
    }
}
