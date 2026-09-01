@extends('user.layouts.app')



@section('title', 'African Investment Partners – Fast Loan Solutions in Nigeria')



@section('content')



{{-- ============================================================

     HERO SECTION

============================================================ --}}

<section class="hero-section min-h-screen flex items-center py-20" id="home">

    <div class="container relative z-10">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">



            {{-- Left Content --}}

            <div class="animate-fade-up">

                <div class="section-badge mb-4">

                    <i class="fas fa-circle-check text-xs"></i>

                    Trusted by 5,000+ Nigerians

                </div>



                <h1 class="font-display font-extrabold text-white leading-tight mb-6" style="font-size: clamp(36px, 5vw, 60px); letter-spacing: -0.03em;">

                    Fast & Affordable

                    <span class="block text-primary">Loan Solutions</span>

                    for Every Need

                </h1>



                <p class="text-gray-300 text-lg leading-relaxed mb-8 max-w-xl">

                    Whether you need a personal loan or SME business funding, African Investment Partners connects you with Polaris Bank and Zenith Bank for quick approvals — no hidden fees, no stress.

                </p>



                {{-- Key Points --}}

                <div class="flex flex-wrap gap-4 mb-10">

                    <div class="flex items-center gap-2 text-gray-300 text-sm font-medium">

                        <i class="fas fa-check-circle text-primary"></i> Approval in 2 – 5 Days

                    </div>

                    <div class="flex items-center gap-2 text-gray-300 text-sm font-medium">

                        <i class="fas fa-check-circle text-primary"></i> Low Interest Rates

                    </div>

                    <div class="flex items-center gap-2 text-gray-300 text-sm font-medium">

                        <i class="fas fa-check-circle text-primary"></i> No Hidden Charges

                    </div>

                    <div class="flex items-center gap-2 text-gray-300 text-sm font-medium">

                        <i class="fas fa-check-circle text-primary"></i> Flexible Repayment

                    </div>

                </div>



                <div class="flex flex-wrap gap-4">

                    <a href="#apply" class="btn-primary">

                        Apply for Loan <i class="fas fa-arrow-right text-sm"></i>

                    </a>

                    <a href="#how-it-works" class="btn-outline-white">

                        <i class="fas fa-play text-xs"></i> How It Works

                    </a>

                </div>



                {{-- Trust Badges --}}

                <div class="flex items-center gap-6 mt-10 pt-10 border-t border-white/10">

                    <div class="text-center">

                        <p class="text-white font-bold text-xl font-display">Polaris</p>

                        <p class="text-gray-500 text-xs">Bank Partner</p>

                    </div>

                    <div class="w-px h-10 bg-white/10"></div>

                    <div class="text-center">

                        <p class="text-white font-bold text-xl font-display">Zenith</p>

                        <p class="text-gray-500 text-xs">Bank Partner</p>

                    </div>

                    <div class="w-px h-10 bg-white/10"></div>

                    <div class="flex items-center gap-1">

                        <i class="fas fa-star text-yellow-400 text-sm"></i>

                        <i class="fas fa-star text-yellow-400 text-sm"></i>

                        <i class="fas fa-star text-yellow-400 text-sm"></i>

                        <i class="fas fa-star text-yellow-400 text-sm"></i>

                        <i class="fas fa-star text-yellow-400 text-sm"></i>

                        <span class="text-white text-sm font-semibold ml-1">4.9/5</span>

                        <span class="text-gray-500 text-xs ml-1">(1,200+ reviews)</span>

                    </div>

                </div>

            </div>

            {{-- Calculator ke liye dynamic rates --}}

{{-- Calculator ke liye dynamic rates --}}
<div id="loan-rates-data"
     data-personal-rate="{{ $personalProduct ? $personalProduct->interest_rate : 20 }}"
     data-personal-min="{{ $personalProduct ? $personalProduct->minimum_amount : 50000 }}"
     data-personal-max="{{ $personalProduct ? $personalProduct->maximum_amount : 200000 }}"
     data-personal-min-duration="{{ $personalProduct ? $personalProduct->minimum_duration_month : 3 }}"
     data-personal-max-duration="{{ $personalProduct ? $personalProduct->duration_months : 36 }}"
     data-sme-rate="{{ $smeProduct ? $smeProduct->interest_rate : 20 }}"
     data-sme-min="{{ $smeProduct ? $smeProduct->minimum_amount : 50000 }}"
     data-sme-max="{{ $smeProduct ? $smeProduct->maximum_amount : 300000 }}"
     data-sme-min-duration="{{ $smeProduct ? $smeProduct->minimum_duration_month : 3 }}"
     data-sme-max-duration="{{ $smeProduct ? $smeProduct->duration_months : 36 }}"
     style="display:none;">
</div>


            {{-- Right: Loan Calculator Card --}}

            <div class="animate-fade-up delay-2">

                <div class="calc-card p-8">

                    <h3 class="font-display font-bold text-dark text-xl mb-2">Loan Calculator</h3>

                    <p class="text-gray-500 text-sm mb-6">Estimate your monthly repayment instantly</p>



                    {{-- Tabs --}}

                    <div class="flex gap-2 bg-gray-100 p-1 rounded-xl mb-6">

                        <button class="calc-tab active flex-1" data-type="personal">Personal Loan</button>

                        <button class="calc-tab flex-1" data-type="sme">SME Loan</button>

                    </div>


{{-- Amount --}}
<div class="mb-6">
    <div class="flex justify-between items-center mb-3">
        <label class="text-sm font-semibold text-gray-800">Loan Amount</label>
        <span id="loan-amount-display" class="text-primary font-bold text-lg font-display">
            ₦{{ number_format($personalProduct ? $personalProduct->minimum_amount : 50000, 0) }}
        </span>
    </div>
    <input type="range" id="loan-amount-slider" class="range-slider" 
        min="{{ $personalProduct ? $personalProduct->minimum_amount : 50000 }}" 
        max="{{ $personalProduct ? $personalProduct->maximum_amount : 200000 }}" 
        value="{{ $personalProduct ? $personalProduct->minimum_amount : 50000 }}" 
        step="1000">
    <div class="flex justify-between text-xs text-gray-500 mt-1">
        <span id="loan-amount-min-label">
            ₦{{ number_format($personalProduct ? $personalProduct->minimum_amount : 50000, 0) }}
        </span>
        <span id="loan-amount-max-label">
            ₦{{ number_format($personalProduct ? $personalProduct->maximum_amount : 200000, 0) }}
        </span>
    </div>
