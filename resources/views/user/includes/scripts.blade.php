<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ===== Header scroll effect =====
    const header = document.getElementById('main-header');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 20) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // ===== Mobile menu toggle =====
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu    = document.getElementById('mobile-menu');
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            const isOpen = !mobileMenu.classList.contains('hidden');
            if (isOpen) {
                mobileMenu.classList.add('hidden');
                mobileMenuBtn.innerHTML = '<i class="fas fa-bars text-xl"></i>';
            } else {
                mobileMenu.classList.remove('hidden');
                mobileMenuBtn.innerHTML = '<i class="fas fa-times text-xl"></i>';
            }
        });
        // Close on link click
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                mobileMenuBtn.innerHTML = '<i class="fas fa-bars text-xl"></i>';
            });
        });
    }

    // ===== FAQ Accordion =====
    document.querySelectorAll('.faq-question').forEach(btn => {
        btn.addEventListener('click', () => {
            const item = btn.closest('.faq-item');
            const isOpen = item.classList.contains('open');
            // Close all
            document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
            // Toggle current
            if (!isOpen) item.classList.add('open');
        });
    });

    // ===== Loan Calculator =====
    const amountSlider   = document.getElementById('loan-amount-slider');
    const amountDisplay  = document.getElementById('loan-amount-display');
    const tenureSlider   = document.getElementById('loan-tenure-slider');
    const tenureDisplay  = document.getElementById('loan-tenure-display');
    const monthlyDisplay = document.getElementById('monthly-payment');
    const totalDisplay   = document.getElementById('total-payment');
    const interestDisplay = document.getElementById('total-interest');

    function calcLoan() {
        if (!amountSlider) return;
        const amount  = parseInt(amountSlider.value);
        const months  = parseInt(tenureSlider.value);
        const rate    = 0.24 / 12; // 24% p.a. monthly
        const emi     = (amount * rate * Math.pow(1 + rate, months)) / (Math.pow(1 + rate, months) - 1);
        const total   = emi * months;
        const interest = total - amount;

        amountDisplay.textContent  = '₦' + amount.toLocaleString();
        tenureDisplay.textContent  = months + ' Months';
        monthlyDisplay.textContent = '₦' + Math.round(emi).toLocaleString();
        totalDisplay.textContent   = '₦' + Math.round(total).toLocaleString();
        interestDisplay.textContent = '₦' + Math.round(interest).toLocaleString();

        // Update slider track fill
        const amountPct = ((amount - amountSlider.min) / (amountSlider.max - amountSlider.min)) * 100;
        const tenurePct = ((months - tenureSlider.min) / (tenureSlider.max - tenureSlider.min)) * 100;
        amountSlider.style.background = `linear-gradient(to right, #6DBE3B ${amountPct}%, #e5e7eb ${amountPct}%)`;
        tenureSlider.style.background = `linear-gradient(to right, #6DBE3B ${tenurePct}%, #e5e7eb ${tenurePct}%)`;
    }

    if (amountSlider) {
        amountSlider.addEventListener('input', calcLoan);
        tenureSlider.addEventListener('input', calcLoan);
        calcLoan();
    }

    // ===== Loan Type Tabs (Calculator) =====
    document.querySelectorAll('.calc-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.calc-tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            // Update max amount based on tab
            if (amountSlider) {
                if (tab.dataset.type === 'sme') {
                    amountSlider.max = 150000;
                } else {
                    amountSlider.max = 100000;
                }
                calcLoan();
            }
        });
    });

    // ===== Scroll Reveal (lightweight) =====
    const reveals = document.querySelectorAll('.reveal');
    if (reveals.length) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        reveals.forEach(el => observer.observe(el));
    }

    // ===== Counter Animation =====
    const counters = document.querySelectorAll('.counter');
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el     = entry.target;
                const target = parseInt(el.dataset.target);
                const suffix = el.dataset.suffix || '';
                let current  = 0;
                const step   = target / 60;
                const timer  = setInterval(() => {
                    current += step;
                    if (current >= target) {
                        el.textContent = target.toLocaleString() + suffix;
                        clearInterval(timer);
                    } else {
                        el.textContent = Math.floor(current).toLocaleString() + suffix;
                    }
                }, 20);
                counterObserver.unobserve(el);
            }
        });
    }, { threshold: 0.5 });
    counters.forEach(el => counterObserver.observe(el));

    // ===== Footer year =====
    const yearEl = document.getElementById('footer-year');
    if (yearEl) yearEl.textContent = new Date().getFullYear();

    // ===== Smooth scroll for anchor links =====
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
});
</script>
