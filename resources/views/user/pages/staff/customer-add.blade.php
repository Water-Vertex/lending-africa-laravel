@extends('user.pages.staff.layouts.app')

@section('title', 'Customer Registration – African Investment Partners')

@section('content')
<section class="section-pad bg-gray-50" id="customer-registration">
    <div class="container">

        <div class="text-center mb-10">
            <div class="section-badge"><i class="fas fa-user-plus text-xs"></i> Staff Portal</div>
            <h2 class="section-title mb-4">Customer Registration</h2>
            <p class="section-subtitle mx-auto">Create a new customer record with their documents and loan application.</p>
        </div>

        {{-- Success / Error Messages --}}
        @if (session('success'))
            <div class="max-w-4xl mx-auto bg-green-50 border border-green-200 rounded-2xl p-4 mb-6">
                <div class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-green-600"></i>
                    <span class="text-green-800 text-sm">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="max-w-4xl mx-auto bg-red-50 border border-red-200 rounded-2xl p-4 mb-6">
                <div class="flex items-start gap-2">
                    <i class="fas fa-exclamation-circle text-red-600 mt-0.5"></i>
                    <ul class="text-red-800 text-sm space-y-1">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-xl border border-gray-100 p-8">

            {{-- Step Indicator --}}
            <div class="flex items-center gap-3 mb-8">
                <div id="step-indicator-1" class="flex items-center gap-2 step-indicator-active">
                    <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-sm font-bold">1</div>
                    <span class="text-sm font-semibold text-dark">Customer Details</span>
                </div>
                <div class="flex-1 h-px bg-gray-200"></div>
                <div id="step-indicator-2" class="flex items-center gap-2 opacity-40">
                    <div class="w-8 h-8 rounded-full bg-gray-300 text-white flex items-center justify-center text-sm font-bold">2</div>
                    <span class="text-sm font-semibold text-dark">Loan Application</span>
                </div>
            </div>

            <form method="POST" action="{{ route('staff.customer.store') }}" enctype="multipart/form-data" id="customer-form" class="space-y-8">
                @csrf

                {{-- ============================= STEP 1 ============================= --}}
                <div id="step-1">

                    {{-- Personal Information --}}
                    <div>
                        <h3 class="font-display font-bold text-dark text-lg mb-4">Personal Information</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Customer Type *</label>
                                <select name="customer_type" id="customer_type" required class="contact-form-input">
                                    <option value="personal" {{ old('customer_type', 'personal') == 'personal' ? 'selected' : '' }}>Personal</option>
                                    <option value="sme" {{ old('customer_type') == 'sme' ? 'selected' : '' }}>SME</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">First Name *</label>
                                <input type="text" name="first_name" required class="contact-form-input" value="{{ old('first_name') }}" placeholder="Enter first name">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Last Name *</label>
                                <input type="text" name="last_name" required class="contact-form-input" value="{{ old('last_name') }}" placeholder="Enter last name">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Middle Name</label>
                                <input type="text" name="middle_name" class="contact-form-input" value="{{ old('middle_name') }}" placeholder="Enter middle name">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="contact-form-input" value="{{ old('date_of_birth') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Gender</label>
                                <select name="gender" class="contact-form-input">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Contact Information --}}
                    <div class="border-t border-gray-100 pt-6">
                        <h3 class="font-display font-bold text-dark text-lg mb-4">Contact Information</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Email</label>
                                <input type="email" name="email" class="contact-form-input" value="{{ old('email') }}" placeholder="Enter email address">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">National ID</label>
                                <input type="text" name="national_id" class="contact-form-input" value="{{ old('national_id') }}" placeholder="Enter national ID">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Primary Phone *</label>
                                <input type="text" name="phone_primary" required class="contact-form-input" value="{{ old('phone_primary') }}" placeholder="Enter primary phone number">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Secondary Phone</label>
                                <input type="text" name="phone_secondary" class="contact-form-input" value="{{ old('phone_secondary') }}" placeholder="Enter secondary phone number">
                            </div>
                        </div>
                    </div>

                    {{-- Professional Information --}}
                    <div class="border-t border-gray-100 pt-6">
                        <h3 class="font-display font-bold text-dark text-lg mb-4">Professional Information</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Occupation</label>
                                <input type="text" name="occupation" class="contact-form-input" value="{{ old('occupation') }}" placeholder="Enter occupation">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Monthly Income</label>
                                <input type="number" step="0.01" name="monthly_income" class="contact-form-input" value="{{ old('monthly_income') }}" placeholder="Enter monthly income">
                            </div>
                        </div>
                    </div>

                    {{-- Business Information (SME only) --}}
                    <div id="business-info-section" style="display:none;" class="border-t border-gray-100 pt-6">
                        <h3 class="font-display font-bold text-dark text-lg mb-4">
                            <i class="fas fa-briefcase text-primary mr-1"></i> Business Information
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Business Name *</label>
                                <input type="text" name="business_name" class="contact-form-input" value="{{ old('business_name') }}" placeholder="Enter business name">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Business Type</label>
                                <input type="text" name="business_type" class="contact-form-input" value="{{ old('business_type') }}" placeholder="e.g. Retail, Services, Manufacturing">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Registration Number</label>
                                <input type="text" name="registration_number" class="contact-form-input" value="{{ old('registration_number') }}" placeholder="Enter CAC/registration number">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Tax Number</label>
                                <input type="text" name="tax_number" class="contact-form-input" value="{{ old('tax_number') }}" placeholder="Enter tax identification number">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Monthly Revenue (₦)</label>
                                <input type="number" step="0.01" name="business_monthly_revenue" class="contact-form-input" value="{{ old('business_monthly_revenue') }}" placeholder="Enter monthly revenue">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Monthly Expense (₦)</label>
                                <input type="number" step="0.01" name="business_monthly_expense" class="contact-form-input" value="{{ old('business_monthly_expense') }}" placeholder="Enter monthly expense">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Business State</label>
                                <input type="text" name="business_state" class="contact-form-input" value="{{ old('business_state') }}" placeholder="Enter state">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Business City</label>
                                <input type="text" name="business_city" class="contact-form-input" value="{{ old('business_city') }}" placeholder="Enter city">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Business LGA</label>
                                <input type="text" name="business_local_government_area" class="contact-form-input" value="{{ old('business_local_government_area') }}" placeholder="Enter LGA">
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-semibold text-gray-800 mb-1.5">Business Address</label>
                            <textarea name="business_address" rows="2" class="contact-form-input resize-none" placeholder="Enter full business address">{{ old('business_address') }}</textarea>
                        </div>
                    </div>

                    {{-- Address Information --}}
                    <div class="border-t border-gray-100 pt-6">
                        <h3 class="font-display font-bold text-dark text-lg mb-4">Address Information</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Country</label>
                                <input type="text" name="country" class="contact-form-input" value="{{ old('country', 'Nigeria') }}" placeholder="Enter country">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">State</label>
                                <input type="text" name="state" class="contact-form-input" value="{{ old('state') }}" placeholder="Enter state">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">City</label>
                                <input type="text" name="city" class="contact-form-input" value="{{ old('city') }}" placeholder="Enter city">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">LGA</label>
                                <input type="text" name="local_government_area" class="contact-form-input" value="{{ old('local_government_area') }}" placeholder="Enter LGA">
                            </div>
                            <div class="sm:col-span-3">
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Address</label>
                                <textarea name="address" rows="2" class="contact-form-input resize-none" placeholder="Enter full address">{{ old('address') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="border-t border-gray-100 pt-6">
                        <h3 class="font-display font-bold text-dark text-lg mb-4">Status</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Status</label>
                                <select name="status" class="contact-form-input">
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="blacklisted" {{ old('status') == 'blacklisted' ? 'selected' : '' }}>Blacklisted</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Documents --}}
                    <div class="border-t border-gray-100 pt-6">
                        <h3 class="font-display font-bold text-dark text-lg mb-4">Documents</h3>

                        <div id="documents-wrapper" class="space-y-3 mb-4"></div>

                        <button type="button" id="add-document-btn"
                            class="px-4 py-2 bg-primary-light text-primary-dark rounded-xl hover:bg-primary hover:text-white transition-colors text-sm font-semibold">
                            <i class="fas fa-plus mr-2"></i> Add Document
                        </button>
                    </div>

                    {{-- Step 1 Actions --}}
                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('staff.dashboard') }}" class="btn-outline-white !text-gray-700 !border-gray-300 px-4 py-2 rounded-xl text-sm font-medium">
                            Cancel
                        </a>
                        <button type="button" id="next-btn" class="btn-primary" style="display:flex;">
                            <span>Next: Loan Application</span>
                            <i class="fas fa-arrow-right text-sm"></i>
                        </button>
                    </div>
                </div>

                {{-- ============================= STEP 2 ============================= --}}
                <div id="step-2" style="display:none;">

                    <div>
                        <h3 class="font-display font-bold text-dark text-lg mb-4">Loan Application</h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Loan Product *</label>
                                <select name="loan_product_id" id="loan_product_id" class="contact-form-input">
                                    <option value="">Select Loan Product</option>
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
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Loan Amount (₦) *</label>
                                <input type="number" step="0.01" name="loan_amount" id="loan_amount" class="contact-form-input" value="{{ old('loan_amount') }}" placeholder="Enter loan amount">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Duration (Months) *</label>
                                <input type="number" name="duration_months" id="duration_months" class="contact-form-input" value="{{ old('duration_months') }}" placeholder="e.g. 12">
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-semibold text-gray-800 mb-1.5">Loan Purpose *</label>
                            <textarea name="purpose" rows="3" class="contact-form-input resize-none" placeholder="Briefly describe what the loan is for...">{{ old('purpose') }}</textarea>
                        </div>
                    </div>

                    {{-- Step 2 Actions --}}
                    <div class="flex justify-between gap-3 pt-6 border-t border-gray-100 mt-6">
                        <button type="button" id="back-btn" class="btn-outline-white !text-gray-700 !border-gray-300 px-4 py-2 rounded-xl text-sm font-medium">
                            <i class="fas fa-arrow-left text-sm mr-2"></i> Back
                        </button>
                        <button type="submit" id="submit-btn" class="btn-primary" style="display:flex;">
                            <span id="submit-btn-text">Create Customer</span>
                            <i class="fas fa-paper-plane text-sm"></i>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</section>

