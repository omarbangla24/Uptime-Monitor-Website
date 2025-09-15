@extends('layouts.guest')

@section('title', $title)
@section('description', $description)

@section('content')
<div class="py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Contact Us</h1>
            <p class="text-xl text-gray-600 dark:text-gray-300">
                Have a question? We're here to help.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-8 p-4 bg-success-100 border border-success-200 text-success-800 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('contact.store') }}" method="POST" class="bg-white dark:bg-dark-800 rounded-lg shadow-lg p-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           class="form-input @error('name') border-danger-500 @enderror">
                    @error('name')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email *</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           class="form-input @error('email') border-danger-500 @enderror">
                    @error('email')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type *</label>
                    <select id="type" name="type" required
                            class="form-select @error('type') border-danger-500 @enderror">
                        <option value="">Select...</option>
                        <option value="general" {{ old('type') === 'general' ? 'selected' : '' }}>General</option>
                        <option value="support" {{ old('type') === 'support' ? 'selected' : '' }}>Support</option>
                        <option value="sales" {{ old('type') === sales ? 'selected' : '' }}>Sales</option>
                        <option value="technical" {{ old('type') === 'technical' ? 'selected' : '' }}>Technical</option>
                        <option value="billing" {{ old('type') === 'billing' ? 'selected' : '' }}>Billing</option>
                    </select>
                    @error('type')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Subject</label>
                    <input type="text" id="subject" name="subject" value="{{ old('subject') }}"
                           class="form-input @error('subject') border-danger-500 @enderror">
                    @error('subject')
                        <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Message *</label>
                <textarea id="message" name="message" rows="6" required
                          class="form-textarea @error('message') border-danger-500 @enderror">{{ old('message') }}</textarea>
                @error('message')
                    <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="text-center">
                <button type="submit" class="btn-primary px-8 py-3">
                    Send Message
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