</div>

{{-- Tenure --}}
<div class="mb-8">
    <div class="flex justify-between items-center mb-3">
        <label class="text-sm font-semibold text-gray-800">Loan Tenure</label>
        <span id="loan-tenure-display" class="text-primary font-bold text-lg font-display">
            {{ $personalProduct ? $personalProduct->minimum_duration_month : 3 }} Months
        </span>
    </div>
    <input type="range" id="loan-tenure-slider" class="range-slider" 
        min="{{ $personalProduct ? $personalProduct->minimum_duration_month : 3 }}" 
        max="{{ $personalProduct ? $personalProduct->duration_months : 36 }}" 
        value="{{ $personalProduct ? $personalProduct->minimum_duration_month : 3 }}" 
        step="1">
    <div class="flex justify-between text-xs text-gray-500 mt-1">
        <span id="loan-tenure-min-label">
            {{ $personalProduct ? $personalProduct->minimum_duration_month : 3 }} Months
        </span>
        <span id="loan-tenure-max-label">
            {{ $personalProduct ? $personalProduct->duration_months : 36 }} Months
        </span>
    </div>
</div>



                    {{-- Results --}}

                    <div class="grid grid-cols-3 gap-3 mb-6">

                        <div class="bg-primary-xlight rounded-xl p-4 text-center">

                            <p class="text-xs text-gray-500 mb-1">Monthly</p>

                            <p id="monthly-payment" class="text-primary font-bold font-display text-base">₦4,667</p>

                        </div>

                        <div class="bg-gray-50 rounded-xl p-4 text-center">

                            <p class="text-xs text-gray-500 mb-1">Total</p>

                            <p id="total-payment" class="text-dark font-bold font-display text-base">₦56,000</p>

                        </div>

                        <div class="bg-gray-50 rounded-xl p-4 text-center">

                            <p class="text-xs text-gray-500 mb-1">Interest</p>

                            <p id="total-interest" class="text-dark font-bold font-display text-base">₦6,000</p>

                        </div>

                    </div>



                    <a href="#apply" class="btn-primary w-full justify-center text-center" style="display:flex;">

                        Apply Now <i class="fas fa-arrow-right text-sm"></i>

                    </a>

                    <p class="text-center text-xs text-gray-500 mt-3">* Estimated values. Actual rates may vary.</p>

                </div>

            </div>



        </div>

    </div>

</section>





{{-- ============================================================

     STATS SECTION

============================================================ --}}

<section class="stats-section section-pad-sm" id="stats">

    <div class="container relative z-10">

        <div class="grid grid-cols-2 lg:grid-cols-4">

            <div class="stat-card">

                <p class="stat-number counter" data-target="5000" data-suffix="+">0</p>

                <p class="stat-label">Happy Borrowers</p>

            </div>

            <div class="stat-card">

                <p class="stat-number counter" data-target="98" data-suffix="%">0</p>

                <p class="stat-label">Approval Rate</p>

            </div>

            <div class="stat-card">

                <p class="stat-number counter" data-target="5" data-suffix=" Days">0</p>

                <p class="stat-label">Avg. Disbursement</p>

            </div>

            <div class="stat-card">

                <p class="stat-number counter" data-target="150" data-suffix="M+">0</p>

                <p class="stat-label">Loans Disbursed (₦)</p>

            </div>

        </div>

    </div>

</section>





{{-- ============================================================

     LOAN TYPES SECTION

============================================================ --}}

<section class="section-pad bg-soft section-decor" id="personal-loan">

    <div class="container">

        <div class="section-decor-dot"></div>

        <div class="text-center mb-14">

            <div class="section-badge"><i class="fas fa-hand-holding-dollar text-xs"></i> Our Products</div>

            <h2 class="section-title mb-4">Loan Products Designed<br>For You</h2>

            <p class="section-subtitle mx-auto">Pick the right loan for your situation — personal expenses or business growth, we have a tailored solution.</p>

        </div>



        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">



{{-- Personal Loan --}}

<div class="loan-type-card personal" id="personal-loan">

    <div class="loan-badge">Personal Loan</div>

    <h3 class="font-display font-bold text-white text-3xl mb-3">

        Up to ₦{{ $personalProduct ? number_format($personalProduct->maximum_amount, 0) : '200,000' }}

    </h3>

    <p class="text-gray-300 text-sm leading-relaxed mb-8">

        Get quick personal financing for medical bills, school fees, rent, travel, or any personal need — with a simple application and fast disbursement. We approve it fast and send the money to you without any delay.

    </p>

    <div class="grid grid-cols-2 gap-4 mb-8">

        <div class="bg-white/10 rounded-xl p-4">

            <p class="text-white/70 text-xs mb-1">Interest Rate</p>

            <p class="text-white font-bold font-display text-xl">

                From {{ $personalProduct ? number_format($personalProduct->interest_rate, 0) : '20' }}% p.a.

            </p>

        </div>

        <div class="bg-white/10 rounded-xl p-4">

            <p class="text-white/70 text-xs mb-1">Tenure</p>

            <p class="text-white font-bold font-display text-xl">

              {{ $personalProduct ? $personalProduct->minimum_duration_month : 6 }} – {{ $personalProduct ? $personalProduct->duration_months : '36' }} Months

            </p>

        </div>

        <div class="bg-white/10 rounded-xl p-4">

            <p class="text-white/70 text-xs mb-1">Min. Amount</p>

            <p class="text-white font-bold font-display text-xl">

                ₦{{ $personalProduct ? number_format($personalProduct->minimum_amount, 0) : '50,000' }}

            </p>

        </div>

        <div class="bg-white/10 rounded-xl p-4">

            <p class="text-white/70 text-xs mb-1">Processing Time</p>

            <p class="text-white font-bold font-display text-xl">2 – 5 Days</p>

        </div>

    </div>

    <div class="space-y-1 mb-8">

        <div class="loan-feature"><i class="fas fa-check-circle"></i> No collateral required for amounts under ₦50,000</div>

        <div class="loan-feature"><i class="fas fa-check-circle"></i> Salary earners & self-employed welcome</div>

        <div class="loan-feature"><i class="fas fa-check-circle"></i> Disbursed directly to your bank account</div>

        <div class="loan-feature"><i class="fas fa-check-circle"></i> Flexible repayment schedule</div>

    </div>

    <a href="#apply" class="btn-white">

        Apply for Personal Loan <i class="fas fa-arrow-right text-sm"></i>

    </a>

</div>



{{-- SME Loan --}}

