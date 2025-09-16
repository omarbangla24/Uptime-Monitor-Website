@extends('layouts.admin')

@section('title', 'Edit Page')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <div class="mx-auto">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                    Edit Page
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('admin.pages.index') }}" class="btn-outline">
                    Back to Pages
                </a>
            </div>
        </div>

        <form action="{{ route('admin.pages.update', $page) }}" method="POST" class="mt-8">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="admin-card">
                        <div class="admin-card-body">
                            <div class="mb-6">
                                <label for="title" class="form-label">Title *</label>
                                <input type="text" name="title" id="title" value="{{ old('title', $page->title) }}" required class="form-input">
                            </div>
                            <div class="mb-6">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" name="slug" id="slug" value="{{ old('slug', $page->slug) }}" class="form-input">
                            </div>
                            <div>
                                <label for="content" class="form-label">Content</label>
                                <textarea name="content" id="content" class="form-textarea js-tinymce" rows="20">{{ old('content', $page->content) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sidebar -->
                <div class="space-y-6">
                    <div class="admin-card">
                        <div class="admin-card-header"><h3 class="text-lg font-medium">Publish</h3></div>
                        <div class="admin-card-body">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="draft" @if($page->status == 'draft') selected @endif>Draft</option>
                                <option value="published" @if($page->status == 'published') selected @endif>Published</option>
                            </select>
                        </div>
                        <div class="admin-card-footer">
                            <button type="submit" class="btn-primary w-full">Update Page</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
