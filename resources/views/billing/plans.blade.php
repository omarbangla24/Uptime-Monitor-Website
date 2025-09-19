@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Choose Your Plan</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($plans as $plan)
        <div class="bg-white rounded-lg shadow-lg p-6 {{ $plan->slug === 'pro' ? 'border-2 border-blue-500' : '' }}">
            @if($plan->slug === 'pro')
                <div class="text-center mb-4">
                    <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm">Most Popular</span>
                </div>
            @endif

            <h3 class="text-xl font-bold text-center mb-2">{{ $plan->name }}</h3>
            <div class="text-center mb-4">
                <span class="text-3xl font-bold">${{ number_format($plan->price, 0) }}</span>
                <span class="text-gray-500">/{{ $plan->interval }}</span>
            </div>

            <p class="text-gray-600 text-center mb-6">{{ $plan->description }}</p>

            <ul class="space-y-2 mb-6">
                <li class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $plan->websites_limit === 0 ? 'Unlimited' : $plan->websites_limit }} websites
                </li>
                <li class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $plan->checks_per_minute }} min intervals
                </li>
                @if($plan->ssl_monitoring)
                <li class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    SSL Monitoring
                </li>
                @endif
            </ul>

            <div class="text-center">
                @if($user->subscription_plan_id === $plan->id)
                    <button class="w-full bg-gray-400 text-white px-4 py-2 rounded-lg cursor-not-allowed">
                        Current Plan
                    </button>
                @else
                    <form action="{{ route('billing.upgrade', $plan) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                            {{ $plan->price == 0 ? 'Get Started' : 'Upgrade' }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
