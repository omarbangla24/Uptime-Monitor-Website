@extends('layouts.guest')

@section('title', $title)
@section('description', $description)

@section('content')
<div class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                Choose Your Plan
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                Start monitoring your website for free. Upgrade anytime as your needs grow.
            </p>
        </div>

        @include('components.pricing-cards')
    </div>
</div>
@endsection
