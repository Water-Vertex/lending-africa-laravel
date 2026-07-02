<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'African Investment Partners')</title>
    <meta name="description" content="@yield('meta_description', 'Fast, transparent loan solutions for personal and business needs in Nigeria.')">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

   {{-- Tailwind CSS — compiled locally via Vite (no CDN, production-ready) --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('styles')
</head>
<body class="bg-white font-sans text-dark antialiased">

    <!-- Top Announcement Bar -->
    <div class="bg-primary text-white text-center py-2.5 text-sm font-medium">
        <span class="mr-2">🎉</span>
        Quick loan approval in <strong>24 hours</strong> — Personal & SME loans from ₦50,000 to ₦150,000.
        <a href="#apply" class="ml-2 underline font-semibold hover:text-primary-light transition">Apply Now →</a>
    </div>

    <!-- Header -->
    @include('user.includes.header')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('user.includes.footer')

    <!-- Scripts (jQuery + page interactivity, unchanged) -->
    @include('user.includes.scripts')
    @yield('scripts')

</body>
</html>
