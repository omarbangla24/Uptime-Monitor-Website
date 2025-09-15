@php
    $plans = \App\Models\SubscriptionPlan::active()->ordered()->get();
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
    @foreach($plans as $plan)
    <div class="relative bg-white dark:bg-dark-800 rounded-2xl shadow-xl overflow-hidden border-2 {{ $plan->is_popular ? 'border-primary-500' : 'border-gray-200 dark:border-dark-700' }} hover:border-primary-400 dark:hover:border-primary-600 transition-all duration-300 group">
        @if($plan->is_popular)
            <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-primary-500 text-white px-4 py-1 rounded-full text-sm font-medium">
                Most Popular
            </div>
        @endif

        <div class="p-8">
            <!-- Plan Header -->
            <div class="text-center mb-8">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $plan->name }}</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $plan->description }}</p>
                <div class="mb-4">
                    <span class="text-4xl font-bold text-gray-900 dark:text-white">${{ number_format($plan->price, 0) }}</span>
                    <span class="text-gray-600 dark:text-gray-400">/{{ $plan->interval }}</span>
                </div>
                @if($plan->price > 0)
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        ${{ number_format($plan->monthly_price, 2) }}/month
                    </p>
                @endif
            </div>

            <!-- Features -->
            <ul class="space-y-3 mb-8">
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-success-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">
                        {{ $plan->websites_limit === 0 ? 'Unlimited' : $plan->websites_limit }} websites
                    </span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-success-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">{{ $plan->checks_per_minute }}-minute checks</span>
                </li>
                @if($plan->ssl_monitoring)
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-success-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">SSL monitoring</span>
                </li>
                @endif
                @if($plan->dns_monitoring)
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-success-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">DNS monitoring</span>
                </li>
                @endif
                @if($plan->email_alerts)
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-success-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">Email alerts</span>
                </li>
                @endif
                @if($plan->sms_alerts)
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-success-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">SMS alerts</span>
                </li>
                @endif
                @if($plan->api_access)
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-success-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">API access</span>
                </li>
                @endif
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-success-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">{{ $plan->data_retention_days }} days data retention</span>
                </li>
            </ul>

            <!-- CTA Button -->
            <div class="text-center">
                @guest
                    <a href="{{ route('register', ['plan' => $plan->slug]) }}"
                       class="{{ $plan->is_popular ? 'btn-primary' : 'btn-outline' }} w-full justify-center group-hover:scale-105 transition-transform">
                        @if($plan->price == 0)
                            Get Started Free
                        @else
                            Start {{ $plan->name }}
                        @endif
                    </a>
                @else
                    @if(auth()->user()->subscription_plan_id === $plan->id)
                        <button disabled class="w-full btn-secondary cursor-not-allowed">
                            Current Plan
                        </button>
                    @else
                        <a href="{{ route('billing.upgrade', $plan) }}"
                           class="{{ $plan->is_popular ? 'btn-primary' : 'btn-outline' }} w-full justify-center">
                            @if($plan->price == 0)
                                Downgrade to Free
                            @else
                                Upgrade to {{ $plan->name }}
                            @endif
                        </a>
                    @endif
                @endguest
            </div>
        </div>
    </div>
    @endforeach
</div>
