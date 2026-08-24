@extends('user.layouts.app')
@section('title', 'Link Expired – AIP')
@section('content')
<div class="max-w-xl mx-auto px-4 py-16 text-center">
    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <i class="fas fa-times-circle text-red-500 text-4xl"></i>
    </div>
    <h1 class="text-2xl font-bold text-gray-900 mb-3">Link Expired</h1>
    <p class="text-gray-600 text-sm leading-relaxed mb-6">
        This agreement submission link has expired. Your loan approval has been cancelled as the signed agreement was not received within 7 days.
    </p>
    <p class="text-gray-500 text-sm">
        If you believe this is an error, please contact us at
        <a href="mailto:info@aiploan.com" class="text-[#6DBE3B] font-semibold">info@aiploan.com</a>
    </p>
</div>
@endsection