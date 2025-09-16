@extends('layouts.app')

@section('title', $blog->title)

@section('content')
<div class="bg-white dark:bg-gray-900 py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            @if($blog->featured_image)
                <img class="w-full h-auto object-cover" src="{{ asset('storage/' . $blog->featured_image) }}" alt="{{ $blog->title }}">
            @endif

            <div class="p-6 md:p-8">
                <header class="mb-8">
                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                        <a href="#" class="font-medium text-primary-600 dark:text-primary-400 hover:underline">{{ $blog->category->name }}</a>
                        <span class="mx-2">&bull;</span>
                        <time datetime="{{ $blog->published_at->toIso8601String() }}">{{ $blog->published_at->format('M d, Y') }}</time>
                        <span class="mx-2">&bull;</span>
                        <span>{{ $blog->reading_time }} min read</span>
                    </div>
                    <h1 class="mt-4 text-3xl md:text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white leading-tight">
                        {{ $blog->title }}
                    </h1>
                    <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">
                        {{ $blog->excerpt }}
                    </p>
                </header>

                <div class="flex items-center space-x-4 mt-6 mb-8">
                    <img class="h-12 w-12 rounded-full object-cover" src="{{ $blog->author->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($blog->author->name) }}" alt="{{ $blog->author->name }}">
                    <div>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $blog->author->name }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Content Creator</p>
                    </div>
                </div>

                <div class="prose prose-lg dark:prose-invert max-w-none">
                    {!! $blog->content !!}
                </div>

                <footer class="mt-10 pt-8 border-t border-gray-200 dark:border-gray-700">
                    @if($blog->tags->isNotEmpty())
                        <div class="mb-6">
                            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Tags</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($blog->tags as $tag)
                                    <a href="#" class="inline-block bg-gray-100 dark:bg-gray-700 rounded-full px-3 py-1 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                        #{{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Share this post</h3>
                        <div class="flex space-x-4">
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($blog->title) }}" target="_blank" class="text-gray-400 hover:text-primary-500 dark:hover:text-primary-400 transition-colors">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.71v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path></svg>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="text-gray-400 hover:text-primary-500 dark:hover:text-primary-400 transition-colors">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path></svg>
                            </a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($blog->title) }}&summary={{ urlencode($blog->excerpt) }}" target="_blank" class="text-gray-400 hover:text-primary-500 dark:hover:text-primary-400 transition-colors">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd"></path></svg>
                            </a>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</div>
@endsection
