@extends('user.layouts.app')
@section('title', 'Submit Signed Agreement – AIP')

@section('content')
<style>
    .agreement-container {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 24px;
        box-shadow: 0 20px 60px -15px rgba(0,0,0,0.08);
    }
    .agreement-header {
        background: linear-gradient(135deg, #1A2332 0%, #243447 100%);
        border-radius: 24px 24px 0 0;
        padding: 2rem 2.5rem;
    }
    .upload-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 2.5rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        background: #f8fafc;
    }
    .upload-zone:hover, .upload-zone.drag-over {
        border-color: #6DBE3B;
        background: #f0fdf4;
    }
    .upload-zone.has-file {
        border-color: #6DBE3B;
        background: #f0fdf4;
    }
    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 2rem;
        background: linear-gradient(135deg, #6DBE3B, #58A02E);
        color: white;
        font-weight: 700;
        font-size: 0.9rem;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(109,190,59,0.25);
        width: 100%;
        justify-content: center;
    }
    .btn-submit:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(109,190,59,0.35);
    }
    .btn-submit:disabled { opacity: 0.6; cursor: not-allowed; }
</style>

<div class="max-w-2xl mx-auto px-4 py-10">

    {{-- Header --}}
    <div class="agreement-header mb-6">
        <div class="flex items-center gap-2 text-[#6DBE3B] font-semibold text-xs tracking-widest uppercase mb-1">
            <span class="inline-block w-2 h-2 rounded-full bg-[#6DBE3B] animate-pulse"></span>
            Loan Agreement
        </div>
        <h1 class="font-display font-extrabold text-2xl text-white">Submit Signed Agreement</h1>
        <p class="text-white/60 text-sm mt-1">
            African Investment Partners – {{ $agreement->loanApplication->application_no }}
        </p>
    </div>

    {{-- Expires Notice --}}
    <div class="bg-amber-50 border-2 border-amber-200 rounded-2xl p-4 mb-6 flex items-start gap-3">
        <div class="w-8 h-8 bg-amber-400 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
            <i class="fas fa-clock text-white text-sm"></i>
        </div>
        <div>
            <p class="font-bold text-amber-800 text-sm">Time Sensitive</p>
            <p class="text-amber-700 text-sm mt-0.5">
                This link expires on
                <strong>{{ $agreement->agreement_token_expires_at?->format('d M Y \a\t h:i A') }}</strong>.
                If you do not submit within this period, your loan approval will be automatically cancelled.
            </p>
        </div>
    </div>

    {{-- Success --}}
    <div id="success-box" style="display:none;" class="bg-green-50 border-2 border-green-200 rounded-2xl p-6 mb-6 text-center">
        <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-check text-white text-2xl"></i>
        </div>
        <h3 class="font-bold text-green-800 text-xl mb-2">Agreement Submitted!</h3>
        <p class="text-green-700 text-sm">
            Your signed agreement has been received. Our team will process your loan disbursement shortly. You will be contacted via phone or email.
        </p>
    </div>

    {{-- Error --}}
    <div id="error-box" style="display:none;" class="bg-red-50 border-2 border-red-200 rounded-2xl p-4 mb-4">
        <p class="text-red-700 text-sm font-medium" id="error-msg"></p>
    </div>

    {{-- Form --}}
    <div id="form-box" class="agreement-container p-6 sm:p-8">

        {{-- Loan Summary --}}
        <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 mb-6">
            <p class="text-xs font-bold text-gray-500 uppercase mb-3">Loan Summary</p>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <p class="text-xs text-gray-400">Customer</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $agreement->loanApplication->customer->first_name }} {{ $agreement->loanApplication->customer->last_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Application No</p>
                    <p class="text-sm font-mono font-semibold text-gray-800">{{ $agreement->loanApplication->application_no }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Loan Amount</p>
                    <p class="text-sm font-bold" style="color:#6DBE3B;">₦{{ number_format($agreement->loanApplication->loan_amount, 0) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Duration</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $agreement->loanApplication->duration_months }} Months</p>
                </div>
            </div>
        </div>

        {{-- Instructions --}}
        <div class="mb-6">
            <p class="text-sm font-bold text-gray-700 mb-3">Instructions:</p>
            <ol class="space-y-2">
                <li class="flex items-start gap-2">
                    <span class="w-5 h-5 bg-[#6DBE3B] text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">1</span>
                    <p class="text-sm text-gray-600">Download the loan agreement PDF from your email</p>
                </li>
                <li class="flex items-start gap-2">
                    <span class="w-5 h-5 bg-[#6DBE3B] text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">2</span>
                    <p class="text-sm text-gray-600">Print and sign it, or sign it digitally</p>
                </li>
                <li class="flex items-start gap-2">
                    <span class="w-5 h-5 bg-[#6DBE3B] text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">3</span>
                    <p class="text-sm text-gray-600">Upload the signed document below (PDF, JPG, or PNG — max 5MB)</p>
                </li>
            </ol>
        </div>

        {{-- Upload Zone --}}
        <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-2">
                Upload Signed Agreement <span class="text-red-500">*</span>
            </label>
            <div class="upload-zone" id="upload-zone" onclick="document.getElementById('file-input').click()">
                <div id="upload-placeholder">
                    <i class="fas fa-cloud-upload-alt text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-600 font-semibold text-sm">Click to upload or drag & drop</p>
                    <p class="text-gray-400 text-xs mt-1">PDF, JPG, PNG — max 5MB</p>
                </div>
                <div id="file-preview" style="display:none;" class="flex items-center gap-3 justify-center">
                    <i class="fas fa-file-alt text-[#6DBE3B] text-2xl"></i>
                    <div class="text-left">
                        <p class="text-sm font-semibold text-gray-800" id="file-name"></p>
                        <p class="text-xs text-gray-400" id="file-size"></p>
                    </div>
                    <button type="button" onclick="clearFile(event)" class="ml-2 text-red-400 hover:text-red-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <input type="file" id="file-input" accept=".pdf,.jpg,.jpeg,.png" style="display:none;" onchange="handleFileSelect(this)">
            <p class="text-xs text-red-500 mt-1" id="file-error" style="display:none;"></p>
        </div>

        {{-- Submit Button --}}
        <button type="button" id="submit-btn" onclick="submitAgreement()" class="btn-submit" disabled>
            <i class="fas fa-paper-plane"></i>
            <span id="btn-text">Submit Signed Agreement</span>
        </button>

    </div>
</div>

<script>
const MAX_SIZE_MB = 5;
let selectedFile  = null;

function handleFileSelect(input) {
    const file    = input.files[0];
    const errEl   = document.getElementById('file-error');
    const zone    = document.getElementById('upload-zone');
    const preview = document.getElementById('file-preview');
    const ph      = document.getElementById('upload-placeholder');
    const nameEl  = document.getElementById('file-name');
    const sizeEl  = document.getElementById('file-size');
    const btn     = document.getElementById('submit-btn');

    errEl.style.display = 'none';

    if (!file) return;

    const allowedTypes = ['application/pdf','image/jpeg','image/jpg','image/png'];
    if (!allowedTypes.includes(file.type)) {
        errEl.textContent = 'Only PDF, JPG, or PNG files are accepted.';
        errEl.style.display = 'block';
        input.value = '';
        return;
    }

    if (file.size > MAX_SIZE_MB * 1024 * 1024) {
        errEl.textContent = `File too large. Maximum size is ${MAX_SIZE_MB}MB.`;
        errEl.style.display = 'block';
        input.value = '';
        return;
    }

    selectedFile        = file;
    nameEl.textContent  = file.name;
    sizeEl.textContent  = (file.size / 1024 / 1024).toFixed(2) + ' MB';
    ph.style.display    = 'none';
    preview.style.display = 'flex';
    zone.classList.add('has-file');
    btn.disabled        = false;
}

function clearFile(e) {
    e.stopPropagation();
    selectedFile = null;
    document.getElementById('file-input').value = '';
    document.getElementById('file-preview').style.display  = 'none';
    document.getElementById('upload-placeholder').style.display = 'block';
    document.getElementById('upload-zone').classList.remove('has-file');
    document.getElementById('submit-btn').disabled = true;
}

// Drag & drop
const zone = document.getElementById('upload-zone');
zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('drag-over'); });
zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
zone.addEventListener('drop', e => {
    e.preventDefault();
    zone.classList.remove('drag-over');
    const dt = e.dataTransfer;
    if (dt.files.length) {
        document.getElementById('file-input').files = dt.files;
        handleFileSelect(document.getElementById('file-input'));
    }
});

async function submitAgreement() {
    if (!selectedFile) return;

    const btn    = document.getElementById('submit-btn');
    const btnTxt = document.getElementById('btn-text');
    const errBox = document.getElementById('error-box');
    const errMsg = document.getElementById('error-msg');

    btn.disabled    = true;
    btnTxt.textContent = 'Submitting...';
    errBox.style.display = 'none';

    const formData = new FormData();
    formData.append('signed_agreement', selectedFile);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}');

    try {
        const res  = await fetch('{{ route("agreement.submit", $token) }}', {
            method: 'POST',
            body:   formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();

        if (data.success) {
            document.getElementById('form-box').style.display    = 'none';
            document.getElementById('success-box').style.display = 'block';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            errMsg.textContent   = data.message || 'Submission failed. Please try again.';
            errBox.style.display = 'block';
            btn.disabled         = false;
            btnTxt.textContent   = 'Submit Signed Agreement';
        }
    } catch (err) {
        errMsg.textContent   = 'Something went wrong. Please try again.';
        errBox.style.display = 'block';
        btn.disabled         = false;
        btnTxt.textContent   = 'Submit Signed Agreement';
    }
}
</script>
@endsection