@extends('user.layouts.app')
@section('title', 'Customer Registration – African Investment Partners')

@section('content')
<style>
    .form-container {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 24px;
        box-shadow: 0 20px 60px -15px rgba(0, 0, 0, 0.08);
    }
    .form-header {
        background: linear-gradient(135deg, #1A2332 0%, #243447 100%);
        border-radius: 24px 24px 0 0;
        padding: 2rem 2.5rem;
        position: relative;
        overflow: hidden;
    }
    .form-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: rgba(109, 190, 59, 0.08);
        border-radius: 50%;
    }
    .form-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 200px;
        height: 200px;
        background: rgba(109, 190, 59, 0.05);
        border-radius: 50%;
    }
    .form-input-premium {
        width: 100%;
        padding: 0.75rem 1rem;
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        color: #1a2332;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }
    .form-input-premium:hover { border-color: #b8c5d6; background: #fafbfc; }
    .form-input-premium:focus {
        background: #ffffff;
        border-color: #6DBE3B;
        outline: none;
        box-shadow: 0 0 0 4px rgba(109, 190, 59, 0.12), 0 4px 12px rgba(109, 190, 59, 0.08);
        transform: translateY(-1px);
    }
    .form-input-premium.input-error {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
    }
    .form-input-premium.input-success {
        border-color: #6DBE3B !important;
        box-shadow: 0 0 0 3px rgba(109, 190, 59, 0.1) !important;
    }
    .form-input-premium::placeholder { color: #94a3b8; font-weight: 400; font-size: 0.8rem; }
    .form-input-premium:disabled { background: #f1f5f9; cursor: not-allowed; opacity: 0.7; }
    .field-error-msg {
        font-size: 0.72rem;
        color: #ef4444;
        margin-top: 0.3rem;
        display: none;
        align-items: center;
        gap: 0.3rem;
    }
    .field-error-msg.show { display: flex; }
    .field-hint {
        font-size: 0.7rem;
        color: #94a3b8;
        margin-top: 0.25rem;
    }
    .form-label-premium {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        transition: color 0.3s ease;
    }
    .form-group:focus-within .form-label-premium { color: #6DBE3B; }
    .form-section-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 1.1rem;
        color: #1a2332;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f1f5f9;
        margin-bottom: 1.5rem;
    }
    .form-section-title .icon-wrapper {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #6DBE3B, #58A02E);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.9rem;
        flex-shrink: 0;
    }
    .step-wizard {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        background: #f8fafc;
        border-radius: 16px;
        border: 1px solid #eef2f6;
    }
    .step-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex: 1;
        position: relative;
    }
    .step-item:not(:last-child)::after {
        content: '';
        position: absolute;
        right: -0.5rem;
        top: 50%;
        transform: translateY(-50%);
        width: 2rem;
        height: 2px;
        background: #e2e8f0;
    }
    .step-number {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.875rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        flex-shrink: 0;
        background: #e2e8f0;
        color: #64748b;
    }
    .step-active .step-number {
        background: linear-gradient(135deg, #6DBE3B, #58A02E);
        color: white;
        box-shadow: 0 4px 12px rgba(109, 190, 59, 0.3);
    }
    .step-completed .step-number { background: #6DBE3B; color: white; }
    .step-text { font-size: 0.8rem; font-weight: 600; color: #94a3b8; transition: color 0.3s ease; }
    .step-active .step-text { color: #1a2332; }
    .step-completed .step-text { color: #6DBE3B; }
    .document-row {
        background: #f8fafc;
        border: 2px solid #eef2f6;
        border-radius: 16px;
        padding: 1.25rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .document-row:hover { border-color: #cbd5e1; background: #fafbfc; }
    .document-row .form-input-premium { background: #ffffff; }
    .btn-primary-premium {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.7rem 1.75rem;
        background: linear-gradient(135deg, #6DBE3B, #58A02E);
        color: white;
        font-weight: 700;
        font-size: 0.8rem;
        border-radius: 12px;
        border: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        box-shadow: 0 4px 16px rgba(109, 190, 59, 0.25);
    }
    .btn-primary-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(109, 190, 59, 0.35);
        background: linear-gradient(135deg, #7acc4a, #58A02E);
    }
    .btn-secondary-premium {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.7rem 1.75rem;
        background: #f1f5f9;
        color: #475569;
        font-weight: 700;
        font-size: 0.8rem;
        border-radius: 12px;
        border: 2px solid transparent;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .btn-secondary-premium:hover { background: #e2e8f0; color: #1a2332; }
    .btn-outline-premium {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.7rem 1.75rem;
        background: transparent;
        color: #6DBE3B;
        font-weight: 700;
        font-size: 0.8rem;
        border-radius: 12px;
        border: 2px solid #6DBE3B;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .btn-outline-premium:hover { background: #6DBE3B; color: white; }
    .alert-premium {
        border-radius: 16px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        border: 2px solid transparent;
    }
    .alert-premium-success { background: #f0fdf4; border-color: #bbf7d0; }
    .alert-premium-error { background: #fef2f2; border-color: #fecaca; }
    .alert-premium .icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.75rem;
    }
    .alert-premium-success .icon { background: #6DBE3B; color: white; }
    .alert-premium-error .icon { background: #ef4444; color: white; }

    @media (max-width: 640px) {
        .form-header { padding: 1.5rem; }
        .step-wizard { flex-direction: column; align-items: stretch; gap: 0.5rem; }
        .step-item:not(:last-child)::after { display: none; }
        .form-section-title { font-size: 0.95rem; }
    }
</style>

<div class="max-w-5xl mx-auto px-4 py-8">

    {{-- HEADER --}}
    <div class="form-header mb-6">
        <div class="relative z-10">
            <div class="flex items-center gap-2 text-[#6DBE3B] font-semibold text-xs tracking-widest uppercase mb-1">
                <span class="inline-block w-2 h-2 rounded-full bg-[#6DBE3B] animate-pulse"></span>
                Staff Portal • Customer Onboarding
            </div>
            <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-white">Register New Customer</h1>
            <p class="text-white/60 text-sm mt-1 max-w-2xl">
                Complete the form below to create a customer profile, attach KYC documents, and initiate a loan application.
            </p>
        </div>
    </div>

    {{-- ALERTS --}}
    @if (session('success'))
        <div class="alert-premium alert-premium-success mb-6">
            <div class="icon"><i class="fas fa-check"></i></div>
            <div>
                <h4 class="font-bold text-emerald-800 text-sm">Registration Successful</h4>
                <p class="text-emerald-700 text-xs mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert-premium alert-premium-error mb-6">
            <div class="icon"><i class="fas fa-exclamation"></i></div>
            <div class="flex-1 min-w-0">
                <h4 class="font-bold text-rose-800 text-sm">Please fix the following errors</h4>
                <ul class="text-rose-700 text-xs mt-1 space-y-0.5">
                    @foreach ($errors->all() as $err)
                        <li>• {{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- STEP WIZARD --}}
    <div class="step-wizard mb-6">
        <div id="step-indicator-1" class="step-item step-active">
            <div class="step-number">1</div>
            <span class="step-text">Customer Profile</span>
        </div>
        <div id="step-indicator-2" class="step-item">
            <div class="step-number">2</div>
            <span class="step-text">Co-Signer</span>
        </div>
        <div id="step-indicator-3" class="step-item">
            <div class="step-number">3</div>
            <span class="step-text">Loan Application</span>
        </div>
    </div>

    {{-- FORM --}}
    <div class="form-container p-6 sm:p-8">
        <form method="POST" action="{{ route('staff.customer.store') }}" enctype="multipart/form-data" id="customer-form" novalidate>
            @csrf

            {{-- STEP 1 --}}
            <div id="step-1">

                {{-- Personal Information --}}
                <div class="mb-8">
                    <div class="form-section-title">
                        <div class="icon-wrapper"><i class="fas fa-user"></i></div>
                        Personal Information
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="form-group">
                            <label class="form-label-premium">Customer Type <span class="text-red-500">*</span></label>
                            <select name="customer_type" id="customer_type" required class="form-input-premium">
                                <option value="personal" {{ old('customer_type', 'personal') == 'personal' ? 'selected' : '' }}>👤 Personal</option>
                                <option value="sme" {{ old('customer_type') == 'sme' ? 'selected' : '' }}>🏢 SME / Business</option>
                            </select>
                            <p class="field-error-msg" id="err-customer_type"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">First Name <span class="text-red-500">*</span></label>
                            <input type="text" name="first_name" id="first_name" required class="form-input-premium" value="{{ old('first_name') }}" placeholder="e.g. Emeka" maxlength="100">
                            <p class="field-error-msg" id="err-first_name"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" name="last_name" id="last_name" required class="form-input-premium" value="{{ old('last_name') }}" placeholder="e.g. Okonkwo" maxlength="100">
                            <p class="field-error-msg" id="err-last_name"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">Middle Name</label>
                            <input type="text" name="middle_name" id="middle_name" class="form-input-premium" value="{{ old('middle_name') }}" placeholder="Optional" maxlength="100">
                            <p class="field-error-msg" id="err-middle_name"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" class="form-input-premium" value="{{ old('date_of_birth') }}">
                            <p class="field-hint">Customer must be 18–80 years old</p>
                            <p class="field-error-msg" id="err-date_of_birth"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Gender</label>
                            <select name="gender" id="gender" class="form-input-premium">
                                <option value="">Select gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <p class="field-error-msg" id="err-gender"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                </div>

                {{-- Contact Information --}}
                <div class="mb-8">
                    <div class="form-section-title">
                        <div class="icon-wrapper"><i class="fas fa-address-book"></i></div>
                        Contact Details
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-white mb-1.5">Email Address</label>
                            <input type="email" 
                                   name="email" 
                                   placeholder="john@example.com" 
                                   required 
                                   class="contact-form-input {{ $prefillEmail ? 'bg-green-50' : '' }}" 
                                   value="{{ old('email', $prefillEmail) }}"
                                   {{ $prefillEmail ? 'readonly' : '' }}>
                            @if($prefillEmail)
                                <p style="font-size:0.72rem; color:#6DBE3B; margin-top:0.3rem;">
                                    <i class="fas fa-check-circle"></i> Email pre-filled from your loan inquiry
                                </p>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">National ID (NIN)</label>
                            <input type="text" name="national_id" id="national_id" class="form-input-premium" value="{{ old('national_id') }}" placeholder="e.g. 12345678901" maxlength="11" inputmode="numeric">
                            <p class="field-hint">NIN is exactly 11 digits</p>
                            <p class="field-error-msg" id="err-national_id"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">Primary Phone <span class="text-red-500">*</span></label>
                            <input type="text" name="phone_primary" id="phone_primary" required class="form-input-premium" value="{{ old('phone_primary') }}" placeholder="e.g. 08012345678" maxlength="11" inputmode="numeric">
                            <p class="field-hint">11 digits starting with 0 (e.g. 0801 234 5678)</p>
                            <p class="field-error-msg" id="err-phone_primary"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Secondary Phone</label>
                            <input type="text" name="phone_secondary" id="phone_secondary" class="form-input-premium" value="{{ old('phone_secondary') }}" placeholder="e.g. 07012345678" maxlength="11" inputmode="numeric">
                            <p class="field-hint">11 digits starting with 0 (optional)</p>
                            <p class="field-error-msg" id="err-phone_secondary"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                </div>

                {{-- Professional Information --}}
                <div class="mb-8">
                    <div class="form-section-title">
                        <div class="icon-wrapper"><i class="fas fa-briefcase"></i></div>
                        Professional Information
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label-premium">Occupation</label>
                            <input type="text" name="occupation" id="occupation" class="form-input-premium" value="{{ old('occupation') }}" placeholder="e.g. Civil Servant, Trader, Engineer" maxlength="150">
                            <p class="field-error-msg" id="err-occupation"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Monthly Income (₦)</label>
                            <input type="number" step="0.01" name="monthly_income" id="monthly_income" class="form-input-premium" value="{{ old('monthly_income') }}" placeholder="e.g. 150000" min="0" inputmode="numeric">
                            <p class="field-hint">Enter amount in Naira (numbers only)</p>
                            <p class="field-error-msg" id="err-monthly_income"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                </div>

                {{-- Business Section (SME) --}}
                <div id="business-info-section" style="display:none;" class="mb-8 p-5 bg-slate-50 rounded-2xl border-2 border-slate-200">
                    <div class="form-section-title !border-slate-300">
                        <div class="icon-wrapper !bg-slate-700"><i class="fas fa-building"></i></div>
                        Business Information
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label-premium">Business Name <span class="text-red-500">*</span></label>
                            <input type="text" name="business_name" id="business_name" class="form-input-premium" value="{{ old('business_name') }}" placeholder="Registered business name" maxlength="200">
                            <p class="field-error-msg" id="err-business_name"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Business Type</label>
                            <input type="text" name="business_type" id="business_type" class="form-input-premium" value="{{ old('business_type') }}" placeholder="e.g. Retail, Technology, Agriculture" maxlength="100">
                            <p class="field-error-msg" id="err-business_type"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">CAC Registration Number</label>
                            <input type="text" name="registration_number" id="registration_number" class="form-input-premium" value="{{ old('registration_number') }}" placeholder="e.g. RC1234567" maxlength="20">
                            <p class="field-hint">Format: RC followed by 6–7 digits (e.g. RC1234567)</p>
                            <p class="field-error-msg" id="err-registration_number"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Tax ID (TIN)</label>
                            <input type="text" name="tax_number" id="tax_number" class="form-input-premium" value="{{ old('tax_number') }}" placeholder="e.g. 1234567890" maxlength="14" inputmode="numeric">
                            <p class="field-hint">10 digits (e.g. 1234567890)</p>
                            <p class="field-error-msg" id="err-tax_number"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">Monthly Revenue (₦)</label>
                            <input type="number" step="0.01" name="business_monthly_revenue" id="business_monthly_revenue" class="form-input-premium" value="{{ old('business_monthly_revenue') }}" placeholder="e.g. 500000" min="0" inputmode="numeric">
                            <p class="field-hint">Numbers only, in Naira</p>
                            <p class="field-error-msg" id="err-business_monthly_revenue"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Monthly Expenses (₦)</label>
                            <input type="number" step="0.01" name="business_monthly_expense" id="business_monthly_expense" class="form-input-premium" value="{{ old('business_monthly_expense') }}" placeholder="e.g. 200000" min="0" inputmode="numeric">
                            <p class="field-hint">Numbers only, in Naira</p>
                            <p class="field-error-msg" id="err-business_monthly_expense"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">State</label>
                            <input type="text" name="business_state" id="business_state" class="form-input-premium" value="{{ old('business_state') }}" placeholder="e.g. Lagos">
                            <p class="field-error-msg" id="err-business_state"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">City</label>
                            <input type="text" name="business_city" id="business_city" class="form-input-premium" value="{{ old('business_city') }}" placeholder="e.g. Ikeja">
                            <p class="field-error-msg" id="err-business_city"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">LGA</label>
                            <input type="text" name="business_local_government_area" id="business_local_government_area" class="form-input-premium" value="{{ old('business_local_government_area') }}" placeholder="e.g. Ikeja LGA">
                            <p class="field-error-msg" id="err-business_lga"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                    <div class="form-group mt-4">
                        <label class="form-label-premium">Business Address</label>
                        <textarea name="business_address" id="business_address" rows="2" class="form-input-premium resize-none" placeholder="Full business address including street, area">{{ old('business_address') }}</textarea>
                        <p class="field-error-msg" id="err-business_address"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                    </div>
                </div>

                {{-- Address --}}
                <div class="mb-8">
                    <div class="form-section-title">
                        <div class="icon-wrapper"><i class="fas fa-map-pin"></i></div>
                        Residential Address
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="form-group">
                            <label class="form-label-premium">Country</label>
                            <input type="text" name="country" id="country" class="form-input-premium" value="{{ old('country', 'Nigeria') }}" placeholder="Nigeria">
                            <p class="field-error-msg" id="err-country"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">State <span class="text-red-500">*</span></label>
                            <input type="text" name="state" id="state" required class="form-input-premium" value="{{ old('state') }}" placeholder="e.g. Lagos, Abuja, Kano">
                            <p class="field-error-msg" id="err-state"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">City <span class="text-red-500">*</span></label>
                            <input type="text" name="city" id="city" required class="form-input-premium" value="{{ old('city') }}" placeholder="e.g. Ikeja, Wuse, Sabon Gari">
                            <p class="field-error-msg" id="err-city"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">LGA</label>
                            <input type="text" name="local_government_area" id="local_government_area" class="form-input-premium" value="{{ old('local_government_area') }}" placeholder="Local Government Area">
                            <p class="field-error-msg" id="err-lga"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group sm:col-span-3">
                            <label class="form-label-premium">Full Address <span class="text-red-500">*</span></label>
                            <textarea name="address" id="address" required rows="2" class="form-input-premium resize-none" placeholder="House number, street name, area, landmark">{{ old('address') }}</textarea>
                            <p class="field-error-msg" id="err-address"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                </div>

                {{-- Status --}}
                <div class="mb-8">
                    <div class="form-section-title">
                        <div class="icon-wrapper"><i class="fas fa-shield-alt"></i></div>
                        Account Status
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label-premium">Initial Status</label>
                            <select name="status" id="status" class="form-input-premium">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>🟢 Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>🔴 Inactive</option>
                                <option value="blacklisted" {{ old('status') == 'blacklisted' ? 'selected' : '' }}>⚫ Blacklisted</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Documents --}}
                <div class="mb-8">
                    <div class="form-section-title">
                        <div class="icon-wrapper"><i class="fas fa-folder-open"></i></div>
                        KYC Documents
                    </div>
                    <div id="documents-wrapper" class="space-y-4"></div>
                    <button type="button" id="add-document-btn" class="btn-outline-premium mt-3 !text-xs">
                        <i class="fas fa-plus"></i> Add Document
                    </button>
                </div>

                {{-- Bank Details --}}
                <div class="mb-8">
                    <div class="form-section-title">
                        <div class="icon-wrapper"><i class="fas fa-university"></i></div>
                        Customer Bank Details
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="form-group">
                            <label class="form-label-premium">Bank <span class="text-red-500">*</span></label>
                            <select name="bank_id" id="bank_id" required class="form-input-premium">
                                <option value="">Select bank</option>
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank->id }}" {{ old('bank_id') == $bank->id ? 'selected' : '' }}>
                                        {{ $bank->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="field-error-msg" id="err-bank_id"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Account Name <span class="text-red-500">*</span></label>
                            <input type="text" name="account_name" id="account_name" required class="form-input-premium" value="{{ old('account_name') }}" placeholder="e.g. EMEKA JOHN OKONKWO" maxlength="200">
                            <p class="field-hint">As it appears on your bank account</p>
                            <p class="field-error-msg" id="err-account_name"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Account Number <span class="text-red-500">*</span></label>
                            <input type="text" name="account_number" id="account_number" required class="form-input-premium" value="{{ old('account_number') }}" placeholder="e.g. 0123456789" maxlength="10" inputmode="numeric">
                            <p class="field-hint">NUBAN — exactly 10 digits, no dashes</p>
                            <p class="field-error-msg" id="err-account_number"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                </div>

                {{-- Step 1 Actions --}}
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t-2 border-slate-100">
                    <a href="{{ url()->previous() }}" class="btn-secondary-premium">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="button" id="next-btn-1" class="btn-primary-premium">
                        <span>Continue to Co-Signer</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            {{-- STEP 2 — CO-SIGNER --}}
            <div id="step-2" style="display:none;">
                <div class="mb-8">
                    <div class="form-section-title">
                        <div class="icon-wrapper"><i class="fas fa-user-shield"></i></div>
                        Co-Signer / Guarantor Information
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="form-group">
                            <label class="form-label-premium">First Name <span class="text-red-500">*</span></label>
                            <input type="text" name="cosigner_first_name" id="cosigner_first_name" required class="form-input-premium" value="{{ old('cosigner_first_name') }}" placeholder="e.g. Chidi" maxlength="100">
                            <p class="field-error-msg" id="err-cosigner_first_name"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" name="cosigner_last_name" id="cosigner_last_name" required class="form-input-premium" value="{{ old('cosigner_last_name') }}" placeholder="e.g. Nwosu" maxlength="100">
                            <p class="field-error-msg" id="err-cosigner_last_name"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Middle Name</label>
                            <input type="text" name="cosigner_middle_name" id="cosigner_middle_name" class="form-input-premium" value="{{ old('cosigner_middle_name') }}" placeholder="Optional" maxlength="100">
                            <p class="field-error-msg" id="err-cosigner_middle_name"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">Date of Birth</label>
                            <input type="date" name="cosigner_date_of_birth" id="cosigner_date_of_birth" class="form-input-premium" value="{{ old('cosigner_date_of_birth') }}">
                            <p class="field-hint">Must be 18–80 years old</p>
                            <p class="field-error-msg" id="err-cosigner_date_of_birth"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Relationship to Customer <span class="text-red-500">*</span></label>
                            <input type="text" name="cosigner_relationship" id="cosigner_relationship" required class="form-input-premium" value="{{ old('cosigner_relationship') }}" placeholder="e.g. Spouse, Sibling, Colleague" maxlength="100">
                            <p class="field-error-msg" id="err-cosigner_relationship"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">BVN <span class="text-red-500">*</span></label>
                            <input type="text" name="cosigner_bvn" id="cosigner_bvn" required class="form-input-premium" value="{{ old('cosigner_bvn') }}" placeholder="e.g. 12345678901" maxlength="11" inputmode="numeric">
                            <p class="field-hint">Bank Verification Number — exactly 11 digits</p>
                            <p class="field-error-msg" id="err-cosigner_bvn"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">Email Address</label>
                            <input type="email" name="cosigner_email" id="cosigner_email" class="form-input-premium" value="{{ old('cosigner_email') }}" placeholder="cosigner@example.com">
                            <p class="field-error-msg" id="err-cosigner_email"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Occupation <span class="text-red-500">*</span></label>
                            <input type="text" name="cosigner_occupation" id="cosigner_occupation" required class="form-input-premium" value="{{ old('cosigner_occupation') }}" placeholder="e.g. Teacher, Trader, Civil Servant" maxlength="150">
                            <p class="field-error-msg" id="err-cosigner_occupation"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">Primary Phone <span class="text-red-500">*</span></label>
                            <input type="text" name="cosigner_phone_primary" id="cosigner_phone_primary" required class="form-input-premium" value="{{ old('cosigner_phone_primary') }}" placeholder="e.g. 08012345678" maxlength="11" inputmode="numeric">
                            <p class="field-hint">11 digits starting with 0 (e.g. 0801 234 5678)</p>
                            <p class="field-error-msg" id="err-cosigner_phone_primary"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Secondary Phone</label>
                            <input type="text" name="cosigner_phone_secondary" id="cosigner_phone_secondary" class="form-input-premium" value="{{ old('cosigner_phone_secondary') }}" placeholder="e.g. 07012345678" maxlength="11" inputmode="numeric">
                            <p class="field-hint">11 digits starting with 0 (optional)</p>
                            <p class="field-error-msg" id="err-cosigner_phone_secondary"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">Country</label>
                            <input type="text" name="cosigner_country" id="cosigner_country" class="form-input-premium" value="{{ old('cosigner_country', 'Nigeria') }}" placeholder="Nigeria">
                            <p class="field-error-msg" id="err-cosigner_country"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">State <span class="text-red-500">*</span></label>
                            <input type="text" name="cosigner_state" id="cosigner_state" required class="form-input-premium" value="{{ old('cosigner_state') }}" placeholder="e.g. Lagos">
                            <p class="field-error-msg" id="err-cosigner_state"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">City <span class="text-red-500">*</span></label>
                            <input type="text" name="cosigner_city" id="cosigner_city" required class="form-input-premium" value="{{ old('cosigner_city') }}" placeholder="e.g. Ikeja">
                            <p class="field-error-msg" id="err-cosigner_city"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                    <div class="form-group mt-4">
                        <label class="form-label-premium">Address <span class="text-red-500">*</span></label>
                        <textarea name="cosigner_address" id="cosigner_address" required rows="2" class="form-input-premium resize-none" placeholder="Full residential address">{{ old('cosigner_address') }}</textarea>
                        <p class="field-error-msg" id="err-cosigner_address"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">Photo ID (Upload)</label>
                            <input type="file" name="cosigner_photo_id" id="cosigner_photo_id" class="form-input-premium !py-2" accept=".jpg,.jpeg,.png,.pdf">
                            <p class="field-hint">Accepted: JPG, PNG, PDF — max 2MB</p>
                            <p class="field-error-msg" id="err-cosigner_photo_id"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Evidence of Occupation (Upload)</label>
                            <input type="file" name="cosigner_evidence_of_occupation" id="cosigner_evidence_of_occupation" class="form-input-premium !py-2" accept=".jpg,.jpeg,.png,.pdf">
                            <p class="field-hint">Accepted: JPG, PNG, PDF — max 2MB</p>
                            <p class="field-error-msg" id="err-cosigner_evidence"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                </div>

                {{-- Step 2 Actions --}}
                <div class="flex flex-col sm:flex-row justify-between gap-3 pt-6 border-t-2 border-slate-100">
                    <button type="button" id="back-btn-2" class="btn-secondary-premium">
                        <i class="fas fa-arrow-left"></i> Back to Profile
                    </button>
                    <button type="button" id="next-btn-2" class="btn-primary-premium">
                        <span>Continue to Loan</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            {{-- STEP 3 — LOAN APPLICATION --}}
            <div id="step-3" style="display:none;">
                <div class="mb-8">
                    <div class="form-section-title">
                        <div class="icon-wrapper"><i class="fas fa-hand-holding-usd"></i></div>
                        Loan Application
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label-premium">Loan Product <span class="text-red-500">*</span></label>
                            <select name="loan_product_id" id="loan_product_id" required class="form-input-premium">
                                <option value="">Select a product</option>
                                @foreach ($loanProducts as $product)
                                    <option
                                        value="{{ $product->id }}"
                                        data-type="{{ $product->loan_type }}"
                                        data-min="{{ $product->minimum_amount }}"
                                        data-max="{{ $product->maximum_amount }}"
                                        data-minDuration="{{ $product->minimum_duration_month ?? $product->duration_months ?? 3 }}"
                                        data-maxDuration="{{ $product->duration_months ?? 360 }}"
                                        data-duration="{{ $product->duration_months }}"
                                        {{ old('loan_product_id') == $product->id ? 'selected' : '' }}
                                    >
                                        {{ $product->name }} (₦{{ number_format($product->minimum_amount) }} – ₦{{ number_format($product->maximum_amount) }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="field-error-msg" id="err-loan_product_id"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Loan Amount (₦) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="loan_amount" id="loan_amount" required class="form-input-premium" value="{{ old('loan_amount') }}" placeholder="e.g. 100000" min="0" inputmode="numeric">
                            <p class="field-hint" id="loan-amount-hint">Select a loan product first</p>
                            <p class="field-error-msg" id="err-loan_amount"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                    
                    {{-- DURATION DROPDOWN --}}
                    <div class="grid grid-cols-1 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">Duration (Months) <span class="text-red-500">*</span></label>
                            
                            <select name="duration_months" id="duration_months" required class="form-input-premium">
                                <option value="">Select duration</option>
                                @foreach ($loanProducts as $product)
                                    @php
                                        $minDur = $product->minimum_duration_month ?? $product->duration_months ?? 3;
                                        $maxDur = $product->duration_months ?? 12;
                                        // Agar min > max ho toh swap karo
                                        if ($minDur > $maxDur) {
                                            $temp = $minDur;
                                            $minDur = $maxDur;
                                            $maxDur = $temp;
                                        }
                                    @endphp
                                    @for ($i = $minDur; $i <= $maxDur; $i++)
                                        <option 
                                            value="{{ $i }}" 
                                            class="duration-option" 
                                            data-product-id="{{ $product->id }}"
                                            style="display: none;"
                                            {{ old('duration_months') == $i ? 'selected' : '' }}
                                        >
                                            {{ $i }} month{{ $i > 1 ? 's' : '' }}
                                        </option>
                                    @endfor
                                @endforeach
                            </select>
                            
                            <p class="field-hint" id="duration-hint">Select a loan product first</p>
                            <p class="field-error-msg" id="err-duration_months">
                                <i class="fas fa-circle-exclamation"></i> <span></span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="form-group mt-4">
                        <label class="form-label-premium">Loan Purpose <span class="text-red-500">*</span></label>
                        <textarea name="purpose" id="purpose" required rows="3" class="form-input-premium resize-none" placeholder="Describe how the customer will use these funds (minimum 20 characters)">{{ old('purpose') }}</textarea>
                        <p class="field-hint">Minimum 20 characters</p>
                        <p class="field-error-msg" id="err-purpose"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                    </div>
                </div>

                {{-- Step 3 Actions --}}
                <div class="flex flex-col sm:flex-row justify-between gap-3 pt-6 border-t-2 border-slate-100">
                    <button type="button" id="back-btn-3" class="btn-secondary-premium">
                        <i class="fas fa-arrow-left"></i> Back to Co-Signer
                    </button>
                    <button type="submit" id="submit-btn" class="btn-primary-premium">
                        <span id="submit-btn-text">Submit & Create Customer</span>
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

{{-- Document Template --}}
<template id="document-row-template">
    <div class="document-row">
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="form-group">
                <label class="form-label-premium !text-[10px]">Document Type</label>
                <select name="documents[__INDEX__][document_type]" class="form-input-premium !py-2">
                    <option value="national_id">National ID / NIN</option>
                    <option value="passport">International Passport</option>
                    <option value="driver_license">Driver's License</option>
                    <option value="other">Other Document</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label-premium !text-[10px]">Upload File</label>
                <input type="file" name="documents[__INDEX__][file]" class="form-input-premium !py-1.5 doc-file-input" accept=".jpg,.jpeg,.png,.pdf">
                <p class="field-hint">JPG, PNG or PDF — max 2MB</p>
                <p class="field-error-msg doc-file-error"><i class="fas fa-circle-exclamation"></i> <span></span></p>
            </div>
            <div class="form-group">
                <label class="form-label-premium !text-[10px]">Verification Status</label>
                <select name="documents[__INDEX__][verification_status]" class="form-input-premium !py-2">
                    <option value="pending">Pending Review</option>
                    <option value="verified">Verified</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="button" class="remove-document-btn w-full py-2.5 border-2 border-rose-200 text-rose-600 rounded-xl text-xs font-bold hover:bg-rose-50 transition flex items-center justify-center gap-1.5">
                    <i class="fas fa-trash-can"></i> Remove
                </button>
            </div>
        </div>
    </div>
</template>

<script>
(function () {

    // ─── Nigerian Validation Rules ───────────────────────────────────────────
    const NIGERIAN_PHONE_REGEX   = /^0[7-9][01]\d{8}$/;
    const BVN_REGEX              = /^\d{11}$/;
    const NIN_REGEX              = /^\d{11}$/;
    const NUBAN_REGEX            = /^\d{10}$/;
    const CAC_REGEX              = /^RC\d{6,7}$/i;
    const TIN_REGEX              = /^\d{10}(\-\d{4})?$/;
    const EMAIL_REGEX            = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const NAME_REGEX             = /^[a-zA-Z\s'\-]{2,100}$/;
    const MAX_FILE_MB            = 2;

    // ─── Helper: show/hide error ─────────────────────────────────────────────
    function showErr(id, msg) {
        const el = document.getElementById(id);
        if (!el) return;
        el.querySelector('span').textContent = msg;
        el.classList.add('show');
    }
    function clearErr(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.querySelector('span').textContent = '';
        el.classList.remove('show');
    }
    function markInput(inputEl, isValid) {
        if (!inputEl) return;
        inputEl.classList.remove('input-error', 'input-success');
        inputEl.classList.add(isValid ? 'input-success' : 'input-error');
    }

    // ─── Date age helper ─────────────────────────────────────────────────────
    function getAge(dob) {
        if (!dob) return null;
        const diff = Date.now() - new Date(dob).getTime();
        return Math.floor(diff / (1000 * 60 * 60 * 24 * 365.25));
    }

    // ─── File size helper ────────────────────────────────────────────────────
    function fileSizeOk(input) {
        if (!input.files || input.files.length === 0) return true;
        return input.files[0].size <= MAX_FILE_MB * 1024 * 1024;
    }

    // ─── Individual field validators ──────────────────────────────────────────
    const validators = {

        first_name: (v) => NAME_REGEX.test(v.trim())
            ? [true]
            : [false, 'Please enter a valid first name (letters only, 2–100 characters)'],

        last_name: (v) => NAME_REGEX.test(v.trim())
            ? [true]
            : [false, 'Please enter a valid last name (letters only, 2–100 characters)'],

        middle_name: (v) => !v || NAME_REGEX.test(v.trim())
            ? [true]
            : [false, 'Middle name should contain letters only'],

        date_of_birth: (v) => {
            if (!v) return [true];
            const age = getAge(v);
            if (age < 18) return [false, 'Customer must be at least 18 years old'];
            if (age > 80) return [false, 'Customer cannot be older than 80 years'];
            return [true];
        },

        email: (v) => !v || EMAIL_REGEX.test(v.trim())
            ? [true]
            : [false, 'Please enter a valid email address (e.g. example@gmail.com)'],

        national_id: (v) => !v || NIN_REGEX.test(v.trim())
            ? [true]
            : [false, 'NIN must be exactly 11 digits with no spaces or letters (e.g. 12345678901)'],

        phone_primary: (v) => {
            if (!v) return [false, 'Primary phone number is required'];
            if (!/^\d+$/.test(v)) return [false, 'Phone number must contain digits only — no spaces, dashes or letters'];
            if (v.length !== 11) return [false, `Phone number must be exactly 11 digits — you entered ${v.length} digit(s)`];
            if (!NIGERIAN_PHONE_REGEX.test(v)) return [false, 'Please enter a valid Nigerian phone number (e.g. 08012345678 or 07012345678)'];
            return [true];
        },

        phone_secondary: (v) => {
            if (!v) return [true];
            if (!/^\d+$/.test(v)) return [false, 'Phone number must contain digits only — no spaces, dashes or letters'];
            if (v.length !== 11) return [false, `Phone number must be exactly 11 digits — you entered ${v.length} digit(s)`];
            if (!NIGERIAN_PHONE_REGEX.test(v)) return [false, 'Please enter a valid Nigerian phone number (e.g. 08012345678 or 07012345678)'];
            return [true];
        },

        occupation: (v) => !v || v.trim().length >= 2
            ? [true]
            : [false, 'Occupation must be at least 2 characters'],

        monthly_income: (v) => {
            if (!v) return [true];
            if (isNaN(v) || Number(v) < 0) return [false, 'Monthly income must be a positive number'];
            return [true];
        },

        business_name: (v, isSme) => {
            if (!isSme) return [true];
            return v && v.trim().length >= 2 ? [true] : [false, 'Business name is required for SME customers'];
        },

        registration_number: (v) => {
            if (!v) return [true];
            if (!CAC_REGEX.test(v.trim())) return [false, 'CAC number format: RC followed by 6–7 digits (e.g. RC1234567)'];
            return [true];
        },

        tax_number: (v) => {
            if (!v) return [true];
            if (!TIN_REGEX.test(v.trim())) return [false, 'TIN must be 10 digits (e.g. 1234567890)'];
            return [true];
        },

        business_monthly_revenue: (v) => {
            if (!v) return [true];
            if (isNaN(v) || Number(v) < 0) return [false, 'Revenue must be a positive number'];
            return [true];
        },

        business_monthly_expense: (v) => {
            if (!v) return [true];
            if (isNaN(v) || Number(v) < 0) return [false, 'Expenses must be a positive number'];
            return [true];
        },

        state: (v) => v && v.trim().length >= 2
            ? [true]
            : [false, 'Please enter the state (e.g. Lagos, Abuja, Rivers)'],

        city: (v) => v && v.trim().length >= 2
            ? [true]
            : [false, 'Please enter the city name'],

        address: (v) => v && v.trim().length >= 10
            ? [true]
            : [false, 'Please enter a full address (minimum 10 characters)'],

        bank_id: (v) => v
            ? [true]
            : [false, 'Please select a bank'],

        account_name: (v) => v && v.trim().length >= 3
            ? [true]
            : [false, 'Account name must be at least 3 characters — as it appears on your bank account'],

        account_number: (v) => {
            if (!v) return [false, 'Account number is required'];
            if (!/^\d+$/.test(v)) return [false, 'Account number must contain digits only — no spaces, dashes or letters'];
            if (v.length !== 10) return [false, `Nigerian NUBAN account number must be exactly 10 digits — you entered ${v.length} digit(s)`];
            return [true];
        },

        // Co-signer
        cosigner_first_name: (v) => NAME_REGEX.test(v.trim())
            ? [true]
            : [false, 'Please enter a valid first name (letters only, 2–100 characters)'],

        cosigner_last_name: (v) => NAME_REGEX.test(v.trim())
            ? [true]
            : [false, 'Please enter a valid last name (letters only, 2–100 characters)'],

        cosigner_middle_name: (v) => !v || NAME_REGEX.test(v.trim())
            ? [true]
            : [false, 'Middle name should contain letters only'],

        cosigner_date_of_birth: (v) => {
            if (!v) return [true];
            const age = getAge(v);
            if (age < 18) return [false, 'Co-signer must be at least 18 years old'];
            if (age > 80) return [false, 'Co-signer cannot be older than 80 years'];
            return [true];
        },

        cosigner_relationship: (v) => v && v.trim().length >= 2
            ? [true]
            : [false, 'Please specify the relationship (e.g. Spouse, Brother, Colleague)'],

        cosigner_bvn: (v) => {
            if (!v) return [false, 'BVN is required for co-signer'];
            if (!/^\d+$/.test(v)) return [false, 'BVN must contain digits only — no spaces, dashes or letters'];
            if (!BVN_REGEX.test(v)) return [false, `BVN must be exactly 11 digits — you entered ${v.length} digit(s)`];
            return [true];
        },

        cosigner_email: (v) => !v || EMAIL_REGEX.test(v.trim())
            ? [true]
            : [false, 'Please enter a valid email address'],

        cosigner_occupation: (v) => v && v.trim().length >= 2
            ? [true]
            : [false, 'Please enter the co-signer\'s occupation'],

        cosigner_phone_primary: (v) => {
            if (!v) return [false, 'Primary phone number is required for co-signer'];
            if (!/^\d+$/.test(v)) return [false, 'Phone number must contain digits only'];
            if (v.length !== 11) return [false, `Phone number must be exactly 11 digits — you entered ${v.length} digit(s)`];
            if (!NIGERIAN_PHONE_REGEX.test(v)) return [false, 'Please enter a valid Nigerian phone number (e.g. 08012345678)'];
            return [true];
        },

        cosigner_phone_secondary: (v) => {
            if (!v) return [true];
            if (!/^\d+$/.test(v)) return [false, 'Phone number must contain digits only'];
            if (v.length !== 11) return [false, `Phone number must be exactly 11 digits — you entered ${v.length} digit(s)`];
            if (!NIGERIAN_PHONE_REGEX.test(v)) return [false, 'Please enter a valid Nigerian phone number'];
            return [true];
        },

        cosigner_state: (v) => v && v.trim().length >= 2
            ? [true]
            : [false, 'Please enter the co-signer\'s state'],

        cosigner_city: (v) => v && v.trim().length >= 2
            ? [true]
            : [false, 'Please enter the co-signer\'s city'],

        cosigner_address: (v) => v && v.trim().length >= 10
            ? [true]
            : [false, 'Please enter a full address (minimum 10 characters)'],

        // Loan
        loan_product_id: (v) => v
            ? [true]
            : [false, 'Please select a loan product'],

        loan_amount: (v, min, max) => {
            if (!v) return [false, 'Loan amount is required'];
            if (isNaN(v) || Number(v) <= 0) return [false, 'Please enter a valid loan amount'];
            if (min && Number(v) < Number(min)) return [false, `Minimum loan amount is ₦${Number(min).toLocaleString()}`];
            if (max && Number(v) > Number(max)) return [false, `Maximum loan amount is ₦${Number(max).toLocaleString()}`];
            return [true];
        },

        duration_months: (v) => {
            if (!v) return [false, 'Loan duration is required'];
            if (!/^\d+$/.test(v)) return [false, 'Duration must be a number (months)'];
            if (Number(v) < 1) return [false, 'Duration must be at least 1 month'];
            if (Number(v) > 360) return [false, 'Duration cannot exceed 360 months'];
            return [true];
        },

        purpose: (v) => v && v.trim().length >= 20
            ? [true]
            : [false, `Loan purpose must be at least 20 characters — you have entered ${v ? v.trim().length : 0}`],
    };

    // ─── Validate single field with live feedback ────────────────────────────
    function validateField(id, errId, validatorFn) {
        const el = document.getElementById(id);
        if (!el) return true;
        const [ok, msg] = validatorFn(el.value);
        markInput(el, ok);
        ok ? clearErr(errId) : showErr(errId, msg);
        return ok;
    }

    // ─── Attach live validation on blur/input ────────────────────────────────
    function attachLive(id, errId, fn) {
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('blur',  () => { const [ok, msg] = fn(el.value); markInput(el, ok); ok ? clearErr(errId) : showErr(errId, msg); });
        el.addEventListener('input', () => {
            if (el.classList.contains('input-error')) {
                const [ok, msg] = fn(el.value);
                markInput(el, ok);
                ok ? clearErr(errId) : showErr(errId, msg);
            }
        });
    }

    // ─── Block non-numeric keypresses for digit-only fields ─────────────────
    function numericOnly(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('keypress', (e) => {
            if (!/\d/.test(e.key) && !['Backspace','Delete','Tab','ArrowLeft','ArrowRight'].includes(e.key)) {
                e.preventDefault();
            }
        });
        el.addEventListener('paste', (e) => {
            const pasted = (e.clipboardData || window.clipboardData).getData('text');
            if (!/^\d+$/.test(pasted)) e.preventDefault();
        });
    }

    // ─── Block alphabets and special chars for numeric fields ────────────────
    ['phone_primary','phone_secondary','national_id','account_number',
     'cosigner_bvn','cosigner_phone_primary','cosigner_phone_secondary'].forEach(numericOnly);

    // ─── Attach live validators ──────────────────────────────────────────────
    attachLive('first_name',               'err-first_name',              validators.first_name);
    attachLive('last_name',                'err-last_name',               validators.last_name);
    attachLive('middle_name',              'err-middle_name',             validators.middle_name);
    attachLive('date_of_birth',            'err-date_of_birth',           validators.date_of_birth);
    attachLive('email',                    'err-email',                   validators.email);
    attachLive('national_id',             'err-national_id',             validators.national_id);
    attachLive('phone_primary',            'err-phone_primary',           validators.phone_primary);
    attachLive('phone_secondary',          'err-phone_secondary',         validators.phone_secondary);
    attachLive('occupation',               'err-occupation',              validators.occupation);
    attachLive('monthly_income',           'err-monthly_income',          validators.monthly_income);
    attachLive('registration_number',      'err-registration_number',     validators.registration_number);
    attachLive('tax_number',               'err-tax_number',              validators.tax_number);
    attachLive('business_monthly_revenue', 'err-business_monthly_revenue',validators.business_monthly_revenue);
    attachLive('business_monthly_expense', 'err-business_monthly_expense',validators.business_monthly_expense);
    attachLive('state',                    'err-state',                   validators.state);
    attachLive('city',                     'err-city',                    validators.city);
    attachLive('address',                  'err-address',                 validators.address);
    attachLive('bank_id',                  'err-bank_id',                 validators.bank_id);
    attachLive('account_name',             'err-account_name',            validators.account_name);
    attachLive('account_number',           'err-account_number',          validators.account_number);
    attachLive('cosigner_first_name',      'err-cosigner_first_name',     validators.cosigner_first_name);
    attachLive('cosigner_last_name',       'err-cosigner_last_name',      validators.cosigner_last_name);
    attachLive('cosigner_middle_name',     'err-cosigner_middle_name',    validators.cosigner_middle_name);
    attachLive('cosigner_date_of_birth',   'err-cosigner_date_of_birth',  validators.cosigner_date_of_birth);
    attachLive('cosigner_relationship',    'err-cosigner_relationship',   validators.cosigner_relationship);
    attachLive('cosigner_bvn',             'err-cosigner_bvn',            validators.cosigner_bvn);
    attachLive('cosigner_email',           'err-cosigner_email',          validators.cosigner_email);
    attachLive('cosigner_occupation',      'err-cosigner_occupation',     validators.cosigner_occupation);
    attachLive('cosigner_phone_primary',   'err-cosigner_phone_primary',  validators.cosigner_phone_primary);
    attachLive('cosigner_phone_secondary', 'err-cosigner_phone_secondary',validators.cosigner_phone_secondary);
    attachLive('cosigner_state',           'err-cosigner_state',          validators.cosigner_state);
    attachLive('cosigner_city',            'err-cosigner_city',           validators.cosigner_city);
    attachLive('cosigner_address',         'err-cosigner_address',        validators.cosigner_address);
    attachLive('purpose',                  'err-purpose',                 validators.purpose);

    // ─── Duration Dropdown Functions ──────────────────────────────────────────
    function filterDurationOptions(productId) {
        const durationSelect = document.getElementById('duration_months');
        const durationHint = document.getElementById('duration-hint');
        if (!durationSelect) return;
        
        const options = durationSelect.querySelectorAll('.duration-option');
        let firstVisible = null;
        let count = 0;
        
        options.forEach(opt => {
            if (opt.dataset.productId == productId) {
                opt.style.display = '';
                if (!firstVisible) firstVisible = opt;
                count++;
            } else {
                opt.style.display = 'none';
            }
        });
        
        // Select first visible option
        if (firstVisible) {
            firstVisible.selected = true;
        }
        
        // Update hint with min/max values from selected product
        const loanProductSelect = document.getElementById('loan_product_id');
        const selectedProduct = loanProductSelect?.options[loanProductSelect.selectedIndex];
        
        if (durationHint) {
            if (selectedProduct && selectedProduct.dataset.minDuration && selectedProduct.dataset.maxDuration) {
                const minDur = selectedProduct.dataset.minDuration;
                const maxDur = selectedProduct.dataset.maxDuration;
                durationHint.textContent = `Select loan duration between ${minDur} and ${maxDur} months`;
            } else {
                durationHint.textContent = 'Select a loan product first';
            }
        }
        
        // Trigger validation
        const [ok, msg] = validators.duration_months(durationSelect.value);
        markInput(durationSelect, ok);
        ok ? clearErr('err-duration_months') : showErr('err-duration_months', msg);
    }

    function updateDurationDropdown() {
        const loanProductSelect = document.getElementById('loan_product_id');
        const selected = loanProductSelect?.options[loanProductSelect.selectedIndex];
        
        if (selected && selected.value) {
            filterDurationOptions(selected.value);
        } else {
            // Hide all options
            const options = document.querySelectorAll('.duration-option');
            options.forEach(opt => opt.style.display = 'none');
        }
    }

    // ─── Step 1 full validate ────────────────────────────────────────────────
    function validateStep1() {
        const isSme = document.getElementById('customer_type')?.value === 'sme';
        let ok = true;

        const checks = [
            ['first_name',    'err-first_name',    (v) => validators.first_name(v)],
            ['last_name',     'err-last_name',     (v) => validators.last_name(v)],
            ['middle_name',   'err-middle_name',   (v) => validators.middle_name(v)],
            ['date_of_birth', 'err-date_of_birth', (v) => validators.date_of_birth(v)],
            ['email',         'err-email',         (v) => validators.email(v)],
            ['national_id',   'err-national_id',   (v) => validators.national_id(v)],
            ['phone_primary', 'err-phone_primary', (v) => validators.phone_primary(v)],
            ['phone_secondary','err-phone_secondary',(v) => validators.phone_secondary(v)],
            ['occupation',    'err-occupation',    (v) => validators.occupation(v)],
            ['monthly_income','err-monthly_income',(v) => validators.monthly_income(v)],
            ['state',         'err-state',         (v) => validators.state(v)],
            ['city',          'err-city',          (v) => validators.city(v)],
            ['address',       'err-address',       (v) => validators.address(v)],
            ['bank_id',       'err-bank_id',       (v) => validators.bank_id(v)],
            ['account_name',  'err-account_name',  (v) => validators.account_name(v)],
            ['account_number','err-account_number',(v) => validators.account_number(v)],
        ];

        if (isSme) {
            checks.push(['business_name','err-business_name',(v) => validators.business_name(v, true)]);
            checks.push(['registration_number','err-registration_number',(v) => validators.registration_number(v)]);
            checks.push(['tax_number','err-tax_number',(v) => validators.tax_number(v)]);
        }

        let firstInvalid = null;
        checks.forEach(([id, errId, fn]) => {
            const el = document.getElementById(id);
            if (!el) return;
            const [valid, msg] = fn(el.value);
            markInput(el, valid);
            valid ? clearErr(errId) : showErr(errId, msg);
            if (!valid) { ok = false; if (!firstInvalid) firstInvalid = el; }
        });

        // Document file size check
        document.querySelectorAll('.doc-file-input').forEach(inp => {
            const errEl = inp.closest('.form-group')?.querySelector('.doc-file-error');
            if (!fileSizeOk(inp)) {
                if (errEl) { errEl.querySelector('span').textContent = `File too large — maximum size is ${MAX_FILE_MB}MB`; errEl.classList.add('show'); }
                markInput(inp, false);
                ok = false;
                if (!firstInvalid) firstInvalid = inp;
            } else {
                if (errEl) errEl.classList.remove('show');
                if (inp.files?.length) markInput(inp, true);
            }
        });

        if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return ok;
    }

    // ─── Step 2 full validate ────────────────────────────────────────────────
    function validateStep2() {
        let ok = true;
        const checks = [
            ['cosigner_first_name',      'err-cosigner_first_name',     (v) => validators.cosigner_first_name(v)],
            ['cosigner_last_name',       'err-cosigner_last_name',      (v) => validators.cosigner_last_name(v)],
            ['cosigner_middle_name',     'err-cosigner_middle_name',    (v) => validators.cosigner_middle_name(v)],
            ['cosigner_date_of_birth',   'err-cosigner_date_of_birth',  (v) => validators.cosigner_date_of_birth(v)],
            ['cosigner_relationship',    'err-cosigner_relationship',   (v) => validators.cosigner_relationship(v)],
            ['cosigner_bvn',             'err-cosigner_bvn',            (v) => validators.cosigner_bvn(v)],
            ['cosigner_email',           'err-cosigner_email',          (v) => validators.cosigner_email(v)],
            ['cosigner_occupation',      'err-cosigner_occupation',     (v) => validators.cosigner_occupation(v)],
            ['cosigner_phone_primary',   'err-cosigner_phone_primary',  (v) => validators.cosigner_phone_primary(v)],
            ['cosigner_phone_secondary', 'err-cosigner_phone_secondary',(v) => validators.cosigner_phone_secondary(v)],
            ['cosigner_state',           'err-cosigner_state',          (v) => validators.cosigner_state(v)],
            ['cosigner_city',            'err-cosigner_city',           (v) => validators.cosigner_city(v)],
            ['cosigner_address',         'err-cosigner_address',        (v) => validators.cosigner_address(v)],
        ];

        // File size
        ['cosigner_photo_id','cosigner_evidence_of_occupation'].forEach((fid, i) => {
            const inp = document.getElementById(fid);
            const errId = i === 0 ? 'err-cosigner_photo_id' : 'err-cosigner_evidence';
            if (inp && !fileSizeOk(inp)) {
                showErr(errId, `File too large — maximum size is ${MAX_FILE_MB}MB`);
                markInput(inp, false);
                ok = false;
            } else if (inp) clearErr(errId);
        });

        let firstInvalid = null;
        checks.forEach(([id, errId, fn]) => {
            const el = document.getElementById(id);
            if (!el) return;
            const [valid, msg] = fn(el.value);
            markInput(el, valid);
            valid ? clearErr(errId) : showErr(errId, msg);
            if (!valid) { ok = false; if (!firstInvalid) firstInvalid = el; }
        });
        if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return ok;
    }

    // ─── Step 3 full validate ────────────────────────────────────────────────
    function validateStep3() {
        let ok = true;
        const loanProductEl = document.getElementById('loan_product_id');
        const selected = loanProductEl?.options[loanProductEl.selectedIndex];
        const minAmt = selected?.dataset.min;
        const maxAmt = selected?.dataset.max;

        const checks = [
            ['loan_product_id','err-loan_product_id',(v) => validators.loan_product_id(v)],
            ['loan_amount',    'err-loan_amount',    (v) => validators.loan_amount(v, minAmt, maxAmt)],
            ['duration_months','err-duration_months',(v) => validators.duration_months(v)],
            ['purpose',        'err-purpose',        (v) => validators.purpose(v)],
        ];

        let firstInvalid = null;
        checks.forEach(([id, errId, fn]) => {
            const el = document.getElementById(id);
            if (!el) return;
            const [valid, msg] = fn(el.value);
            markInput(el, valid);
            valid ? clearErr(errId) : showErr(errId, msg);
            if (!valid) { ok = false; if (!firstInvalid) firstInvalid = el; }
        });
        if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return ok;
    }

    // ─── Form init ───────────────────────────────────────────────────────────
    function initForm() {
        const wrapper   = document.getElementById('documents-wrapper');
        const template  = document.getElementById('document-row-template');
        const addBtn    = document.getElementById('add-document-btn');
        const form      = document.getElementById('customer-form');
        const submitBtn = document.getElementById('submit-btn');
        const submitTxt = document.getElementById('submit-btn-text');

        if (!wrapper || !template || !addBtn || !form) return;

        let docIndex = 0;

        function addDocumentRow() {
            const clone = template.content.cloneNode(true);
            clone.querySelectorAll('[name]').forEach(el => { el.name = el.name.replace('__INDEX__', docIndex); });
            const row = document.createElement('div');
            row.appendChild(clone);
            row.querySelector('.doc-file-input')?.addEventListener('change', function () {
                const errEl = this.closest('.form-group')?.querySelector('.doc-file-error');
                if (!fileSizeOk(this)) {
                    if (errEl) { errEl.querySelector('span').textContent = `File too large — maximum size is ${MAX_FILE_MB}MB`; errEl.classList.add('show'); }
                    markInput(this, false);
                } else {
                    if (errEl) errEl.classList.remove('show');
                    if (this.files?.length) markInput(this, true);
                }
            });
            row.querySelector('.remove-document-btn')?.addEventListener('click', () => row.remove());
            wrapper.appendChild(row);
            docIndex++;
        }

        addBtn.addEventListener('click', addDocumentRow);
        if (wrapper.children.length === 0) addDocumentRow();

        form.addEventListener('submit', () => {
            if (submitBtn) submitBtn.disabled = true;
            if (submitTxt) submitTxt.textContent = 'Processing...';
        });

        // Customer Type toggle
        const typeSelect        = document.getElementById('customer_type');
        const businessSection   = document.getElementById('business-info-section');
        function toggleBusiness() {
            if (typeSelect && businessSection)
                businessSection.style.display = typeSelect.value === 'sme' ? 'block' : 'none';
        }
        typeSelect?.addEventListener('change', toggleBusiness);
        toggleBusiness();

        // Loan product filter + amount hint
        const loanProductSelect = document.getElementById('loan_product_id');
        const loanAmountEl      = document.getElementById('loan_amount');
        const loanHint          = document.getElementById('loan-amount-hint');
        const durationSelect    = document.getElementById('duration_months');

        function filterProducts() {
            if (!typeSelect || !loanProductSelect) return;
            const type = typeSelect.value;
            loanProductSelect.querySelectorAll('option[data-type]').forEach(opt => {
                opt.hidden = opt.dataset.type !== type;
                if (opt.selected && opt.dataset.type !== type) {
                    opt.selected = false;
                }
            });
            updateLimits();
            // Update duration dropdown after product filter
            setTimeout(updateDurationDropdown, 50);
        }

        function updateLimits() {
            const sel = loanProductSelect?.options[loanProductSelect.selectedIndex];
            if (sel?.dataset.min) {
                if (loanAmountEl) { loanAmountEl.min = sel.dataset.min; loanAmountEl.max = sel.dataset.max; }
                if (loanHint) loanHint.textContent = `Min: ₦${Number(sel.dataset.min).toLocaleString()} — Max: ₦${Number(sel.dataset.max).toLocaleString()}`;
            } else {
                if (loanHint) loanHint.textContent = 'Select a loan product first';
            }
        }

        typeSelect?.addEventListener('change', filterProducts);
        loanProductSelect?.addEventListener('change', function() {
            updateLimits();
            updateDurationDropdown();
        });
        
        // Initial load
        filterProducts();

        // Live loan amount validation with min/max from selected product
        loanAmountEl?.addEventListener('blur', () => {
            const sel = loanProductSelect?.options[loanProductSelect.selectedIndex];
            const [ok, msg] = validators.loan_amount(loanAmountEl.value, sel?.dataset.min, sel?.dataset.max);
            markInput(loanAmountEl, ok);
            ok ? clearErr('err-loan_amount') : showErr('err-loan_amount', msg);
        });

        // Step navigation
        const step1 = document.getElementById('step-1');
        const step2 = document.getElementById('step-2');
        const step3 = document.getElementById('step-3');
        const ind1  = document.getElementById('step-indicator-1');
        const ind2  = document.getElementById('step-indicator-2');
        const ind3  = document.getElementById('step-indicator-3');

        function showStep(n) {
            [step1, step2, step3].forEach(s => { if(s) s.style.display = 'none'; });
            [ind1, ind2, ind3].forEach(i => { if(i) { i.classList.remove('step-active','step-completed'); } });
            if (n === 1) { step1.style.display = 'block'; ind1.classList.add('step-active'); }
            else if (n === 2) { step2.style.display = 'block'; ind1.classList.add('step-completed'); ind2.classList.add('step-active'); }
            else if (n === 3) { step3.style.display = 'block'; ind1.classList.add('step-completed'); ind2.classList.add('step-completed'); ind3.classList.add('step-active'); }
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        document.getElementById('next-btn-1')?.addEventListener('click', () => { if (validateStep1()) showStep(2); });
        document.getElementById('back-btn-2')?.addEventListener('click', () => showStep(1));
        document.getElementById('next-btn-2')?.addEventListener('click', () => { if (validateStep2()) showStep(3); });
        document.getElementById('back-btn-3')?.addEventListener('click', () => showStep(2));

        @if ($errors->any())
            const errorKeys  = @json(array_keys($errors->toArray()));
            const step2Fields = ['cosigner_first_name','cosigner_last_name','cosigner_phone_primary','cosigner_bvn','cosigner_relationship','cosigner_occupation','cosigner_state','cosigner_city','cosigner_address'];
            const step3Fields = ['loan_product_id','loan_amount','duration_months','purpose'];
            if (errorKeys.some(k => step3Fields.includes(k))) showStep(3);
            else if (errorKeys.some(k => step2Fields.includes(k) || k.startsWith('cosigner_'))) showStep(2);
            else showStep(1);
        @endif
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initForm);
    else initForm();

})();
</script>
@endsection