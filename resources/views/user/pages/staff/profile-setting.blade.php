@extends('user.pages.staff.layouts.app')

@section('title', 'Profile Settings')

@section('content')
<div class="max-w-2xl mx-auto py-6">

    <a href="{{ route('staff.dashboard') }}"
        class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-[#1A2332] mb-6 transition group">
        <span class="w-7 h-7 rounded-full bg-gray-100 group-hover:bg-gray-200 flex items-center justify-center transition">
            <i class="fas fa-arrow-left text-xs"></i>
        </span>
        <span>Back to Dashboard</span>
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Header strip --}}
        <div class="px-6 sm:px-8 pt-7 pb-6 border-b border-gray-100 bg-gradient-to-br from-gray-50 to-white">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full avatar-gradient flex items-center justify-center text-white text-xl font-bold flex-shrink-0">
                    {{ strtoupper(substr($staff->first_name ?? 'S', 0, 1)) }}
                </div>
                <div>
                    <h2 class="font-display text-lg font-bold text-[#1A2332]">Profile Settings</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Manage your personal information</p>
                </div>
            </div>
        </div>

        <div class="px-6 sm:px-8 py-7">

            @if (session('success'))
                <div class="mb-6 flex items-start gap-3 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3.5">
                    <i class="fas fa-circle-check mt-0.5 text-green-500"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 flex items-start gap-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3.5">
                    <i class="fas fa-circle-exclamation mt-0.5 text-red-500"></i>
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Read-only info strip: Staff Code + Bank --}}
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="rounded-xl bg-gray-50 border border-gray-100 px-4 py-3">
                    <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide mb-1">Staff Code</p>
                    <p class="text-sm font-bold text-[#1A2332] flex items-center gap-2">
                        {{ $staff->staff_code }}
                        <i class="fas fa-lock text-[10px] text-gray-300"></i>
                    </p>
                </div>
                <div class="rounded-xl bg-gray-50 border border-gray-100 px-4 py-3">
                    <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide mb-1">Bank</p>
                    <p class="text-sm font-bold text-[#1A2332] flex items-center gap-2 truncate">
                        {{ $staff->bank->name ?? '—' }}
                        <i class="fas fa-lock text-[10px] text-gray-300"></i>
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('staff.profile.update') }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="first_name" class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">
                            First Name
                        </label>
                        <input type="text" id="first_name" name="first_name" required
                            value="{{ old('first_name', $staff->first_name) }}"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50/60 px-4 py-3 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#6DBE3B]/40 focus:border-[#6DBE3B] focus:bg-white transition">
                    </div>

                    <div>
                        <label for="last_name" class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">
                            Last Name
                        </label>
                        <input type="text" id="last_name" name="last_name" required
                            value="{{ old('last_name', $staff->last_name) }}"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50/60 px-4 py-3 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#6DBE3B]/40 focus:border-[#6DBE3B] focus:bg-white transition">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">
                        Email Address
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-300">
                            <i class="fas fa-envelope text-sm"></i>
                        </span>
                        <input type="email" id="email" name="email" required
                            value="{{ old('email', $staff->email) }}"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50/60 pl-10 pr-4 py-3 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#6DBE3B]/40 focus:border-[#6DBE3B] focus:bg-white transition">
                    </div>
                </div>

                <div>
                    <label for="phone" class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">
                        Phone Number
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-300">
                            <i class="fas fa-phone text-sm"></i>
                        </span>
                        <input type="text" id="phone" name="phone"
                            value="{{ old('phone', $staff->phone) }}"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50/60 pl-10 pr-4 py-3 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#6DBE3B]/40 focus:border-[#6DBE3B] focus:bg-white transition">
                    </div>
                </div>

                <div class="border-t border-dashed border-gray-200 pt-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="branch_name" class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">
                            Branch Name
                        </label>
                        <input type="text" id="branch_name" name="branch_name"
                            value="{{ old('branch_name', $staff->branch_name) }}"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50/60 px-4 py-3 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#6DBE3B]/40 focus:border-[#6DBE3B] focus:bg-white transition">
                    </div>

                    <div>
                        <label for="designation" class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">
                            Designation
                        </label>
                        <input type="text" id="designation" name="designation"
                            value="{{ old('designation', $staff->designation) }}"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50/60 px-4 py-3 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#6DBE3B]/40 focus:border-[#6DBE3B] focus:bg-white transition">
                    </div>
                </div>

                <div>
                    <label for="employee_id" class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">
                        Employee ID
                    </label>
                    <input type="text" id="employee_id" name="employee_id"
                        value="{{ old('employee_id', $staff->employee_id) }}"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50/60 px-4 py-3 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#6DBE3B]/40 focus:border-[#6DBE3B] focus:bg-white transition">
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                        class="flex-1 inline-flex items-center justify-center gap-2 bg-[#6DBE3B] hover:bg-[#58A02E] text-white font-semibold text-sm py-3 rounded-xl transition shadow-sm shadow-[#6DBE3B]/20">
                        <i class="fas fa-check text-xs"></i>
                        <span>Save Changes</span>
                    </button>
                    <a href="{{ route('staff.dashboard') }}"
                        class="px-5 py-3 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection