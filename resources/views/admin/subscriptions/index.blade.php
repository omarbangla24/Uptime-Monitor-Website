@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-semibold text-gray-800">Subscription Plans</h1>
            <a href="{{ route('admin.subscriptions.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Create Plan
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

        <div class="overflow-x-auto bg-white shadow-md rounded-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Interval</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Websites Limit</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Active</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Users</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payments</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($plans as $plan)
                    <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }}">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $plan->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($plan->interval) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $plan->formatted_price }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $plan->websites_limit === 0 ? 'Unlimited' : $plan->websites_limit }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $plan->is_active ? 'Yes' : 'No' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $plan->users_count ?? $plan->users->count() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $plan->payments_count ?? $plan->payments->count() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex space-x-1">
                                <a href="{{ route('admin.subscriptions.show', $plan) }}"
                                   class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-blue-500 hover:bg-blue-600 rounded">
                                    View
                                </a>
                                <a href="{{ route('admin.subscriptions.edit', $plan) }}"
                                   class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-yellow-500 hover:bg-yellow-600 rounded">
                                    Edit
                                </a>
                                <form action="{{ route('admin.subscriptions.toggleStatus', $plan) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-white {{ $plan->is_active ? 'bg-gray-600 hover:bg-gray-700' : 'bg-green-600 hover:bg-green-700' }} rounded">
                                        {{ $plan->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.subscriptions.destroy', $plan) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this plan?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center">No subscription plans found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $plans->links() }}
            </div>
        </div>
    </div>
@endsection