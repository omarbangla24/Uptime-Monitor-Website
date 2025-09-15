<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Setting;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function welcome()
    {
        // Redirect to the LandingController instead
        return app(LandingController::class)->welcome();
    }

    public function pricing()
    {
        $plans = \App\Models\SubscriptionPlan::active()->ordered()->get();

        return view('pages.pricing', [
            'title' => 'Pricing Plans - Choose Your Monitoring Package',
            'description' => 'Affordable website monitoring plans starting from free. Monitor your website uptime with SSL, DNS, and performance tracking.',
            'plans' => $plans
        ]);
    }

    public function about()
    {
        return view('pages.about', [
            'title' => 'About Us - Professional Website Monitoring',
            'description' => 'Learn about our mission to provide reliable website monitoring services for businesses worldwide.'
        ]);
    }

    public function privacy()
    {
        $page = Page::where('slug', 'privacy-policy')->first();

        return view('pages.static', [
            'title' => 'Privacy Policy',
            'description' => 'Our privacy policy and how we handle your data.',
            'page' => $page,
            'heading' => 'Privacy Policy'
        ]);
    }

    public function terms()
    {
        $page = Page::where('slug', 'terms-of-service')->first();

        return view('pages.static', [
            'title' => 'Terms of Service',
            'description' => 'Terms and conditions for using our monitoring service.',
            'page' => $page,
            'heading' => 'Terms of Service'
        ]);
    }

    public function show($slug)
    {
        $page = Page::where('slug', $slug)
                   ->where('status', 'published')
                   ->firstOrFail();

        return view('pages.show', [
            'title' => $page->meta_title ?: $page->title,
            'description' => $page->meta_description ?: $page->excerpt,
            'page' => $page
        ]);
    }
}
