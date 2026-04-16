<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;

class BlogController extends Controller
{
    public function index()
    {
        return view('frontend.blogs.index', [
            'blogs' => Blog::where('is_published', true)->latest('published_at')->paginate(9),
        ]);
    }

    public function show(Blog $blog)
    {
        abort_unless($blog->is_published, 404);

        return view('frontend.blogs.show', [
            'blog' => $blog,
        ]);
    }
}
