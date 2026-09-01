@extends('user.layouts.app')
@section('title', 'Already Submitted – AIP')
@section('content')
<div class="max-w-xl mx-auto px-4 py-16 text-center">
    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <i class="fas fa-check-circle text-green-500 text-4xl"></i>
    </div>
    <h1 class="text-2xl font-bold text-gray-900 mb-3">Already Submitted</h1>
    <p class="text-gray-600 text-sm leading-relaxed">
        You have already submitted your signed agreement on
        <strong>{{ $agreement->signed_submitted_at?->format('d M Y \a\t h:i A') }}</strong>.
        Our team is processing your loan. You will be contacted shortly.
    </p>
</div>
@endsection