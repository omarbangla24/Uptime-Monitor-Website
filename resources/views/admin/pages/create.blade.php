@extends('layouts.admin')

@section('title', 'Create Page')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <div class="mx-auto">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                    Create New Page
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('admin.pages.index') }}" class="btn-outline">
                    Back to Pages
                </a>
            </div>
        </div>

        <form action="{{ route('admin.pages.store') }}" method="POST" class="mt-8">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="admin-card">
                        <div class="admin-card-body">
                            <div class="mb-6">
                                <label for="title" class="form-label">Title *</label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" required class="form-input">
                            </div>
                            <div class="mb-6">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" name="slug" id="slug" value="{{ old('slug') }}" class="form-input">
                                <p class="mt-1 text-sm text-gray-500">Leave blank to auto-generate from title.</p>
                            </div>
                            <div>
                                <label for="content" class="form-label">Content</label>
                                <textarea name="content" id="content" class="form-textarea js-tinymce" rows="20">{{ old('content') }}</textarea>
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
                                <option value="draft" selected>Draft</option>
                                <option value="published">Published</option>
                            </select>
                        </div>
                        <div class="admin-card-footer">
                            <button type="submit" class="btn-primary w-full">Create Page</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
