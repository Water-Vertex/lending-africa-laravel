@extends('user.layouts.app')

@section('title', $policy->title)

@section('content')

<!-- Policy Content - Full Background Shade -->
<section class="bg-soft" style="padding: 40px 0 60px 0;">
    <div class="px-3 md:px-4" style="max-width:1200px; margin:0 auto;">
        <div style="max-width:900px; margin:0;">
            <!-- Header - Left Aligned -->
            <div class="mb-4 text-left">
                <h1 class="font-display font-bold text-dark text-xl lg:text-2xl mb-1">
                    {{ $policy->title }}
                </h1>
                <div class="flex items-center gap-3 text-sm text-gray-500">
                    <i class="fas fa-calendar-alt text-primary text-xs"></i>
                    <span>Last updated: {{ $policy->updated_at ? $policy->updated_at->format('F d, Y') : 'N/A' }}</span>
                </div>
            </div>

            <!-- Content Body - Left Aligned -->
            <div class="policy-content text-left">
                {!! $policy->content !!}
            </div>

            <!-- Back Button - Left Aligned -->
            <div class="mt-6 pt-4 border-t border-gray-200 text-left">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-primary hover:text-primary-dark text-sm font-medium transition">
                    <i class="fas fa-arrow-left text-xs"></i>
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@section('styles')
<style>
    /* ===== POLICY CONTENT STYLES - COMPACT & LEFT ALIGNED ===== */
    .policy-content {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: #1a1a2e;
        line-height: 1.7;
        font-size: 15px;
        max-width: 100%;
        overflow-wrap: break-word;
        word-wrap: break-word;
        text-align: left;
    }

    /* Override any center alignment */
    .policy-content * {
        text-align: left !important;
    }

    /* Headings - Compact & Left Aligned */
    .policy-content h1 {
        font-size: 1.6rem !important;
        font-weight: 700 !important;
        margin-top: 1.2rem !important;
        margin-bottom: 0.5rem !important;
        color: #1a1a2e !important;
        line-height: 1.2 !important;
        text-align: left !important;
    }

    .policy-content h2 {
        font-size: 1.3rem !important;
        font-weight: 600 !important;
        margin-top: 1rem !important;
        margin-bottom: 0.4rem !important;
        color: #1a1a2e !important;
        line-height: 1.3 !important;
        text-align: left !important;
    }

    .policy-content h3 {
        font-size: 1.1rem !important;
        font-weight: 600 !important;
        margin-top: 0.8rem !important;
        margin-bottom: 0.4rem !important;
        color: #1a1a2e !important;
        line-height: 1.4 !important;
        text-align: left !important;
    }

    .policy-content h4 {
        font-size: 1rem !important;
        font-weight: 600 !important;
        margin-top: 0.7rem !important;
        margin-bottom: 0.3rem !important;
        color: #1a1a2e !important;
        text-align: left !important;
    }

    .policy-content h5 {
        font-size: 0.95rem !important;
        font-weight: 600 !important;
        margin-top: 0.6rem !important;
        margin-bottom: 0.3rem !important;
        color: #1a1a2e !important;
        text-align: left !important;
    }

    .policy-content h6 {
        font-size: 0.9rem !important;
        font-weight: 600 !important;
        margin-top: 0.5rem !important;
        margin-bottom: 0.3rem !important;
        color: #1a1a2e !important;
        text-align: left !important;
    }

    /* Paragraphs - Compact & Left Aligned */
    .policy-content p {
        margin-bottom: 0.6rem !important;
        color: #4a4a6a !important;
        line-height: 1.7 !important;
        text-align: left !important;
    }

    /* Bold, Italic, Underline */
    .policy-content strong,
    .policy-content b {
        font-weight: 700 !important;
        color: #1a1a2e !important;
    }

    .policy-content em,
    .policy-content i {
        font-style: italic !important;
        color: #4a4a6a !important;
    }

    .policy-content u {
        text-decoration: underline !important;
    }

    .policy-content strike,
    .policy-content s {
        text-decoration: line-through !important;
    }

    /* Lists - Compact & Left Aligned */
    .policy-content ul {
        list-style: disc !important;
        padding-left: 1.5rem !important;
        margin-bottom: 0.6rem !important;
        text-align: left !important;
    }

    .policy-content ol {
        list-style: decimal !important;
        padding-left: 1.5rem !important;
        margin-bottom: 0.6rem !important;
        text-align: left !important;
    }

    .policy-content ul li,
    .policy-content ol li {
        margin-bottom: 0.3rem !important;
        color: #4a4a6a !important;
        line-height: 1.6 !important;
        text-align: left !important;
    }

    /* Blockquotes */
    .policy-content blockquote {
        border-left: 4px solid #4f46e5 !important;
        padding: 0.6rem 1rem !important;
        margin: 0.8rem 0 !important;
        background: #f8fafc !important;
        border-radius: 0 6px 6px 0 !important;
        color: #4a4a6a !important;
        font-style: italic !important;
        font-size: 0.95rem !important;
        text-align: left !important;
    }

    .policy-content blockquote p {
        margin-bottom: 0 !important;
        text-align: left !important;
    }

    /* Links */
    .policy-content a {
        color: #4f46e5 !important;
        text-decoration: underline !important;
        transition: color 0.2s !important;
    }

    .policy-content a:hover {
        color: #4338ca !important;
    }

    /* Tables */
    .policy-content table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin: 0.8rem 0 !important;
        display: block !important;
        overflow-x: auto !important;
        font-size: 0.9rem !important;
        text-align: left !important;
    }

    .policy-content table th,
    .policy-content table td {
        border: 1px solid #e2e8f0 !important;
        padding: 0.4rem 0.6rem !important;
        text-align: left !important;
    }

    .policy-content table th {
        background: #f1f5f9 !important;
        font-weight: 600 !important;
        color: #1a1a2e !important;
        text-align: left !important;
    }

    .policy-content table tr:nth-child(even) {
        background: #f8fafc !important;
    }

    /* Images */
    .policy-content img {
        max-width: 100% !important;
        height: auto !important;
        border-radius: 6px !important;
        margin: 0.6rem 0 !important;
    }

    /* Code */
    .policy-content pre {
        background: #1e293b !important;
        color: #e2e8f0 !important;
        padding: 0.8rem !important;
        border-radius: 6px !important;
        overflow-x: auto !important;
        margin: 0.8rem 0 !important;
        white-space: pre-wrap !important;
        word-wrap: break-word !important;
        font-size: 0.85rem !important;
        text-align: left !important;
    }

    .policy-content code {
        background: #f1f5f9 !important;
        padding: 0.15rem 0.35rem !important;
        border-radius: 4px !important;
        font-size: 0.85em !important;
        color: #1a1a2e !important;
    }

    .policy-content pre code {
        background: transparent !important;
        padding: 0 !important;
        color: inherit !important;
    }

    /* Horizontal Rule */
    .policy-content hr {
        border: none !important;
        border-top: 1.5px solid #e2e8f0 !important;
        margin: 1.2rem 0 !important;
    }

    /* Hide empty paragraphs */
    .policy-content p:empty {
        display: none !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .policy-content {
            font-size: 14px !important;
        }

        .policy-content h1 {
            font-size: 1.4rem !important;
        }

        .policy-content h2 {
            font-size: 1.2rem !important;
        }

        .policy-content h3 {
            font-size: 1.05rem !important;
        }

        .policy-content table {
            display: block !important;
            overflow-x: auto !important;
            white-space: nowrap !important;
            font-size: 0.8rem !important;
        }
    }
</style>
@endsection