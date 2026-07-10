@extends('layouts.app')

@section('title', __('app.qr_attendance') ?? 'QR Code Attendance')

@section('content')
<div class="w-full px-2 pb-8">
    <div class="max-w-md mx-auto text-center">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('app.qr_attendance') }}</h1>
            <p class="text-sm text-gray-500 mt-2">
                @if($bootcamp)
                    {{ $bootcamp->title }}
                @else
                    {{ __('app.bootcamp_attendance') }}
                @endif
            </p>
        </div>

        <!-- QR Code Display -->
        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-lg">
            <div class="mb-6">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($qrCode) }}"
                     alt="QR Code"
                     class="mx-auto rounded-xl shadow-md">
            </div>

            <div class="border-t border-gray-100 pt-4">
                <p class="text-xs text-gray-400 mb-2">{{ __('app.date_label') }}</p>
                <p class="font-medium text-gray-900">{{ $attendance->attendance_date->format('d M Y') }}</p>
            </div>

            <!-- Status Badge -->
            <div class="mt-4">
                @if($attendance->verified)
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded-full inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="font-medium">{{ __('app.verified') }}</span>
                </div>
                @else
                <div class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">{{ __('app.waiting_for_scan') }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Instructions -->
        <div class="mt-6 text-sm text-gray-500">
            <p>{{ __('app.scan_instruction_admin') }}</p>
        </div>

        <!-- Print Button -->
        <div class="mt-6">
            <button onclick="window.print()" class="bg-gray-900 hover:bg-gray-800 text-white px-6 py-3 rounded-xl font-medium inline-flex items-center gap-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                {{ __('app.print_qr_code') }}
            </button>
        </div>

    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .max-w-md, .max-w-md * {
        visibility: visible;
    }
    .max-w-md {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }
    button {
        display: none !important;
    }
}
</style>
@endsection
