<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    /**
     * Display a specific policy by slug
     */
public function show($slug)
{
    $policy = Policy::where('slug', $slug)
        ->where('status', true)
        ->firstOrFail();

    return view('user.pages.policy', compact('policy'));
}

    /**
     * Get all policies for footer
     */
    public function getFooterPolicies()
    {
        return Policy::where('status', true)
            ->orderBy('title', 'asc')
            ->get();
    }
}