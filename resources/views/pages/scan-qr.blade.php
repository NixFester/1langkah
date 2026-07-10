@extends('layouts.app')

@section('title', __('app.scan_qr_attendance') ?? 'Scan QR Kehadiran')

@section('content')
<div class="w-full px-2 pb-8">
    <div class="max-w-lg mx-auto">

        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('app.scan_qr_attendance') }}</h1>
            <p class="text-sm text-gray-500 mt-2">{{ __('app.point_camera_to_qr') }}</p>
        </div>

        <!-- Bootcamp Selection (if not pre-selected) -->
        @if(!$bootcamp)
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.select_bootcamp') }}</label>
            <select id="bootcampSelect" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-transparent">
                <option value="">{{ __('app.select_bootcamp_placeholder') }}</option>
                @forelse($userBootcamps as $userBootcamp)
                <option value="{{ $userBootcamp['id'] }}">{{ $userBootcamp['title'] }}</option>
                @empty
                <option value="" disabled>{{ __('app.no_bootcamp_registered') }}</option>
                @endforelse
            </select>
        </div>
        @endif

        <!-- QR Scanner Container -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div id="qr-reader" class="w-full aspect-square bg-gray-900 rounded-xl overflow-hidden mb-4">
                <div id="qr-reader-placeholder" class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                    <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                    </svg>
                    <p class="text-sm">{{ __('app.camera_not_available') }}</p>
                    <p class="text-xs mt-1">{{ __('app.enable_camera_or_manual') }}</p>
                </div>
            </div>

            <!-- Manual Code Input -->
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.or_enter_qr_code') }}</label>
                <div class="flex gap-2">
                    <input type="text" id="qrCodeInput" placeholder="{{ __('app.enter_qr_code_placeholder') }}"
                           class="flex-1 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <button onclick="submitManualCode()" class="bg-[#cc0000] hover:bg-red-700 text-white px-6 py-3 rounded-xl font-medium transition-colors">
                        {{ __('app.submit') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Result Message -->
        <div id="resultMessage" class="hidden mt-6 p-4 rounded-2xl text-center"></div>

        <!-- Selected Bootcamp Info -->
        @if($bootcamp)
        <div class="mt-6 bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
            <p class="text-sm text-gray-500">Bootcamp:</p>
            <p class="font-medium text-gray-900">{{ $bootcamp->title }}</p>
        </div>
        @endif

        <!-- Instructions -->
        <div class="mt-8 bg-blue-50 rounded-2xl p-4">
            <h3 class="font-medium text-blue-900 mb-2">{{ __('app.how_to_use') }}</h3>
            <ol class="text-sm text-blue-700 space-y-1 list-decimal list-inside">
                <li>{{ __('app.instruction_1') }}</li>
                <li>{{ __('app.instruction_2') }}</li>
                <li>{{ __('app.instruction_3') }}</li>
                <li>{{ __('app.instruction_4') }}</li>
            </ol>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
let html5QrCode;
let bootcampId = {{ $bootcamp?->id ?? 'null' }};

document.addEventListener('DOMContentLoaded', function() {
    initQrScanner();
});

async function initQrScanner() {
    try {
        html5QrCode = new Html5Qrcode("qr-reader");

        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

        await html5QrCode.start(
            { facingMode: "environment" },
            config,
            onScanSuccess,
            onScanFailure
        );

        document.getElementById('qr-reader-placeholder').style.display = 'none';
    } catch (err) {
        console.log("Camera not available:", err);
    }
}

function onScanSuccess(decodedText) {
    document.getElementById('qrCodeInput').value = decodedText;
    submitManualCode();
}

function onScanFailure(error) {
    // Silent fail - continuous scanning
}

function submitManualCode() {
    const code = document.getElementById('qrCodeInput').value.trim();
    const resultDiv = document.getElementById('resultMessage');

    if (!code) {
        alert('{{ __('app.enter_qr_code_first') }}');
        return;
    }

    if (!bootcampId) {
        alert('{{ __('app.select_bootcamp_first') }}');
        return;
    }

    resultDiv.classList.add('hidden');

    fetch('{{ route("scan-qr.process") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            qr_code: code,
            bootcamp_id: bootcampId
        })
    })
    .then(r => r.json())
    .then(data => {
        resultDiv.classList.remove('hidden');

        if (data.success) {
            resultDiv.className = 'mt-6 p-4 rounded-2xl text-center bg-green-100 text-green-800';
            resultDiv.innerHTML = '<span class="text-2xl">🎉</span><br><strong>{{ __('app.success_exclamation') }}</strong><br>' + data.message;
        } else {
            resultDiv.className = 'mt-6 p-4 rounded-2xl text-center bg-red-100 text-red-800';
            resultDiv.innerHTML = '<span class="text-2xl">❌</span><br><strong>{{ __('app.failed_exclamation') }}</strong><br>' + data.message;
        }
    })
    .catch(err => {
        resultDiv.classList.remove('hidden');
        resultDiv.className = 'mt-6 p-4 rounded-2xl text-center bg-red-100 text-red-800';
        resultDiv.innerHTML = '<span class="text-2xl">❌</span><br><strong>{{ __('app.error_exclamation') }}</strong><br>{{ __('app.connection_error') }}';
    });
}

document.getElementById('bootcampSelect')?.addEventListener('change', function() {
    bootcampId = this.value;
});
</script>
@endpush