<div class="loan-type-card sme" id="sme-loan">

    <div class="loan-badge">SME Business Loan</div>

    <h3 class="font-display font-bold text-white text-3xl mb-3">

        Up to ₦{{ $smeProduct ? number_format($smeProduct->maximum_amount, 0) : '300,000' }}

    </h3>

    <p class="text-gray-300 text-sm leading-relaxed mb-8">

        Power your small or medium enterprise with working capital, equipment purchase, or business expansion funding — designed specifically for Nigerian entrepreneurs.

    </p>

    <div class="grid grid-cols-2 gap-4 mb-8">

        <div class="bg-white/10 rounded-xl p-4">

            <p class="text-white/70 text-xs mb-1">Interest Rate</p>

            <p class="text-white font-bold font-display text-xl">

                From {{ $smeProduct ? number_format($smeProduct->interest_rate, 0) : '20' }}% p.a.

            </p>

        </div>

        <div class="bg-white/10 rounded-xl p-4">

            <p class="text-white/70 text-xs mb-1">Tenure</p>

            <p class="text-white font-bold font-display text-xl">

               {{ $smeProduct ? $smeProduct->minimum_duration_month : 6 }} – {{ $smeProduct ? $smeProduct->duration_months : '36' }} Months

            </p>

        </div>

        <div class="bg-white/10 rounded-xl p-4">

            <p class="text-white/70 text-xs mb-1">Min. Amount</p>

            <p class="text-white font-bold font-display text-xl">

                ₦{{ $smeProduct ? number_format($smeProduct->minimum_amount, 0) : '50,000' }}

            </p>

        </div>

        <div class="bg-white/10 rounded-xl p-4">

            <p class="text-white/70 text-xs mb-1">Processing Time</p>

            <p class="text-white font-bold font-display text-xl">2 – 5 Days</p>

        </div>

    </div>

    <div class="space-y-1 mb-8">

        <div class="loan-feature"><i class="fas fa-check-circle"></i> Business registration documents accepted</div>

        <div class="loan-feature"><i class="fas fa-check-circle"></i> Co-signer / collateral may be required</div>

        <div class="loan-feature"><i class="fas fa-check-circle"></i> Dedicated SME loan officer assigned</div>

        <div class="loan-feature"><i class="fas fa-check-circle"></i> Multiple disbursement options</div>

    </div>

    <a href="#apply" class="btn-white">

        Apply for SME Loan <i class="fas fa-arrow-right text-sm"></i>

    </a>

</div>



        </div>

    </div>

</section>





{{-- ============================================================

     HOW IT WORKS

============================================================ --}}

<section class="section-pad bg-gray-50" id="how-it-works">

    <div class="container">

        <div class="text-center mb-14">

            <div class="section-badge"><i class="fas fa-list-ol text-xs"></i> Simple Process</div>

            <h2 class="section-title mb-4">Get Your Loan in<br>4 Simple Steps</h2>

            <p class="section-subtitle mx-auto">Our streamlined process means less paperwork and faster money in your account.</p>

        </div>



        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">



            @php

            $steps = [

                ['icon' => 'fa-file-alt',       'title' => 'Fill Application',     'desc' => 'Complete our simple online form in under 5 minutes. No complex paperwork, no branch visit needed.'],

                ['icon' => 'fa-file-magnifying-glass', 'title' => 'Document Review', 'desc' => 'Our loan officers verify your documents and assess your eligibility swiftly and fairly.'],

                ['icon' => 'fa-circle-check',   'title' => 'Loan Approval',        'desc' => 'Receive a decision within 2 to 5 business days. We notify you via SMS and email once your loan is approved.'],

                ['icon' => 'fa-building-columns','title' => 'Get Disbursed',        'desc' => 'Funds transferred directly to your Polaris or Zenith Bank account after approval.'],

            ];

            @endphp



            @foreach($steps as $i => $step)

            <div class="step-card relative bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg hover:border-primary-light transition-all duration-300 group">

                <div class="step-number group-hover:bg-primary group-hover:text-white group-hover:border-primary transition-all duration-300">

                    {{ $i + 1 }}

                </div>

                <div class="w-14 h-14 bg-primary-light rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-primary transition-all duration-300">

                    <i class="fas {{ $step['icon'] }} text-xl text-primary-dark group-hover:text-white transition-all duration-300"></i>

                </div>

                <h4 class="font-display font-bold text-dark text-lg mb-3">{{ $step['title'] }}</h4>

                <p class="text-gray-500 text-sm leading-relaxed">{{ $step['desc'] }}</p>



                @if($i < 3)

                <div class="hidden lg:block step-connector"></div>

                @endif

            </div>

            @endforeach



        </div>

    </div>

</section>




{{-- ============================================================

     ELIGIBILITY REQUIREMENTS

============================================================ --}}

<section class="section-pad bg-primary-xlight section-decor">

    <div class="container">

        <div class="section-decor-dot"></div>

        <div class="text-center mb-14">

            <div class="section-badge"><i class="fas fa-clipboard-check text-xs"></i> Requirements</div>

            <h2 class="section-title mb-4">Who Can Apply?</h2>

            <p class="section-subtitle mx-auto">Basic requirements to qualify for an AIP loan. Most Nigerians with stable income are eligible.</p>

        </div>



        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">



            @php

            $requirements = [

                ['icon' => 'fa-id-card',        'color' => 'bg-blue-50 text-blue-600',   'title' => 'Valid ID',             'desc' => 'Government-issued ID — National ID Card, Voter\'s Card, International Passport, or Driver\'s License.'],

                ['icon' => 'fa-calendar-check', 'color' => 'bg-purple-50 text-purple-600','title' => 'Age 21 – 60 Years',   'desc' => 'Applicants must be between 21 and 60 years of age at the time of loan application.'],

                ['icon' => 'fa-money-bill-wave', 'color' => 'bg-green-50 text-green-600', 'title' => 'Stable Income',        'desc' => 'Verifiable monthly income — salary slips, bank statements, or business revenue records.'],

                ['icon' => 'fa-building-columns','color' => 'bg-orange-50 text-orange-600','title' => 'Bank Account',        'desc' => 'Active Polaris Bank or Zenith Bank account for loan disbursement and repayment.'],

                ['icon' => 'fa-phone',           'color' => 'bg-teal-50 text-teal-600',   'title' => 'Nigerian Phone Number','desc' => 'Active Nigerian mobile number linked to your BVN for OTP verification.'],

                ['icon' => 'fa-file-invoice',    'color' => 'bg-red-50 text-red-600',     'title' => 'BVN Verified',         'desc' => 'Bank Verification Number (BVN) is mandatory for identity verification and credit assessment.'],

            ];

            @endphp



            @foreach($requirements as $req)

            <div class="service-card group">

                <div class="service-icon {{ $req['color'] }} group-hover:!bg-primary group-hover:!text-white">

                    <i class="fas {{ $req['icon'] }}"></i>

                </div>

                <h4 class="font-display font-bold text-dark text-lg mb-2">{{ $req['title'] }}</h4>

                <p class="text-gray-500 text-sm leading-relaxed">{{ $req['desc'] }}</p>

            </div>

            @endforeach



        </div>



        <div class="text-center mt-10">

            <a href="#apply" class="btn-primary">

                Check My Eligibility <i class="fas fa-arrow-right text-sm"></i>

            </a>

        </div>

    </div>

