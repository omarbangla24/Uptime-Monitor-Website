@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="mb-6">
            <svg class="w-16 h-16 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-4">Payment Successful!</h1>
        <p class="text-gray-600 mb-6">Your subscription has been updated successfully.</p>

        <div class="space-y-3">
            <a href="{{ route('dashboard') }}" class="block w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                Go to Dashboard
            </a>
            <a href="{{ route('billing.index') }}" class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition duration-200">
                View Billing
            </a>
        </div>
    </div>
</div>
@endsection
