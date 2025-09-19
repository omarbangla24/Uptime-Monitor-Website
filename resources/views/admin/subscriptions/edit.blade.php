@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Edit Subscription Plan</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.subscriptions.update', $subscription) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $subscription->name) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                    <input type="number" step="0.01" id="price" name="price" value="{{ old('price', $subscription->price) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="interval" class="block text-sm font-medium text-gray-700">Interval</label>
                    <select id="interval" name="interval" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="monthly" {{ old('interval', $subscription->interval) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="yearly" {{ old('interval', $subscription->interval) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                    </select>
                </div>
                <div>
                    <label for="websites_limit" class="block text-sm font-medium text-gray-700">Websites Limit (0 = unlimited)</label>
                    <input type="number" id="websites_limit" name="websites_limit" value="{{ old('websites_limit', $subscription->websites_limit) }}" min="0" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="checks_per_minute" class="block text-sm font-medium text-gray-700">Checks per Minute</label>
                    <input type="number" id="checks_per_minute" name="checks_per_minute" value="{{ old('checks_per_minute', $subscription->checks_per_minute) }}" min="1" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="data_retention_days" class="block text-sm font-medium text-gray-700">Data Retention (days)</label>
                    <input type="number" id="data_retention_days" name="data_retention_days" value="{{ old('data_retention_days', $subscription->data_retention_days) }}" min="1" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="team_members" class="block text-sm font-medium text-gray-700">Team Members</label>
                    <input type="number" id="team_members" name="team_members" value="{{ old('team_members', $subscription->team_members) }}" min="1" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700">Sort Order</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $subscription->sort_order) }}" min="0"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description" rows="3" required
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ old('description', $subscription->description) }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label for="features" class="block text-sm font-medium text-gray-700">Additional Features (JSON or comma separated)</label>
                    <textarea id="features" name="features" rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ old('features', is_array($subscription->features) ? json_encode($subscription->features) : $subscription->features) }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <div class="flex flex-wrap -mx-2">
                        <div class="flex items-center mx-2 mb-2">
                            <input id="ssl_monitoring" name="ssl_monitoring" type="checkbox" value="1"
                                   {{ old('ssl_monitoring', $subscription->ssl_monitoring) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <label for="ssl_monitoring" class="ml-2 text-sm text-gray-700">SSL Monitoring</label>
                        </div>
                        <div class="flex items-center mx-2 mb-2">
                            <input id="dns_monitoring" name="dns_monitoring" type="checkbox" value="1"
                                   {{ old('dns_monitoring', $subscription->dns_monitoring) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <label for="dns_monitoring" class="ml-2 text-sm text-gray-700">DNS Monitoring</label>
                        </div>
                        <div class="flex items-center mx-2 mb-2">
                            <input id="domain_expiry_monitoring" name="domain_expiry_monitoring" type="checkbox" value="1"
                                   {{ old('domain_expiry_monitoring', $subscription->domain_expiry_monitoring) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <label for="domain_expiry_monitoring" class="ml-2 text-sm text-gray-700">Domain Expiry Monitoring</label>
                        </div>
                        <div class="flex items-center mx-2 mb-2">
                            <input id="email_alerts" name="email_alerts" type="checkbox" value="1"
                                   {{ old('email_alerts', $subscription->email_alerts) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <label for="email_alerts" class="ml-2 text-sm text-gray-700">Email Alerts</label>
                        </div>
                        <div class="flex items-center mx-2 mb-2">
                            <input id="sms_alerts" name="sms_alerts" type="checkbox" value="1"
                                   {{ old('sms_alerts', $subscription->sms_alerts) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <label for="sms_alerts" class="ml-2 text-sm text-gray-700">SMS Alerts</label>
                        </div>
                        <div class="flex items-center mx-2 mb-2">
                            <input id="webhook_alerts" name="webhook_alerts" type="checkbox" value="1"
                                   {{ old('webhook_alerts', $subscription->webhook_alerts) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <label for="webhook_alerts" class="ml-2 text-sm text-gray-700">Webhook Alerts</label>
                        </div>
                        <div class="flex items-center mx-2 mb-2">
                            <input id="api_access" name="api_access" type="checkbox" value="1"
                                   {{ old('api_access', $subscription->api_access) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <label for="api_access" class="ml-2 text-sm text-gray-700">API Access</label>
                        </div>
                        <div class="flex items-center mx-2 mb-2">
                            <input id="white_label" name="white_label" type="checkbox" value="1"
                                   {{ old('white_label', $subscription->white_label) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <label for="white_label" class="ml-2 text-sm text-gray-700">White Label</label>
                        </div>
                        <div class="flex items-center mx-2 mb-2">
                            <input id="is_active" name="is_active" type="checkbox" value="1"
                                   {{ old('is_active', $subscription->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex space-x-2">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Update
                </button>
                <a href="{{ route('admin.subscriptions.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection