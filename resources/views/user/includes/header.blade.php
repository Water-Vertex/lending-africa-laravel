<header class="sticky top-0 z-50 bg-white shadow-sm" id="main-header">
    <nav class="container mx-auto px-4 lg:px-8">
        <div class="flex items-center justify-between h-18 py-3">

            <!-- Logo -->
            <!-- <a href="{{ route('home') }}" class="flex items-center gap-2.5 flex-shrink-0">
                <div class="w-9 h-9 bg-primary rounded-lg flex items-center justify-center">
                    <span class="text-white font-display font-bold text-lg leading-none">A</span>
                </div>
                <div class="leading-tight">
                    <span class="font-display font-bold text-dark text-lg tracking-tight block">AIP</span>
                    <span class="text-[10px] text-gray-500 font-medium tracking-wide block -mt-0.5">African Investment Partners</span>
                </div>
            </a> -->
           <a href="{{ route('home') }}" class="flex items-center flex-shrink-0">
    <img
        src="{{ asset('assets/images/logo/aip-logo.png') }}"
        alt="AIP Logo"
        class="h-10 sm:h-12 md:h-14 w-auto object-contain"
    >
</a>

            <!-- Desktop Nav -->
            <ul class="hidden lg:flex items-center gap-1" id="main-nav-links">
                <li>
                    <a href="{{ route('home') }}" data-nav-section="home"
                       class="nav-link px-4 py-2 text-sm font-medium text-dark hover:text-primary transition rounded-lg hover:bg-primary-xlight {{ request()->routeIs('home') ? 'text-primary bg-primary-xlight' : '' }}">
                        Home
                    </a>
                </li>
                <li class="relative group">
                    <button class="px-4 py-2 text-sm font-medium text-dark hover:text-primary transition rounded-lg hover:bg-primary-xlight flex items-center gap-1">
                        Loans <i class="fas fa-chevron-down text-[10px] mt-0.5 transition group-hover:rotate-180 duration-200"></i>
                    </button>
                    <div class="absolute top-full left-0 mt-1 w-52 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 translate-y-1 group-hover:translate-y-0">
                        <div class="p-2">
                            <a href="#personal-loan" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-primary-xlight hover:text-primary text-sm font-medium text-gray-800 transition">
                                <i class="fas fa-user-circle text-primary w-4"></i> Personal Loan
                            </a>
                            <a href="#sme-loan" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-primary-xlight hover:text-primary text-sm font-medium text-gray-800 transition">
                                <i class="fas fa-briefcase text-primary w-4"></i> SME Loan
                            </a>
                        </div>
                    </div>
                </li>
              
                <li>
                    <a href="#how-it-works" data-nav-section="how-it-works"
                       class="nav-link px-4 py-2 text-sm font-medium text-dark hover:text-primary transition rounded-lg hover:bg-primary-xlight">
                        How It Works
                    </a>
                </li>
                  <li>
                    <a href="#about" data-nav-section="about"
                       class="nav-link px-4 py-2 text-sm font-medium text-dark hover:text-primary transition rounded-lg hover:bg-primary-xlight">
                        About Us
                    </a>
                </li>
                <li>
                    <a href="#faq" data-nav-section="faq"
                       class="nav-link px-4 py-2 text-sm font-medium text-dark hover:text-primary transition rounded-lg hover:bg-primary-xlight">
                        FAQs
                    </a>
                </li>
                <li>
                    <a href="#contact" data-nav-section="contact"
                       class="nav-link px-4 py-2 text-sm font-medium text-dark hover:text-primary transition rounded-lg hover:bg-primary-xlight">
                        Contact
                    </a>
                </li>
            </ul>

              <!-- Right Side -->


               
          <div class="hidden lg:flex items-center gap-3">
    <a href="http://portal.aiploan.com/staff/login"   target="_blank" class="text-sm font-semibold text-gray-800 hover:text-primary transition flex items-center gap-1.5">
        <i class="fas fa-sign-in-alt text-xs"></i> Staff Login

    <a href="#apply"
       class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-5 py-2.5 rounded-full transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5">
        Apply for Loan
    </a>
</div>


            <!-- Mobile Hamburger -->
            <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-lg text-dark hover:bg-gray-100 transition">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="lg:hidden hidden border-t border-gray-100 bg-white">
        <div class="container mx-auto px-4 py-4 space-y-1">
            <a href="{{ route('home') }}" data-nav-section="home" class="nav-link-mobile block px-4 py-2.5 text-sm font-medium text-dark hover:text-primary hover:bg-primary-xlight rounded-lg transition">Home</a>
            <a href="#personal-loan" class="block px-4 py-2.5 text-sm font-medium text-dark hover:text-primary hover:bg-primary-xlight rounded-lg transition">Personal Loan</a>
            <a href="#sme-loan" class="block px-4 py-2.5 text-sm font-medium text-dark hover:text-primary hover:bg-primary-xlight rounded-lg transition">SME Loan</a>
            <a href="#about" data-nav-section="about" class="nav-link-mobile block px-4 py-2.5 text-sm font-medium text-dark hover:text-primary hover:bg-primary-xlight rounded-lg transition">About Us</a>
            <a href="#how-it-works" data-nav-section="how-it-works" class="nav-link-mobile block px-4 py-2.5 text-sm font-medium text-dark hover:text-primary hover:bg-primary-xlight rounded-lg transition">How It Works</a>
            <a href="#faq" data-nav-section="faq" class="nav-link-mobile block px-4 py-2.5 text-sm font-medium text-dark hover:text-primary hover:bg-primary-xlight rounded-lg transition">FAQs</a>
            <a href="#contact" data-nav-section="contact" class="nav-link-mobile block px-4 py-2.5 text-sm font-medium text-dark hover:text-primary hover:bg-primary-xlight rounded-lg transition">Contact</a>
            <div class="pt-2 pb-1 border-t border-gray-100 mt-2">
                <a href="#apply" class="block text-center bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-5 py-3 rounded-full transition">
                    Apply for Loan
                </a>
            </div>
        </div>
    </div>
</header>