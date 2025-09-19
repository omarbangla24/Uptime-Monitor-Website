@extends('layouts.app')

@section('title', $title ?? 'Upgrade Your Plan')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 sm:text-5xl">
                {{ $title ?? 'Choose Your Plan' }}
            </h1>
            <p class="mt-4 text-xl text-gray-600 max-w-2xl mx-auto">
                {{ $description ?? 'Select the perfect plan for your monitoring needs' }}
            </p>
        </div>

        <!-- Pricing Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            @foreach($plans as $plan)
            <div class="relative bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden {{ $plan->slug === 'pro' ? 'ring-2 ring-blue-500 scale-105' : '' }}">

                @if($plan->slug === 'pro')
                <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-4 py-1 rounded-full text-sm font-medium">
                        Most Popular
                    </div>
                </div>
                @endif

                <div class="px-6 py-8">
                    <!-- Plan Name -->
                    <h3 class="text-2xl font-bold text-gray-900 text-center mb-2">
                        {{ $plan->name }}
                    </h3>

                    <!-- Price -->
                    <div class="text-center mb-6">
                        @if($plan->price == 0)
                            <span class="text-4xl font-bold text-gray-900">Free</span>
                        @else
                            <span class="text-4xl font-bold text-gray-900">${{ number_format($plan->price, 0) }}</span>
                            <span class="text-gray-500 ml-1">/{{ $plan->interval }}</span>
                        @endif
                    </div>

                    <!-- Description -->
                    <p class="text-gray-600 text-center mb-6 text-sm">
                        {{ $plan->description }}
                    </p>

                    <!-- Features -->
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-700 text-sm">
                                {{ $plan->websites_limit === 0 ? 'Unlimited' : $plan->websites_limit }}
                                {{ $plan->websites_limit === 1 ? 'website' : 'websites' }}
                            </span>
                        </li>

                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-700 text-sm">
                                {{ $plan->checks_per_minute }} minute check intervals
                            </span>
                        </li>

                        @if($plan->email_alerts)
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-700 text-sm">Email alerts</span>
                        </li>
                        @endif

                        @if($plan->ssl_monitoring)
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-700 text-sm">SSL certificate monitoring</span>
                        </li>
                        @endif

                        @if($plan->sms_alerts)
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-700 text-sm">SMS alerts</span>
                        </li>
                        @endif

                        @if($plan->api_access)
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-700 text-sm">API access</span>
                        </li>
                        @endif

                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-700 text-sm">{{ $plan->data_retention_days }} days data retention</span>
                        </li>
                    </ul>

                    <!-- CTA Button -->
                    <div class="text-center">
                        @auth
                            @if(auth()->user()->subscription_plan_id === $plan->id)
                                <button class="w-full bg-gray-400 text-white px-6 py-3 rounded-lg font-medium cursor-not-allowed">
                                    Current Plan
                                </button>
                            @else
                                <form action="{{ route('billing.upgrade.process', $plan) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full {{ $plan->slug === 'pro' ? 'bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700' : 'bg-gray-900 hover:bg-gray-800' }} text-white px-6 py-3 rounded-lg font-medium transition duration-200 transform hover:scale-105">
                                        {{ $plan->price == 0 ? 'Get Started Free' : 'Upgrade Now' }}
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('register') }}" class="w-full inline-block {{ $plan->slug === 'pro' ? 'bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700' : 'bg-gray-900 hover:bg-gray-800' }} text-white px-6 py-3 rounded-lg font-medium transition duration-200 transform hover:scale-105 text-center">
                                {{ $plan->price == 0 ? 'Get Started Free' : 'Get Started' }}
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Features comparison or additional info -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 text-center mb-6">
                All plans include
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="bg-blue-100 rounded-lg p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">99.9% Uptime SLA</h3>
                    <p class="text-gray-600 text-sm">Reliable monitoring you can count on</p>
                </div>

                <div class="text-center">
                    <div class="bg-green-100 rounded-lg p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 110 19.5 9.75 9.75 0 010-19.5z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">24/7 Support</h3>
                    <p class="text-gray-600 text-sm">Get help whenever you need it</p>
                </div>

                <div class="text-center">
                    <div class="bg-purple-100 rounded-lg p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Secure & Private</h3>
                    <p class="text-gray-600 text-sm">Your data is protected and encrypted</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
