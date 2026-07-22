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
    <body>

    {{-- Force page to always open at the top — prevents unwanted auto-scroll --}}
    <script>
        // Disable browser's automatic scroll-position restoration
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }

        // Remove any stray hash from the URL on load (e.g. #apply) without a jump
        if (window.location.hash) {
            history.replaceState(null, '', window.location.pathname + window.location.search);
        }

        // Belt-and-braces: force scroll to top before anything else paints
        window.scrollTo(0, 0);
    </script>
<script>
    window.addEventListener('load', function () {
        window.scrollTo(0, 0);
    });
</script>
    <!-- Top Announcement Bar -->
    <div class="bg-primary text-white text-center py-2.5 text-sm font-medium">
        <span class="mr-2">🎉</span>
        Approval in <strong>2 to 5 days</strong> — Personal loans ₦50,000–₦200,000 & SME loans ₦50,000–₦300,000.
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