</section>


{{-- ============================================================

     WHY CHOOSE US

============================================================ --}}

<section class="section-pad bg-alt section-decor" id="about">

    <div class="container">

        <div class="section-decor-dot"></div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">



            {{-- Left Image Placeholder --}}

            <div class="relative">

                <div class="rounded-3xl overflow-hidden aspect-[4/3] bg-gradient-to-br from-primary-light to-primary-xlight flex items-center justify-center">

                    <div class="text-center">

                        <div class="w-24 h-24 bg-primary/20 rounded-full flex items-center justify-center mx-auto mb-4">

                            <i class="fas fa-handshake text-4xl text-primary"></i>

                        </div>

                        <p class="text-primary-dark font-semibold text-lg">Your Trusted Loan Partner</p>

                        <p class="text-gray-500 text-sm mt-1">Since 2018</p>

                    </div>

                </div>



                {{-- Floating Badges --}}

                <div class="absolute -top-4 -right-4 bg-white rounded-2xl shadow-xl p-4 border border-gray-100">

                    <div class="flex items-center gap-3">

                        <div class="w-10 h-10 bg-primary-light rounded-xl flex items-center justify-center">

                            <i class="fas fa-shield-halved text-primary"></i>

                        </div>

                        <div>

                            <p class="text-xs text-gray-500">CBN Compliant</p>

                            <p class="text-sm font-bold text-dark">100% Secure</p>

                        </div>

                    </div>

                </div>



                <div class="absolute -bottom-4 -left-4 bg-white rounded-2xl shadow-xl p-4 border border-gray-100">

                    <div class="flex items-center gap-3">

                        <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center">

                            <i class="fas fa-clock text-primary"></i>

                        </div>

                        <div>

                            <p class="text-xs text-gray-500">Quick Approval</p>

                            <p class="text-sm font-bold text-dark">Within 2 – 5 Days</p>

                        </div>

                    </div>

                </div>

            </div>



            {{-- Right Content --}}

            <div>

                <div class="section-badge"><i class="fas fa-star text-xs"></i> Why Choose AIP</div>

                <h2 class="section-title mb-4">We Make Borrowing<br>Simple & Transparent</h2>

                <p class="section-subtitle mb-10">

                    African Investment Partners has been connecting Nigerians with affordable credit since 2018. We work with two of Nigeria's most trusted banks to give you the best rates.

                </p>



                <div class="space-y-2">

                    <div class="why-card">

                        <div class="why-icon"><i class="fas fa-bolt"></i></div>

                        <div>

                            <h4 class="font-semibold text-dark mb-1">Fast Approval Process</h4>

                            <p class="text-gray-500 text-sm leading-relaxed">Our verification system processes your application efficiently, giving you a decision within 2 to 5 business days.</p>

                        </div>

                    </div>

                    <div class="why-card">

                        <div class="why-icon"><i class="fas fa-eye"></i></div>

                        <div>

                            <h4 class="font-semibold text-dark mb-1">100% Transparent Fees</h4>

                            <p class="text-gray-500 text-sm leading-relaxed">No hidden charges, no surprises. Every fee is clearly stated before you sign anything.</p>

                        </div>

                    </div>

                    <div class="why-card">

                        <div class="why-icon"><i class="fas fa-user-shield"></i></div>

                        <div>

                            <h4 class="font-semibold text-dark mb-1">Data Security Guaranteed</h4>

                            <p class="text-gray-500 text-sm leading-relaxed">Your personal and financial data is protected with bank-grade encryption and strict privacy policies.</p>

                        </div>

                    </div>

                    <div class="why-card">

                        <div class="why-icon"><i class="fas fa-headset"></i></div>

                        <div>

                            <h4 class="font-semibold text-dark mb-1">Dedicated Support Team</h4>

                            <p class="text-gray-500 text-sm leading-relaxed">Our loan officers are available Monday to Friday to guide you through every step of your loan journey.</p>

                        </div>

                    </div>

                    <div class="why-card">

                        <div class="why-icon"><i class="fas fa-repeat"></i></div>

                        <div>

                            <h4 class="font-semibold text-dark mb-1">Flexible Repayment Plans</h4>

                            <p class="text-gray-500 text-sm leading-relaxed">Choose a repayment schedule that fits your income — weekly, bi-weekly, or monthly installments available.</p>

                        </div>

                    </div>

                </div>

            </div>



        </div>

    </div>

</section>









{{-- ============================================================

     TESTIMONIALS

============================================================ --}}

