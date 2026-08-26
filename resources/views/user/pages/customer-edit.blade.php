@extends('user.layouts.app')
@section('title', 'Update Your Application – African Investment Partners')

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
                Application Update
            </div>
            <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-white">Update Your Application</h1>
            <p class="text-white/60 text-sm mt-1 max-w-2xl">
                Your existing information is pre-filled below. Please update the required fields and resubmit.
            </p>
        </div>
    </div>

    {{-- Notice Banner --}}
    <div class="bg-amber-50 border-2 border-amber-200 rounded-2xl p-4 mb-6 flex items-start gap-3">
        <div class="w-8 h-8 bg-amber-400 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
            <i class="fas fa-exclamation text-white text-sm"></i>
        </div>
        <div>
            <p class="font-bold text-amber-800 text-sm">Action Required</p>
            <p class="text-amber-700 text-sm mt-0.5">
                Our loan officer has requested additional information. Please review your details below and update any required fields before resubmitting. This link expires in 7 days.
            </p>
        </div>
    </div>

    {{-- Success/Error --}}
    <div id="form-success" style="display:none;" class="bg-green-50 border-2 border-green-200 rounded-2xl p-4 mb-6 flex items-start gap-3">
        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fas fa-check text-white text-sm"></i>
        </div>
        <div>
            <p class="font-bold text-green-800 text-sm">Submitted Successfully!</p>
            <p class="text-green-700 text-sm mt-0.5">Your application has been updated. Our team will review it shortly.</p>
        </div>
    </div>

    <div id="form-error" style="display:none;" class="bg-red-50 border-2 border-red-200 rounded-2xl p-4 mb-6">
        <p class="text-red-700 text-sm font-semibold" id="form-error-msg"></p>
    </div>

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
    @php
        $bank        = $customer->bankAccounts->first();
        $business    = $customer->businesses->first();
        $loanApp     = $customer->loanApplications->first();
        $coSigner    = null;
        if ($loanApp) {
            $coSigner = \App\Models\CoSigner::where('application_id', $loanApp->id)->first();
        }
    @endphp

    <div class="form-container p-6 sm:p-8">
        <form method="POST" action="{{ route('customer.update.token', $token) }}" enctype="multipart/form-data" id="customer-edit-form" novalidate>
            @csrf
            @method('POST')

            {{-- STEP 1 --}}
            <div id="step-1">
                <div class="mb-8">
                    <div class="form-section-title">
                        <div class="icon-wrapper"><i class="fas fa-user"></i></div>
                        Personal Information
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="form-group">
                            <label class="form-label-premium">Customer Type</label>
                            <input type="text" class="form-input-premium bg-gray-100" value="{{ ucfirst($customer->customer_type) }}" disabled>
                            <input type="hidden" name="customer_type" value="{{ $customer->customer_type }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">First Name <span class="text-red-500">*</span></label>
                            <input type="text" name="first_name" id="first_name" required class="form-input-premium" value="{{ old('first_name', $customer->first_name) }}" maxlength="100">
                            <p class="field-error-msg" id="err-first_name"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" name="last_name" id="last_name" required class="form-input-premium" value="{{ old('last_name', $customer->last_name) }}" maxlength="100">
                            <p class="field-error-msg" id="err-last_name"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">Middle Name</label>
                            <input type="text" name="middle_name" id="middle_name" class="form-input-premium" value="{{ old('middle_name', $customer->middle_name) }}" maxlength="100">
                            <p class="field-error-msg" id="err-middle_name"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" class="form-input-premium" value="{{ old('date_of_birth', $customer->date_of_birth ? \Carbon\Carbon::parse($customer->date_of_birth)->format('Y-m-d') : '') }}">
                            <p class="field-hint">Must be 18–80 years old</p>
                            <p class="field-error-msg" id="err-date_of_birth"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Gender</label>
                            <select name="gender" id="gender" class="form-input-premium">
                                <option value="">Select gender</option>
                                <option value="male"   {{ old('gender', $customer->gender) == 'male'   ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $customer->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other"  {{ old('gender', $customer->gender) == 'other'  ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Contact --}}
                <div class="mb-8">
                    <div class="form-section-title">
                        <div class="icon-wrapper"><i class="fas fa-address-book"></i></div>
                        Contact Details
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label-premium">Email Address</label>
                            <input type="email" name="email" id="email" class="form-input-premium input-success"
                                   value="{{ old('email', $customer->email) }}" readonly>
                            <p class="field-hint" style="color:#6DBE3B;"><i class="fas fa-lock text-xs"></i> Email cannot be changed</p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">National ID (NIN)</label>
                            <input type="text" name="national_id" id="national_id" class="form-input-premium"
                                   value="{{ old('national_id', $customer->national_id) }}" maxlength="11" inputmode="numeric">
                            <p class="field-hint">NIN is exactly 11 digits</p>
                            <p class="field-error-msg" id="err-national_id"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">Primary Phone <span class="text-red-500">*</span></label>
                            <input type="text" name="phone_primary" id="phone_primary" required class="form-input-premium"
                                   value="{{ old('phone_primary', $customer->phone_primary) }}" maxlength="11" inputmode="numeric">
                            <p class="field-hint">11 digits starting with 0</p>
                            <p class="field-error-msg" id="err-phone_primary"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Secondary Phone</label>
                            <input type="text" name="phone_secondary" id="phone_secondary" class="form-input-premium"
                                   value="{{ old('phone_secondary', $customer->phone_secondary) }}" maxlength="11" inputmode="numeric">
                            <p class="field-hint">11 digits starting with 0 (optional)</p>
                            <p class="field-error-msg" id="err-phone_secondary"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                </div>

                {{-- Professional --}}
                <div class="mb-8">
                    <div class="form-section-title">
                        <div class="icon-wrapper"><i class="fas fa-briefcase"></i></div>
                        Professional Information
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label-premium">Occupation</label>
                            <input type="text" name="occupation" id="occupation" class="form-input-premium"
                                   value="{{ old('occupation', $customer->occupation) }}" maxlength="150">
                            <p class="field-error-msg" id="err-occupation"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Monthly Income (₦)</label>
                            <input type="number" name="monthly_income" id="monthly_income" class="form-input-premium"
                                   value="{{ old('monthly_income', $customer->monthly_income) }}" min="0" inputmode="numeric">
                            <p class="field-hint">Numbers only, in Naira</p>
                            <p class="field-error-msg" id="err-monthly_income"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                </div>

                {{-- Business (SME) --}}
                @if($customer->customer_type === 'sme')
                <div class="mb-8 p-5 bg-slate-50 rounded-2xl border-2 border-slate-200">
                    <div class="form-section-title !border-slate-300">
                        <div class="icon-wrapper !bg-slate-700"><i class="fas fa-building"></i></div>
                        Business Information
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label-premium">Business Name <span class="text-red-500">*</span></label>
                            <input type="text" name="business_name" id="business_name" class="form-input-premium"
                                   value="{{ old('business_name', $business->business_name ?? '') }}" maxlength="200">
                            <p class="field-error-msg" id="err-business_name"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Business Type</label>
                            <input type="text" name="business_type" class="form-input-premium"
                                   value="{{ old('business_type', $business->business_type ?? '') }}" maxlength="100">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">CAC Registration Number</label>
                            <input type="text" name="registration_number" id="registration_number" class="form-input-premium"
                                   value="{{ old('registration_number', $business->registration_number ?? '') }}" maxlength="20">
                            <p class="field-hint">Format: RC followed by 6–7 digits (e.g. RC1234567)</p>
                            <p class="field-error-msg" id="err-registration_number"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Tax ID (TIN)</label>
                            <input type="text" name="tax_number" id="tax_number" class="form-input-premium"
                                   value="{{ old('tax_number', $business->tax_number ?? '') }}" maxlength="14" inputmode="numeric">
                            <p class="field-hint">10 digits</p>
                            <p class="field-error-msg" id="err-tax_number"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">Monthly Revenue (₦)</label>
                            <input type="number" name="business_monthly_revenue" class="form-input-premium"
                                   value="{{ old('business_monthly_revenue', $business->monthly_revenue ?? '') }}" min="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Monthly Expenses (₦)</label>
                            <input type="number" name="business_monthly_expense" class="form-input-premium"
                                   value="{{ old('business_monthly_expense', $business->monthly_expense ?? '') }}" min="0">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                        <div>
                            <label class="form-label-premium">State</label>
                            <input type="text" name="business_state" class="form-input-premium"
                                   value="{{ old('business_state', $business->state ?? '') }}">
                        </div>
                        <div>
                            <label class="form-label-premium">City</label>
                            <input type="text" name="business_city" class="form-input-premium"
                                   value="{{ old('business_city', $business->city ?? '') }}">
                        </div>
                        <div>
                            <label class="form-label-premium">LGA</label>
                            <input type="text" name="business_local_government_area" class="form-input-premium"
                                   value="{{ old('business_local_government_area', $business->local_government_area ?? '') }}">
                        </div>
                    </div>
                    <div class="form-group mt-4">
                        <label class="form-label-premium">Business Address</label>
                        <textarea name="business_address" rows="2" class="form-input-premium resize-none">{{ old('business_address', $business->address ?? '') }}</textarea>
                    </div>
                </div>
                @endif

                {{-- Address --}}
                <div class="mb-8">
                    <div class="form-section-title">
                        <div class="icon-wrapper"><i class="fas fa-map-pin"></i></div>
                        Residential Address
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="form-group">
                            <label class="form-label-premium">Country</label>
                            <input type="text" name="country" class="form-input-premium"
                                   value="{{ old('country', $customer->country ?? 'Nigeria') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">State <span class="text-red-500">*</span></label>
                            <input type="text" name="state" id="state" required class="form-input-premium"
                                   value="{{ old('state', $customer->state) }}">
                            <p class="field-error-msg" id="err-state"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">City <span class="text-red-500">*</span></label>
                            <input type="text" name="city" id="city" required class="form-input-premium"
                                   value="{{ old('city', $customer->city) }}">
                            <p class="field-error-msg" id="err-city"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">LGA</label>
                            <input type="text" name="local_government_area" class="form-input-premium"
                                   value="{{ old('local_government_area', $customer->local_government_area) }}">
                        </div>
                        <div class="form-group sm:col-span-3">
                            <label class="form-label-premium">Full Address <span class="text-red-500">*</span></label>
                            <textarea name="address" id="address" required rows="2" class="form-input-premium resize-none">{{ old('address', $customer->address) }}</textarea>
                            <p class="field-error-msg" id="err-address"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                </div>

                {{-- Documents --}}
                <div class="mb-8">
                    <div class="form-section-title">
                        <div class="icon-wrapper"><i class="fas fa-folder-open"></i></div>
                        Documents
                    </div>

                    {{-- Existing Documents --}}
                    @if($customer->documents->count() > 0)
                    <div class="mb-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Existing Documents</p>
                        <div class="space-y-2">
                            @foreach($customer->documents as $doc)
                            <div class="flex items-center justify-between bg-gray-50 rounded-lg border border-gray-200 p-3">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-file-alt text-orange-500"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}</p>
                                        <p class="text-xs text-gray-400">{{ $doc->file_path }}</p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    {{ $doc->verification_status === 'verified' ? 'bg-green-100 text-green-700' : ($doc->verification_status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                    {{ ucfirst($doc->verification_status) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Add New Documents</p>
                    <div id="documents-wrapper" class="space-y-4"></div>
                    <button type="button" id="add-document-btn" class="btn-outline-premium mt-3 !text-xs">
                        <i class="fas fa-plus"></i> Add Document
                    </button>
                </div>

                {{-- Bank Details --}}
                <div class="mb-8">
                    <div class="form-section-title">
                        <div class="icon-wrapper"><i class="fas fa-university"></i></div>
                        Bank Details
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="form-group">
                            <label class="form-label-premium">Bank <span class="text-red-500">*</span></label>
                            <select name="bank_id" id="bank_id" required class="form-input-premium">
                                <option value="">Select bank</option>
                                @foreach ($banks as $b)
                                    <option value="{{ $b->id }}" {{ old('bank_id', $bank->bank_id ?? '') == $b->id ? 'selected' : '' }}>
                                        {{ $b->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="field-error-msg" id="err-bank_id"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Account Name <span class="text-red-500">*</span></label>
                            <input type="text" name="account_name" id="account_name" required class="form-input-premium"
                                   value="{{ old('account_name', $bank->account_name ?? '') }}" maxlength="200">
                            <p class="field-error-msg" id="err-account_name"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Account Number <span class="text-red-500">*</span></label>
                            <input type="text" name="account_number" id="account_number" required class="form-input-premium"
                                   value="{{ old('account_number', $bank->account_number ?? '') }}" maxlength="10" inputmode="numeric">
                            <p class="field-hint">NUBAN — exactly 10 digits</p>
                            <p class="field-error-msg" id="err-account_number"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t-2 border-slate-100">
                    <button type="button" id="next-btn-1" class="btn-primary-premium">
                        Continue to Co-Signer <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            {{-- STEP 2 — CO-SIGNER --}}
            <div id="step-2" style="display:none;">
                <div class="mb-8">
                    <div class="form-section-title">
                        <div class="icon-wrapper"><i class="fas fa-user-shield"></i></div>
                        Co-Signer / Guarantor
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="form-group">
                            <label class="form-label-premium">First Name <span class="text-red-500">*</span></label>
                            <input type="text" name="cosigner_first_name" id="cosigner_first_name" required class="form-input-premium"
                                   value="{{ old('cosigner_first_name', $coSigner->first_name ?? '') }}" maxlength="100">
                            <p class="field-error-msg" id="err-cosigner_first_name"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" name="cosigner_last_name" id="cosigner_last_name" required class="form-input-premium"
                                   value="{{ old('cosigner_last_name', $coSigner->last_name ?? '') }}" maxlength="100">
                            <p class="field-error-msg" id="err-cosigner_last_name"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Middle Name</label>
                            <input type="text" name="cosigner_middle_name" id="cosigner_middle_name" class="form-input-premium"
                                   value="{{ old('cosigner_middle_name', $coSigner->middle_name ?? '') }}" maxlength="100">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">Date of Birth</label>
                            <input type="date" name="cosigner_date_of_birth" id="cosigner_date_of_birth" class="form-input-premium"
                                   value="{{ old('cosigner_date_of_birth', $coSigner && $coSigner->date_of_birth ? \Carbon\Carbon::parse($coSigner->date_of_birth)->format('Y-m-d') : '') }}">
                            <p class="field-hint">Must be 18–80 years old</p>
                            <p class="field-error-msg" id="err-cosigner_date_of_birth"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Relationship <span class="text-red-500">*</span></label>
                            <input type="text" name="cosigner_relationship" id="cosigner_relationship" required class="form-input-premium"
                                   value="{{ old('cosigner_relationship', $coSigner->relationship ?? '') }}" maxlength="100">
                            <p class="field-error-msg" id="err-cosigner_relationship"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">BVN <span class="text-red-500">*</span></label>
                            <input type="text" name="cosigner_bvn" id="cosigner_bvn" required class="form-input-premium"
                                   value="{{ old('cosigner_bvn', $coSigner->bvn ?? '') }}" maxlength="11" inputmode="numeric">
                            <p class="field-hint">Exactly 11 digits</p>
                            <p class="field-error-msg" id="err-cosigner_bvn"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">Email</label>
                            <input type="email" name="cosigner_email" id="cosigner_email" class="form-input-premium"
                                   value="{{ old('cosigner_email', $coSigner->email ?? '') }}">
                            <p class="field-error-msg" id="err-cosigner_email"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Occupation <span class="text-red-500">*</span></label>
                            <input type="text" name="cosigner_occupation" id="cosigner_occupation" required class="form-input-premium"
                                   value="{{ old('cosigner_occupation', $coSigner->occupation ?? '') }}" maxlength="150">
                            <p class="field-error-msg" id="err-cosigner_occupation"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">Primary Phone <span class="text-red-500">*</span></label>
                            <input type="text" name="cosigner_phone_primary" id="cosigner_phone_primary" required class="form-input-premium"
                                   value="{{ old('cosigner_phone_primary', $coSigner->phone_primary ?? '') }}" maxlength="11" inputmode="numeric">
                            <p class="field-hint">11 digits starting with 0</p>
                            <p class="field-error-msg" id="err-cosigner_phone_primary"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Secondary Phone</label>
                            <input type="text" name="cosigner_phone_secondary" id="cosigner_phone_secondary" class="form-input-premium"
                                   value="{{ old('cosigner_phone_secondary', $coSigner->phone_secondary ?? '') }}" maxlength="11" inputmode="numeric">
                            <p class="field-hint">Optional</p>
                            <p class="field-error-msg" id="err-cosigner_phone_secondary"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">Country</label>
                            <input type="text" name="cosigner_country" class="form-input-premium"
                                   value="{{ old('cosigner_country', $coSigner->country ?? 'Nigeria') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">State <span class="text-red-500">*</span></label>
                            <input type="text" name="cosigner_state" id="cosigner_state" required class="form-input-premium"
                                   value="{{ old('cosigner_state', $coSigner->state ?? '') }}">
                            <p class="field-error-msg" id="err-cosigner_state"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">City <span class="text-red-500">*</span></label>
                            <input type="text" name="cosigner_city" id="cosigner_city" required class="form-input-premium"
                                   value="{{ old('cosigner_city', $coSigner->city ?? '') }}">
                            <p class="field-error-msg" id="err-cosigner_city"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                    <div class="form-group mt-4">
                        <label class="form-label-premium">Address <span class="text-red-500">*</span></label>
                        <textarea name="cosigner_address" id="cosigner_address" required rows="2" class="form-input-premium resize-none">{{ old('cosigner_address', $coSigner->address ?? '') }}</textarea>
                        <p class="field-error-msg" id="err-cosigner_address"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                    </div>
                     <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <!-- <div class="form-group">
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
                        </div> -->
                        {{-- Co-signer Photo ID --}}
<div class="form-group mt-4">
    <label class="form-label-premium">Photo ID</label>

    <div class="grid grid-cols-2 gap-2 mb-2">
        <button type="button"
                onclick="triggerFileSelect('cosigner_photo_id_file', 'cosigner_photo_id_camera')"
                class="flex flex-col items-center justify-center gap-1.5 py-3 border-2 border-dashed border-gray-200 rounded-xl text-xs font-semibold text-gray-600 hover:border-[#6DBE3B] hover:text-[#6DBE3B] hover:bg-green-50 transition">
            <i class="fas fa-folder-open text-lg"></i>
            <span>Choose File</span>
            <span class="text-[10px] font-normal text-gray-400">PDF, JPG, PNG</span>
        </button>
        <button type="button"
                onclick="triggerCameraSelect('cosigner_photo_id_camera', 'cosigner_photo_id_file')"
                class="flex flex-col items-center justify-center gap-1.5 py-3 border-2 border-dashed border-gray-200 rounded-xl text-xs font-semibold text-gray-600 hover:border-[#6DBE3B] hover:text-[#6DBE3B] hover:bg-green-50 transition">
            <i class="fas fa-camera text-lg"></i>
            <span>Take Photo</span>
            <span class="text-[10px] font-normal text-gray-400">Use Camera</span>
        </button>
    </div>

    <input type="file" id="cosigner_photo_id_file" name="cosigner_photo_id"
           class="hidden" accept=".jpg,.jpeg,.png,.pdf"
           onchange="showSinglePreview(this, 'cosigner_photo_id_preview')">
    <input type="file" id="cosigner_photo_id_camera"
           class="hidden" accept="image/*" capture="environment"
           onchange="showSinglePreview(this, 'cosigner_photo_id_preview'); document.getElementById('cosigner_photo_id_file').removeAttribute('name'); this.name='cosigner_photo_id';">

    <div id="cosigner_photo_id_preview" class="hidden mt-2 flex items-center gap-3 bg-green-50 border border-green-200 rounded-xl px-3 py-2">
        <i class="fas fa-file-check text-[#6DBE3B]"></i>
        <div class="flex-1 min-w-0">
            <p class="text-xs font-semibold text-gray-700 preview-name truncate"></p>
            <p class="text-[10px] text-gray-400 preview-size"></p>
        </div>
        <button type="button" onclick="clearSinglePreview('cosigner_photo_id_preview','cosigner_photo_id_file','cosigner_photo_id_camera','cosigner_photo_id')"
                class="text-red-400 hover:text-red-600 text-xs">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <p class="field-error-msg" id="err-cosigner_photo_id"><i class="fas fa-circle-exclamation"></i> <span></span></p>
</div>

{{-- Co-signer Evidence of Occupation --}}
<div class="form-group mt-4">
    <label class="form-label-premium">Evidence of Occupation</label>

    <div class="grid grid-cols-2 gap-2 mb-2">
        <button type="button"
                onclick="triggerFileSelect('cosigner_evidence_file', 'cosigner_evidence_camera')"
                class="flex flex-col items-center justify-center gap-1.5 py-3 border-2 border-dashed border-gray-200 rounded-xl text-xs font-semibold text-gray-600 hover:border-[#6DBE3B] hover:text-[#6DBE3B] hover:bg-green-50 transition">
            <i class="fas fa-folder-open text-lg"></i>
            <span>Choose File</span>
            <span class="text-[10px] font-normal text-gray-400">PDF, JPG, PNG</span>
        </button>
        <button type="button"
                onclick="triggerCameraSelect('cosigner_evidence_camera', 'cosigner_evidence_file')"
                class="flex flex-col items-center justify-center gap-1.5 py-3 border-2 border-dashed border-gray-200 rounded-xl text-xs font-semibold text-gray-600 hover:border-[#6DBE3B] hover:text-[#6DBE3B] hover:bg-green-50 transition">
            <i class="fas fa-camera text-lg"></i>
            <span>Take Photo</span>
            <span class="text-[10px] font-normal text-gray-400">Use Camera</span>
        </button>
    </div>

    <input type="file" id="cosigner_evidence_file" name="cosigner_evidence_of_occupation"
           class="hidden" accept=".jpg,.jpeg,.png,.pdf"
           onchange="showSinglePreview(this, 'cosigner_evidence_preview')">
    <input type="file" id="cosigner_evidence_camera"
           class="hidden" accept="image/*" capture="environment"
           onchange="showSinglePreview(this, 'cosigner_evidence_preview'); document.getElementById('cosigner_evidence_file').removeAttribute('name'); this.name='cosigner_evidence_of_occupation';">

    <div id="cosigner_evidence_preview" class="hidden mt-2 flex items-center gap-3 bg-green-50 border border-green-200 rounded-xl px-3 py-2">
        <i class="fas fa-file-check text-[#6DBE3B]"></i>
        <div class="flex-1 min-w-0">
            <p class="text-xs font-semibold text-gray-700 preview-name truncate"></p>
            <p class="text-[10px] text-gray-400 preview-size"></p>
        </div>
        <button type="button"
                onclick="clearSinglePreview('cosigner_evidence_preview','cosigner_evidence_file','cosigner_evidence_camera','cosigner_evidence_of_occupation')"
                class="text-red-400 hover:text-red-600 text-xs">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <p class="field-error-msg" id="err-cosigner_evidence"><i class="fas fa-circle-exclamation"></i> <span></span></p>
</div>
                    </div>
                </div>

                <div class="flex justify-between gap-3 pt-6 border-t-2 border-slate-100">
                    <button type="button" id="back-btn-2" class="btn-secondary-premium">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <button type="button" id="next-btn-2" class="btn-primary-premium">
                        Continue to Loan <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            {{-- STEP 3 — LOAN --}}
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
                                    <option value="{{ $product->id }}"
                                        data-min="{{ $product->minimum_amount }}"
                                        data-max="{{ $product->maximum_amount }}"
                                        data-duration="{{ $product->duration_months }}"
                                        {{ old('loan_product_id', $loanApp->loan_product_id ?? '') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} (₦{{ number_format($product->minimum_amount) }} – ₦{{ number_format($product->maximum_amount) }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="field-error-msg" id="err-loan_product_id"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Loan Amount (₦) <span class="text-red-500">*</span></label>
                            <input type="number" name="loan_amount" id="loan_amount" required class="form-input-premium"
                                   value="{{ old('loan_amount', $loanApp->loan_amount ?? '') }}" min="0" inputmode="numeric">
                            <p class="field-hint" id="loan-amount-hint">Select a loan product first</p>
                            <p class="field-error-msg" id="err-loan_amount"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">Duration (Months) <span class="text-red-500">*</span></label>
                            <input type="number" name="duration_months" id="duration_months" required class="form-input-premium"
                                   value="{{ old('duration_months', $loanApp->duration_months ?? '') }}" min="1" max="360" inputmode="numeric">
                            <p class="field-hint">Between 1 and 360 months</p>
                            <p class="field-error-msg" id="err-duration_months"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                        </div>
                    </div>
                    <div class="form-group mt-4">
                        <label class="form-label-premium">Loan Purpose <span class="text-red-500">*</span></label>
                        <textarea name="purpose" id="purpose" required rows="3" class="form-input-premium resize-none">{{ old('purpose', $loanApp->purpose ?? '') }}</textarea>
                        <p class="field-hint">Minimum 20 characters</p>
                        <p class="field-error-msg" id="err-purpose"><i class="fas fa-circle-exclamation"></i> <span></span></p>
                    </div>
                </div>

                <div class="flex justify-between gap-3 pt-6 border-t-2 border-slate-100">
                    <button type="button" id="back-btn-3" class="btn-secondary-premium">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <button type="submit" id="submit-btn" class="btn-primary-premium">
                        <span id="submit-btn-text">Update & Resubmit Application</span>
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

{{-- Document Template --}}
<template id="document-row-template">
    <div class="document-row bg-white border-2 border-gray-100 rounded-2xl p-4 mb-3">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-bold text-gray-700">
                <i class="fas fa-file-alt text-orange-500 mr-1"></i> New Document
            </p>
            <button type="button" class="remove-document-btn text-red-400 hover:text-red-600 transition flex items-center gap-1 text-xs font-semibold">
                <i class="fas fa-trash-can"></i> Remove
            </button>
        </div>

        <div class="form-group mb-3">
            <label class="form-label-premium !text-[10px]">Document Type</label>
            <select name="documents[__INDEX__][document_type]" class="form-input-premium !py-2">
                <option value="national_id">National ID / NIN</option>
                <option value="passport">International Passport</option>
                <option value="driver_license">Driver's License</option>
                <option value="other">Other Document</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Upload Document</label>

            <div class="grid grid-cols-2 gap-2 mb-3">
                <button type="button"
                        class="upload-option-btn select-file-btn flex flex-col items-center justify-center gap-1.5 py-3 px-3 border-2 border-dashed border-gray-200 rounded-xl text-xs font-semibold text-gray-600 hover:border-[#6DBE3B] hover:text-[#6DBE3B] hover:bg-green-50 transition cursor-pointer">
                    <i class="fas fa-folder-open text-lg"></i>
                    <span>Choose File</span>
                    <span class="text-[10px] font-normal text-gray-400">PDF, JPG, PNG</span>
                </button>
                <button type="button"
                        class="upload-option-btn take-photo-btn flex flex-col items-center justify-center gap-1.5 py-3 px-3 border-2 border-dashed border-gray-200 rounded-xl text-xs font-semibold text-gray-600 hover:border-[#6DBE3B] hover:text-[#6DBE3B] hover:bg-green-50 transition cursor-pointer">
                    <i class="fas fa-camera text-lg"></i>
                    <span>Take Photo</span>
                    <span class="text-[10px] font-normal text-gray-400">Use Camera</span>
                </button>
            </div>

            <input type="file" name="documents[__INDEX__][file]"
                   class="doc-file-input hidden"
                   accept=".jpg,.jpeg,.png,.pdf">
            <input type="file"
                   class="doc-camera-input hidden"
                   accept="image/*" capture="environment">

            <div class="doc-preview hidden mt-2 flex items-center gap-3 bg-green-50 border border-green-200 rounded-xl px-3 py-2">
                <i class="fas fa-file-check text-[#6DBE3B]"></i>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-gray-700 doc-preview-name truncate"></p>
                    <p class="text-[10px] text-gray-400 doc-preview-size"></p>
                </div>
                <button type="button" class="doc-clear-btn text-red-400 hover:text-red-600 text-xs">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <p class="field-error-msg doc-file-error hidden mt-1 text-xs text-red-500 flex items-center gap-1">
                <i class="fas fa-circle-exclamation"></i> <span></span>
            </p>
        </div>
    </div>
</template>
<script>
(function () {
    // Same validation JS as customer-add — copy karo
    // Sirf form submit part alag hoga

    const NIGERIAN_PHONE_REGEX = /^0[7-9][01]\d{8}$/;
    const BVN_REGEX            = /^\d{11}$/;
    const NIN_REGEX            = /^\d{11}$/;
    const NUBAN_REGEX          = /^\d{10}$/;
    const CAC_REGEX            = /^RC\d{6,7}$/i;
    const TIN_REGEX            = /^\d{10}(\-\d{4})?$/;
    const EMAIL_REGEX          = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const NAME_REGEX           = /^[a-zA-Z\s'\-]{2,100}$/;
    const MAX_FILE_MB          = 2;

    function showErr(id, msg) { const el = document.getElementById(id); if (!el) return; el.querySelector('span').textContent = msg; el.classList.add('show'); }
    function clearErr(id) { const el = document.getElementById(id); if (!el) return; el.querySelector('span').textContent = ''; el.classList.remove('show'); }
    function markInput(el, ok) { if (!el) return; el.classList.remove('input-error','input-success'); el.classList.add(ok ? 'input-success' : 'input-error'); }
    function getAge(dob) { if (!dob) return null; return Math.floor((Date.now() - new Date(dob).getTime()) / (1000*60*60*24*365.25)); }
    function fileSizeOk(input) { if (!input.files || !input.files.length) return true; return input.files[0].size <= MAX_FILE_MB * 1024 * 1024; }

    function numericOnly(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('keypress', e => { if (!/\d/.test(e.key) && !['Backspace','Delete','Tab','ArrowLeft','ArrowRight'].includes(e.key)) e.preventDefault(); });
        el.addEventListener('paste', e => { if (!/^\d+$/.test((e.clipboardData||window.clipboardData).getData('text'))) e.preventDefault(); });
    }
    ['phone_primary','phone_secondary','national_id','account_number','cosigner_bvn','cosigner_phone_primary','cosigner_phone_secondary'].forEach(numericOnly);

    // ── Validators (same as customer-add) ──
    const validators = {
        first_name:             v => NAME_REGEX.test(v.trim()) ? [true] : [false, 'Valid first name required (letters only, 2–100 chars)'],
        last_name:              v => NAME_REGEX.test(v.trim()) ? [true] : [false, 'Valid last name required (letters only, 2–100 chars)'],
        middle_name:            v => !v || NAME_REGEX.test(v.trim()) ? [true] : [false, 'Letters only'],
        date_of_birth:          v => { if (!v) return [true]; const a = getAge(v); if (a < 18) return [false,'Must be at least 18 years old']; if (a > 80) return [false,'Cannot be older than 80 years']; return [true]; },
        national_id:            v => !v || NIN_REGEX.test(v.trim()) ? [true] : [false,'NIN must be exactly 11 digits'],
        phone_primary:          v => { if (!v) return [false,'Required']; if (!/^\d+$/.test(v)) return [false,'Digits only']; if (v.length!==11) return [false,`Must be 11 digits — you entered ${v.length}`]; if (!NIGERIAN_PHONE_REGEX.test(v)) return [false,'Valid Nigerian number required (e.g. 08012345678)']; return [true]; },
        phone_secondary:        v => { if (!v) return [true]; if (!/^\d+$/.test(v)) return [false,'Digits only']; if (v.length!==11) return [false,`Must be 11 digits — you entered ${v.length}`]; if (!NIGERIAN_PHONE_REGEX.test(v)) return [false,'Valid Nigerian number required']; return [true]; },
        state:                  v => v && v.trim().length >= 2 ? [true] : [false,'State is required'],
        city:                   v => v && v.trim().length >= 2 ? [true] : [false,'City is required'],
        address:                v => v && v.trim().length >= 10 ? [true] : [false,'Full address required (min 10 chars)'],
        bank_id:                v => v ? [true] : [false,'Please select a bank'],
        account_name:           v => v && v.trim().length >= 3 ? [true] : [false,'Account name required (min 3 chars)'],
        account_number:         v => { if (!v) return [false,'Required']; if (!/^\d+$/.test(v)) return [false,'Digits only, no dashes']; if (v.length!==10) return [false,`NUBAN must be 10 digits — you entered ${v.length}`]; return [true]; },
        registration_number:    v => !v || CAC_REGEX.test(v.trim()) ? [true] : [false,'Format: RC + 6–7 digits (e.g. RC1234567)'],
        tax_number:             v => !v || TIN_REGEX.test(v.trim()) ? [true] : [false,'TIN must be 10 digits'],
        cosigner_first_name:    v => NAME_REGEX.test(v.trim()) ? [true] : [false,'Valid first name required'],
        cosigner_last_name:     v => NAME_REGEX.test(v.trim()) ? [true] : [false,'Valid last name required'],
        cosigner_middle_name:   v => !v || NAME_REGEX.test(v.trim()) ? [true] : [false,'Letters only'],
        cosigner_date_of_birth: v => { if (!v) return [true]; const a = getAge(v); if (a < 18) return [false,'Must be at least 18']; if (a > 80) return [false,'Cannot be older than 80']; return [true]; },
        cosigner_relationship:  v => v && v.trim().length >= 2 ? [true] : [false,'Relationship required'],
        cosigner_bvn:           v => { if (!v) return [false,'BVN required']; if (!/^\d+$/.test(v)) return [false,'Digits only']; if (!BVN_REGEX.test(v)) return [false,`BVN must be 11 digits — you entered ${v.length}`]; return [true]; },
        cosigner_email:         v => !v || EMAIL_REGEX.test(v.trim()) ? [true] : [false,'Valid email required'],
        cosigner_occupation:    v => v && v.trim().length >= 2 ? [true] : [false,'Occupation required'],
        cosigner_phone_primary: v => { if (!v) return [false,'Required']; if (!/^\d+$/.test(v)) return [false,'Digits only']; if (v.length!==11) return [false,`Must be 11 digits — you entered ${v.length}`]; if (!NIGERIAN_PHONE_REGEX.test(v)) return [false,'Valid Nigerian number required']; return [true]; },
        cosigner_phone_secondary: v => { if (!v) return [true]; if (!/^\d+$/.test(v)) return [false,'Digits only']; if (v.length!==11) return [false,`Must be 11 digits`]; if (!NIGERIAN_PHONE_REGEX.test(v)) return [false,'Valid Nigerian number required']; return [true]; },
        cosigner_state:         v => v && v.trim().length >= 2 ? [true] : [false,'State required'],
        cosigner_city:          v => v && v.trim().length >= 2 ? [true] : [false,'City required'],
        cosigner_address:       v => v && v.trim().length >= 10 ? [true] : [false,'Full address required (min 10 chars)'],
        loan_product_id:        v => v ? [true] : [false,'Please select a loan product'],
        loan_amount:            (v, min, max) => { if (!v) return [false,'Required']; if (isNaN(v)||Number(v)<=0) return [false,'Valid amount required']; if (min&&Number(v)<Number(min)) return [false,`Min: ₦${Number(min).toLocaleString()}`]; if (max&&Number(v)>Number(max)) return [false,`Max: ₦${Number(max).toLocaleString()}`]; return [true]; },
        duration_months:        v => { if (!v) return [false,'Required']; if (!/^\d+$/.test(v)) return [false,'Numbers only']; if (Number(v)<1) return [false,'Min 1 month']; if (Number(v)>360) return [false,'Max 360 months']; return [true]; },
        purpose:                v => v && v.trim().length >= 20 ? [true] : [false,`Min 20 chars — you have ${v?v.trim().length:0}`],
    };

    function attachLive(id, errId, fn) {
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('blur', () => { const [ok,msg] = fn(el.value); markInput(el,ok); ok ? clearErr(errId) : showErr(errId,msg); });
        el.addEventListener('input', () => { if (el.classList.contains('input-error')) { const [ok,msg] = fn(el.value); markInput(el,ok); ok ? clearErr(errId) : showErr(errId,msg); } });
    }

    // Attach all
    [
        ['first_name','err-first_name',validators.first_name],
        ['last_name','err-last_name',validators.last_name],
        ['middle_name','err-middle_name',validators.middle_name],
        ['date_of_birth','err-date_of_birth',validators.date_of_birth],
        ['national_id','err-national_id',validators.national_id],
        ['phone_primary','err-phone_primary',validators.phone_primary],
        ['phone_secondary','err-phone_secondary',validators.phone_secondary],
        ['state','err-state',validators.state],
        ['city','err-city',validators.city],
        ['address','err-address',validators.address],
        ['bank_id','err-bank_id',validators.bank_id],
        ['account_name','err-account_name',validators.account_name],
        ['account_number','err-account_number',validators.account_number],
        ['registration_number','err-registration_number',validators.registration_number],
        ['tax_number','err-tax_number',validators.tax_number],
        ['cosigner_first_name','err-cosigner_first_name',validators.cosigner_first_name],
        ['cosigner_last_name','err-cosigner_last_name',validators.cosigner_last_name],
        ['cosigner_middle_name','err-cosigner_middle_name',validators.cosigner_middle_name],
        ['cosigner_date_of_birth','err-cosigner_date_of_birth',validators.cosigner_date_of_birth],
        ['cosigner_relationship','err-cosigner_relationship',validators.cosigner_relationship],
        ['cosigner_bvn','err-cosigner_bvn',validators.cosigner_bvn],
        ['cosigner_email','err-cosigner_email',validators.cosigner_email],
        ['cosigner_occupation','err-cosigner_occupation',validators.cosigner_occupation],
        ['cosigner_phone_primary','err-cosigner_phone_primary',validators.cosigner_phone_primary],
        ['cosigner_phone_secondary','err-cosigner_phone_secondary',validators.cosigner_phone_secondary],
        ['cosigner_state','err-cosigner_state',validators.cosigner_state],
        ['cosigner_city','err-cosigner_city',validators.cosigner_city],
        ['cosigner_address','err-cosigner_address',validators.cosigner_address],
        ['purpose','err-purpose',validators.purpose],
        ['duration_months','err-duration_months',validators.duration_months],
    ].forEach(([id, errId, fn]) => attachLive(id, errId, fn));

    function validateStep(fields) {
        let ok = true, first = null;
        fields.forEach(([id, errId, fn]) => {
            const el = document.getElementById(id);
            if (!el) return;
            const [valid, msg] = fn(el.value);
            markInput(el, valid);
            valid ? clearErr(errId) : showErr(errId, msg);
            if (!valid) { ok = false; if (!first) first = el; }
        });
        if (first) first.scrollIntoView({ behavior:'smooth', block:'center' });
        return ok;
    }

    const step1Fields = [
        ['first_name','err-first_name',validators.first_name],
        ['last_name','err-last_name',validators.last_name],
        ['phone_primary','err-phone_primary',validators.phone_primary],
        ['phone_secondary','err-phone_secondary',validators.phone_secondary],
        ['state','err-state',validators.state],
        ['city','err-city',validators.city],
        ['address','err-address',validators.address],
        ['bank_id','err-bank_id',validators.bank_id],
        ['account_name','err-account_name',validators.account_name],
        ['account_number','err-account_number',validators.account_number],
    ];

    const step2Fields = [
        ['cosigner_first_name','err-cosigner_first_name',validators.cosigner_first_name],
        ['cosigner_last_name','err-cosigner_last_name',validators.cosigner_last_name],
        ['cosigner_relationship','err-cosigner_relationship',validators.cosigner_relationship],
        ['cosigner_bvn','err-cosigner_bvn',validators.cosigner_bvn],
        ['cosigner_occupation','err-cosigner_occupation',validators.cosigner_occupation],
        ['cosigner_phone_primary','err-cosigner_phone_primary',validators.cosigner_phone_primary],
        ['cosigner_state','err-cosigner_state',validators.cosigner_state],
        ['cosigner_city','err-cosigner_city',validators.cosigner_city],
        ['cosigner_address','err-cosigner_address',validators.cosigner_address],
    ];

    const step3Fields = [
        ['loan_product_id','err-loan_product_id',validators.loan_product_id],
        ['duration_months','err-duration_months',validators.duration_months],
        ['purpose','err-purpose',validators.purpose],
    ];

    // Step navigation
    function showStep(n) {
        ['step-1','step-2','step-3'].forEach(s => { const el = document.getElementById(s); if(el) el.style.display = 'none'; });
        ['step-indicator-1','step-indicator-2','step-indicator-3'].forEach(s => {
            const el = document.getElementById(s);
            if(el) { el.classList.remove('step-active','step-completed'); }
        });
        document.getElementById('step-' + n).style.display = 'block';
        for (let i = 1; i < n; i++) document.getElementById('step-indicator-' + i)?.classList.add('step-completed');
        document.getElementById('step-indicator-' + n)?.classList.add('step-active');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    document.getElementById('next-btn-1')?.addEventListener('click', () => { if (validateStep(step1Fields)) showStep(2); });
    document.getElementById('back-btn-2')?.addEventListener('click', () => showStep(1));
    document.getElementById('next-btn-2')?.addEventListener('click', () => { if (validateStep(step2Fields)) showStep(3); });
    document.getElementById('back-btn-3')?.addEventListener('click', () => showStep(2));

    // Loan product limits
    const loanProductSelect = document.getElementById('loan_product_id');
    const loanAmountEl      = document.getElementById('loan_amount');
    const loanHint          = document.getElementById('loan-amount-hint');

    function updateLimits() {
        const sel = loanProductSelect?.options[loanProductSelect.selectedIndex];
        if (sel?.dataset.min) {
            if (loanAmountEl) { loanAmountEl.min = sel.dataset.min; loanAmountEl.max = sel.dataset.max; }
            if (loanHint) loanHint.textContent = `Min: ₦${Number(sel.dataset.min).toLocaleString()} — Max: ₦${Number(sel.dataset.max).toLocaleString()}`;
        }
    }
    loanProductSelect?.addEventListener('change', updateLimits);
    updateLimits();

    loanAmountEl?.addEventListener('blur', () => {
        const sel = loanProductSelect?.options[loanProductSelect.selectedIndex];
        const [ok, msg] = validators.loan_amount(loanAmountEl.value, sel?.dataset.min, sel?.dataset.max);
        markInput(loanAmountEl, ok);
        ok ? clearErr('err-loan_amount') : showErr('err-loan_amount', msg);
    });

    // Document rows
    const wrapper  = document.getElementById('documents-wrapper');
    const template = document.getElementById('document-row-template');
    const addBtn   = document.getElementById('add-document-btn');
    let docIndex   = 0;

    function addDocumentRow() {
        const clone = template.content.cloneNode(true);
        const idx   = docIndex;

        clone.querySelectorAll('[name]').forEach(el => { el.name = el.name.replace('__INDEX__', idx); });

        const row   = document.createElement('div');
        row.appendChild(clone);
        const rowEl = row.firstElementChild;

        const fileInput   = rowEl.querySelector('.doc-file-input');
        const cameraInput = rowEl.querySelector('.doc-camera-input');
        const preview     = rowEl.querySelector('.doc-preview');
        const previewName = rowEl.querySelector('.doc-preview-name');
        const previewSize = rowEl.querySelector('.doc-preview-size');
        const clearBtn    = rowEl.querySelector('.doc-clear-btn');
        const errEl       = rowEl.querySelector('.doc-file-error');
        const selectBtn   = rowEl.querySelector('.select-file-btn');
        const cameraBtn   = rowEl.querySelector('.take-photo-btn');

        cameraInput.removeAttribute('name');

        function showPreview(file) {
            if (!file) return;
            const MAX = 5 * 1024 * 1024;
            if (file.size > MAX) {
                errEl.querySelector('span').textContent = 'File too large — max 5MB';
                errEl.classList.remove('hidden');
                return;
            }
            errEl.classList.add('hidden');
            previewName.textContent = file.name;
            previewSize.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            preview.classList.remove('hidden');
            markInput(fileInput, true);
        }

        function clearPreview() {
            fileInput.value   = '';
            cameraInput.value = '';
            fileInput.name    = `documents[${idx}][file]`;
            preview.classList.add('hidden');
            previewName.textContent = '';
            previewSize.textContent = '';
            errEl.classList.add('hidden');
        }

        selectBtn.addEventListener('click', () => {
            clearPreview();
            fileInput.name = `documents[${idx}][file]`;
            fileInput.click();
        });

        fileInput.addEventListener('change', function () {
            if (this.files[0]) showPreview(this.files[0]);
        });

        cameraBtn.addEventListener('click', () => {
            clearPreview();
            cameraInput.name = `documents[${idx}][file]`;
            fileInput.removeAttribute('name');
            cameraInput.click();
        });

        cameraInput.addEventListener('change', function () {
            if (this.files[0]) showPreview(this.files[0]);
        });

        clearBtn.addEventListener('click', () => {
            clearPreview();
            fileInput.name = `documents[${idx}][file]`;
            cameraInput.removeAttribute('name');
        });

        rowEl.querySelector('.remove-document-btn')?.addEventListener('click', () => rowEl.remove());

        wrapper.appendChild(rowEl);
        docIndex++;
    }
    addBtn?.addEventListener('click', addDocumentRow);

    // AJAX Submit
    const form      = document.getElementById('customer-edit-form');
    const submitBtn = document.getElementById('submit-btn');
    const submitTxt = document.getElementById('submit-btn-text');

    form?.addEventListener('submit', async function (e) {
        e.preventDefault();

        // Step 3 validate
        const loanOk = validateStep([
            ...step3Fields,
            ['loan_amount','err-loan_amount', v => {
                const sel = loanProductSelect?.options[loanProductSelect.selectedIndex];
                return validators.loan_amount(v, sel?.dataset.min, sel?.dataset.max);
            }],
        ]);
        if (!loanOk) return;

        if (submitBtn) submitBtn.disabled = true;
        if (submitTxt) submitTxt.textContent = 'Submitting...';

        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body:   formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const result = await response.json();

            if (result.success) {
                document.getElementById('form-success').style.display = 'flex';
                document.getElementById('form-error').style.display   = 'none';
                form.style.display = 'none';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                document.getElementById('form-error').style.display    = 'block';
                document.getElementById('form-error-msg').textContent  = result.message || 'Update failed.';
                if (submitBtn) submitBtn.disabled = false;
                if (submitTxt) submitTxt.textContent = 'Update & Resubmit Application';
            }
        } catch (err) {
            document.getElementById('form-error').style.display   = 'block';
            document.getElementById('form-error-msg').textContent = 'Something went wrong. Please try again.';
            if (submitBtn) submitBtn.disabled = false;
            if (submitTxt) submitTxt.textContent = 'Update & Resubmit Application';
        }
    });

})();
</script>

@endsection