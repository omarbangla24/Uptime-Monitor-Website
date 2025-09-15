<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function index()
    {
        $websites = auth()->user()->websites()
                                 ->with('monitoringResults')
                                 ->latest()
                                 ->get();

        return view('websites.index', [
            'websites' => $websites
        ]);
    }

    public function create()
    {
        return view('websites.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'check_interval' => 'required|integer|min:1|max:60'
        ]);

        $website = auth()->user()->websites()->create([
            ...$validated,
            'domain' => parse_url($validated['url'], PHP_URL_HOST),
            'protocol' => parse_url($validated['url'], PHP_URL_SCHEME) ?: 'https'
        ]);

        return redirect()->route('websites.show', $website)
                        ->with('success', 'Website added successfully!');
    }

    public function show(Website $website)
    {
        $this->authorize('view', $website);

        return view('websites.show', [
            'website' => $website
        ]);
    }

    public function edit(Website $website)
    {
        $this->authorize('update', $website);

        return view('websites.edit', [
            'website' => $website
        ]);
    }

    public function update(Request $request, Website $website)
    {
        $this->authorize('update', $website);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'check_interval' => 'required|integer|min:1|max:60'
        ]);

        $website->update([
            ...$validated,
            'domain' => parse_url($validated['url'], PHP_URL_HOST),
            'protocol' => parse_url($validated['url'], PHP_URL_SCHEME) ?: 'https'
        ]);

        return back()->with('success', 'Website updated successfully!');
    }

    public function destroy(Website $website)
    {
        $this->authorize('delete', $website);

        $website->delete();

        return redirect()->route('websites.index')
                        ->with('success', 'Website removed successfully!');
    }
}