<section class="section-pad bg-soft section-decor">

    <div class="container">

        <div class="section-decor-dot"></div>

        <div class="text-center mb-14">

            <div class="section-badge"><i class="fas fa-quote-left text-xs"></i> Testimonials</div>

            <h2 class="section-title mb-4">What Our Customers Say</h2>

            <p class="section-subtitle mx-auto">Thousands of Nigerians have trusted AIP for their loan needs. Here are some of their stories.</p>

        </div>



        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">



            @php

            $testimonials = [

                ['name' => 'Adaeze Okonkwo',    'role' => 'Petty Trader, Lagos',         'rating' => 5, 'text' => 'I needed money urgently to restock my shop before the festive season. AIP approved my loan within a few days and the money was in my account soon after. Best experience ever!'],

                ['name' => 'Emeka Chukwuemeka', 'role' => 'Civil Servant, Abuja',         'rating' => 5, 'text' => 'The process was super straightforward. I filled the form online, submitted my documents, and got a call from my loan officer the next morning. No stress at all!'],

                ['name' => 'Fatima Al-Hassan',  'role' => 'Fashion Designer, Kano',       'rating' => 5, 'text' => 'The SME loan helped me buy new sewing machines for my fashion business. The interest rate was very fair and the repayment plan was flexible. Highly recommend AIP!'],

                ['name' => 'Chidi Nwosu',       'role' => 'Software Developer, Port Harcourt','rating' => 5, 'text' => 'I was skeptical at first, but AIP delivered exactly as promised. Transparent fees, no hidden charges. My loan was disbursed to my Zenith Bank account within days!'],

                ['name' => 'Blessing Ikenna',   'role' => 'Nurse, Enugu',                 'rating' => 5, 'text' => 'Used AIP for my house rent when I was in a tight spot. The online application took only 3 minutes and the customer service team was very responsive throughout.'],

                ['name' => 'Ibrahim Musa',      'role' => 'Restaurant Owner, Kaduna',     'rating' => 5, 'text' => 'African Investment Partners gave my food business the boost it needed. The SME loan process was smooth and the dedicated loan officer was always reachable.'],

            ];

            @endphp



            @foreach($testimonials as $t)

            <div class="testimonial-card">

                <div class="star-rating mb-4">

                    @for($i = 0; $i < $t['rating']; $i++)

                        <i class="fas fa-star"></i>

                    @endfor

                </div>

                <p class="text-gray-800 text-sm leading-relaxed mb-6">"{{ $t['text'] }}"</p>

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center font-bold text-primary-dark text-sm">

                        {{ strtoupper(substr($t['name'], 0, 1)) }}

                    </div>

                    <div>

                        <p class="font-semibold text-dark text-sm">{{ $t['name'] }}</p>

                        <p class="text-gray-500 text-xs">{{ $t['role'] }}</p>

                    </div>

                </div>

            </div>

            @endforeach



        </div>

    </div>

</section>





{{-- ============================================================

     APPLY NOW SECTION

============================================================ --}}

<section class="section-pad bg-gray-50" id="apply">

    <div class="container">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">



            {{-- Left Content --}}

            <div>

                <div class="section-badge"><i class="fas fa-paper-plane text-xs"></i> Apply Now</div>

                <h2 class="section-title mb-4">Start Your Loan<br>Application Today</h2>

                <p class="section-subtitle mb-8">Fill in your details below and our team will contact you within 2 business hours to guide you through the rest.</p>



                <div class="space-y-4">

                    <div class="info-strip">

                        <div class="w-10 h-10 bg-primary-light rounded-xl flex items-center justify-center flex-shrink-0">

                            <i class="fas fa-clock text-primary text-sm"></i>

                        </div>

                        <div>

                            <h5 class="font-semibold text-dark text-sm mb-1">Quick Response</h5>

                            <p class="text-gray-500 text-xs leading-relaxed">Our loan officers will call you back within 2 business hours of submitting your application.</p>

                        </div>

                    </div>

                    <div class="info-strip">

                        <div class="w-10 h-10 bg-primary-light rounded-xl flex items-center justify-center flex-shrink-0">

                            <i class="fas fa-lock text-primary text-sm"></i>

                        </div>

                        <div>

                            <h5 class="font-semibold text-dark text-sm mb-1">Your Data Is Safe</h5>

                            <p class="text-gray-500 text-xs leading-relaxed">We use bank-grade SSL encryption to protect all your personal and financial information.</p>

                        </div>

                    </div>

                    <div class="info-strip">

                        <div class="w-10 h-10 bg-primary-light rounded-xl flex items-center justify-center flex-shrink-0">

                            <i class="fas fa-ban text-primary text-sm"></i>

                        </div>

                        <div>

                            <h5 class="font-semibold text-dark text-sm mb-1">No Obligation</h5>

                            <p class="text-gray-500 text-xs leading-relaxed">Submitting this form doesn't commit you to anything. You can decline the offer at any stage.</p>

                        </div>

                    </div>

                </div>

            </div>



{{-- Application Form --}}

<div class="rounded-3xl p-8 shadow-2xl overflow-hidden" style="background-color: #243447;">

    <div class="flex items-center gap-3 mb-6">

        <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: rgba(186, 232, 45, 0.15);">

            <i class="fas fa-file-signature text-primary"></i>

        </div>

        <h3 class="font-display font-bold text-white text-xl">Pre Loan Application Form</h3>

    </div>



    <form method="POST" action="{{ route('loan.application.store') }}" id="loan-application-form" class="space-y-5">

        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <div>

                <label class="block text-sm font-semibold text-white mb-1.5">First Name *</label>

                <input type="text" name="first_name" placeholder="John" required class="contact-form-input" value="{{ old('first_name') }}">

            </div>

            <div>

                <label class="block text-sm font-semibold text-white mb-1.5">Last Name *</label>

                <input type="text" name="last_name" placeholder="Doe" required class="contact-form-input" value="{{ old('last_name') }}">

            </div>

        </div>



        <div>

            <label class="block text-sm font-semibold text-white mb-1.5">Email Address *</label>

            <input type="email" name="email" placeholder="john@example.com" required class="contact-form-input" value="{{ old('email') }}">

        </div>



        <div>

            <label class="block text-sm font-semibold text-white mb-1.5">Phone Number *</label>

            <input type="tel" name="phone" placeholder="+234 800 000 0000" required class="contact-form-input" value="{{ old('phone') }}">

        </div>



        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <div>

                <label class="block text-sm font-semibold text-white mb-1.5">Loan Type *</label>

                <select name="loan_type" id="loan_type" required class="contact-form-input">

                    <option value="">Select Type</option>

                    <option value="personal" {{ old('loan_type') == 'personal' ? 'selected' : '' }}>Personal Loan</option>

                    <option value="sme"      {{ old('loan_type') == 'sme' ? 'selected' : '' }}>SME Business Loan</option>

                </select>

            </div>

            <div>

                <label class="block text-sm font-semibold text-white mb-1.5">Loan Amount (₦) *</label>

                <input type="number" name="loan_amount" id="loan_amount" placeholder="50000" min="50000" max="300000" required class="contact-form-input" value="{{ old('loan_amount') }}">

            </div>

        </div>



        <div>

            <label class="block text-sm font-semibold text-white mb-1.5">Preferred Bank *</label>

            <select name="preferred_bank" required class="contact-form-input">

                <option value="">Select Bank</option>

                <option value="polaris" {{ old('preferred_bank') == 'polaris' ? 'selected' : '' }}>Polaris Bank</option>

                <option value="zenith"  {{ old('preferred_bank') == 'zenith' ? 'selected' : '' }}>Zenith Bank</option>

            </select>

        </div>



        <div>

            <label class="block text-sm font-semibold text-white mb-1.5">Loan Purpose *</label>

            <textarea name="loan_purpose" rows="3" placeholder="Briefly describe what you need the loan for..." required class="contact-form-input resize-none">{{ old('purpose') }}</textarea>

        </div>



        <button type="submit" class="btn-primary w-full justify-center" style="display:flex;">

            Submit Application <i class="fas fa-paper-plane text-sm"></i>

        </button>



        <p class="text-center text-xs text-white">

            By submitting this form, you agree to our

            <a href="#" class="text-primary hover:underline">Privacy Policy</a> and

            <a href="#" class="text-primary hover:underline">Terms of Use</a>.

        </p>

    </form>

