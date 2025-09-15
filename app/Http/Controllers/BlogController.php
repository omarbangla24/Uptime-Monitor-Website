<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::published()->with(['category', 'author', 'tags']);

        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by tag
        if ($request->has('tag')) {
            $query->whereHas('tags', function($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $blogs = $query->latest('published_at')->paginate(12);
        $categories = BlogCategory::active()->get();
        $popularTags = BlogTag::withCount('blogs')
                              ->orderBy('blogs_count', 'desc')
                              ->limit(10)
                              ->get();

        return view('blog.index', [
            'title' => 'Website Monitoring Blog - Tips & Tutorials',
            'description' => 'Learn about website monitoring, uptime tracking, SSL certificates, and performance optimization.',
            'blogs' => $blogs,
            'categories' => $categories,
            'popularTags' => $popularTags,
            'currentCategory' => $request->category,
            'currentTag' => $request->tag,
            'search' => $request->search
        ]);
    }

    public function show(Blog $blog)
    {
        // Increment view count
        $blog->increment('views_count');

        // Get related posts
        $relatedPosts = Blog::published()
                           ->where('id', '!=', $blog->id)
                           ->where('blog_category_id', $blog->blog_category_id)
                           ->limit(3)
                           ->get();

        return view('blog.show', [
            'title' => $blog->meta_title ?: $blog->title,
            'description' => $blog->meta_description ?: $blog->excerpt,
            'blog' => $blog,
            'relatedPosts' => $relatedPosts
        ]);
    }
}
