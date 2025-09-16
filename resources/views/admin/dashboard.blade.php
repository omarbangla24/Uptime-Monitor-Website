@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h1 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                Dashboard Overview
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Welcome back! Here's what's happening with your platform.
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <span class="text-sm text-gray-500 dark:text-gray-400">
                Last updated: {{ now()->format('M j, Y g:i A') }}
            </span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-primary-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Users</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['total_users']) }}</div>
                                <div class="ml-2 text-sm text-success-600">{{ number_format($stats['active_users']) }} active</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paid Users -->
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-success-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Paid Users</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['paid_users']) }}</div>
                                <div class="ml-2 text-sm text-gray-600 dark:text-gray-400">subscribers</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Websites -->
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-info-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9m0 9c-5 0-9-4-9-9s4-9 9-9"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Websites</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['total_websites']) }}</div>
                                <div class="ml-2 text-sm text-success-600">{{ number_format($stats['websites_up']) }} up</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-warning-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Revenue</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white">${{ number_format($stats['total_revenue'], 2) }}</div>
                                <div class="ml-2 text-sm text-info-600">${{ number_format($stats['revenue_this_month'], 2) }} this month</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- User Growth Chart -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">User Growth (30 Days)</h3>
            </div>
            <div class="admin-card-body">
                <div class="h-64">
                    <canvas id="userGrowthChart"></canvas>
                </div>
            </div>
        </div>

        <!-- System Uptime -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">System Uptime (24h)</h3>
            </div>
            <div class="admin-card-body">
                <div class="h-64">
                    <canvas id="uptimeChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Users -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Recent Users</h3>
            </div>
            <div class="admin-card-body p-0">
                <div class="flow-root">
                    <ul class="divide-y divide-gray-200 dark:divide-dark-700">
                        @foreach($recentUsers as $user)
                        <li class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img class="h-8 w-8 rounded-full" src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $user->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="px-6 py-3 border-t border-gray-200 dark:border-dark-700">
                    <a href="{{ route('admin.users.index') }}" class="text-primary-600 hover:text-primary-500 text-sm font-medium">
                        View all users →
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Websites -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Recent Websites</h3>
            </div>
            <div class="admin-card-body p-0">
                <div class="flow-root">
                    <ul class="divide-y divide-gray-200 dark:divide-dark-700">
                        @foreach($recentWebsites->take(5) as $website)
                        <li class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $website->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $website->domain }}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="admin-badge-{{ $website->current_status === 'up' ? 'success' : 'danger' }}">
                                        {{ ucfirst($website->current_status) }}
                                    </span>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="px-6 py-3 border-t border-gray-200 dark:border-dark-700">
                    <a href="{{ route('admin.websites.index') }}" class="text-primary-600 hover:text-primary-500 text-sm font-medium">
                        View all websites →
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Contacts -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Recent Contacts</h3>
            </div>
            <div class="admin-card-body p-0">
                <div class="flow-root">
                    <ul class="divide-y divide-gray-200 dark:divide-dark-700">
                        @foreach($recentContacts as $contact)
                        <li class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $contact->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($contact->message, 40) }}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="admin-badge-{{ $contact->status === 'new' ? 'warning' : 'success' }}">
                                        {{ ucfirst($contact->status) }}
                                    </span>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="px-6 py-3 border-t border-gray-200 dark:border-dark-700">
                    <a href="{{ route('admin.contacts.index') }}" class="text-primary-600 hover:text-primary-500 text-sm font-medium">
                        View all messages →
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // User Growth Chart
    const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
    const userGrowthData = @json($userGrowthData);

    new Chart(userGrowthCtx, {
        type: 'linelabels,
            datasets: [{
                rowthData.data,
                borderColor: '#3B82F6',
                backgroundColor: '#3B82F620',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // System Uptime Chart
    const uptimeCtx = document.getElementById('uptimeChart').getContext('2d');
    const uptimeData = @json($uptimeData);

    new Chart(uptimeCtx, {
        type: labels: uptimeData.labels,
            datasets: [{
                label.data,
                borderColor: '#10B981',
                backgroundColor: '#10B98120',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
});
</script>
@endpush