</div>



        </div>

    </div>

</section>





{{-- ============================================================

     FAQ SECTION

============================================================ --}}

{{-- FAQ Section --}}
<section id="faq" class="py-20 bg-white">
    <div class="container mx-auto px-4 max-w-4xl">

        {{-- Section Header --}}
        <div class="text-center mb-12">
            <span class="inline-block bg-primary/10 text-primary font-semibold text-xs tracking-widest uppercase px-4 py-2 rounded-full mb-4">FAQ</span>
            <h2 class="font-display font-bold text-3xl sm:text-4xl text-dark mb-4">Frequently Asked Questions</h2>
            <p class="text-gray-500 text-lg max-w-2xl mx-auto">Everything you need to know about our loan products and process.</p>
        </div>

        {{-- FAQ Items — dynamically loaded --}}
        <div id="faq-container" class="space-y-4">
            {{-- Loading state --}}
            <div id="faq-loading" class="space-y-4">
                @for($i = 0; $i < 4; $i++)
                <div class="animate-pulse bg-gray-100 rounded-2xl h-16"></div>
                @endfor
            </div>

            {{-- Error state --}}
            <div id="faq-error" style="display:none;" class="text-center py-8">
                <p class="text-gray-400 text-sm">Could not load FAQs. Please refresh the page.</p>
            </div>

            {{-- FAQ list --}}
            <div id="faq-list" style="display:none;" class="space-y-4"></div>
        </div>

    </div>
</section>

<script>
(function() {
    async function loadFaqs() {
        const loading = document.getElementById('faq-loading');
        const error   = document.getElementById('faq-error');
        const list    = document.getElementById('faq-list');

        try {
            const res  = await fetch('/api/faqs');
            const data = await res.json();

            loading.style.display = 'none';

            if (!data.success || !data.data.length) {
                error.style.display = 'block';
                return;
            }

            data.data.forEach((faq, index) => {
                const item = document.createElement('div');
                item.className = 'faq-item border border-gray-200 rounded-2xl overflow-hidden';
                item.innerHTML = `
                    <button
                        class="w-full flex items-center justify-between px-6 py-5 text-left bg-white hover:bg-gray-50 transition-colors duration-200 faq-trigger"
                        data-index="${index}"
                        aria-expanded="false">
                        <span class="font-semibold text-dark text-sm sm:text-base pr-4">${escapeHtml(faq.question)}</span>
                        <span class="flex-shrink-0 w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center transition-transform duration-300 faq-icon">
                            <i class="fas fa-chevron-down text-primary text-xs"></i>
                        </span>
                    </button>
                    <div class="faq-answer overflow-hidden max-h-0 transition-all duration-300 ease-in-out">
                        <div class="px-6 pb-5 pt-2 text-gray-500 text-sm leading-relaxed border-t border-gray-100">
                            ${escapeHtml(faq.answer)}
                        </div>
                    </div>
                `;
                list.appendChild(item);
            });

            // Accordion logic
            list.querySelectorAll('.faq-trigger').forEach(btn => {
                btn.addEventListener('click', function() {
                    const item    = this.closest('.faq-item');
                    const answer  = item.querySelector('.faq-answer');
                    const icon    = item.querySelector('.faq-icon');
                    const isOpen  = this.getAttribute('aria-expanded') === 'true';

                    // Close all
                    list.querySelectorAll('.faq-item').forEach(i => {
                        i.querySelector('.faq-answer').style.maxHeight = '0px';
                        i.querySelector('.faq-trigger').setAttribute('aria-expanded', 'false');
                        i.querySelector('.faq-icon').style.transform = 'rotate(0deg)';
                        i.querySelector('.faq-item')?.classList?.remove('border-primary');
                    });

                    // Open clicked
                    if (!isOpen) {
                        const content       = answer.querySelector('div');
                        answer.style.maxHeight = content.scrollHeight + 'px';
                        this.setAttribute('aria-expanded', 'true');
                        icon.style.transform   = 'rotate(180deg)';
                    }
                });
            });

            list.style.display = 'block';

        } catch (e) {
            loading.style.display = 'none';
            error.style.display   = 'block';
        }
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', loadFaqs);
    } else {
        loadFaqs();
    }
})();
</script>





{{-- ============================================================

     CTA BANNER

============================================================ --}}

<section class="cta-section section-pad-sm">

    <div class="container relative z-10">

        <div class="text-center max-w-3xl mx-auto">

            <h2 class="font-display font-extrabold text-white text-4xl mb-4" style="letter-spacing:-0.02em;">

                Ready to Get Your Loan?

            </h2>

            <p class="text-white/80 text-lg mb-8 leading-relaxed">

                Join thousands of Nigerians who have trusted African Investment Partners for fast, transparent, and affordable credit.

            </p>

            <div class="flex flex-wrap items-center justify-center gap-4">

                <a href="#apply" class="btn-white">

                    Apply Now — It's Free <i class="fas fa-arrow-right text-sm"></i>

                </a>

                <a href="#how-it-works" class="btn-outline-white">

                    <i class="fas fa-play text-xs"></i> Learn How It Works

                </a>

            </div>

            <p class="text-white/60 text-sm mt-6">No commitment required · Response within 2 to 5 days · 100% online</p>

        </div>

    </div>

</section>





{{-- ============================================================

     CONTACT SECTION

============================================================ --}}

