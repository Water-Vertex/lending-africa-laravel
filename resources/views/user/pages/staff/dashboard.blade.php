<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Dashboard – African Investment Partners</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ============================================================
           BASE STYLES
        ============================================================ */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #F9FAFB; min-height: 100vh; }
        .font-display { font-family: 'Poppins', sans-serif; }

        /* ============================================================
           SIDEBAR GRADIENT (matches your hero section)
        ============================================================ */
        .sidebar-gradient {
            background: linear-gradient(180deg, #1A2332 0%, #243447 60%, #1e3a2f 100%);
        }

        /* ============================================================
           CUSTOM SCROLLBAR
        ============================================================ */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #F3F4F6; }
        ::-webkit-scrollbar-thumb { background: #6DBE3B; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #58A02E; }

        /* ============================================================
           CARD HOVER EFFECTS
        ============================================================ */
        .stat-card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: default;
        }
        .stat-card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -12px rgba(109, 190, 59, 0.25);
            border-color: #6DBE3B;
        }

        /* ============================================================
           QUICK ACTION BUTTON HOVER
        ============================================================ */
        .quick-action {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }
        .quick-action:hover {
            transform: translateY(-3px);
            border-color: #6DBE3B;
            background: #F4FAF0;
            box-shadow: 0 8px 24px -8px rgba(109, 190, 59, 0.3);
        }
        .quick-action:hover i {
            color: #58A02E;
        }

        /* ============================================================
           SIDEBAR NAVIGATION
        ============================================================ */
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

        /* ============================================================
           STATUS PULSE ANIMATION
        ============================================================ */
        .status-pulse {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(0.8); }
        }

        /* ============================================================
           AVATAR GRADIENT
        ============================================================ */
        .avatar-gradient {
            background: linear-gradient(135deg, #6DBE3B, #58A02E);
        }

        /* ============================================================
           MOBILE SIDEBAR OVERLAY
        ============================================================ */
        .sidebar-overlay {
            transition: opacity 0.3s ease;
        }
        .sidebar-overlay.hidden {
            opacity: 0;
            pointer-events: none;
        }

        /* ============================================================
           SIDEBAR SLIDE ANIMATION
        ============================================================ */
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

        /* ============================================================
           MOBILE MENU BUTTON
        ============================================================ */
        .menu-btn {
            transition: all 0.3s ease;
        }
        .menu-btn:hover {
            background: #E8F5DE;
        }

        /* ============================================================
           CLOSE SIDEBAR BUTTON
        ============================================================ */
        .close-sidebar-btn {
            transition: all 0.2s ease;
        }
        .close-sidebar-btn:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: rotate(90deg);
        }

        /* ============================================================
           NOTIFICATION BADGE
        ============================================================ */
        .notification-dot {
            animation: ping 1.5s infinite;
        }
        @keyframes ping {
            0% { transform: scale(1); opacity: 1; }
            75% { transform: scale(1.5); opacity: 0.5; }
            100% { transform: scale(1); opacity: 0; }
        }

        /* ============================================================
           LOGOUT BUTTON LOADING STATE
        ============================================================ */
        .logout-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .logout-btn .spinner {
            display: none;
        }
        .logout-btn.loading .spinner {
            display: inline-block;
        }
        .logout-btn.loading .logout-text {
            display: none;
        }
    </style>
</head>
<body>

    <div class="flex flex-col lg:flex-row min-h-screen relative">

        <!-- ============================================================
             MOBILE SIDEBAR OVERLAY
        ============================================================ -->
        <div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 bg-black/60 backdrop-blur-sm z-40 hidden lg:hidden" onclick="closeSidebar()"></div>

        <!-- ============================================================
             SIDEBAR
        ============================================================ -->
        <aside id="sidebar" class="sidebar-slide fixed lg:relative top-0 left-0 h-full w-[280px] sidebar-gradient text-white p-6 flex flex-col shrink-0 z-50 lg:z-auto shadow-2xl lg:shadow-none">

            <!-- Mobile Close Button -->
            <button onclick="closeSidebar()" class="close-sidebar-btn lg:hidden absolute top-4 right-4 size-10 rounded-full flex items-center justify-center text-white/70 hover:text-white transition">
                <i class="fas fa-times text-xl"></i>
            </button>

            <!-- Profile Section -->
            <div class="text-center mb-8">
                <div class="size-20 rounded-full avatar-gradient flex items-center justify-center mx-auto mb-4 shadow-lg shadow-primary/30 ring-4 ring-white/20">
                    <i class="fas fa-user text-3xl text-white"></i>
                </div>
                <h2 class="font-display font-bold text-lg truncate px-2">{{ $staff->full_name }}</h2>
                <p class="text-white/60 text-sm truncate px-2">{{ $staff->email }}</p>
                <span class="inline-block mt-2 text-[11px] font-semibold bg-white/10 px-3 py-1 rounded-full border border-white/10">
                    {{ $staff->staff_code }}
                </span>
            </div>

            <!-- Navigation -->
            <nav class="space-y-0.5 flex-1 overflow-y-auto">
                <a href="{{ route('staff.dashboard') }}" class="nav-link active flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium">
                    <i class="fas fa-chart-pie size-4"></i> Dashboard
                </a>
                <a href="#" class="nav-link flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-white/70 hover:text-white">
                    <i class="fas fa-user-pen size-4"></i> Profile Settings
                </a>
                <a href="#" class="nav-link flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-white/70 hover:text-white">
                    <i class="fas fa-users size-4"></i> Customers
                    <span class="ml-auto bg-primary/30 text-xs px-2 py-0.5 rounded-full">24</span>
                </a>
                <a href="#" class="nav-link flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-white/70 hover:text-white">
                    <i class="fas fa-hand-holding-dollar size-4"></i> Loan Applications
                    <span class="ml-auto bg-yellow-400/30 text-yellow-200 text-xs px-2 py-0.5 rounded-full">12</span>
                </a>
                <a href="#" class="nav-link flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-white/70 hover:text-white">
                    <i class="fas fa-file-invoice size-4"></i> Reports
                </a>
                <a href="#" class="nav-link flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-white/70 hover:text-white">
                    <i class="fas fa-gear size-4"></i> Settings
                </a>
            </nav>

            <!-- ============================================================
                 LOGOUT BUTTON - Redirects to Staff Login
            ============================================================ -->
            <div class="mt-6 pt-6 border-t border-white/10">
                <form method="POST" action="{{ route('staff.logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-white/10 text-sm font-medium transition text-white/70 hover:text-white">
                        <i class="fas fa-arrow-right-from-bracket size-4"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- ============================================================
             MAIN CONTENT
        ============================================================ -->
        <main class="flex-1 p-4 sm:p-6 lg:p-10 overflow-y-auto w-full min-h-screen">

            <!-- ============================================================
                 HEADER
            ============================================================ -->
            <div class="mb-6 sm:mb-8">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <button onclick="toggleSidebar()" class="menu-btn lg:hidden size-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-600 shadow-sm hover:shadow-md transition flex-shrink-0">
                            <i class="fas fa-bars text-lg"></i>
                        </button>
                        <div class="min-w-0">
                            <h1 class="font-display font-bold text-xl sm:text-2xl text-[#1A2332] truncate">
                                Welcome, {{ $staff->first_name }} 👋
                            </h1>
                            <p class="text-gray-500 text-xs sm:text-sm mt-0.5 hidden sm:block truncate">Here's what's happening with your account today</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                        <span class="text-xs sm:text-sm text-gray-400 hidden md:block">Today, {{ date('M d, Y') }}</span>
                        <div class="size-9 sm:size-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 relative cursor-pointer hover:bg-gray-50 hover:border-gray-300 transition shadow-sm flex-shrink-0">
                            <i class="fas fa-bell text-sm sm:text-base"></i>
                            <span class="absolute -top-1 -right-1 size-4 sm:size-5 bg-red-500 rounded-full text-[8px] sm:text-[10px] text-white flex items-center justify-center font-bold">3</span>
                            <span class="absolute -top-1 -right-1 size-4 sm:size-5 bg-red-500 rounded-full notification-dot"></span>
                        </div>
                    </div>
                </div>
                <p class="text-gray-500 text-xs sm:text-sm mt-1 lg:hidden">Here's what's happening with your account today</p>
            </div>

            <!-- ============================================================
                 STAFF INFO CARDS
            ============================================================ -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-5 mb-6 sm:mb-8">
                <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4 stat-card-hover">
                    <div class="size-10 sm:size-12 bg-[#F4FAF0] rounded-xl flex items-center justify-center shrink-0">
                        <i class="fas fa-id-badge text-[#6DBE3B] text-base sm:text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-xs text-gray-500 font-medium uppercase tracking-wider">Staff Code</p>
                        <p class="font-bold text-[#1A2332] font-mono text-sm sm:text-lg truncate">{{ $staff->staff_code }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4 stat-card-hover">
                    <div class="size-10 sm:size-12 bg-[#F4FAF0] rounded-xl flex items-center justify-center shrink-0">
                        <i class="fas fa-building-columns text-[#6DBE3B] text-base sm:text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-xs text-gray-500 font-medium uppercase tracking-wider">Bank</p>
                        <p class="font-bold text-[#1A2332] text-sm sm:text-lg truncate">{{ $staff->bank->name ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4 stat-card-hover">
                    <div class="size-10 sm:size-12 bg-[#F4FAF0] rounded-xl flex items-center justify-center shrink-0">
                        <i class="fas fa-briefcase text-[#6DBE3B] text-base sm:text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-xs text-gray-500 font-medium uppercase tracking-wider">Designation</p>
                        <p class="font-bold text-[#1A2332] text-sm sm:text-lg truncate">{{ $staff->designation ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4 stat-card-hover">
                    <div class="size-10 sm:size-12 bg-[#F4FAF0] rounded-xl flex items-center justify-center shrink-0">
                        <i class="fas fa-location-dot text-[#6DBE3B] text-base sm:text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-xs text-gray-500 font-medium uppercase tracking-wider">Branch</p>
                        <p class="font-bold text-[#1A2332] text-sm sm:text-lg truncate">{{ $staff->branch_name ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4 stat-card-hover">
                    <div class="size-10 sm:size-12 bg-[#F4FAF0] rounded-xl flex items-center justify-center shrink-0">
                        <i class="fas fa-phone text-[#6DBE3B] text-base sm:text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-xs text-gray-500 font-medium uppercase tracking-wider">Phone</p>
                        <p class="font-bold text-[#1A2332] text-sm sm:text-lg truncate">{{ $staff->phone ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4 stat-card-hover">
                    <div class="size-10 sm:size-12 {{ $staff->status === 'active' ? 'bg-green-50' : 'bg-red-50' }} rounded-xl flex items-center justify-center shrink-0">
                        <i class="fas fa-circle-check {{ $staff->status === 'active' ? 'text-green-600' : 'text-red-600' }} text-base sm:text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-xs text-gray-500 font-medium uppercase tracking-wider">Status</p>
                        <p class="font-bold {{ $staff->status === 'active' ? 'text-green-600' : 'text-red-600' }} capitalize flex items-center gap-2 text-sm sm:text-lg">
                            {{ $staff->status }}
                            @if($staff->status === 'active')
                                <span class="size-1.5 sm:size-2 bg-green-600 rounded-full status-pulse"></span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- ============================================================
                 QUICK ACTIONS
            ============================================================ -->
            <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-200 shadow-sm p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h3 class="font-display font-bold text-[#1A2332] text-base sm:text-lg">Quick Actions</h3>
                    <a href="#" class="text-xs sm:text-sm text-[#6DBE3B] hover:text-[#58A02E] font-medium transition flex items-center gap-1">
                        View All <i class="fas fa-arrow-right text-[10px] sm:text-xs"></i>
                    </a>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
                    <a href="#" class="quick-action border border-gray-200 rounded-xl py-3 sm:py-5 px-2 text-center transition block">
                        <div class="size-10 sm:size-12 bg-[#F4FAF0] rounded-xl flex items-center justify-center mx-auto mb-2 sm:mb-3 transition">
                            <i class="fas fa-user-pen text-[#6DBE3B] text-base sm:text-lg"></i>
                        </div>
                        <p class="text-xs sm:text-sm font-medium text-[#1A2332]">Edit Profile</p>
                    </a>
                    <a href="#" class="quick-action border border-gray-200 rounded-xl py-3 sm:py-5 px-2 text-center transition block">
                        <div class="size-10 sm:size-12 bg-[#F4FAF0] rounded-xl flex items-center justify-center mx-auto mb-2 sm:mb-3 transition">
                            <i class="fas fa-users text-[#6DBE3B] text-base sm:text-lg"></i>
                        </div>
                        <p class="text-xs sm:text-sm font-medium text-[#1A2332]">Customers</p>
                    </a>
                    <a href="#" class="quick-action border border-gray-200 rounded-xl py-3 sm:py-5 px-2 text-center transition block">
                        <div class="size-10 sm:size-12 bg-[#F4FAF0] rounded-xl flex items-center justify-center mx-auto mb-2 sm:mb-3 transition">
                            <i class="fas fa-hand-holding-dollar text-[#6DBE3B] text-base sm:text-lg"></i>
                        </div>
                        <p class="text-xs sm:text-sm font-medium text-[#1A2332]">Loans</p>
                    </a>
                    <a href="#" class="quick-action border border-gray-200 rounded-xl py-3 sm:py-5 px-2 text-center transition block">
                        <div class="size-10 sm:size-12 bg-[#F4FAF0] rounded-xl flex items-center justify-center mx-auto mb-2 sm:mb-3 transition">
                            <i class="fas fa-file-invoice text-[#6DBE3B] text-base sm:text-lg"></i>
                        </div>
                        <p class="text-xs sm:text-sm font-medium text-[#1A2332]">Reports</p>
                    </a>
                    <a href="#" class="quick-action border border-gray-200 rounded-xl py-3 sm:py-5 px-2 text-center transition block col-span-2 sm:col-span-3 lg:col-span-1">
                        <div class="size-10 sm:size-12 bg-[#F4FAF0] rounded-xl flex items-center justify-center mx-auto mb-2 sm:mb-3 transition">
                            <i class="fas fa-gear text-[#6DBE3B] text-base sm:text-lg"></i>
                        </div>
                        <p class="text-xs sm:text-sm font-medium text-[#1A2332]">Settings</p>
                    </a>
                </div>
            </div>

        </main>
    </div>

    <!-- ============================================================
         JAVASCRIPT
    ============================================================ -->
    <script>
        // ============================================================
        // SIDEBAR TOGGLE
        // ============================================================
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            if (window.innerWidth < 1024) {
                const isClosed = sidebar.classList.contains('closed');
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

        function checkDesktopSidebar() {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('closed');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeSidebar();
        });

        window.addEventListener('resize', checkDesktopSidebar);
        checkDesktopSidebar();

        document.addEventListener('touchmove', function(e) {
            if (!sidebar.classList.contains('closed') && window.innerWidth < 1024) {
                const target = e.target;
                if (!sidebar.contains(target) && !overlay.contains(target)) {
                    e.preventDefault();
                }
            }
        }, { passive: false });
    </script>

</body>
</html>