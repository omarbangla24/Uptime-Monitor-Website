@extends('layouts.guest')

@section('title', $title)
@section('description', $description)

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-dark-900">
    <!-- Header Section -->
    <div class="bg-gradient-to-br from-primary-50 via-white to-primary-100 dark:from-dark-900 dark:via-dark-800 dark:to-dark-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white mb-6">
                    Blog & <span class="text-gradient">Resources</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto leading-relaxed">
                    Tips, tutorials, and insights about website monitoring, performance optimization, and web development.
                </p>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <!-- Search and Filters -->
        <div class="filter-bar mb-12">
            <div class="filter-section">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search Articles</label>
                    <form method="GET" class="blog-search">
                        <input type="search" name="search" value="{{ $search }}"
                               placeholder="Search for articles..."
                               class="form-input">
                        <button type="submit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                    <select onchange="window.location.href=this.value" class="form-select">
                        <option value="{{ route('blog.index') }}">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ route('blog.index', ['category' => $category->slug]) }}"
                                    {{ $currentCategory === $category->slug ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Clear Filters -->
                <div>
                    @if($search || $currentCategory || $currentTag)
                        <a href="{{ route('blog.index') }}" class="btn-outline w-full justify-center">
                            Clear Filters
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Blog Posts Grid -->
        @if($blogs->count() > 0)
            <div class="blog-grid mb-16">
                @foreach($blogs as $blog)
                    <article class="blog-card">
                        @if($blog->featured_image_url)
                            <div class="overflow-hidden">
                                <img src="{{ $blog->featured_image_url }}"
                                     alt="{{ $blog->title }}"
                                     class="blog-image">
                            </div>
                        @endif

                        <div class="blog-content">
                            <!-- Category & Reading Time -->
                            <div class="flex items-center justify-between mb-4">
                                <span class="blog-category px-3 py-1 text-xs font-semibold rounded-full"
                                      style="background-color: {{ $blog->category->color }}20; color: {{ $blog->category->color }}">
                                    {{ $blog->category->name }}
                                </span>
                                <span class="text-gray-500 dark:text-gray-400 text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $blog->reading_time_text }}
                                </span>
                            </div>

                            <!-- Title -->
                            <h2 class="blog-title">
                                <a href="{{ route('blog.show', $blog) }}">{{ $blog->title }}</a>
                            </h2>

                            <!-- Excerpt -->
                            <p class="blog-excerpt line-clamp-3">{{ $blog->excerpt }}</p>

                            <!-- Author & Date -->
                            <div class="blog-meta">
                                <div class="blog-author">
                                    <img src="{{ $blog->author->avatar_url }}"
                                         alt="{{ $blog->author->name }}"
                                         class="blog-avatar">
                                    <span class="blog-author-name">{{ $blog->author->name }}</span>
                                </div>
                                <span class="blog-date">
                                    {{ $blog->published_at->format('M j, Y') }}
                                </span>
                            </div>

                            <!-- Tags -->
                            @if($blog->tags->count() > 0)
                                <div class="blog-tags">
                                    @foreach($blog->tags->take(3) as $tag)
                                        <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}" class="blog-tag">
                                            #{{ $tag->name }}
                                        </a>
                                    @endforeach
                                    @if($blog->tags->count() > 3)
                                        <span class="text-gray-400 text-xs">+{{ $blog->tags->count() - 3 }} more</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($blogs->hasPages())
                <div class="flex justify-center">
                    <div class="bg-white dark:bg-dark-800 rounded-xl shadow-lg p-4">
                        {{ $blogs->links() }}
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-dark-800 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">No Articles Found</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-8">
                        {{ $search ? 'Try searching with different keywords or browse our categories.' : 'We\'re working on adding new articles. Check back soon!' }}
                    </p>
                    @if($search || $currentCategory || $currentTag)
                        <a href="{{ route('blog.index') }}" class="btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                            </svg>
                            View All Articles
                        </a>
                    @endif
                </div>
            </div>
        @endif

        <!-- Popular Tags Section -->
        @if($popularTags->count() > 0)
            <div class="mt-20">
                <div class="text-center mb-10">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Explore by Topics</h3>
                    <p class="text-gray-600 dark:text-gray-400">Discover articles by popular tags</p>
                </div>
                <div class="flex flex-wrap justify-center gap-4">
                    @foreach($popularTags as $tag)
                        <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}"
                           class="group inline-flex items-center px-6 py-3 bg-white dark:bg-dark-800 border border-gray-200 dark:border-dark-700 rounded-full hover:border-primary-300 dark:hover:border-primary-700 hover:shadow-md transition-all duration-200">
                            <span class="text-gray-700 dark:text-gray-300 font-medium group-hover:text-primary-600 dark:group-hover:text-primary-400">
                                {{ $tag->name }}
                            </span>
                            <span class="ml-2 px-2 py-1 text-xs bg-gray-100 dark:bg-dark-700 text-gray-500 dark:text-gray-400 rounded-full group-hover:bg-primary-100 dark:group-hover:bg-primary-900/30">
                                {{ $tag->blogs_count }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Newsletter Signup -->
        <div class="mt-20">
            <div class="bg-gradient-to-r from-primary-600 to-primary-800 rounded-2xl p-8 md:p-12 text-center">
                <h3 class="text-2xl md:text-3xl font-bold text-white mb-4">Stay Updated</h3>
                <p class="text-primary-100 mb-8 max-w-2xl mx-auto">
                    Get the latest articles about website monitoring, performance tips, and industry insights delivered to your inbox.
                </p>
                <form class="max-w-md mx-auto flex gap-4">
                    <input type="email" placeholder="Enter your email"
                           class="flex-1 px-4 py-3 rounded-lg border-0 focus:ring-2 focus:ring-white">
                    <button type="submit" class="px-6 py-3 bg-white text-primary-600 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Add smooth scrolling and enhanced interactions
document.addEventListener('DOMContentLoaded', function() {
    // Search form enhancement
    const searchForm = document.querySelector('.blog-search form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const input = this.querySelector('input[name="search"]');
            if (!input.value.trim()) {
                e.preventDefault();
                input.focus();
            }
        });
    }

    // Card hover effects
    const blogCards = document.querySelectorAll('.blog-card');
    blogCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endpush