<section class="section-pad bg-gray-50" id="contact">

    <div class="container">

        <div class="text-center mb-14">

            <div class="section-badge"><i class="fas fa-envelope text-xs"></i> Contact Us</div>

            <h2 class="section-title mb-4">Get In Touch With Us</h2>

            <p class="section-subtitle mx-auto">Our friendly team is here to help. Reach us through any of the channels below.</p>

        </div>



        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">



            {{-- Contact Info Cards --}}

            <div class="space-y-6 lg:col-span-1">

                <div class="service-card group text-center">

                    <div class="service-icon mx-auto group-hover:bg-primary group-hover:text-white">

                        <i class="fas fa-location-dot"></i>

                    </div>

                    <h4 class="font-semibold text-dark mb-1">Our Office</h4>

                    <p class="text-gray-500 text-sm">Lagos, Nigeria<br>Registered in Nigeria</p>

                </div>

                <div class="service-card group text-center">

                    <div class="service-icon mx-auto group-hover:bg-primary group-hover:text-white">

                        <i class="fas fa-phone"></i>

                    </div>

                    <h4 class="font-semibold text-dark mb-1">Call Us</h4>

                    <a href="tel:+2348000000000" class="text-primary hover:text-primary-dark text-sm font-medium transition">+234 800 000 0000</a>

                    <p class="text-gray-500 text-xs mt-1">Mon – Fri, 8am – 6pm</p>

                </div>

                <div class="service-card group text-center">

                    <div class="service-icon mx-auto group-hover:bg-primary group-hover:text-white">

                        <i class="fas fa-envelope"></i>

                    </div>

                    <h4 class="font-semibold text-dark mb-1">Email Us</h4>

                    <a href="mailto:info@aip-loans.com" class="text-primary hover:text-primary-dark text-sm font-medium transition">info@aip-loans.com</a>

                    <p class="text-gray-500 text-xs mt-1">We reply within 24 hours</p>

                </div>

            </div>



            {{-- Contact Form --}}

            <div class="lg:col-span-2 form-panel">

                <h3 class="font-display font-bold text-dark text-xl mb-6">Send Us a Message</h3>



                {{-- Success / Error message container --}}

                <div id="contact-form-alert" class="hidden px-4 py-3 rounded-xl mb-5 text-sm"></div>



                <form action="{{ route('contact.store') }}" method="POST" id="contact-form" class="space-y-5">

                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>

                            <label class="block text-sm font-semibold text-gray-800 mb-1.5">Full Name *</label>

                            <input type="text" name="name" placeholder="Your name" required class="contact-form-input">

                            <p class="text-red-500 text-xs mt-1 field-error" data-field="name"></p>

                        </div>

                        <div>

                            <label class="block text-sm font-semibold text-gray-800 mb-1.5">Email Address *</label>

                            <input type="email" name="email" placeholder="your@email.com" required class="contact-form-input">

                            <p class="text-red-500 text-xs mt-1 field-error" data-field="email"></p>

                        </div>

                    </div>



                    <div>

                        <label class="block text-sm font-semibold text-gray-800 mb-1.5">Contact Number *</label>

                        <input type="tel" name="phone" placeholder="+234 800 000 0000" required class="contact-form-input">

                        <p class="text-red-500 text-xs mt-1 field-error" data-field="phone"></p>

                    </div>



                    <div>

                        <label class="block text-sm font-semibold text-gray-800 mb-1.5">Message *</label>

                        <textarea name="message" rows="5" placeholder="Write your message here..." required class="contact-form-input resize-none"></textarea>

                        <p class="text-red-500 text-xs mt-1 field-error" data-field="message"></p>

                    </div>



                   <button type="submit" id="contact-submit-btn" class="btn-primary">

    <span id="contact-btn-text">Send Message</span>

    <i class="fas fa-paper-plane text-sm" id="contact-btn-icon"></i>

    <i class="fas fa-spinner fa-spin text-sm" id="contact-btn-spinner" style="display:none;"></i>

</button>

                </form>

            </div>



        </div>

    </div>

</section>



