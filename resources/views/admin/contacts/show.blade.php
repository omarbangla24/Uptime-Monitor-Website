@extends('layouts.admin')

@section('title', 'View Contact Message')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <div class="mx-auto">
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                    Contact Message from {{ $contact->name }}
                </h2>
            </div>
             <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('admin.contacts.index') }}" class="btn-outline">
                    Back to Messages
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Message Details -->
            <div class="lg:col-span-2 space-y-6">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="text-lg font-medium">Message Details</h3>
                    </div>
                    <div class="admin-card-body">
                         <dl class="divide-y divide-gray-200 dark:divide-dark-700">
                            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="form-label-view">Subject</dt>
                                <dd class="form-value-view sm:col-span-2">{{ $contact->subject }}</dd>
                            </div>
                             <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="form-label-view">Message</dt>
                                <dd class="form-value-view sm:col-span-2 whitespace-pre-wrap">{{ $contact->message }}</dd>
                            </div>
                         </dl>
                    </div>
                </div>

                 <!-- Reply Form -->
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="text-lg font-medium">Send Reply</h3>
                    </div>
                    <form action="{{ route('admin.contacts.reply', $contact) }}" method="POST">
                        @csrf
                        <div class="admin-card-body space-y-4">
                            <div>
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" name="subject" id="subject" class="form-input" value="Re: {{ $contact->subject }}" required>
                            </div>
                            <div>
                                <label for="message" class="form-label">Message</label>
                                <textarea name="message" id="message" rows="8" class="form-textarea js-tinymce" required></textarea>
                            </div>
                        </div>
                        <div class="admin-card-footer">
                            <button type="submit" class="btn-primary">Send Reply</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar with Details & Status Update -->
            <div class="space-y-6">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="text-lg font-medium">Contact Details</h3>
                    </div>
                     <div class="admin-card-body">
                         <dl class="divide-y divide-gray-200 dark:divide-dark-700">
                             <div class="py-3 flex justify-between text-sm">
                                 <dt class="font-medium text-gray-500 dark:text-gray-400">Name</dt>
                                 <dd class="text-gray-900 dark:text-white">{{ $contact->name }}</dd>
                             </div>
                             <div class="py-3 flex justify-between text-sm">
                                 <dt class="font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                 <dd class="text-gray-900 dark:text-white">{{ $contact->email }}</dd>
                             </div>
                             <div class="py-3 flex justify-between text-sm">
                                 <dt class="font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                                 <dd class="text-gray-900 dark:text-white">{{ $contact->phone ?? 'N/A' }}</dd>
                             </div>
                             <div class="py-3 flex justify-between text-sm">
                                 <dt class="font-medium text-gray-500 dark:text-gray-400">Company</dt>
                                 <dd class="text-gray-900 dark:text-white">{{ $contact->company ?? 'N/A' }}</dd>
                             </div>
                              <div class="py-3 flex justify-between text-sm">
                                 <dt class="font-medium text-gray-500 dark:text-gray-400">Received</dt>
                                 <dd class="text-gray-900 dark:text-white">{{ $contact->created_at->diffForHumans() }}</dd>
                             </div>
                         </dl>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3 class="text-lg font-medium">Update Status</h3>
                    </div>
                     <form action="{{ route('admin.contacts.update', $contact) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="admin-card-body space-y-4">
                            <div>
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="new" @if($contact->status == 'new') selected @endif>New</option>
                                    <option value="in_progress" @if($contact->status == 'in_progress') selected @endif>In Progress</option>
                                    <option value="resolved" @if($contact->status == 'resolved') selected @endif>Resolved</option>
                                    <option value="closed" @if($contact->status == 'closed') selected @endif>Closed</option>
                                </select>
                            </div>
                             <div>
                                <label for="admin_notes" class="form-label">Admin Notes</label>
                                <textarea name="admin_notes" id="admin_notes" rows="4" class="form-textarea">{{ $contact->admin_notes }}</textarea>
                            </div>
                        </div>
                        <div class="admin-card-footer">
                            <button type="submit" class="btn-primary w-full">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

