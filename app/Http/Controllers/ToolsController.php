<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ToolsController extends Controller
{
    public function index()
    {
        return view('tools.index', [
            'title' => 'Free Website Monitoring Tools',
            'description' => 'Free online tools for website monitoring, SSL checking, DNS lookup, and performance testing.'
        ]);
    }
}
