@extends('layouts.admin')

@section('title', 'Blog Posts')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Blog Posts</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Manage your blog posts and articles.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('admin.blogs.create') }}" class="btn-primary">
                Add Blog Post
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="mt-6 bg-white dark:bg-dark-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-input mt-1" placeholder="Search posts...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select name="status" class="form-select mt-1">
                        <option value="">All Statuses</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="private" {{ request('status') === 'private' ? 'selected' : '' }}>Private</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                    <select name="category" class="form-select mt-1">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn-primary w-full">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Blog Posts Table -->
    <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Author</th>
                                <th>Status</th>
                                <th>Views</th>
                                <th>Published</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-dark-800 divide-y divide-gray-200 dark:divide-dark-700">
                            @forelse($blogs as $blog)
                                <tr>
                                    <td class="admin-table td">
                                        <div class="flex items-center">
                                            @if($blog->featured_image_url)
                                                <img class="h-10 w-10 rounded object-cover mr-3" src="{{ $blog->featured_image_url }}" alt="">
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $blog->title }}
                                                </div>
                                                @if($blog->is_featured)
                                                    <span class="admin-badge-warning">Featured</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="admin-table td">
                                        <span class="px-2 py-1 text-xs rounded" style="background-color: {{ $blog->category->color }}20; color: {{ $blog->category->color }}">
                                            {{ $blog->category->name }}
                                        </span>
                                    </td>
                                    <td class="admin-table td">{{ $blog->author->name }}</td>
                                    <td class="admin-table td">
                                        <span class="admin-badge-{{ $blog->status === 'published' ? 'success' : ($blog->status === 'draft' ? 'warning' : 'info') }}">
                                            {{ ucfirst($blog->status) }}
                                        </span>
                                    </td>
                                    <td class="admin-table td">{{ number_format($blog->views_count) }}</td>
                                    <td class="admin-table td">
                                        {{ $blog->published_at ? $blog->published_at->format('M j, Y') : '-' }}
                                    </td>
                                    <td class="admin-table td">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('blog.show', $blog) }}" target="_blank" class="text-primary-600 hover:text-primary-900">View</a>
                                            <a href="{{ route('admin.blogs.edit', $blog) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <form method="POST" action="{{ route('admin.blogs.destroy', $blog) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900"
                                                        onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        No blog posts found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($blogs->hasPages())
        <div class="mt-6">
            {{ $blogs->links() }}
        </div>
    @endif
</div>
@endsection
