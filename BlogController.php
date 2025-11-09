<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::all();
        return view('dashboard.blog.index', compact('blogs'));
    }

    public function create()
    {
        return view('dashboard.blog.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'         => 'required|string',
            'title'        => 'required|string',
            'slug'         => 'required|string|unique:blogs,slug',
            'content'      => 'required|string',
            'status'       => 'nullable|string',
            'image'        => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'category_id'  => 'required|numeric|exists:categories,id',
            'author_id'    => 'required|numeric|exists:users,id',
            'views'        => 'nullable|numeric|min:0',
            'tags'         => 'nullable|string',
            'published_at' => 'nullable|date',
        ]);

        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('blog_images', 'public');
        }

        Blog::create($validatedData);

        return redirect()->route('blogs.index')->with('success', 'Blog created successfully!');
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        return view('dashboard.blog.edit', compact('blog'));
    }

    public function update(Request $request, $id)
    {
        $validatedData =  $request->validate([
            'name'         => 'required|string',
            'title'        => 'required|string',
            'slug'         => 'required|string|unique:blogs,slug,' . $id,
            'content'      => 'required|string',
            'status'       => 'nullable|string',
            'image'        => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'category_id'  => 'required|numeric|exists:categories,id',
            'author_id'    => 'required|numeric|exists:users,id',
            'views'        => 'nullable|numeric|min:0',
            'tags'         => 'nullable|string',
            'published_at' => 'nullable|date',
        ]);

        $blog = Blog::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }
            $validatedData['image'] = $request->file('image')->store('blog_images', 'public');
        }

        $blog->update($validatedData);

        return redirect()->route('blog.index')->with('success', 'Blog updated successfully!');
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        
        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }

        $blog->delete();

        return redirect()->route('blog.index')->with('success', 'Blog deleted successfully!');
    }
}
