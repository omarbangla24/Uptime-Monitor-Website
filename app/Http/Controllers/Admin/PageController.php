<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $query = Page::query();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $pages = $query->latest()->paginate(20)->withQueryString();

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|array',
            'og_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published,private',
            'is_featured' => 'boolean',
            'template' => 'nullable|string|max:50',
            'published_at' => 'nullable|date',
        ]);

        if (!$validated['slug']) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Handle OG image upload
        if ($request->hasFile('og_image')) {
            $validated['og_image'] = $request->file('og_image')
                                            ->store('pages/images', 'public');
        }

        // Set published_at for published pages
        if ($validated['status'] === 'published' && !$validated['published_at']) {
            $validated['published_at'] = now();
        }

        Page::create($validated);

        return redirect()->route('admin.pages.index')
                        ->with('success', 'Page created successfully.');
    }

    public function show(Page $page)
    {
        return view('admin.pages.show', compact('page'));
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $page->id,
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|array',
            'og_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published,private',
            'is_featured' => 'boolean',
            'template' => 'nullable|string|max:50',
            'published_at' => 'nullable|date',
        ]);

        // Handle OG image upload
        if ($request->hasFile('og_image')) {
            // Delete old image
            if ($page->og_image) {
                Storage::disk('public')->delete($page->og_image);
            }

            $validated['og_image'] = $request->file('og_image')
                                            ->store('pages/images', 'public');
        }

        $page->update($validated);

        return back()->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        // Delete OG image
        if ($page->og_image) {
            Storage::disk('public')->delete($page->og_image);
        }

        $page->delete();

        return redirect()->route('admin.pages.index')
                        ->with('success', 'Page deleted successfully.');
    }
}
