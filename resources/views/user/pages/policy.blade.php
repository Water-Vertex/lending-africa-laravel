@extends('user.layouts.app')

@section('title', $policy->title)

@section('content')

<!-- Breadcrumb -->
            <!-- <div class="bg-gray-50 border-b border-gray-200">
                <div class="container mx-auto px-4 lg:px-8 py-4">
                    <div class="flex items-center gap-2 text-sm">
                        <a href="{{ route('home') }}" class="text-gray-500 hover:text-primary transition">Home</a>
                        <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                        <span class="text-gray-800 font-medium">{{ $policy->title }}</span>
                    </div>
                </div>
            </div> -->

<!-- Policy Content -->
<section class="py-12 lg:py-16">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl lg:text-4xl font-display font-bold text-gray-900 mb-4">
                    {{ $policy->title }}
                </h1>
                <div class="flex items-center gap-4 text-sm text-gray-500">
                    <span>
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Last updated: {{ $policy->updated_at ? $policy->updated_at->format('F d, Y') : 'N/A' }}
                    </span>
                </div>
            </div>

            <!-- Content Body - HTML render karega -->
          <div class="policy-content">
    @php
        $content = html_entity_decode($policy->content, ENT_QUOTES, 'UTF-8');
    @endphp
    {!! $content !!}
</div>

            <!-- Back Button -->
            <div class="mt-10 pt-8 border-t border-gray-200">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-primary hover:text-primary-dark transition">
                    <i class="fas fa-arrow-left"></i>
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
    /* ===== POLICY CONTENT STYLES ===== */
    .policy-content {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: #1a1a2e;
        line-height: 1.8;
        font-size: 16px;
        max-width: 100%;
        overflow-wrap: break-word;
        word-wrap: break-word;
    }

    /* Headings */
    .policy-content h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
        color: #1a1a2e;
        line-height: 1.2;
    }

    .policy-content h2 {
        font-size: 2rem;
        font-weight: 600;
        margin-top: 1.8rem;
        margin-bottom: 0.8rem;
        color: #1a1a2e;
        line-height: 1.3;
    }

    .policy-content h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.6rem;
        color: #1a1a2e;
        line-height: 1.4;
    }

    .policy-content h4 {
        font-size: 1.25rem;
        font-weight: 600;
        margin-top: 1.2rem;
        margin-bottom: 0.5rem;
        color: #1a1a2e;
    }

    .policy-content h5 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-top: 1rem;
        margin-bottom: 0.5rem;
        color: #1a1a2e;
    }

    .policy-content h6 {
        font-size: 1rem;
        font-weight: 600;
        margin-top: 0.8rem;
        margin-bottom: 0.5rem;
        color: #1a1a2e;
    }

    /* Paragraphs */
    .policy-content p {
        margin-bottom: 1rem;
        color: #4a4a6a;
        line-height: 1.8;
    }

    /* Bold, Italic, Underline */
    .policy-content strong,
    .policy-content b {
        font-weight: 700;
        color: #1a1a2e;
    }

    .policy-content em,
    .policy-content i {
        font-style: italic;
        color: #4a4a6a;
    }

    .policy-content u {
        text-decoration: underline;
    }

    .policy-content strike,
    .policy-content s {
        text-decoration: line-through;
    }

    /* Lists */
    .policy-content ul {
        list-style: disc;
        padding-left: 1.5rem;
        margin-bottom: 1rem;
    }

    .policy-content ol {
        list-style: decimal;
        padding-left: 1.5rem;
        margin-bottom: 1rem;
    }

    .policy-content ul li,
    .policy-content ol li {
        margin-bottom: 0.5rem;
        color: #4a4a6a;
    }

    /* Blockquotes */
    .policy-content blockquote {
        border-left: 4px solid #4f46e5;
        padding: 1rem 1.5rem;
        margin: 1.5rem 0;
        background: #f8fafc;
        border-radius: 0 8px 8px 0;
        color: #4a4a6a;
        font-style: italic;
    }

    .policy-content blockquote p {
        margin-bottom: 0;
    }

    /* Links */
    .policy-content a {
        color: #4f46e5;
        text-decoration: underline;
        transition: color 0.2s;
    }

    .policy-content a:hover {
        color: #4338ca;
    }

    /* Tables */
    .policy-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.5rem 0;
        display: block;
        overflow-x: auto;
    }

    .policy-content table th,
    .policy-content table td {
        border: 1px solid #e2e8f0;
        padding: 0.75rem 1rem;
        text-align: left;
    }

    .policy-content table th {
        background: #f1f5f9;
        font-weight: 600;
        color: #1a1a2e;
    }

    .policy-content table tr:nth-child(even) {
        background: #f8fafc;
    }

    /* Images */
    .policy-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1rem 0;
    }

    /* Code */
    .policy-content pre {
        background: #1e293b;
        color: #e2e8f0;
        padding: 1rem;
        border-radius: 8px;
        overflow-x: auto;
        margin: 1rem 0;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .policy-content code {
        background: #f1f5f9;
        padding: 0.2rem 0.4rem;
        border-radius: 4px;
        font-size: 0.875em;
        color: #1a1a2e;
    }

    .policy-content pre code {
        background: transparent;
        padding: 0;
        color: inherit;
    }

    /* Horizontal Rule */
    .policy-content hr {
        border: none;
        border-top: 2px solid #e2e8f0;
        margin: 2rem 0;
    }

    /* Hide empty paragraphs */
    .policy-content p:empty {
        display: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .policy-content {
            font-size: 15px;
        }

        .policy-content h1 {
            font-size: 2rem;
        }

        .policy-content h2 {
            font-size: 1.5rem;
        }

        .policy-content h3 {
            font-size: 1.25rem;
        }

        .policy-content table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
    }
</style>
@endpush