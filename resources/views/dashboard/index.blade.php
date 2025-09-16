@extends('layouts.app')

@section('title', 'Dashboard - Website Monitoring')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-dark-900">
    <!-- Header -->
    <div class="bg-white dark:bg-dark-800 shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
                    <p class="text-gray-600 dark:text-gray-400">Welcome back, {{ $user->name }}!</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('websites.create') }}" class="btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Website
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Websites -->
            <div class="bg-white dark:bg-dark-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-primary-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9m0 9c-5 0-9-4-9-9s4-9 9-9"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Websites</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['total_websites'] }}</div>
                                    <div class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                        / {{ $stats['websites_limit'] === 0 ? '∞' : $stats['websites_limit'] }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-dark-700 px-5 py-3">
                    <div class="text-sm">
                        <span class="text-gray-600 dark:text-gray-400">{{ $stats['websites_remaining'] }} slots remaining</span>
                    </div>
                </div>
            </div>

            <!-- Websites Up -->
            <div class="bg-white dark:bg-dark-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-success-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Websites Up</dt>
                                <dd class="text-2xl font-semibold text-success-600">{{ $stats['websites_up'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-dark-700 px-5 py-3">
                    <div class="text-sm">
                        <span class="text-success-600 font-medium">{{ $stats['overall_uptime'] }}%</span>
                        <span class="text-gray-600 dark:text-gray-400"> overall uptime</span>
                    </div>
                </div>
            </div>

            <!-- Websites Down -->
            <div class="bg-white dark:bg-dark-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-danger-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Websites Down</dt>
                                <dd class="text-2xl font-semibold text-danger-600">{{ $stats['websites_down'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-dark-700 px-5 py-3">
                    <div class="text-sm">
                        <span class="text-danger-600 font-medium">{{ $stats['incidents_7d'] }}</span>
                        <span class="text-gray-600 dark:text-gray-400"> incidents this week</span>
                    </div>
                </div>
            </div>

            <!-- Average Response Time -->
            <div class="bg-white dark:bg-dark-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-info-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Avg Response</dt>
                                <dd class="text-2xl font-semibold text-info-600">{{ $stats['avg_response_time'] }}ms</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-dark-700 px-5 py-3">
                    <div class="text-sm">
                        <span class="text-gray-600 dark:text-gray-400">{{ $stats['total_checks_24h'] }} checks today</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Response Time Chart -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-dark-800 shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-dark-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Response Times (Last 24 Hours)</h3>
                    </div>
                    <div class="p-6">
                        <div class="h-64">
                            <canvas id="responseTimeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-dark-800 shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-dark-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activity</h3>
                    </div>
                    <div class="flow-root">
                        @if($recentActivity->count() > 0)
                            <ul class="divide-y divide-gray-200 dark:divide-dark-700">
                                @foreach($recentActivity as $activity)
                                <li class="px-6 py-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="w-2 h-2 rounded-full {{ $activity['status'] === 'up' ? 'bg-success-500' : 'bg-danger-500' }}"></div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                {{ $activity['website'] }}
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                                {{ $activity['message'] }}
                                            </p>
                                        </div>
                                        <div class="flex-shrink-0 text-sm text-gray-400">
                                            {{ $activity['checked_at']->diffForHumans() }}
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="px-6 py-8 text-center">
                                <p class="text-gray-500 dark:text-gray-400">No recent activity</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Websites Overview -->
        @if($websites->count() > 0)
        <div class="mt-8">
            <div class="bg-white dark:bg-dark-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-dark-700 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Your Websites</h3>
                    <a href="{{ route('websites.index') }}" class="text-primary-600 hover:text-primary-500 text-sm font-medium">
                        View all →
                    </a>
                </div>
                <div class="overflow-hidden">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-gray-200 dark:divide-dark-700">
                        @foreach($websites->take(6) as $website)
                        <div class="p-6 hover:bg-gray-50 dark:hover:bg-dark-700 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 rounded-full {{ $website->current_status === 'up' ? 'bg-success-500 pulse-green' : ($website->current_status === 'down' ? 'bg-danger-500 pulse-red' : 'bg-warning-500') }}"></div>
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white">{{ $website->name }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $website->domain }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $website->uptime_percentage }}%</p>
                                    @if($website->response_time)
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $website->response_time }}ms</p>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('websites.show', $website) }}" class="text-primary-600 hover:text-primary-500 text-sm font-medium">
                                    View details →
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Empty State -->
        <div class="mt-8">
            <div class="bg-white dark:bg-dark-800 shadow rounded-lg">
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9m0 9c-5 0-9-4-9-9s4-9 9-9"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No websites</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by adding your first website.</p>
                    <div class="mt-6">
                        <a href="{{ route('websites.create') }}" class="btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Website
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('responseTimeChart').getContext('2d');
    const uptimeData = @json($uptimeData);

    new Chart(ctx, { uptimeData.labels,
            datasets: uptimeData.datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + (context.parsed.y || 'Offline') + (context.parsed.y ? 'ms' : '');
                        }
                    }
                }
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Time'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Response Time (ms)'
                    },
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
