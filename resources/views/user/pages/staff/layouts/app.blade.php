<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Staff Portal – African Investment Partners')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #F9FAFB; min-height: 100vh; overflow-x: hidden; }
        .font-display { font-family: 'Poppins', sans-serif; }

        .sidebar-gradient {
            background: linear-gradient(180deg, #1A2332 0%, #243447 60%, #1e3a2f 100%);
        }

        .nav-link {
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .nav-link.active {
            background: rgba(109, 190, 59, 0.2);
            border-left: 3px solid #6DBE3B;
        }
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        .avatar-gradient {
            background: linear-gradient(135deg, #6DBE3B, #58A02E);
        }

        #staff-content {
            opacity: 1;
            transition: opacity 0.15s ease;
        }
        #staff-content.spa-loading {
            opacity: 0.4;
        }

        #spa-progress {
            position: fixed;
            top: 0; left: 0;
            height: 3px;
            width: 0%;
            background: #6DBE3B;
            z-index: 9999;
            transition: width 0.2s ease;
        }

        .sidebar-overlay {
            transition: opacity 0.3s ease;
        }
        .sidebar-overlay.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .sidebar-slide {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-slide.closed {
            transform: translateX(-100%);
        }
        @media (min-width: 1024px) {
            .sidebar-slide {
                transform: translateX(0) !important;
            }
            .sidebar-slide.closed {
                transform: translateX(0) !important;
            }
        }

        .close-sidebar-btn {
            transition: all 0.2s ease;
        }
        .close-sidebar-btn:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: rotate(90deg);
        }

        .dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: top right;
        }
        .dropdown-menu.open {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .dropdown-item {
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .dropdown-item:hover {
            background: #F3F4F6;
        }
        .dropdown-item.danger:hover {
            background: #FEF2F2;
            color: #DC2626;
        }

        .status-pulse {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(0.8); }
        }

        /* ============================================================
            ADMIN STYLE HEADER - FULL WIDTH & CAPSULE DROPDOWN
        ============================================================ */
        .admin-header {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 64px;
            width: 100%;
        }
        .admin-header .brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .admin-header .brand-text {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 0.9rem;
            color: #1A2332;
        }
        .admin-header .brand-text span {
            color: #6DBE3B;
        }
        .admin-header .brand-sub {
            color: #9ca3af;
            font-size: 0.55rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        
        /* Premium Capsule User Dropdown Trigger */
        .capsule-user-trigger {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            padding: 0.375rem 0.75rem 0.375rem 0.5rem;
            border-radius: 9999px;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .capsule-user-trigger:hover {
            background: #F3F4F6;
            border-color: #D1D5DB;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }
        .capsule-user-trigger .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6DBE3B, #4a8a2a);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .capsule-user-trigger .user-name {
            font-size: 0.8rem;
            font-weight: 600;
            color: #1F2937;
        }
        .capsule-user-trigger .dropdown-bars {
            display: flex;
            flex-direction: column;
            gap: 2.5px;
            margin-left: 0.25rem;
        }
        .capsule-user-trigger .dropdown-bars span {
            width: 12px;
            height: 1.5px;
            background: #9CA3AF;
            border-radius: 1px;
            transition: all 0.2s ease;
        }
        .capsule-user-trigger:hover .dropdown-bars span {
            background: #1A2332;
        }

        /* Mobile Menu Button Styling */
        .admin-header .mobile-menu-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.2s ease;
            background: transparent;
            border: none;
        }
        .admin-header .mobile-menu-btn:hover {
            background: #f3f4f6;
            color: #1A2332;
        }

        /* Content spacing with padding on inner section only, header touches the corners */
        .inner-content-wrapper {
            padding: 1.5rem;
        }
        @media (min-width: 640px) {
            .inner-content-wrapper {
                padding: 2rem;
            }
        }
        @media (min-width: 1024px) {
            .inner-content-wrapper {
                padding: 2.5rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <div id="spa-progress"></div>

    <div class="flex flex-col lg:flex-row min-h-screen relative">

        <!-- Mobile Sidebar Overlay -->
        <div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 bg-black/60 backdrop-blur-sm z-40 hidden lg:hidden" onclick="closeSidebar()"></div>

        {{-- Sidebar --}}
        @include('user.pages.staff.sidebar')

        {{-- Main Content Window --}}
        <main class="flex-1 flex flex-col min-h-screen bg-gray-50 overflow-x-hidden">
            
            {{-- ============================================================
                 ADMIN HEADER - FULL WIDTH TOUCHING THE CORNERS
            ============================================================ --}}
            @php
                $staff = $staff ?? auth('staff')->user();
                $firstInitial = strtoupper(substr($staff->first_name ?? 'S', 0, 1));
            @endphp
            
            <div class="admin-header">
                <!-- Left: Brand + Mobile Menu Toggle -->
                <div class="brand">
                    <!-- <button onclick="toggleSidebar()" class="mobile-menu-btn lg:hidden">
                        <i class="fas fa-bars text-sm"></i>
                    </button> -->
                    
                    <!-- Logo Image - Small Size -->
                    <img
                        src="{{ asset('assets/images/logo/aip-logo.png') }}"
                        alt="AIP Logo"
                        class="h-8 w-auto object-contain"
                    >
                    
                    
                </div>

                <!-- Right: Profile Capsule Box (Clickable Area) -->
                <div class="relative" id="dropdownContainer">
                    <button onclick="toggleDropdown()" class="capsule-user-trigger" type="button">
                        <div class="avatar">
                            {{ $firstInitial }}
                        </div>
                        <span class="user-name hidden sm:block">
                            {{ $staff->first_name ?? 'Staff' }}
                        </span>
                        <div class="dropdown-bars">
                            <span></span>
                            <span></span>
                        </div>
                    </button>

                    <!-- Dropdown Menu Options -->
                    <div id="dropdownMenu" class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 py-1 z-50">
                        <div class="px-4 py-2 border-b border-gray-100 sm:hidden">
                            <p class="text-xs font-bold text-gray-800">{{ $staff->first_name }} {{ $staff->last_name }}</p>
                            <p class="text-[10px] text-gray-400 truncate">{{ $staff->email }}</p>
                        </div>
                        <a href="{{ route('staff.password.change') }}" class="dropdown-item flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
    <i class="fas fa-key text-gray-400 text-xs"></i>
    <span>Change Password</span> 
        </a>
                        <div class="border-t border-gray-200 my-1"></div>
                        <form method="POST" action="{{ route('staff.logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item danger flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 w-full text-left transition">
                                <i class="fas fa-arrow-right-from-bracket text-red-400 text-xs"></i>
                                <span>Sign Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ============================================================
                 PAGE CONTENT WRAPPED WITH APPROPRIATE PADDINGS
            ============================================================ --}}
            <div id="staff-content" class="flex-1 w-full">
                <div class="inner-content-wrapper">
                    @yield('content')
                </div>
            </div>

        </main>

    </div>

    @stack('scripts')

    <script>
        // ============================================================
        // SIDEBAR FUNCTIONS
        // ============================================================
        var sidebar = document.getElementById('sidebar');
        var overlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            if (window.innerWidth < 1024) {
                var isClosed = sidebar.classList.contains('closed');
                sidebar.classList.toggle('closed');
                overlay.classList.toggle('hidden');
                document.body.style.overflow = isClosed ? 'hidden' : '';
            }
        }

        function closeSidebar() {
            if (window.innerWidth < 1024) {
                sidebar.classList.add('closed');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }

        // ============================================================
        // DROPDOWN FUNCTIONS
        // ============================================================
        function toggleDropdown() {
            var menu = document.getElementById('dropdownMenu');
            if (menu) menu.classList.toggle('open');
        }

        document.addEventListener('click', function(event) {
            var container = document.getElementById('dropdownContainer');
            var menu = document.getElementById('dropdownMenu');
            if (container && !container.contains(event.target)) {
                if (menu) menu.classList.remove('open');
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                var menu = document.getElementById('dropdownMenu');
                if (menu) menu.classList.remove('open');
            }
        });

        // ============================================================
        // SPA NAVIGATION
        // ============================================================
        (function () {
            var contentEl = document.getElementById('staff-content');
            var progressEl = document.getElementById('spa-progress');

            function setProgress(pct) {
                progressEl.style.width = pct + '%';
                if (pct >= 100) {
                    setTimeout(function () { progressEl.style.width = '0%'; }, 200);
                }
            }

            function setActiveNav(url) {
                document.querySelectorAll('#sidebar .nav-link').forEach(function (link) {
                    var href = link.getAttribute('href');
                    link.classList.toggle('active', href === url || (href && url.indexOf(href) !== -1));
                });
            }

            function loadPage(url, push) {
                if (window.location.href === url) return;
                
                contentEl.classList.add('spa-loading');
                setProgress(30);

                fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(function (res) {
                    setProgress(70);
                    if (!res.ok) throw new Error('Request failed: ' + res.status);
                    return res.text();
                })
                .then(function (html) {
                    var parser = new DOMParser();
                    var doc = parser.parseFromString(html, 'text/html');
                    var newContent = doc.getElementById('staff-content');
                    var newTitle = doc.querySelector('title');

                    if (newContent) {
                        contentEl.innerHTML = newContent.innerHTML;
                    } else {
                        window.location.href = url;
                        return;
                    }

                    if (newTitle) document.title = newTitle.textContent;

                    if (push) {
                        window.history.pushState({ spa: true }, '', url);
                    }

                    setActiveNav(url);
                    window.scrollTo({ top: 0, behavior: 'smooth' });

                    newContent.querySelectorAll('script').forEach(function (oldScript) {
                        var newScript = document.createElement('script');
                        if (oldScript.src) {
                            newScript.src = oldScript.src;
                        } else {
                            newScript.textContent = oldScript.textContent;
                        }
                        document.body.appendChild(newScript);
                    });

                    if (window.innerWidth < 1024) {
                        closeSidebar();
                    }
                })
                .catch(function (err) {
                    console.error('SPA navigation failed, falling back to full reload:', err);
                    window.location.href = url;
                })
                .finally(function () {
                    contentEl.classList.remove('spa-loading');
                    setProgress(100);
                });
            }

            document.addEventListener('click', function (e) {
                var link = e.target.closest('#sidebar a[href]');
                if (!link) return;

                var url = link.getAttribute('href');

                if (!url || url.startsWith('#') || link.target === '_blank') return;
                if (url.indexOf(window.location.origin) !== 0 && url.indexOf('http') === 0 && url.indexOf(window.location.host) === -1) return;

                e.preventDefault();
                closeSidebar();
                loadPage(url, true);
            });

            window.addEventListener('popstate', function () {
                loadPage(window.location.href, false);
            });

            setActiveNav(window.location.href);
        })();
    </script>

</body>
</html>