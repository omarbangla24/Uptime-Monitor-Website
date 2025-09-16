@extends('layouts.admin')

@section('title', 'Pages')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Pages</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Manage your website's static pages.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('admin.pages.create') }}" class="btn-primary">
                Add Page
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="mt-6 bg-white dark:bg-dark-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="form-label">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           class="form-input mt-1" placeholder="Search by title or slug...">
                </div>
                <div>
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select mt-1">
                        <option value="">All Statuses</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn-primary w-full">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Pages Table -->
    <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th>Last Updated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-dark-800 divide-y divide-gray-200 dark:divide-dark-700">
                            @forelse($pages as $page)
                                <tr>
                                    <td class="admin-table-td font-medium">{{ $page->title }}</td>
                                    <td class="admin-table-td"><a href="{{ route('pages.show', $page->slug) }}" target="_blank" class="text-primary-500 hover:underline">/{{ $page->slug }}</a></td>
                                    <td class="admin-table-td">
                                        <span class="admin-badge-{{ $page->status === 'published' ? 'success' : 'warning' }}">{{ ucfirst($page->status) }}</span>
                                    </td>
                                    <td class="admin-table-td">{{ $page->updated_at->format('M j, Y') }}</td>
                                    <td class="admin-table-td">
                                        <div class="flex items-center space-x-4">
                                            <a href="{{ route('admin.pages.show', $page) }}" class="text-gray-500 hover:text-gray-700">View</a>
                                            <a href="{{ route('admin.pages.edit', $page) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" class="inline">
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
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        No pages found.
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
    @if($pages->hasPages())
        <div class="mt-6">
            {{ $pages->links() }}
        </div>
    @endif
</div>
@endsection
