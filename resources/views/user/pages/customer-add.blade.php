@extends('user.pages.staff.layouts.app')

@section('title', 'Customer Registration – African Investment Partners')

@push('styles')
<style>
    /* ============================================================
       PREMIUM FORM STYLES
    ============================================================ */
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
    .form-input-premium:hover {
        border-color: #b8c5d6;
        background: #fafbfc;
    }
    .form-input-premium:focus {
        background: #ffffff;
        border-color: #6DBE3B;
        outline: none;
        box-shadow: 0 0 0 4px rgba(109, 190, 59, 0.12), 0 4px 12px rgba(109, 190, 59, 0.08);
        transform: translateY(-1px);
    }
    .form-input-premium::placeholder {
        color: #94a3b8;
        font-weight: 400;
        font-size: 0.8rem;
    }
    .form-input-premium:disabled {
        background: #f1f5f9;
        cursor: not-allowed;
        opacity: 0.7;
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
    .form-group:focus-within .form-label-premium {
        color: #6DBE3B;
    }

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

    /* Step Wizard */
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
    .step-completed .step-number {
        background: #6DBE3B;
        color: white;
    }
    .step-text {
        font-size: 0.8rem;
        font-weight: 600;
        color: #94a3b8;
        transition: color 0.3s ease;
    }
    .step-active .step-text {
        color: #1a2332;
    }
    .step-completed .step-text {
        color: #6DBE3B;
    }

    /* Document Row */
    .document-row {
        background: #f8fafc;
        border: 2px solid #eef2f6;
        border-radius: 16px;
        padding: 1.25rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .document-row:hover {
        border-color: #cbd5e1;
        background: #fafbfc;
    }
    .document-row .form-input-premium {
        background: #ffffff;
    }

    /* Buttons */
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
    .btn-primary-premium:active {
        transform: translateY(0);
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
    .btn-secondary-premium:hover {
        background: #e2e8f0;
        color: #1a2332;
    }

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
    .btn-outline-premium:hover {
        background: #6DBE3B;
        color: white;
        box-shadow: 0 4px 16px rgba(109, 190, 59, 0.2);
    }

    /* Success/Error Alerts */
    .alert-premium {
        border-radius: 16px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        border: 2px solid transparent;
    }
    .alert-premium-success {
        background: #f0fdf4;
        border-color: #bbf7d0;
    }
    .alert-premium-error {
        background: #fef2f2;
        border-color: #fecaca;
    }
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
    .alert-premium-success .icon {
        background: #6DBE3B;
        color: white;
    }
    .alert-premium-error .icon {
        background: #ef4444;
        color: white;
    }

    /* Responsive */
    @media (max-width: 640px) {
        .form-header {
            padding: 1.5rem;
        }
        .step-wizard {
            flex-direction: column;
            align-items: stretch;
            gap: 0.5rem;
        }
        .step-item:not(:last-child)::after {
            display: none;
        }
        .form-section-title {
            font-size: 0.95rem;
        }
    }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    {{-- ===== HEADER ===== --}}
    <div class="form-header mb-6">
        <div class="relative z-10">
            <div class="flex items-center gap-2 text-[#6DBE3B] font-semibold text-xs tracking-widest uppercase mb-1">
                <span class="inline-block w-2 h-2 rounded-full bg-[#6DBE3B] animate-pulse"></span>
                Staff Portal • Customer Onboarding
            </div>
            <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-white">
                Register New Customer
            </h1>
            <p class="text-white/60 text-sm mt-1 max-w-2xl">
                Complete the form below to create a customer profile, attach KYC documents, and initiate a loan application.
            </p>
        </div>
    </div>

    {{-- ===== ALERTS ===== --}}
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

    {{-- ===== STEP WIZARD ===== --}}
    <div class="step-wizard mb-6">
        <div id="step-indicator-1" class="step-item step-active">
            <div class="step-number">1</div>
            <span class="step-text">Customer Profile</span>
        </div>
        <div id="step-indicator-2" class="step-item">
            <div class="step-number">2</div>
            <span class="step-text">Loan Application</span>
        </div>
    </div>

    {{-- ===== FORM ===== --}}
    <div class="form-container p-6 sm:p-8">
        <form method="POST" action="{{ route('staff.customer.store') }}" enctype="multipart/form-data" id="customer-form">
            @csrf

            {{-- ===== STEP 1 ===== --}}
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
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">First Name <span class="text-red-500">*</span></label>
                            <input type="text" name="first_name" required class="form-input-premium" value="{{ old('first_name') }}" placeholder="Enter first name">
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" name="last_name" required class="form-input-premium" value="{{ old('last_name') }}" placeholder="Enter last name">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">Middle Name</label>
                            <input type="text" name="middle_name" class="form-input-premium" value="{{ old('middle_name') }}" placeholder="Optional">
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-input-premium" value="{{ old('date_of_birth') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Gender</label>
                            <select name="gender" class="form-input-premium">
                                <option value="">Select gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
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
                        <div class="form-group">
                            <label class="form-label-premium">Email Address</label>
                            <input type="email" name="email" class="form-input-premium" value="{{ old('email') }}" placeholder="customer@example.com">
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">National ID (NIN/BVN)</label>
                            <input type="text" name="national_id" class="form-input-premium" value="{{ old('national_id') }}" placeholder="Enter ID number">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">Primary Phone <span class="text-red-500">*</span></label>
                            <input type="text" name="phone_primary" required class="form-input-premium" value="{{ old('phone_primary') }}" placeholder="080 1234 5678">
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Secondary Phone</label>
                            <input type="text" name="phone_secondary" class="form-input-premium" value="{{ old('phone_secondary') }}" placeholder="Optional">
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
                            <input type="text" name="occupation" class="form-input-premium" value="{{ old('occupation') }}" placeholder="e.g. Software Engineer">
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Monthly Income (₦)</label>
                            <input type="number" step="0.01" name="monthly_income" class="form-input-premium" value="{{ old('monthly_income') }}" placeholder="0.00">
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
                            <input type="text" name="business_name" class="form-input-premium" value="{{ old('business_name') }}" placeholder="Registered business name">
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Business Type</label>
                            <input type="text" name="business_type" class="form-input-premium" value="{{ old('business_type') }}" placeholder="e.g. Retail, Tech">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">Registration Number (CAC)</label>
                            <input type="text" name="registration_number" class="form-input-premium" value="{{ old('registration_number') }}" placeholder="RC1234567">
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Tax ID (TIN)</label>
                            <input type="text" name="tax_number" class="form-input-premium" value="{{ old('tax_number') }}" placeholder="TIN number">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">Monthly Revenue (₦)</label>
                            <input type="number" step="0.01" name="business_monthly_revenue" class="form-input-premium" value="{{ old('business_monthly_revenue') }}" placeholder="0.00">
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Monthly Expenses (₦)</label>
                            <input type="number" step="0.01" name="business_monthly_expense" class="form-input-premium" value="{{ old('business_monthly_expense') }}" placeholder="0.00">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">State</label>
                            <input type="text" name="business_state" class="form-input-premium" value="{{ old('business_state') }}" placeholder="State">
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">City</label>
                            <input type="text" name="business_city" class="form-input-premium" value="{{ old('business_city') }}" placeholder="City">
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">LGA</label>
                            <input type="text" name="business_local_government_area" class="form-input-premium" value="{{ old('business_local_government_area') }}" placeholder="Local Government Area">
                        </div>
                    </div>
                    <div class="form-group mt-4">
                        <label class="form-label-premium">Business Address</label>
                        <textarea name="business_address" rows="2" class="form-input-premium resize-none" placeholder="Full business address">{{ old('business_address') }}</textarea>
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
                            <input type="text" name="country" class="form-input-premium" value="{{ old('country', 'Nigeria') }}" placeholder="Nigeria">
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">State</label>
                            <input type="text" name="state" class="form-input-premium" value="{{ old('state') }}" placeholder="State">
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">City</label>
                            <input type="text" name="city" class="form-input-premium" value="{{ old('city') }}" placeholder="City">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">LGA</label>
                            <input type="text" name="local_government_area" class="form-input-premium" value="{{ old('local_government_area') }}" placeholder="LGA">
                        </div>
                        <div class="form-group sm:col-span-3">
                            <label class="form-label-premium">Full Address</label>
                            <textarea name="address" rows="2" class="form-input-premium resize-none" placeholder="House number, street, landmark...">{{ old('address') }}</textarea>
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
                            <select name="status" class="form-input-premium">
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

                {{-- Step 1 Actions --}}
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t-2 border-slate-100">
                    <a href="{{ url()->previous() }}" class="btn-secondary-premium">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="button" id="next-btn" class="btn-primary-premium">
                        <span>Continue to Loan</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            {{-- ===== STEP 2 ===== --}}
            <div id="step-2" style="display:none;">

                <div class="mb-8">
                    <div class="form-section-title">
                        <div class="icon-wrapper"><i class="fas fa-hand-holding-usd"></i></div>
                        Loan Application
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label-premium">Loan Product <span class="text-red-500">*</span></label>
                            <select name="loan_product_id" id="loan_product_id" class="form-input-premium">
                                <option value="">Select a product</option>
                                @foreach ($loanProducts as $product)
                                    <option
                                        value="{{ $product->id }}"
                                        data-type="{{ $product->loan_type }}"
                                        data-min="{{ $product->minimum_amount }}"
                                        data-max="{{ $product->maximum_amount }}"
                                        data-duration="{{ $product->duration_months }}"
                                        {{ old('loan_product_id') == $product->id ? 'selected' : '' }}
                                    >
                                        {{ $product->name }} (₦{{ number_format($product->minimum_amount) }} – ₦{{ number_format($product->maximum_amount) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Loan Amount (₦) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="loan_amount" id="loan_amount" class="form-input-premium" value="{{ old('loan_amount') }}" placeholder="Enter amount">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div class="form-group">
                            <label class="form-label-premium">Duration (Months) <span class="text-red-500">*</span></label>
                            <input type="number" name="duration_months" id="duration_months" class="form-input-premium" value="{{ old('duration_months') }}" placeholder="e.g. 12">
                        </div>
                    </div>
                    <div class="form-group mt-4">
                        <label class="form-label-premium">Loan Purpose <span class="text-red-500">*</span></label>
                        <textarea name="purpose" rows="3" class="form-input-premium resize-none" placeholder="Describe how the customer will use these funds...">{{ old('purpose') }}</textarea>
                    </div>
                </div>

                {{-- Step 2 Actions --}}
                <div class="flex flex-col sm:flex-row justify-between gap-3 pt-6 border-t-2 border-slate-100">
                    <button type="button" id="back-btn" class="btn-secondary-premium">
                        <i class="fas fa-arrow-left"></i> Back to Profile
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
                <input type="file" name="documents[__INDEX__][file]" class="form-input-premium !py-1.5" accept=".jpg,.jpeg,.png,.pdf">
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

{{-- JavaScript --}}
<script>
    (function() {
        function initForm() {
            const wrapper = document.getElementById('documents-wrapper');
            const template = document.getElementById('document-row-template');
            const addBtn = document.getElementById('add-document-btn');
            const form = document.getElementById('customer-form');
            const submitBtn = document.getElementById('submit-btn');
            const submitText = document.getElementById('submit-btn-text');

            if (!wrapper || !template || !addBtn || !form) return;

            let docIndex = 0;

            function addDocumentRow() {
                const clone = template.content.cloneNode(true);
                clone.querySelectorAll('[name]').forEach(el => {
                    el.name = el.name.replace('__INDEX__', docIndex);
                });
                const row = document.createElement('div');
                row.appendChild(clone);
                row.querySelector('.remove-document-btn')?.addEventListener('click', () => row.remove());
                wrapper.appendChild(row);
                docIndex++;
            }

            addBtn.addEventListener('click', addDocumentRow);
            if (wrapper.children.length === 0) addDocumentRow();

            form.addEventListener('submit', () => {
                if (submitBtn) submitBtn.disabled = true;
                if (submitText) submitText.textContent = 'Processing...';
            });

            // Customer Type toggle
            const typeSelect = document.getElementById('customer_type');
            const businessSection = document.getElementById('business-info-section');

            function toggleBusiness() {
                if (typeSelect && businessSection) {
                    businessSection.style.display = typeSelect.value === 'sme' ? 'block' : 'none';
                }
            }

            typeSelect?.addEventListener('change', toggleBusiness);
            toggleBusiness();

            // Loan product filter
            const loanProductSelect = document.getElementById('loan_product_id');
            const loanAmount = document.getElementById('loan_amount');
            const duration = document.getElementById('duration_months');

            function filterProducts() {
                if (!typeSelect || !loanProductSelect) return;
                const type = typeSelect.value;
                const options = loanProductSelect.querySelectorAll('option[data-type]');
                let visible = 0;
                options.forEach(opt => {
                    opt.hidden = opt.dataset.type !== type;
                    if (opt.dataset.type === type) visible++;
                    if (opt.selected && opt.dataset.type !== type) opt.selected = false;
                });
                const placeholder = loanProductSelect.querySelector('option[value=""]');
                if (placeholder) {
                    placeholder.textContent = visible === 0 ? 'No products available' : 'Select Loan Product';
                }
                updateLimits();
            }

            function updateLimits() {
                const selected = loanProductSelect?.options[loanProductSelect.selectedIndex];
                if (selected?.dataset.min) {
                    if (loanAmount) { loanAmount.min = selected.dataset.min; loanAmount.max = selected.dataset.max; }
                    if (duration && !duration.value && selected.dataset.duration) {
                        duration.value = selected.dataset.duration;
                    }
                }
            }

            typeSelect?.addEventListener('change', filterProducts);
            loanProductSelect?.addEventListener('change', updateLimits);
            filterProducts();

            // Step navigation
            const nextBtn = document.getElementById('next-btn');
            const backBtn = document.getElementById('back-btn');
            const step1 = document.getElementById('step-1');
            const step2 = document.getElementById('step-2');
            const ind1 = document.getElementById('step-indicator-1');
            const ind2 = document.getElementById('step-indicator-2');

            function goToStep2() {
                step1.style.display = 'none';
                step2.style.display = 'block';
                ind1.classList.remove('step-active');
                ind1.classList.add('step-completed');
                ind2.classList.add('step-active');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            function goToStep1() {
                step2.style.display = 'none';
                step1.style.display = 'block';
                ind2.classList.remove('step-active');
                ind1.classList.remove('step-completed');
                ind1.classList.add('step-active');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            nextBtn?.addEventListener('click', () => {
                const required = step1.querySelectorAll('[required]');
                let invalid = null;
                required.forEach(f => { if (!invalid && !f.checkValidity()) invalid = f; });
                if (invalid) { invalid.reportValidity(); invalid.focus(); return; }
                goToStep2();
            });

            backBtn?.addEventListener('click', goToStep1);

            @if ($errors->any())
                const errorKeys = @json(array_keys($errors->toArray()));
                const step2Fields = ['loan_product_id', 'loan_amount', 'duration_months', 'purpose'];
                if (errorKeys.some(k => step2Fields.includes(k))) goToStep2();
            @endif
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initForm);
        } else {
            initForm();
        }
    })();
</script>
@endsection