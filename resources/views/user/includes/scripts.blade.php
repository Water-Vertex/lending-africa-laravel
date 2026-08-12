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
const ratesEl = document.getElementById('loan-rates-data');

const loanRates = {
    personal: {
        rate: ratesEl ? parseFloat(ratesEl.dataset.personalRate) : 20,
        min:  ratesEl ? parseInt(ratesEl.dataset.personalMin)    : 50000,
        max:  ratesEl ? parseInt(ratesEl.dataset.personalMax)    : 200000,
    },
    sme: {
        rate: ratesEl ? parseFloat(ratesEl.dataset.smeRate) : 20,
        min:  ratesEl ? parseInt(ratesEl.dataset.smeMin)    : 50000,
        max:  ratesEl ? parseInt(ratesEl.dataset.smeMax)    : 300000,
    },
};

let currentLoanType = 'personal';

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
    const annualRate = loanRates[currentLoanType].rate;
    const rate    = annualRate / 100 / 12;

    let emi;
    if (rate === 0) {
        emi = amount / months;
    } else {
        emi = (amount * rate * Math.pow(1 + rate, months)) / (Math.pow(1 + rate, months) - 1);
    }

    const total    = emi * months;
    const interest = total - amount;

    amountDisplay.textContent   = '₦' + amount.toLocaleString();
    tenureDisplay.textContent   = months + ' Months';
    monthlyDisplay.textContent  = '₦' + Math.round(emi).toLocaleString();
    totalDisplay.textContent    = '₦' + Math.round(total).toLocaleString();
    interestDisplay.textContent = '₦' + Math.round(interest).toLocaleString();

    const amountPct = ((amount - amountSlider.min) / (amountSlider.max - amountSlider.min)) * 100;
    const tenurePct = ((months - tenureSlider.min) / (tenureSlider.max - tenureSlider.min)) * 100;
    amountSlider.style.background = `linear-gradient(to right, #6DBE3B ${amountPct}%, #e5e7eb ${amountPct}%)`;
    tenureSlider.style.background = `linear-gradient(to right, #6DBE3B ${tenurePct}%, #e5e7eb ${tenurePct}%)`;
}

function updateSliderForType(type) {
    if (!amountSlider) return;
    const config = loanRates[type];
    const maxLabel = document.getElementById('loan-amount-max-label');

    amountSlider.min   = config.min;
    amountSlider.max   = config.max;
    amountSlider.value = config.min;

    if (maxLabel) maxLabel.textContent = '₦' + config.max.toLocaleString();

    // Min label bhi update karo
    const minLabel = amountSlider.previousElementSibling;
    const labels   = amountSlider.nextElementSibling;
    if (labels && labels.children[0]) {
        labels.children[0].textContent = '₦' + config.min.toLocaleString();
    }

    calcLoan();
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

        currentLoanType = tab.dataset.type;
        updateSliderForType(currentLoanType);
    });
});

    // ===== Loan Type Tabs (Calculator) =====
// ===== Loan Type Tabs (Calculator) =====
document.querySelectorAll('.calc-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.calc-tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        // Update max amount based on tab
        if (amountSlider) {
            const maxLabel = document.getElementById('loan-amount-max-label');

            if (tab.dataset.type === 'sme') {
                amountSlider.max = 300000;
                if (maxLabel) maxLabel.textContent = '₦300,000';
            } else {
                amountSlider.max = 200000;
                if (maxLabel) maxLabel.textContent = '₦200,000';
            }

            // Agar current slider value naye max se zyada hai to usko clamp karo
            if (parseInt(amountSlider.value) > parseInt(amountSlider.max)) {
                amountSlider.value = amountSlider.max;
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

    // ===== Scroll-Spy: highlight active nav link based on visible section =====
    const navLinks       = document.querySelectorAll('.nav-link');
    const navLinksMobile = document.querySelectorAll('.nav-link-mobile');
    const sectionIds     = ['home', 'about', 'how-it-works', 'faq', 'contact'];
    const sections = sectionIds
        .map(id => document.getElementById(id))
        .filter(Boolean);

    function setActiveNav(sectionId) {
        navLinks.forEach(link => {
            if (link.dataset.navSection === sectionId) {
                link.classList.add('text-primary', 'bg-primary-xlight');
            } else {
                link.classList.remove('text-primary', 'bg-primary-xlight');
            }
        });
        navLinksMobile.forEach(link => {
            if (link.dataset.navSection === sectionId) {
                link.classList.add('text-primary', 'bg-primary-xlight');
            } else {
                link.classList.remove('text-primary', 'bg-primary-xlight');
            }
        });
    }

    if (sections.length) {
        const spyObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    setActiveNav(entry.target.id);
                }
            });
        }, {
            rootMargin: '-40% 0px -50% 0px', // triggers when section is roughly centered in viewport
            threshold: 0
        });
        sections.forEach(sec => spyObserver.observe(sec));
    }

    // ===== Toast Notification =====