@once

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ============================================================
    // DATABASE SE VALUES LE RAHE HAIN
    // ============================================================
    const ratesData = document.getElementById('loan-rates-data');
    
    if (!ratesData) {
        console.error('Loan rates data not found! Using default values.');
    }

    // Personal Loan Values - Database se ya default
    const personal = {
        rate: ratesData ? parseFloat(ratesData.dataset.personalRate) || 20 : 20,
        min: ratesData ? parseInt(ratesData.dataset.personalMin) || 50000 : 50000,
        max: ratesData ? parseInt(ratesData.dataset.personalMax) || 200000 : 200000,
        minDuration: ratesData ? parseInt(ratesData.dataset.personalMinDuration) || 3 : 3,
        maxDuration: ratesData ? parseInt(ratesData.dataset.personalMaxDuration) || 36 : 36
    };

    // SME Loan Values - Database se ya default
    const sme = {
        rate: ratesData ? parseFloat(ratesData.dataset.smeRate) || 20 : 20,
        min: ratesData ? parseInt(ratesData.dataset.smeMin) || 50000 : 50000,
        max: ratesData ? parseInt(ratesData.dataset.smeMax) || 300000 : 300000,
        minDuration: ratesData ? parseInt(ratesData.dataset.smeMinDuration) || 3 : 3,
        maxDuration: ratesData ? parseInt(ratesData.dataset.smeMaxDuration) || 36 : 36
    };

    console.log('📊 Personal Loan:', personal);
    console.log('📊 SME Loan:', sme);

    // ============================================================
    // ELEMENTS
    // ============================================================
    const form = document.getElementById('contact-form');
    const alertBox = document.getElementById('contact-form-alert');
    const submitBtn = document.getElementById('contact-submit-btn');
    const btnText = document.getElementById('contact-btn-text');
    const btnIcon = document.getElementById('contact-btn-icon');
    const btnSpinner = document.getElementById('contact-btn-spinner');

    // Calculator Elements
    const amountSlider = document.getElementById('loan-amount-slider');
    const tenureSlider = document.getElementById('loan-tenure-slider');
    const amountDisplay = document.getElementById('loan-amount-display');
    const tenureDisplay = document.getElementById('loan-tenure-display');
    const monthlyPayment = document.getElementById('monthly-payment');
    const totalPayment = document.getElementById('total-payment');
    const totalInterest = document.getElementById('total-interest');
    const amountMaxLabel = document.getElementById('loan-amount-max-label');

    let currentType = 'personal'; // 'personal' | 'sme'

    // ============================================================
    // FORMAT CURRENCY
    // ============================================================
    function formatCurrency(amount) {
        return '₦' + Number(amount).toLocaleString('en-US', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
    }

    // ============================================================
    // GET CURRENT VALUES
    // ============================================================
    function getCurrentValues() {
        return currentType === 'personal' ? personal : sme;
    }

    // ============================================================
    // UPDATE SLIDERS - DATABASE SE VALUES SET KARO
    // ============================================================
    function updateSliders() {
        const values = getCurrentValues();
        
        // Amount slider
        amountSlider.min = values.min;
        amountSlider.max = values.max;
        amountSlider.step = 1000;
        amountSlider.value = values.min;
        
        // Tenure slider
        tenureSlider.min = values.minDuration;
        tenureSlider.max = values.maxDuration;
        tenureSlider.step = 1;
        tenureSlider.value = values.minDuration;
        
        // Labels update
        if (amountMaxLabel) {
            amountMaxLabel.textContent = formatCurrency(values.max);
        }
        const rangeLabels = document.querySelectorAll('.range-labels span');
        if (rangeLabels.length >= 2) {
            rangeLabels[0].textContent = formatCurrency(values.min);
        }
        
        // Display update
        if (amountDisplay) {
            amountDisplay.textContent = formatCurrency(values.min);
        }
        if (tenureDisplay) {
            tenureDisplay.textContent = values.minDuration + ' Months';
        }
        
        // Calculate
        calculateMonthlyPayment();
    }

    // ============================================================
    // CALCULATE MONTHLY PAYMENT
    // ============================================================
    function calculateMonthlyPayment() {
        const values = getCurrentValues();
        const loanAmount = parseInt(amountSlider.value);
        const tenureMonths = parseInt(tenureSlider.value);
        const rate = values.rate / 100;

        const monthlyRate = rate / 12;
        let emi = 0;

        if (monthlyRate === 0) {
            emi = loanAmount / tenureMonths;
        } else {
            const factor = Math.pow(1 + monthlyRate, tenureMonths);
            emi = (loanAmount * monthlyRate * factor) / (factor - 1);
        }

        const totalRepayment = emi * tenureMonths;
        const totalInterestAmount = totalRepayment - loanAmount;

        if (monthlyPayment) monthlyPayment.textContent = formatCurrency(emi);
        if (totalPayment) totalPayment.textContent = formatCurrency(totalRepayment);
        if (totalInterest) totalInterest.textContent = formatCurrency(totalInterestAmount);
    }

    // ============================================================
    // SWITCH LOAN TYPE
    // ============================================================
    function switchLoanType(type) {
        currentType = type;
        
        // Update buttons
        document.querySelectorAll('.calc-tab').forEach(btn => {
            btn.classList.remove('active');
        });
        const activeBtn = document.querySelector(`.calc-tab[data-type="${type}"]`);
        if (activeBtn) {
            activeBtn.classList.add('active');
        }
        
        // Update sliders with database values
        updateSliders();
    }

    // ============================================================
    // UPDATE DISPLAY ON SLIDER CHANGE
    // ============================================================
    function updateDisplay() {
        const amount = parseInt(amountSlider.value);
        const tenure = parseInt(tenureSlider.value);
        if (amountDisplay) amountDisplay.textContent = formatCurrency(amount);
        if (tenureDisplay) tenureDisplay.textContent = tenure + ' Months';
        calculateMonthlyPayment();
    }

    // ============================================================
    // CALCULATOR EVENT LISTENERS
    // ============================================================
    
    // Tab buttons
    document.querySelectorAll('.calc-tab').forEach(btn => {
        btn.addEventListener('click', function() {
            const type = this.dataset.type;
            switchLoanType(type);
        });
    });

    // Amount slider
    if (amountSlider) {
        amountSlider.addEventListener('input', function() {
            updateDisplay();
        });
    }

    // Tenure slider
    if (tenureSlider) {
        tenureSlider.addEventListener('input', function() {
            updateDisplay();
        });
    }

    // ============================================================
    // INITIALIZE CALCULATOR - DATABASE SE START KARO
    // ============================================================
    updateSliders();
    console.log('✅ Loan Calculator initialized with database values!');

    // ============================================================
    // CONTACT FORM (Pehle jaisa hi)
    // ============================================================
    if (!form) return;

    // Ensure correct initial state
    if (btnSpinner) btnSpinner.style.display = 'none';
    if (btnIcon) btnIcon.style.display = 'inline-block';

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        document.querySelectorAll('.field-error').forEach(el => el.textContent = '');
        if (alertBox) {
            alertBox.style.display = 'none';
            alertBox.textContent = '';
        }

        // Loader ON
        if (submitBtn) submitBtn.disabled = true;
        if (btnText) btnText.textContent = 'Sending...';
        if (btnIcon) btnIcon.style.display = 'none';
        if (btnSpinner) btnSpinner.style.display = 'inline-block';

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: formData,
        })
        .then(async (response) => {
            const data = await response.json();

            if (response.ok && data.success) {
                form.reset();
                if (alertBox) {
                    alertBox.textContent = data.message;
                    alertBox.style.cssText = 'display:block; padding:12px 16px; border-radius:12px; margin-bottom:20px; font-size:14px; background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0;';
                }

                document.getElementById('contact')?.scrollIntoView({ behavior: 'smooth', block: 'start' });

            } else if (response.status === 422 && data.errors) {
                Object.keys(data.errors).forEach(field => {
                    const errEl = document.querySelector(`.field-error[data-field="${field}"]`);
                    if (errEl) errEl.textContent = data.errors[field][0];
                });

                if (alertBox) {
                    alertBox.textContent = 'Please fix the errors below and try again.';
                    alertBox.style.cssText = 'display:block; padding:12px 16px; border-radius:12px; margin-bottom:20px; font-size:14px; background:#fef2f2; color:#b91c1c; border:1px solid #fecaca;';
                }
            } else {
                if (alertBox) {
                    alertBox.textContent = 'Something went wrong. Please try again.';
                    alertBox.style.cssText = 'display:block; padding:12px 16px; border-radius:12px; margin-bottom:20px; font-size:14px; background:#fef2f2; color:#b91c1c; border:1px solid #fecaca;';
                }
            }
        })
        .catch(() => {
            if (alertBox) {
                alertBox.textContent = 'Network error. Please check your connection and try again.';
                alertBox.style.cssText = 'display:block; padding:12px 16px; border-radius:12px; margin-bottom:20px; font-size:14px; background:#fef2f2; color:#b91c1c; border:1px solid #fecaca;';
            }
        })
        .finally(() => {
            // Loader OFF
            if (submitBtn) submitBtn.disabled = false;
            if (btnText) btnText.textContent = 'Send Message';
            if (btnIcon) btnIcon.style.display = 'inline-block';
            if (btnSpinner) btnSpinner.style.display = 'none';
        });
    });

});
</script>

@endonce

@endsection