<?php

namespace App\Providers;

use App\Models\Website;
use App\Policies\WebsitePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Website::class => WebsitePolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
