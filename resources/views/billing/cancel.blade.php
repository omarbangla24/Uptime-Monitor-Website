@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-8 text-center">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Payment Cancelled</h1>
        <p class="text-gray-600 mb-6">Your payment was cancelled. No charges were made.</p>

        <a href="{{ route('billing.plans') }}" class="block w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
            Back to Plans
        </a>
    </div>
</div>
@endsection