function showToast(message, type = 'success') {
    // Container ek dafa banao agar exist nahi karta
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.style.cssText = `
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            gap: 12px;
        `;
        document.body.appendChild(container);
    }

    const isSuccess = type === 'success';
    const toast = document.createElement('div');
    toast.style.cssText = `
        min-width: 280px;
        max-width: 380px;
        background: ${isSuccess ? '#22c55e' : '#ef4444'};
        color: #fff;
        padding: 16px 18px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        display: flex;
        align-items: flex-start;
        gap: 12px;
        font-family: inherit;
        transform: translateX(120%);
        opacity: 0;
        transition: transform 0.35s ease, opacity 0.35s ease;
    `;

    toast.innerHTML = `
        <i class="fas ${isSuccess ? 'fa-circle-check' : 'fa-circle-exclamation'}" style="font-size:18px; margin-top:2px;"></i>
        <div style="flex:1;">
            <p style="font-weight:700; font-size:14px; margin:0 0 2px;">${isSuccess ? 'Success' : 'Error'}</p>
            <p style="font-size:13px; opacity:0.95; margin:0; line-height:1.4;">${message}</p>
        </div>
        <button type="button" style="background:none; border:none; color:#fff; opacity:0.8; cursor:pointer; font-size:14px; line-height:1;">
            <i class="fas fa-times"></i>
        </button>
    `;

    container.appendChild(toast);

    // Animate in
    requestAnimationFrame(() => {
        toast.style.transform = 'translateX(0)';
        toast.style.opacity = '1';
    });

    const removeToast = () => {
        toast.style.transform = 'translateX(120%)';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 350);
    };

    toast.querySelector('button').addEventListener('click', removeToast);
    setTimeout(removeToast, 5000);
}

// ===== Loan Application Form (AJAX submit) =====
const loanForm = document.getElementById('loan-application-form');
if (loanForm) {
    loanForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const submitBtn = loanForm.querySelector('button[type="submit"]');
        const originalBtnHtml = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

        const formData = new FormData(loanForm);

        fetch(loanForm.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: formData,
        })
        .then(async (response) => {
            const data = await response.json();
            return { status: response.status, data };
        })
        .then(({ data }) => {
            if (data.success) {
                showToast(data.message, 'success');
                loanForm.reset();
            } else {
                showToast(data.message || 'Something went wrong. Please try again.', 'error');
            }
        })
        .catch(() => {
            showToast('Something went wrong. Please try again.', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;
        });
    });
}
// ===== Dynamic loan amount max based on loan type =====
const loanTypeSelect = document.getElementById('loan_type');
const loanAmountInput = document.getElementById('loan_amount');

if (loanTypeSelect && loanAmountInput) {
    function updateLoanAmountMax() {
        if (loanTypeSelect.value === 'personal') {
            loanAmountInput.max = 200000;
        } else if (loanTypeSelect.value === 'sme') {
            loanAmountInput.max = 300000;
        } else {
            loanAmountInput.max = 300000; 
        }

        // Agar current value naye max se zyada hai to browser validation trigger karo
        loanAmountInput.reportValidity();
    }

    loanTypeSelect.addEventListener('change', updateLoanAmountMax);

    // Page load par bhi set karo (agar old('loan_type') already selected ho)
    updateLoanAmountMax();
}


});
</script>