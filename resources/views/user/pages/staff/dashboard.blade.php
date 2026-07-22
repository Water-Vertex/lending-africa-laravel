@extends('user.pages.staff.layouts.app')

@section('title', 'Staff Dashboard – African Investment Partners')
 <!-- @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">
            {{ session('success') }}
        </div>
    @endif -->

@push('styles')
<style>
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
       DROPDOWN MENU
    ============================================================ */
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
    
    /* Mobile menu button transition */
    .menu-btn {
        transition: all 0.3s ease;
    }
    .menu-btn:hover {
        background: #E8F5DE;
    }
</style>
@endpush

@section('content')
@php
    $staff = $staff ?? auth('staff')->user();
@endphp

<!-- Header -->
<div class="mb-6 sm:mb-8">
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3 min-w-0">
            <button onclick="toggleSidebar()" class="menu-btn lg:hidden size-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-600 shadow-sm hover:shadow-md transition flex-shrink-0">
                <i class="fas fa-bars text-lg"></i>
            </button>
            <div class="min-w-0">
                <h1 class="font-display font-bold text-xl sm:text-2xl text-[#1A2332] truncate">
                    Welcome, {{ $staff->first_name }} 
                </h1>
                <p class="text-gray-500 text-xs sm:text-sm mt-0.5 hidden sm:block truncate">Here's what's happening with your account today</p>
            </div>
        </div>
        
      
    </div>
    <p class="text-gray-500 text-xs sm:text-sm mt-1 lg:hidden">Here's what's happening with your account today</p>
</div>

<!-- Staff Info Cards -->
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

<!-- Quick Actions -->
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
        <a href="{{ route('staff.customer.create') }}" class="quick-action border border-gray-200 rounded-xl py-3 sm:py-5 px-2 text-center transition block">
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
@endsection

@push('scripts')
<script>
    // Dropdown toggle logic inside dashboard
    function toggleDropdown() {
        const menu = document.getElementById('dropdownMenu');
        menu.classList.toggle('open');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const container = document.getElementById('dropdownContainer');
        const menu = document.getElementById('dropdownMenu');
        
        if (container && !container.contains(event.target)) {
            if (menu) menu.classList.remove('open');
        }
    });

    // Close dropdown on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const menu = document.getElementById('dropdownMenu');
            if (menu) menu.classList.remove('open');
        }
    });
</script>
@endpush