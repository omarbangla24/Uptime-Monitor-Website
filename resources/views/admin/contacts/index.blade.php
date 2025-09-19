@extends('layouts.admin')

@section('title', 'Contact Messages')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Contact Messages</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Manage inquiries from the contact form.</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="mt-6 bg-white dark:bg-dark-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="form-label">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           class="form-input mt-1" placeholder="Search messages...">
                </div>
                <div>
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select mt-1">
                        <option value="">All Statuses</option>
                        <option value="new" @if(request('status') === 'new') selected @endif>New</option>
                        <option value="in_progress" @if(request('status') === 'in_progress') selected @endif>In Progress</option>
                        <option value="resolved" @if(request('status') === 'resolved') selected @endif>Resolved</option>
                        <option value="closed" @if(request('status') === 'closed') selected @endif>Closed</option>
                    </select>
                </div>
                <div>
                    <label for="type" class="form-label">Type</label>
                     <select name="type" id="type" class="form-select mt-1">
                        <option value="">All Types</option>
                        <option value="general" @if(request('type') === 'general') selected @endif>General</option>
                        <option value="support" @if(request('type') === 'support') selected @endif>Support</option>
                        <option value="sales" @if(request('type') === 'sales') selected @endif>Sales</option>
                        <option value="technical" @if(request('type') === 'technical') selected @endif>Technical</option>
                        <option value="billing" @if(request('type') === 'billing') selected @endif>Billing</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn-primary w-full">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Messages Table -->
    <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Sender</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Type</th>
                                <th>Received</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-dark-800 divide-y divide-gray-200 dark:divide-dark-700">
                            @forelse($contacts as $contact)
                                <tr>
                                    <td class="admin-table-td">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $contact->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $contact->email }}</div>
                                    </td>
                                    <td class="admin-table-td">{{ Str::limit($contact->subject, 50) }}</td>
                                    <td class="admin-table-td">
                                        <span class="admin-badge-{{ $contact->status === 'resolved' || $contact->status === 'closed' ? 'success' : ($contact->status === 'new' ? 'danger' : 'warning') }}">
                                            {{ ucfirst(str_replace('_', ' ', $contact->status)) }}
                                        </span>
                                    </td>
                                    <td class="admin-table-td">{{ ucfirst($contact->type) }}</td>
                                    <td class="admin-table-td">{{ $contact->created_at->format('M j, Y H:i') }}</td>
                                    <td class="admin-table-td">
                                        <div class="flex items-center space-x-4">
                                            <a href="{{ route('admin.contacts.show', $contact) }}" class="text-primary-600 hover:text-primary-900">View</a>
                                            <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900"
                                                        onclick="return confirm('Are you sure you want to delete this message?')">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        No contact messages found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if($contacts->hasPages())
        <div class="mt-6">
            {{ $contacts->links() }}
        </div>
    @endif
</div>
@endsection

