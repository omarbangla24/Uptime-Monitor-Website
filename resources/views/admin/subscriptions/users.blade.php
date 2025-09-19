@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-semibold text-gray-800">Users on {{ $subscription->name }} Plan</h1>
            <a href="{{ route('admin.subscriptions.show', $subscription) }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                Back to plan
            </a>
        </div>

        <div class="bg-white shadow-md rounded-md p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subscription Starts</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subscription Ends</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }}">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($user->subscription_status ?? 'inactive') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->subscription_starts_at ? $user->subscription_starts_at->format('M d, Y') : '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->subscription_ends_at ? $user->subscription_ends_at->format('M d, Y') : '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $lastPayment = $user->payments->sortByDesc('created_at')->first();
                                @endphp
                                {{ $lastPayment ? $lastPayment->formatted_amount . ' on ' . $lastPayment->created_at->format('M d, Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-blue-500 hover:bg-blue-600 rounded">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center">No users subscribed to this plan.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection