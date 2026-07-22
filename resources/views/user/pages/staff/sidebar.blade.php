@php
    $staff = $staff ?? auth('staff')->user();
@endphp
<!-- ============================================================
     SIDEBAR - FIXED FULL HEIGHT (WITH RESPONSIVE CLASSES)
============================================================ -->
<aside id="sidebar" class="sidebar-slide closed fixed lg:relative top-0 left-0 min-h-screen w-[280px] sidebar-gradient text-white p-6 flex flex-col shrink-0 z-50 lg:z-auto shadow-2xl lg:shadow-none">

    <!-- Mobile Close Button -->
    <button onclick="closeSidebar()" class="close-sidebar-btn lg:hidden absolute top-4 right-4 size-10 rounded-full flex items-center justify-center text-white/70 hover:text-white transition">
        <i class="fas fa-times text-xl"></i>
    </button>

    <!-- Header Section - Only Title, No Logo Image -->
    <div class="text-center mb-6">
        <!-- AIP | Staff Portal Title -->
        <div class="mb-4">
           <h1 class="font-display font-bold text-2xl tracking-wider text-white">
    AIP | Staff
</h1>
            <span class="inline-block text-[11px] font-semibold bg-white/20 px-4 py-1 rounded-full border border-white/20 text-white/80 tracking-widest mt-1">
                PORTAL
            </span>
        </div>
        
        <!-- Divider -->
        <div class="border-t border-white/10 my-3"></div>
        
       
    </div>

    <!-- Navigation -->
    <nav class="space-y-0.5 flex-1 overflow-y-auto">
        <a href="{{ route('staff.dashboard') }}" class="nav-link active flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium">
            <i class="fas fa-chart-pie size-4"></i> Dashboard
        </a>
      <a href="{{ route('staff.profile.show') }}" class="nav-link flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-white/70 hover:text-white">
    <i class="fas fa-user-pen size-4"></i> Profile Settings
</a>
        <a href="{{ route('staff.customer.create') }}" class="nav-link flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-white/70 hover:text-white">
            <i class="fas fa-users size-4"></i> Customers
            <span class="ml-auto bg-green-500/30 text-green-200 text-xs px-2 py-0.5 rounded-full">24</span>
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

    <!-- Sidebar Footer -->
    <div class="mt-auto pt-4 border-t border-white/10">
        <p class="text-white/30 text-xs text-center">v2.0.1</p>
    </div>
</aside>