<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Login – African Investment Partners</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Inter', sans-serif; 
            min-height: 100vh;
            background: #F9FAFB;
        }
        .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* ============================================================
           SPLIT LAYOUT - Full page
        ============================================================ */
        .login-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ============================================================
           LEFT PANEL - Form
        ============================================================ */
        .left-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #FFFFFF;
            min-height: 100vh;
        }

        /* ============================================================
           RIGHT PANEL - Brand (desktop only)
        ============================================================ */
        .right-panel {
            display: none;
            flex: 1;
            position: relative;
            overflow: hidden;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(160deg, #1A2332 0%, #243447 50%, #1e3a2f 100%);
        }

        @media (min-width: 1024px) {
            .right-panel {
                display: flex;
            }
        }

        /* Blobs */
        .blob-1 {
            position: absolute;
            top: -80px;
            right: -80px;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: rgba(109, 190, 59, 0.12);
            filter: blur(60px);
            pointer-events: none;
        }
        .blob-2 {
            position: absolute;
            bottom: -60px;
            left: -60px;
            width: 256px;
            height: 256px;
            border-radius: 50%;
            background: rgba(109, 190, 59, 0.07);
            filter: blur(60px);
            pointer-events: none;
        }

        /* Dot grid */
        .dot-grid {
            position: absolute;
            inset: 0;
            opacity: 0.06;
            background-image: radial-gradient(circle, #6DBE3B 1px, transparent 1px);
            background-size: 36px 36px;
            pointer-events: none;
        }

        /* ============================================================
           INPUT FOCUS
        ============================================================ */
        .login-input {
            height: 44px;
            width: 100%;
            border-radius: 12px;
            border: 1px solid #E5E7EB;
            background: #F9FAFB;
            padding: 10px 16px;
            font-size: 14px;
            color: #1F2937;
            outline: none;
            transition: all 0.3s ease;
        }
        .login-input::placeholder {
            color: #9CA3AF;
        }
        .login-input:focus {
            border-color: #6DBE3B;
            background: #FFFFFF;
            box-shadow: 0 0 0 3px rgba(109, 190, 59, 0.12);
        }
        .login-input.error {
            border-color: #FCA5A5;
        }

        /* ============================================================
           LOGIN BUTTON
        ============================================================ */
        .btn-login {
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            background: linear-gradient(135deg, #6DBE3B, #58A02E);
            color: #FFFFFF;
            font-weight: 700;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px -8px rgba(109, 190, 59, 0.4);
        }
        .btn-login:active {
            transform: scale(0.98);
        }
        .btn-login:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* ============================================================
           CHECKBOX
        ============================================================ */
        .custom-checkbox {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            border: 1px solid #D1D5DB;
            accent-color: #6DBE3B;
            cursor: pointer;
        }

        /* ============================================================
           ALERTS
        ============================================================ */
        .alert-success {
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 12px;
            border: 1px solid #86EFAC;
            background: #F0FDF4;
            padding: 14px 16px;
            margin-bottom: 24px;
        }
        .alert-error {
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 12px;
            border: 1px solid #FCA5A5;
            background: #FEF2F2;
            padding: 14px 16px;
            margin-bottom: 24px;
        }

        /* ============================================================
           STAT CARD
        ============================================================ */
        .stat-card {
            border-radius: 16px;
            padding: 16px 20px;
            text-align: center;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .stat-number {
            font-size: 24px;
            font-weight: 800;
            color: #6DBE3B;
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin-bottom: 2px;
        }
        .stat-label {
            font-size: 11px;
            color: #9CA3AF;
            font-weight: 500;
            line-height: 1.3;
        }

        /* ============================================================
           TESTIMONIAL CARD
        ============================================================ */
        .testimonial-card {
            width: 100%;
            border-radius: 16px;
            padding: 20px;
            text-align: left;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* ============================================================
           SPINNER
        ============================================================ */
        .spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2px solid #FFFFFF;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }
        .btn-login.loading .spinner {
            display: inline-block;
        }
        .btn-login.loading .btn-text {
            display: none;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* ============================================================
           TOGGLE PASSWORD
        ============================================================ */
        .toggle-pwd {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            background: none;
            border: none;
            cursor: pointer;
            transition: color 0.2s ease;
            padding: 4px;
        }
        .toggle-pwd:hover {
            color: #6DBE3B;
        }

        /* ============================================================
           INPUT WRAPPER
        ============================================================ */
        .input-wrapper {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            font-size: 14px;
        }
        .input-with-icon {
            padding-left: 42px;
            padding-right: 44px;
        }
        .input-without-icon {
            padding-right: 44px;
        }

        /* ============================================================
           DIVIDER
        ============================================================ */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0;
        }
        .divider-line {
            flex: 1;
            height: 1px;
            background: #F3F4F6;
        }
        .divider-text {
            font-size: 10px;
            color: #9CA3AF;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* ============================================================
           RESPONSIVE
        ============================================================ */
        @media (max-width: 640px) {
            .left-panel {
                padding: 1rem;
            }
            .stat-card {
                padding: 12px 14px;
            }
            .stat-number {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="login-wrapper">

        <!-- ============================================================
             LEFT PANEL — Form
        ============================================================ -->
        <div class="left-panel">

            <!-- Top Bar -->
            <div class="flex items-center justify-between px-6 sm:px-8 pt-6 sm:pt-8">
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 hover:text-gray-800 transition-colors">
                    <i class="fas fa-arrow-left text-xs"></i>
                    Back to Home
                </a>
            </div>

            <!-- Form Area -->
            <div class="flex flex-1 items-center justify-center px-6 sm:px-8 py-8 sm:py-12">
                <div class="w-full max-w-md">

                    <!-- Logo -->
                    <div class="flex items-center gap-3 mb-8 sm:mb-10">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#6DBE3B;">
                            <span class="text-white font-bold text-xl leading-none font-display">A</span>
                        </div>
                        <div class="leading-tight">
                            <span class="block font-bold text-gray-800 text-lg tracking-tight font-display">AIP</span>
                            <span class="block text-[10px] text-gray-400 font-medium tracking-wide uppercase -mt-0.5">African Investment Partners</span>
                        </div>
                    </div>

                    <!-- Heading -->
                    <div class="mb-8">
                        <h1 class="text-2xl font-bold text-gray-800 mb-1.5 font-display">Welcome back, Staff</h1>
                        <p class="text-sm text-gray-500">Enter your credentials to access the staff dashboard.</p>
                    </div>

                    <!-- Error Alert -->
                    @if ($errors->any())
                        <div class="alert-error">
                            <i class="fas fa-circle-exclamation text-red-500 text-sm flex-shrink-0"></i>
                            <p class="text-sm font-medium text-red-700">{{ $errors->first() }}</p>
                        </div>
                    @endif

                    <!-- Success Alert (if any) -->
                    @if (session('success'))
                        <div class="alert-success">
                            <i class="fas fa-check-circle text-green-500 text-sm flex-shrink-0"></i>
                            <p class="text-sm font-medium text-green-700">{{ session('success') }}</p>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form id="loginForm" method="POST" action="{{ route('staff.login') }}" class="space-y-5">
                        @csrf

                        <!-- Staff Code -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Staff Code <span class="text-red-500">*</span>
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-id-badge input-icon"></i>
                                <input type="text" name="staff_code" required autofocus
                                       value="{{ old('staff_code') }}"
                                       placeholder="e.g. P0001"
                                       class="login-input input-with-icon @error('staff_code') error @enderror">
                            </div>
                            @error('staff_code')
                                <div class="mt-2 flex items-center gap-1.5">
                                    <i class="fas fa-circle-exclamation text-red-500 text-xs"></i>
                                    <span class="text-red-500 text-xs font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-lock input-icon"></i>
                                <input id="password" type="password" name="password" required
                                       placeholder="Enter your password"
                                       class="login-input input-with-icon @error('password') error @enderror">
                                <button type="button" onclick="togglePwd()" class="toggle-pwd">
                                    <i id="pwd-icon" class="fas fa-eye text-sm"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="mt-2 flex items-center gap-1.5">
                                    <i class="fas fa-circle-exclamation text-red-500 text-xs"></i>
                                    <span class="text-red-500 text-xs font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Remember me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                                <input type="checkbox" name="remember" class="custom-checkbox">
                                <span class="text-sm text-gray-600 font-medium">Keep me logged in</span>
                            </label>
                            <a href="#" class="text-sm font-medium text-[#6DBE3B] hover:text-[#58A02E] transition-colors">
                                Forgot password?
                            </a>
                        </div>

                        <!-- Submit Button -->
                        <button id="loginBtn" type="submit" class="btn-login">
                            <span class="btn-text"><i class="fas fa-arrow-right-to-bracket mr-2"></i> Sign In</span>
                            <span class="spinner"></span>
                        </button>

                    </form>

                    <!-- Divider -->
                    <div class="divider">
                        <div class="divider-line"></div>
                        <span class="divider-text">STAFF PORTAL</span>
                        <div class="divider-line"></div>
                    </div>

                    <!-- Help -->
                    <p class="text-center text-sm text-gray-500">
                        Having trouble?
                        <a href="mailto:info@aip-loans.com" class="text-[#6DBE3B] hover:text-[#58A02E] font-medium transition-colors">
                            Contact support
                        </a>
                    </p>

                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 sm:px-8 pb-6 sm:pb-8 text-center">
                <p class="text-xs text-gray-400">&copy; {{ date('Y') }} African Investment Partners (AIP). All rights reserved.</p>
            </div>

        </div>

        <!-- ============================================================
             RIGHT PANEL — Brand (desktop only)
        ============================================================ -->
        <div class="right-panel">

            <!-- Blobs -->
            <div class="blob-1"></div>
            <div class="blob-2"></div>

            <!-- Dot Grid -->
            <div class="dot-grid"></div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col items-center text-center px-14 max-w-md">

                <!-- Logo -->
                <div class="w-20 h-20 rounded-2xl flex items-center justify-center mb-8 shadow-xl"
                     style="background:rgba(109,190,59,0.2);border:1px solid rgba(109,190,59,0.3);">
                    <span class="text-4xl font-bold font-display" style="color:#6DBE3B;">A</span>
                </div>

                <h2 class="text-3xl font-bold text-white mb-4 leading-tight font-display" style="letter-spacing:-0.5px;">
                    African Investment<br>Partners
                </h2>
                <p class="text-gray-400 text-sm leading-relaxed mb-10">
                    Empowering Nigerians with fast, transparent, and affordable loan solutions through Polaris Bank and Zenith Bank — since 2018.
                </p>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-4 w-full mb-10">
                    <div class="stat-card">
                        <p class="stat-number">5K+</p>
                        <p class="stat-label">Borrowers<br>Served</p>
                    </div>
                    <div class="stat-card">
                        <p class="stat-number">98%</p>
                        <p class="stat-label">Approval<br>Rate</p>
                    </div>
                    <div class="stat-card">
                        <p class="stat-number">24hr</p>
                        <p class="stat-label">Avg. Loan<br>Disbursement</p>
                    </div>
                </div>

                <!-- Testimonial -->
                <div class="testimonial-card">
                    <div class="flex text-yellow-400 text-xs gap-0.5 mb-3">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-300 text-sm leading-relaxed italic mb-4">
                        "AIP approved my loan in less than 24 hours. No hidden fees, no stress — the money was in my account the same evening."
                    </p>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0" style="background:#6DBE3B;">A</div>
                        <div>
                            <p class="text-white text-xs font-semibold">Adaeze Okonkwo</p>
                            <p class="text-gray-500 text-xs">Petty Trader, Lagos</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- SSL Badge -->
            <div class="absolute bottom-8 flex items-center gap-2 text-xs font-medium" style="color:rgba(255,255,255,0.35);">
                <i class="fas fa-lock text-xs" style="color:#6DBE3B;"></i>
                Secured with 256-bit SSL encryption
            </div>

        </div>

    </div>

    <!-- ============================================================
         JAVASCRIPT
    ============================================================ -->
    <script>
        // ============================================================
        // TOGGLE PASSWORD VISIBILITY
        // ============================================================
        function togglePwd() {
            const input = document.getElementById('password');
            const icon = document.getElementById('pwd-icon');
            const isPwd = input.type === 'password';
            input.type = isPwd ? 'text' : 'password';
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }

        // ============================================================
        // LOGIN FORM - SHOW LOADING STATE
        // ============================================================
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            btn.classList.add('loading');
            btn.disabled = true;
        });

        // ============================================================
        // KEYBOARD SHORTCUT: Press Enter to submit
        // ============================================================
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const form = document.getElementById('loginForm');
                const activeElement = document.activeElement;
                if (activeElement && form.contains(activeElement)) {
                    form.dispatchEvent(new Event('submit'));
                }
            }
        });
    </script>

</body>
</html>