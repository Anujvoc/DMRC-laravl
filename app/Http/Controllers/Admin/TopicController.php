<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopicController extends Controller
{
    /**
     * Display a listing of the topics.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetch topics from database
        $topics = DB::table('topics')
            ->leftJoin('subject', 'topics.subject_id', '=', 'subject.id')
            ->select('topics.*', 'subject.title as subject_title')
            ->orderBy('topics.sort_order', 'asc')
            ->orderBy('topics.topic', 'asc')
            ->get();
        
        return view('admin.pages.module_management.topics.index', compact('topics'));
    }

    /**
     * Show the form for creating a new topic.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subjects = DB::table('subject')
            ->orderBy('title', 'asc')
            ->get();

        return view('admin.pages.module_management.topics.create', compact('subjects'));
    }

    /**
     * Show the form for editing the specified topic.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $topic = DB::table('topics')->where('topic_id', $id)->first();
        
        if (!$topic) {
            return redirect()->route('admin.module.topics')
                ->with('error', 'Topic not found');
        }

        $subjects = DB::table('subject')
            ->orderBy('title', 'asc')
            ->get();
        
        return view('admin.pages.module_management.topics.edit', compact('topic', 'subjects'));
    }

    /**
     * Store a newly created topic in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'topic' => 'required|string|max:255',
            'subject_id' => 'nullable|integer',
            'subtopic' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|boolean'
        ]);

        // Handle status - use the actual value from request
        $status = $request->input('status', 0);

        // Insert into database
        $topicId = DB::table('topics')->insertGetId([
            'topic' => $validated['topic'],
            'subject_id' => $validated['subject_id'] ?? null,
            'subtopic' => $validated['subtopic'],
            'description' => $validated['description'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('admin.module.topics')
            ->with('success', 'Topic created successfully!');
    }

    /**
     * Update the specified topic in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'topic' => 'required|string|max:255',
            'subject_id' => 'nullable|integer',
            'subtopic' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|boolean'
        ]);

        // Handle status - use the actual value from request
        $status = $request->input('status', 0);

        // Update database
        DB::table('topics')->where('topic_id', $id)->update([
            'topic' => $validated['topic'],
            'subject_id' => $validated['subject_id'] ?? null,
            'subtopic' => $validated['subtopic'],
            'description' => $validated['description'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'status' => $status,
            'updated_at' => now()
        ]);

        return redirect()->route('admin.module.topics')
            ->with('success', 'Topic updated successfully!');
    }

    /**
     * Remove the specified topic from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = DB::table('topics')->where('topic_id', $id)->delete();
        
        if ($deleted) {
            return redirect()->route('admin.module.topics')
                ->with('success', 'Topic deleted successfully!');
        } else {
            return redirect()->route('admin.module.topics')
                ->with('error', 'Topic not found or could not be deleted');
        }
    }

    /**
     * Display the specified topic.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // For now, return sample data
        // In a real application, you would fetch from database
        $topic = [
            'id' => $id,
            'name' => 'Sample Topic',
            'code' => 'ST001',
            'description' => 'Sample topic description',
            'status' => 'active',
            'created_at' => '2024-01-01',
            'updated_at' => '2024-01-01'
        ];

        return response()->json([
            'success' => true,
            'data' => $topic
        ]);
    }

    /**
     * Get topics data for AJAX requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getTopics(Request $request)
    {
        // For now, return sample data
        // In a real application, you would fetch from database
        $topics = [
            [
                'id' => 1,
                'name' => 'Web Development',
                'code' => 'WD001',
                'description' => 'Web development and programming topics',
                'status' => 'active',
                'created_at' => '2024-01-15',
                'updated_at' => '2024-01-20'
            ],
            [
                'id' => 2,
                'name' => 'Database Management',
                'code' => 'DB001',
                'description' => 'Database design and management topics',
                'status' => 'active',
                'created_at' => '2024-01-10',
                'updated_at' => '2024-01-18'
            ],
            [
                'id' => 3,
                'name' => 'Mobile Development',
                'code' => 'MD001',
                'description' => 'Mobile app development topics',
                'status' => 'inactive',
                'created_at' => '2024-01-05',
                'updated_at' => '2024-01-15'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $topics
        ]);
    }
}
