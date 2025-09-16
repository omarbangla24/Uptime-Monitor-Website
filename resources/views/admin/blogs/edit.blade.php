@extends('layouts.admin')

@section('title', 'Edit Blog Post')

@push('head')
@endpush

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <div class="mx-auto">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                    Edit Blog Post
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('admin.blogs.index') }}" class="btn-outline">
                    Back to Posts
                </a>
            </div>
        </div>

        <form action="{{ route('admin.blogs.update', $blog) }}" method="POST" enctype="multipart/form-data" class="mt-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <div class="admin-card">
                        <div class="admin-card-body">
                            <div class="mb-6">
                                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title *</label>
                                <input type="text" name="title" id="title" value="{{ old('title', $blog->title) }}" required
                                       class="form-input mt-1 @error('title') border-red-500 @enderror">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
                                <input type="text" name="slug" id="slug" value="{{ old('slug', $blog->slug) }}"
                                       class="form-input mt-1 @error('slug') border-red-500 @enderror">
                                <p class="mt-1 text-sm text-gray-500">Leave empty to auto-generate from title</p>
                                @error('slug')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label for="excerpt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Excerpt *</label>
                                <textarea name="excerpt" id="excerpt" rows="3" required
                                          class="form-textarea mt-1 @error('excerpt') border-red-500 @enderror">{{ old('excerpt', $blog->excerpt) }}</textarea>
                                @error('excerpt')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content *</label>
                               <textarea name="content" id="content" class="form-textarea js-tinymce" rows="15">{{ old('content', $blog->content) }}</textarea>
                                @error('content')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="admin-card">
                        <div class="admin-card-header">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">SEO Settings</h3>
                        </div>
                        <div class="admin-card-body space-y-4">
                            <div>
                                <label for="meta_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Title</label>
                                <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $blog->meta_title) }}"
                                       class="form-input mt-1">
                            </div>

                            <div>
                                <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Description</label>
                                <textarea name="meta_description" id="meta_description" rows="3"
                                          class="form-textarea mt-1">{{ old('meta_description', $blog->meta_description) }}</textarea>
                            </div>

                            <div>
                                <label for="meta_keywords" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Keywords</label>
                                <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords', is_array($blog->meta_keywords) ? implode(', ', $blog->meta_keywords) : $blog->meta_keywords) }}"
                                       class="form-input mt-1" placeholder="keyword1, keyword2, keyword3">
                                <p class="mt-1 text-sm text-gray-500">Separate keywords with commas</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="admin-card">
                        <div class="admin-card-header">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Publish</h3>
                        </div>
                        <div class="admin-card-body space-y-4">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select name="status" id="status" class="form-select mt-1">
                                    <option value="draft" {{ old('status', $blog->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $blog->status) === 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="scheduled" {{ old('status', $blog->status) === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="private" {{ old('status', $blog->status) === 'private' ? 'selected' : '' }}>Private</option>
                                </select>
                            </div>

                            <div>
                                <label for="published_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Publish Date</label>
                                <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at', $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : '') }}"
                                       class="form-input mt-1">
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $blog->is_featured) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                <label for="is_featured" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Featured Post</label>
                            </div>

                            <div class="pt-4 border-t">
                                <button type="submit" class="btn-primary w-full">Update Post</button>
                            </div>
                        </div>
                    </div>

                    <div class="admin-card">
                        <div class="admin-card-header">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Category & Author</h3>
                        </div>
                        <div class="admin-card-body space-y-4">
                            <div>
                                <label for="blog_category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                                <select name="blog_category_id" id="blog_category_id" required class="form-select mt-1">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('blog_category_id', $blog->blog_category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="author_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Author</label>
                                <select name="author_id" id="author_id" required class="form-select mt-1">
                                    @foreach($authors as $author)
                                        <option value="{{ $author->id }}" {{ old('author_id', $blog->author_id) == $author->id ? 'selected' : '' }}>
                                            {{ $author->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="admin-card">
                        <div class="admin-card-header">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Featured Image</h3>
                        </div>
                        <div class="admin-card-body">
                            @if ($blog->featured_image)
                                <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="{{ $blog->title }}" class="mb-4 w-full h-auto rounded">
                            @endif
                            <input type="file" name="featured_image" id="featured_image" accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                            <p class="mt-2 text-sm text-gray-500">PNG, JPG, GIF up to 2MB</p>
                        </div>
                    </div>

                    <div class="admin-card">
                        <div class="admin-card-header">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Tags</h3>
                        </div>
                        <div class="admin-card-body">
                            <input type="text" name="tags" id="tags" value="{{ old('tags', $blog->tags->implode('name', ', ')) }}"
                                   class="form-input" placeholder="tag1, tag2, tag3">
                            <p class="mt-2 text-sm text-gray-500">Separate tags with commas</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/pages/blog-create.js')

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');

        if (titleInput && slugInput) {
            titleInput.addEventListener('input', function() {
                const slug = this.value
                    .toLowerCase()
                    .replace(/[^a-z0-9 -]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .replace(/^-+|-+$/g, '');
                slugInput.value = slug;
            });
        }
    });
    </script>
@endpush
