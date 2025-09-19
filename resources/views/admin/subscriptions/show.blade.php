@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-semibold text-gray-800">{{ $subscription->name }} Plan Details</h1>
            <a href="{{ route('admin.subscriptions.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                Back to plans
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white shadow-md rounded-md p-6">
                <h2 class="text-lg font-semibold mb-4">Plan Information</h2>
                <p class="mb-1"><span class="font-medium">Name:</span> {{ $subscription->name }}</p>
                <p class="mb-1"><span class="font-medium">Interval:</span> {{ ucfirst($subscription->interval) }}</p>
                <p class="mb-1"><span class="font-medium">Price:</span> {{ $subscription->formatted_price }}</p>
                <p class="mb-1"><span class="font-medium">Websites Limit:</span> {{ $subscription->websites_limit === 0 ? 'Unlimited' : $subscription->websites_limit }}</p>
                <p class="mb-1"><span class="font-medium">Checks per Minute:</span> {{ $subscription->checks_per_minute }}</p>
                <p class="mb-1"><span class="font-medium">Data Retention:</span> {{ $subscription->data_retention_days }} days</p>
                <p class="mb-1"><span class="font-medium">Team Members:</span> {{ $subscription->team_members }}</p>
                <p class="mb-1"><span class="font-medium">Status:</span> {{ $subscription->is_active ? 'Active' : 'Inactive' }}</p>
                <p class="mb-1"><span class="font-medium">Features:</span>
                    @if(is_array($subscription->features))
                        <ul class="list-disc list-inside ml-4">
                            @foreach($subscription->features as $feature)
                                <li>{{ ucfirst(str_replace('_', ' ', $feature)) }}</li>
                            @endforeach
                        </ul>
                    @else
                        {{ $subscription->features }}
                    @endif
                </p>
                <p><span class="font-medium">Description:</span> {{ $subscription->description }}</p>
            </div>
            <div class="md:col-span-2 bg-white shadow-md rounded-md p-6">
                <h2 class="text-lg font-semibold mb-4">Statistics</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <div class="text-sm font-medium text-gray-500 uppercase mb-1">Total Users</div>
                        <div class="text-xl font-bold text-gray-800">{{ $stats['total_users'] }}</div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500 uppercase mb-1">Active Users</div>
                        <div class="text-xl font-bold text-gray-800">{{ $stats['active_users'] }}</div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500 uppercase mb-1">Total Revenue</div>
                        <div class="text-xl font-bold text-gray-800">${{ number_format($stats['total_revenue'], 2) }}</div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500 uppercase mb-1">Revenue (last 30 days)</div>
                        <div class="text-xl font-bold text-gray-800">${{ number_format($stats['monthly_revenue'], 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white shadow-md rounded-md p-6">
            <h2 class="text-lg font-semibold mb-4">Payments (latest)</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid At</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($subscription->payments->take(10) as $payment)
                        <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }}">
                            <td class="px-6 py-4 whitespace-nowrap">{{ optional($payment->user)->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $payment->formatted_amount }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($payment->status) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $payment->paid_at ? $payment->paid_at->format('M d, Y') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center">No payments found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <a href="{{ route('admin.subscriptions.users', $subscription) }}"
               class="inline-flex items-center mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                View Users
            </a>
        </div>
    </div>
@endsection