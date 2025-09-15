@extends('layouts.guest')

@section('title', 'Website Uptime Monitoring - Real-time Monitoring & Alerts')
@section('description', 'Monitor your website uptime with real-time alerts, SSL certificate monitoring, DNS checks, and performance analytics. Get instant notifications when your site goes down.')



<div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-primary-50 via-white to-primary-100 dark:from-dark-900 dark:via-dark-800 dark:to-dark-900 overflow-hidden">
        <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Hero Content -->
                <div class="space-y-8">
                    <div>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white leading-tight">
                            Monitor Your Website
                            <span class="text-gradient">24/7</span>
                        </h1>
                        <p class="mt-6 text-xl text-gray-600 dark:text-gray-300 leading-relaxed">
                            Get instant alerts when your website goes down. Monitor uptime, SSL certificates, DNS records, and performance from multiple locations worldwide.
                        </p>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        @guest
                            <a href="{{ route('register') }}" class="btn-primary text-center px-8 py-4 text-lg">
                                Start Free Monitoring
                                <svg class="w-5 h-5 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                            <a href="#live-demo" class="btn-outline text-center px-8 py-4 text-lg">
                                View Live Demo
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="btn-primary text-center px-8 py-4 text-lg">
                                Go to Dashboard
                            </a>
                        @endguest
                    </div>

                    <!-- Trust Indicators -->
                    <div class="flex items-center space-x-8 text-sm text-gray-500 dark:text-gray-400">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-success-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Free Plan Available
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-success-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            No Credit Card Required
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-success-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Setup in 2 Minutes
                        </div>
                    </div>
                </div>

                <!-- Hero Image/Stats -->
                <div class="relative">
                    <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-2xl p-8 border border-gray-100 dark:border-dark-700">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Global Statistics</h3>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-primary-600">{{ number_format($stats['total_websites']) }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Websites Monitored</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-success-600">{{ $stats['uptime_percentage'] }}%</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Average Uptime</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-info-600">{{ $stats['avg_response_time'] }}ms</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Avg Response Time</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-warning-600">{{ number_format($stats['total_checks']) }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Total Checks</div>
                            </div>
                        </div>

                        <!-- Live Status Indicator -->
                        <div class="mt-6 p-4 bg-success-50 dark:bg-success-900/20 rounded-lg border border-success-200 dark:border-success-800">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-success-500 rounded-full pulse-green mr-3"></div>
                                <span class="text-success-700 dark:text-success-300 font-medium">All Systems Operational</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Live Website Status Updates -->
    <section id="live-demo" class="py-20 bg-white dark:bg-dark-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    Live Website Status Updates
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    See real-time monitoring data from websites around the world. Updates every 30 seconds.
                </p>
            </div>

            <!-- Status Grid -->
            <div x-data="{
                websites: {{ $liveUpdates->toJson() }},
                loading: false,
                async refreshData() {
                    this.loading = true;
                    try {
                        const response = await fetch('/api/live-updates');
                        const data = await response.json();
                        this.websites = data;
                    } catch (error) {
                        console.error(' finally {
                        this.loading = false;
                    }
                }
            }"
            x-init="setInterval(() => refreshData(), 30000)"
            class="space-y-8">

                <!-- Refresh Button -->
                <div class="text-center">
                    <button @click="refreshData()"
                            :disabled="loading"
                            :class="loading ? 'opacity-50 cursor-not-allowed' : ''"
                            class="btn-outline">
                        <svg x-show="!loading" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <svg x-show="loading" class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="loading ? 'Refreshing...' : 'Refresh Data'"></span>
                    </button>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                        Last updated: <span x-text="new Date().toLocaleTimeString()"></span>
                    </p>
                </div>

                <!-- Website Status Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <template x-for="website in websites.slice(0, 20)" :key="website.id || website.name">
                        <div class="bg-gray-50 dark:bg-dark-800 rounded-xl p-6 border border-gray-200 dark:border-dark-700 hover:shadow-lg transition-all duration-300 hover:border-primary-300 dark:hover:border-primary-700">
                            <!-- Website Header -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div :class="{
                                        'bg-success-500 pulse-green': website.status === 'up',
                                        'bg-danger-500 pulse-red': website.status === 'down',
                                        'bg-warning-500': website.status === 'unknown'
                                    }" class="w-3 h-3 rounded-full"></div>
                                    <span class="font-medium text-gray-900 dark:text-white" x-text="website.name"></span>
                                </div>
                                <span :class="{
                                    'text-success-600 bg-success-100 dark:bg-success-900/30': website.status === 'up',
                                    'text-danger-600 bg-danger-100 dark:bg-danger-900/30': website.status === 'down',
                                    'text-warning-600 bg-warning-100 dark:bg-warning-900/30': website.status === 'unknown'
                                }" class="px-2 py-1 rounded-full text-xs font-medium capitalize" x-text="website.status"></span>
                            </div>

                            <!-- Website Details -->
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Domain:</span>
                                    <span class="text-gray-900 dark:text-white font-mono text-xs" x-text="website.domain"></span>
                                </div>
                                <div class="flex justify-between" x-show="website.response_time">
                                    <span class="text-gray-600 dark:text-gray-400">Response:</span>
                                    <span class="text-gray-900 dark:text-white" x-text="website.response_time + 'ms'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Uptime:</span>
                                    <span :class="{
                                        'text-success-600': website.uptime_percentage >= 99,
                                        'text-warning-600': website.uptime_percentage >= 95 && website.uptime_percentage < 99,
                                        'text-danger-600': website.uptime_percentage < 95
                                    }" class="font-semibold" x-text="website.uptime_percentage + '%'"></span>
                                </div>
                                <div class="flex justify-between" x-show="website.last_checked">
                                    <span class="text-gray-600 dark:text-gray-400">Checked:</span>
                                    <span class="text-gray-900 dark:text-white text-xs" x-text="website.last_checked ? new Date(website.last_checked).toLocaleTimeString() : 'Never'"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </section>

    <!-- Recently Checked Websites Feed -->
    <section class="py-16 bg-gray-50 dark:bg-dark-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                    Recently Checked Websites
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-300">
                    Latest monitoring activity from our network
                </p>
            </div>

            <div class="bg-white dark:bg-dark-900 rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-dark-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Activity Feed</h3>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-dark-700">
                    @foreach($recentlyChecked as $website)
                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-dark-800 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $website->current_status === 'up' ? 'bg-success-100 dark:bg-success-900/30' : 'bg-danger-100 dark:bg-danger-900/30' }}">
                                    @if($website->current_status === 'up')
                                        <svg class="w-5 h-5 text-success-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-danger-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-white">{{ $website->name }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400 font-mono">{{ $website->domain }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="flex items-center space-x-4">
                                    @if($website->response_time)
                                        <div class="text-sm">
                                            <span class="text-gray-600 dark:text-gray-400">Response:</span>
                                            <span class="font-semibold text-gray-900 dark:text-white">{{ $website->response_time }}ms</span>
                                        </div>
                                    @endif
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $website->last_checked_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Features Overview -->
    <section class="py-20 bg-white dark:bg-dark-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    Complete Website Monitoring Suite
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    Everything you need to keep your websites running smoothly, from basic uptime monitoring to advanced SSL and DNS checking.
                </p>
            </div>

            <!-- Feature Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Performance Check -->
                <div class="group bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20 rounded-2xl p-8 hover:shadow-xl transition-all duration-300 border border-primary-200 dark:border-primary-800">
                    <div class="w-12 h-12 bg-primary-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Performance Check</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">
                        Monitor response times, page load speeds, and performance metrics from multiple global locations.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-success-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Global monitoring locations
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-success-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Response time tracking
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-success-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Detailed performance reports
                        </li>
                    </ul>
                </div>

                <!-- Add more feature cards here as needed -->
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="py-20 bg-gray-50 dark:bg-dark-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    Choose Your Monitoring Plan
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300">
                    Start free, upgrade as you grow. No hidden fees.
                </p>
            </div>

            @include('components.pricing-cards')
        </div>
    </section>

    <!-- Blog Preview Section -->
    @if($featuredBlogs->count() > 0)
    <section class="py-20 bg-white dark:bg-dark-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        Latest From Our Blog
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300">
                        Tips, tutorials, and insights about website monitoring
                    </p>
                </div>
                <a href="{{ route('blog.index') }}" class="btn-outline">
                    View All Posts
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredBlogs as $blog)
                <article class="bg-gray-50 dark:bg-dark-800 rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 group">
                    @if($blog->featured_image_url)
                    <div class="aspect-video overflow-hidden">
                        <img src="{{ $blog->featured_image_url }}"
                             alt="{{ $blog->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    @endif
                    <div class="p-6">
                        <div class="flex items-center space-x-2 mb-3">
                            <span class="px-3 py-1 text-xs font-medium rounded-full"
                                  style="background-color: {{ $blog->category->color }}20; color: {{ $blog->category->color }}">
                                {{ $blog->category->name }}
                            </span>
                            <span class="text-gray-500 dark:text-gray-400 text-sm">•</span>
                            <span class="text-gray-500 dark:text-gray-400 text-sm">{{ $blog->reading_time_text }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                            <a href="{{ route('blog.show', $blog) }}">{{ $blog->title }}</a>
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-3">{{ $blog->excerpt }}</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <img src="{{ $blog->author->avatar_url }}"
                                     alt="{{ $blog->author->name }}"
                                     class="w-8 h-8 rounded-full">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $blog->author->name }}</span>
                            </div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $blog->published_at->format('M j, Y') }}
                            </span>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- FAQ Section -->
    <section class="py-20 bg-gray-50 dark:bg-dark-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    Frequently Asked Questions
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300">
                    Everything you need to know about our monitoring service
                </p>
            </div>

            <div x-data="{ openFaq: null }" class="space-y-4">
                <!-- FAQ Items -->
                <div class="bg-white dark:bg-dark-900 rounded-lg shadow-sm border border-gray-200 dark:border-dark-700">
                    <button @click="openFaq = openFaq === 1 ? null : 1"
                            class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 dark:hover:bg-dark-800 transition-colors">
                        <span class="font-semibold text-gray-900 dark:text-white">How often do you check my website?</span>
                        <svg :class="{ 'rotate-180': openFaq === 1 }" class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 1" x-transition class="px-6 pb-4">
                        <p class="text-gray-600 dark:text-gray-300">We check your website every 1-5 minutes depending on your plan. Our monitoring runs 24/7 from multiple locations worldwide to ensure comprehensive coverage and accurate uptime reporting.</p>
                    </div>
                </div>

                <!-- Add more FAQ items as needed -->
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-br from-primary-600 to-primary-800 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">
                Ready to Start Monitoring Your Website?
            </h2>
            <p class="text-xl text-primary-100 mb-8 max-w-2xl mx-auto">
                Join thousands of developers and businesses who trust us to keep their websites running smoothly. Get started in less than 2 minutes.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @guest
                    <a href="{{ route('register') }}" class="bg-white text-primary-600 hover:bg-gray-100 px-8 py-4 rounded-lg font-semibold text-lg transition-colors inline-flex items-center justify-center">
                        Start Free Trial
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                    <a href="{{ route('contact') }}" class="border-2 border-white text-white hover:bg-white hover:text-primary-600 px-8 py-4 rounded-lg font-semibold text-lg transition-colors inline-flex items-center justify-center">
                        Contact Sales
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="bg-white text-primary-600 hover:bg-gray-100 px-8 py-4 rounded-lg font-semibold text-lg transition-colors inline-flex items-center justify-center">
                        Go to Dashboard
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                @endguest
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
// Auto-refresh live updates every 30 seconds
setInterval(() => {
    if (typeof refreshLiveData === 'function') {
        refreshLiveData();
    }
}, 30000);

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>
@endpush
