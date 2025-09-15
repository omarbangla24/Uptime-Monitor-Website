@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-dark-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h1 class="text-2xl font-bold mb-4">Dashboard</h1>

                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg p-6 text-white">
                        <h3 class="text-lg font-semibold">Total Websites</h3>
                        <p class="text-3xl font-bold">{{ $stats['total_websites'] }}</p>
                    </div>
                    <div class="bg-gradient-to-r from-success-500 to-success-600 rounded-lg p-6 text-white">
                        <h3 class="text-lg font-semibold">Websites Up</h3>
                        <p class="text-3xl font-bold">{{ $stats['websites_up'] }}</p>
                    </div>
                    <div class="bg-gradient-to-r from-danger-500 to-danger-600 rounded-lg p-6 text-white">
                        <h3 class="text-lg font-semibold">Websites Down</h3>
                        <p class="text-3xl font-bold">{{ $stats['websites_down'] }}</p>
                    </div>
                    <div class="bg-gradient-to-r from-info-500 to-info-600 rounded-lg p-6 text-white">
                        <h3 class="text-lg font-semibold">Avg Response</h3>
                        <p class="text-3xl font-bold">{{ round($stats['avg_response_time']) }}ms</p>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
                    <div class="flex space-x-4">
                        <a href="{{ route('websites.create') }}" class="btn-primary">
                            Add Website
                        </a>
                        <a href="{{ route('websites.index') }}" class="btn-outline">
                            View All Websites
                        </a>
                    </div>
                </div>

                <!-- Recent Websites -->
                @if($websites->count() > 0)
                <div>
                    <h2 class="text-xl font-semibold mb-4">Your Websites</h2>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        @foreach($websites->take(6) as $website)
                        <div class="bg-gray-50 dark:bg-dark-700 rounded-lg p-4 border border-gray-200 dark:border-dark-600">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-semibold">{{ $website->name }}</h3>
                                <span class="{{ $website->current_status === 'up' ? 'status-online' : 'status-offline' }}">
                                    {{ ucfirst($website->current_status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $website->domain }}</p>
                            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                                <span>{{ $website->uptime_percentage }}% uptime</span>
                                <span>{{ $website->response_time }}ms</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="text-center py-8">
                    <p class="text-gray-600 dark:text-gray-400 mb-4">You haven't added any websites yet.</p>
                    <a href="{{ route('websites.create') }}" class="btn-primary">
                        Add Your First Website
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