{{-- Document row template --}}
<template id="document-row-template">
    <div class="document-row bg-gray-50 rounded-xl border border-gray-200 p-4">
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Document Type</label>
                <select name="documents[__INDEX__][document_type]" class="contact-form-input doc-type">
                    <option value="national_id">National ID</option>
                    <option value="passport">Passport</option>
                    <option value="driver_license">Driver License</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">File</label>
                <input type="file" name="documents[__INDEX__][file]" class="contact-form-input" accept=".jpg,.jpeg,.png,.pdf">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Verification Status</label>
                <select name="documents[__INDEX__][verification_status]" class="contact-form-input">
                    <option value="pending">Pending</option>
                    <option value="verified">Verified</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div>
                <button type="button" class="remove-document-btn w-full px-3 py-2 border border-red-200 text-red-600 rounded-xl text-sm font-semibold hover:bg-red-50 transition-colors">
                    <i class="fas fa-times mr-1"></i> Remove
                </button>
            </div>
        </div>
    </div>
</template>

{{-- Robust script loading both on normal load and SPA dynamic swap --}}
<script>
    (function () {
        function initCustomerForm() {
            var wrapper   = document.getElementById('documents-wrapper');
            var template  = document.getElementById('document-row-template');
            var addBtn    = document.getElementById('add-document-btn');
            var form      = document.getElementById('customer-form');
            var submitBtn = document.getElementById('submit-btn');

            if (!wrapper || !template || !addBtn || !form) {
                return;
            }

            // Purani bindings saaf karne ke liye elements ko clone kar ke replace karte hain
            var newAddBtn = addBtn.cloneNode(true);
            addBtn.parentNode.replaceChild(newAddBtn, addBtn);
            addBtn = newAddBtn;

            var docIndex = 0;
            wrapper.innerHTML = ''; // Pehle se loaded rows saaf karna taake duplicate na hon

            function addDocumentRow() {
                var clone = template.content.cloneNode(true);
                clone.querySelectorAll('[name]').forEach(function (el) {
                    el.name = el.name.replace('__INDEX__', docIndex);
                });

                var row = document.createElement('div');
                row.appendChild(clone);

                var removeBtn = row.querySelector('.remove-document-btn');
                removeBtn.addEventListener('click', function () {
                    row.remove();
                });

                wrapper.appendChild(row);
                docIndex++;
            }

            addBtn.addEventListener('click', addDocumentRow);

            if (wrapper.children.length === 0) {
                addDocumentRow();
            }

            // Submit event cleaner clone
            var newForm = form.cloneNode(true);
            form.parentNode.replaceChild(newForm, form);
            form = newForm;
            submitBtn = form.querySelector('#submit-btn');

            form.addEventListener('submit', function () {
                if (submitBtn) {
                    submitBtn.disabled = true;
                }
                var text = document.getElementById('submit-btn-text');
                if (text) text.textContent = 'Creating...';
            });

            var customerTypeSelect = form.querySelector('#customer_type');
            var businessSection    = form.querySelector('#business-info-section');
            var loanProductSelect  = form.querySelector('#loan_product_id');
            var loanAmountInput    = form.querySelector('#loan_amount');
            var durationInput      = form.querySelector('#duration_months');

            function toggleBusinessSection() {
                if (customerTypeSelect && businessSection) {
                    businessSection.style.display = (customerTypeSelect.value === 'sme') ? 'block' : 'none';
                }
            }

            function filterLoanProducts() {
                if (!customerTypeSelect || !loanProductSelect) return;
                var type = customerTypeSelect.value;
                var options = loanProductSelect.querySelectorAll('option[data-type]');
                var visibleCount = 0;

                options.forEach(function (opt) {
                    if (opt.dataset.type === type) {
                        opt.hidden = false;
                        visibleCount++;
                    } else {
                        opt.hidden = true;
                        if (opt.selected) {
                            opt.selected = false;
                            loanProductSelect.value = '';
                        }
                    }
                });

                var placeholder = loanProductSelect.querySelector('option[value=""]');
                if (placeholder) {
                    if (visibleCount === 0) {
                        placeholder.textContent = 'No loan products available for this customer type';
                    } else {
                        placeholder.textContent = 'Select Loan Product';
                    }
                }

                updateLoanAmountLimits();
            }

            function updateLoanAmountLimits() {
                if (!loanProductSelect || !loanAmountInput) return;
                var selected = loanProductSelect.options[loanProductSelect.selectedIndex];
                if (selected && selected.dataset.min) {
                    loanAmountInput.min = selected.dataset.min;
                    loanAmountInput.max = selected.dataset.max;
                    if (durationInput && !durationInput.value && selected.dataset.duration) {
                        durationInput.value = selected.dataset.duration;
                    }
                }
            }

            if (customerTypeSelect) {
                customerTypeSelect.addEventListener('change', function () {
                    toggleBusinessSection();
                    filterLoanProducts();
                });
            }

            if (loanProductSelect) {
                loanProductSelect.addEventListener('change', updateLoanAmountLimits);
            }

            toggleBusinessSection();
            filterLoanProducts();

            var nextBtn = form.querySelector('#next-btn');
            var backBtn = form.querySelector('#back-btn');
            var step1   = form.querySelector('#step-1');
            var step2   = form.querySelector('#step-2');
            var indicator1 = document.getElementById('step-indicator-1');
            var indicator2 = document.getElementById('step-indicator-2');

            function goToStep2() {
                if (step1 && step2) {
                    step1.style.display = 'none';
                    step2.style.display = 'block';
                }
                if (indicator1 && indicator2) {
                    indicator1.classList.add('opacity-40');
                    indicator2.classList.remove('opacity-40');
                }
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            function goToStep1() {
                if (step1 && step2) {
                    step2.style.display = 'none';
                    step1.style.display = 'block';
                }
                if (indicator1 && indicator2) {
                    indicator2.classList.add('opacity-40');
                    indicator1.classList.remove('opacity-40');
                }
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', function () {
                    var requiredFields = step1.querySelectorAll('[required]');
                    var firstInvalid = null;

                    requiredFields.forEach(function (field) {
                        if (!firstInvalid && !field.checkValidity()) {
                            firstInvalid = field;
                        }
                    });

                    if (firstInvalid) {
                        firstInvalid.reportValidity();
                        firstInvalid.focus();
                        return;
                    }

                    goToStep2();
                });
            }

            if (backBtn) {
                backBtn.addEventListener('click', goToStep1);
            }

            @if ($errors->any())
                var errorKeys = @json(array_keys($errors->toArray()));
                var step2Fields = ['loan_product_id', 'loan_amount', 'duration_months', 'purpose'];
                var hasStep2Error = errorKeys.some(function (k) { return step2Fields.indexOf(k) !== -1; });

                if (hasStep2Error) {
                    goToStep2();
                }
            @endif
        }

        // 1. Instant execution (Normal loading ya fast swap ke liye)
        initCustomerForm();

        // 2. Livewire / Alpine / Turbolinks ke custom event listeners handle karna:
        document.addEventListener('DOMContentLoaded', initCustomerForm);
        document.addEventListener('livewire:load', initCustomerForm);
        document.addEventListener('livewire:navigated', initCustomerForm);
        document.addEventListener('turbo:load', initCustomerForm);
    })();
</script>
@endsection