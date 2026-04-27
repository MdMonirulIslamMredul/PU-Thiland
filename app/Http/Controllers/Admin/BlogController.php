<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index()
    {
        return view('admin.blogs.index', [
            'blogs' => Blog::latest()->paginate(10),
        ]);
    }

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('blogs', 'public');
        }

        Blog::create($data);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post created successfully.');
    }

    public function edit(Blog $blog)
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $data = $this->validatedData($request, $blog->id);

        if ($request->hasFile('image')) {
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }
            $data['image'] = $request->file('image')->store('blogs', 'public');
        }

        $blog->update($data);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }

        $blog->delete();

        return back()->with('success', 'Blog post deleted successfully.');
    }

    private function validatedData(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'array'],
            'title.en' => ['nullable', 'string', 'max:255'],
            'title.bn' => ['required', 'string', 'max:255'],
            'title.zh' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:blogs,slug,' . ($id ?? 'NULL') . ',id'],
            'excerpt' => ['required', 'array'],
            'excerpt.en' => ['nullable', 'string'],
            'excerpt.bn' => ['required', 'string'],
            'excerpt.zh' => ['required', 'string'],
            'body' => ['required', 'array'],
            'body.en' => ['nullable', 'string'],
            'body.bn' => ['required', 'string'],
            'body.zh' => ['required', 'string'],
            'meta_title' => ['required', 'array'],
            'meta_title.en' => ['nullable', 'string', 'max:255'],
            'meta_title.bn' => ['required', 'string', 'max:255'],
            'meta_title.zh' => ['required', 'string', 'max:255'],
            'meta_description' => ['required', 'array'],
            'meta_description.en' => ['nullable', 'string'],
            'meta_description.bn' => ['required', 'string'],
            'meta_description.zh' => ['required', 'string'],
            'seo_keywords' => ['required', 'array'],
            'seo_keywords.en' => ['nullable', 'string', 'max:255'],
            'seo_keywords.bn' => ['required', 'string', 'max:255'],
            'seo_keywords.zh' => ['required', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $data['slug'] = $data['slug'] ?? $this->slugFromTranslation($data['title']);
        $data['is_published'] = $request->boolean('is_published', true);

        return $data;
    }
}
