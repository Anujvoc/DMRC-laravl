<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $categories = DB::table('categories')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pages.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('admin.pages.categories.create');
    }

    /**
     * Store a newly created category.
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

        $now = time();
        DB::table('categories')->insertGetId([
            'title' => $request->title,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully!');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit($id)
    {
        $category = DB::table('categories')->where('id', $id)->first();

        if (!$category) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Category not found');
        }

        return view('admin.pages.categories.edit', compact('category'));
    }

    /**
     * Update the specified category.
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

        $now = time();
        DB::table('categories')
            ->where('id', $id)
            ->update([
                'title' => $request->title,
                'updated_at' => $now,
            ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy($id)
    {
        $deleted = DB::table('categories')
            ->where('id', $id)
            ->delete();

        if ($deleted) {
            return redirect()->route('admin.categories.index')
                ->with('success', 'Category deleted successfully!');
        }

        return redirect()->route('admin.categories.index')
            ->with('error', 'Failed to delete category');
    }
}
