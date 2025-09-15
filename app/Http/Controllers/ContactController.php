<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact.show', [
            'title' => 'Contact Us - Get Support & Sales Help',
            'description' => 'Contact our team for support, sales questions, or technical assistance. We\'re here to help 24/7.'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'type' => 'required|in:general,support,sales,technical,billing'
        ]);

        $contactMessage = ContactMessage::create([
            ...$validated,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Send notification email to admin
        try {
            // Mail::to(config('mail.admin_email'))->send(new ContactMessageReceived($contactMessage));
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to send contact email: ' . $e->getMessage());
        }

        return back()->with('success', 'Thank you for your message! We\'ll get back to you within 24 hours.');
    }
}
