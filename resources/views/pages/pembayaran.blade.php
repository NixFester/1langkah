@extends('layouts.app', ['activePage' => 'pembayaran'])

@section('title', 'Pembayaran — 1Langkah')

@section('content')
@php
    $i = $item ?? [];
    $title = $i['title'] ?? ($i['name'] ?? 'Full-Stack Web Development Bootcamp');
    $subtitle = $i['mentor'] ?? ($i['company'] ?? 'Kursus Online');
    $rawPrice = $i['price'] ?? 599000;
    $price = $i['formatted_price'] ?? (is_numeric($rawPrice) ? 'Rp ' . number_format((float) $rawPrice, 0, ',', '.') : $rawPrice);
    $normalPrice = 'Rp 999.000';
    $isEnrolled = $isEnrolled ?? false;
    $itemId = $i['id'] ?? 0;
    $itemKind = $i['kind'] ?? 'course';
@endphp

{{-- Success/Error/Info Messages --}}
@if(session('success'))
    <div style="background-color: #ecfdf5; border: 1px solid #10b981; color: #065f46; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; font-weight: 500;">
        {{ session('success') }}
    </div>
@endif

@if(session('info'))
    <div style="background-color: #eff6ff; border: 1px solid #3b82f6; color: #1e40af; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; font-weight: 500;">
        {{ session('info') }}
    </div>
@endif

@if(session('error'))
    <div style="background-color: #fef2f2; border: 1px solid #ef4444; color: #991b1b; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; font-weight: 500;">
        {{ session('error') }}
    </div>
@endif

<div class="w-full px-2 pb-8 space-y-6">
    <!-- Header -->
    <div style="margin-bottom: 28px;">
        <a href="javascript:history.back()" style="display: inline-flex; align-items: center; gap: 8px; color: #6b7280; font-size: 14px; font-weight: 500; margin-bottom: 20px; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#6b7280'">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali
        </a>
        <h1 class="font-extrabold text-gray-900" style="font-size: 28px; letter-spacing: -0.02em;">Pembayaran</h1>
    </div>

    <!-- Main Content -->
    <div style="display: flex; gap: 32px; align-items: flex-start; flex-wrap: wrap;">
        
        <!-- Left Column -->
        <div style="flex: 1; min-width: 320px; display: flex; flex-direction: column; gap: 24px;">
            
            <!-- Payment Methods Card -->
            <div class="bg-white border border-gray-100 shadow-sm" style="border-radius: 20px; padding: 28px;">
                <h2 class="font-bold text-gray-900" style="font-size: 20px; margin-bottom: 24px;">Pilih Metode Pembayaran</h2>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px;" x-data="{ selected: '' }">
                    <!-- Virtual Account -->
                    <div @click="selected = 'va'" :style="selected === 'va' ? 'border-color: #cc0000; background-color: #fffafb;' : 'border-color: #e5e7eb;'" style="border: 1.5px solid; border-radius: 16px; padding: 16px 20px; display: flex; align-items: center; gap: 16px; cursor: pointer; transition: all 0.2s;">
                        <div style="width: 44px; height: 44px; border-radius: 50%; background-color: #f3f4f6; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #6b7280;">
                            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div>
                            <div style="font-weight: 700; font-size: 15px; color: #111827;">Virtual Account</div>
                            <div style="font-size: 13px; color: #9ca3af; margin-top: 2px;">Transfer via ATM / m-banking</div>
                        </div>
                    </div>
                    <!-- Kartu Debit / Kredit -->
                    <div @click="selected = 'cc'" :style="selected === 'cc' ? 'border-color: #cc0000; background-color: #fffafb;' : 'border-color: #e5e7eb;'" style="border: 1.5px solid; border-radius: 16px; padding: 16px 20px; display: flex; align-items: center; gap: 16px; cursor: pointer; transition: all 0.2s;">
                        <div style="width: 44px; height: 44px; border-radius: 50%; background-color: #f3f4f6; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #6b7280;">
                            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <div>
                            <div style="font-weight: 700; font-size: 15px; color: #111827;">Kartu Debit / Kredit</div>
                            <div style="font-size: 13px; color: #9ca3af; margin-top: 2px;">Visa, Mastercard, JCB</div>
                        </div>
                    </div>
                    <!-- E-Wallet -->
                    <div @click="selected = 'ewallet'" :style="selected === 'ewallet' ? 'border-color: #cc0000; background-color: #fffafb;' : 'border-color: #e5e7eb;'" style="border: 1.5px solid; border-radius: 16px; padding: 16px 20px; display: flex; align-items: center; gap: 16px; cursor: pointer; transition: all 0.2s;">
                        <div style="width: 44px; height: 44px; border-radius: 50%; background-color: #f3f4f6; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #6b7280;">
                            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <div style="font-weight: 700; font-size: 15px; color: #111827;">E-Wallet</div>
                            <div style="font-size: 13px; color: #9ca3af; margin-top: 2px; line-height: 1.4;">GoPay, OVO, DANA,<br>ShopeePay</div>
                        </div>
                    </div>
                    <!-- QRIS -->
                    <div @click="selected = 'qris'" :style="selected === 'qris' ? 'border-color: #cc0000; background-color: #fffafb;' : 'border-color: #e5e7eb;'" style="border: 1.5px solid; border-radius: 16px; padding: 16px 20px; display: flex; align-items: center; gap: 16px; cursor: pointer; transition: all 0.2s;">
                        <div style="width: 44px; height: 44px; border-radius: 50%; background-color: #f3f4f6; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #6b7280;">
                            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        </div>
                        <div>
                            <div style="font-weight: 700; font-size: 15px; color: #111827;">QRIS</div>
                            <div style="font-size: 13px; color: #9ca3af; margin-top: 2px;">Scan dengan aplikasi apapun</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kupon Card -->
            <div class="bg-white border border-gray-100 shadow-sm" style="border-radius: 20px; padding: 28px;">
                <h2 class="font-bold text-gray-900" style="font-size: 20px; margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
                    Kode Kupon / Voucher 
                    <span style="font-weight: 500; color: #9ca3af; font-size: 16px;">(opsional)</span>
                </h2>
                
                <div style="display: flex; gap: 16px; align-items: center; flex-wrap: wrap;">
                    <input type="text" placeholder="MASUKKAN KODE VOUCHER" style="flex: 1; min-width: 240px; height: 52px; border-radius: 999px; border: 1.5px solid #e5e7eb; padding: 0 24px; font-size: 14px; font-weight: 500; letter-spacing: 0.05em; color: #4b5563; background-color: #f9fafb; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#cc0000'" onblur="this.style.borderColor='#e5e7eb'">
                    <button style="height: 52px; padding: 0 32px; border-radius: 999px; background-color: #ed999c; color: white; font-weight: 700; font-size: 15px; border: none; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#e58487'" onmouseout="this.style.backgroundColor='#ed999c'">Terapkan</button>
                </div>
                <div style="font-size: 13px; font-weight: 500; color: #9ca3af; margin-top: 14px; padding-left: 20px;">Coba: BELAJAR20 &bull; 1LANGKAH50 &bull; HEMAT30</div>
            </div>
        </div>

        <!-- Right Column (Summary) -->
        <div style="width: 380px; flex-shrink: 0; display: flex; flex-direction: column; gap: 24px;">
            
            <!-- Order Summary Card -->
            <div class="bg-white border border-gray-100 shadow-sm" style="border-radius: 20px; padding: 28px;">
                <h2 class="font-bold text-gray-900" style="font-size: 20px; margin-bottom: 28px;">Ringkasan Pesanan</h2>
                
                <!-- Course Item -->
                <div style="display: flex; gap: 16px; align-items: center; margin-bottom: 28px;">
                    <div style="width: 60px; height: 60px; border-radius: 50%; background-color: #ffe4e6; display: flex; align-items: center; justify-content: center; color: #cc0000; flex-shrink: 0;">
                        <svg style="width: 26px; height: 26px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <div>
                        <div style="font-weight: 800; font-size: 16px; color: #111827; line-height: 1.3;">{{ $title }}</div>
                        <div style="font-size: 13px; font-weight: 500; color: #9ca3af; margin-top: 4px;">{{ $subtitle }}</div>
                    </div>
                </div>

                <div style="border-top: 1.5px dashed #f3f4f6; margin: 0 -28px 24px; padding: 0 28px;"></div>

                <!-- Price Details -->
                <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 15px; font-weight: 500; color: #6b7280;">Harga normal</span>
                        <span style="font-size: 15px; font-weight: 500; color: #9ca3af; text-decoration: line-through;">{{ $normalPrice }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 15px; font-weight: 500; color: #6b7280;">Harga kursus</span>
                        <span style="font-size: 15px; font-weight: 600; color: #4b5563;">{{ $price }}</span>
                    </div>
                </div>

                <div style="border-top: 1.5px dashed #f3f4f6; margin: 0 -28px 24px; padding: 0 28px;"></div>

                <!-- Total -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px;">
                    <span style="font-size: 20px; font-weight: 800; color: #111827;">Total</span>
                    <span style="font-size: 22px; font-weight: 900; color: #cc0000;">{{ $price }}</span>
                </div>

                @if($isEnrolled)
                    {{-- Already enrolled - show button to go to course --}}
                    @php
                        $redirectUrl = match($itemKind) {
                            'course' => route('detail-kursus', ['id' => $itemId]),
                            'online' => route('detail-online-bootcamp', ['id' => $itemId]),
                            'offline' => route('detail-offline-bootcamp', ['id' => $itemId]),
                            default => route('kursus-saya'),
                        };
                    @endphp
                    <a href="{{ $redirectUrl }}"
                       style="display: flex; align-items: center; justify-content: center; width: 100%; height: 52px; border-radius: 999px; background-color: #10b981; color: white; font-weight: 700; font-size: 16px; text-decoration: none; transition: background-color 0.2s;"
                       onmouseover="this.style.backgroundColor='#059669'"
                       onmouseout="this.style.backgroundColor='#10b981'">
                        <svg style="width: 20px; height: 20px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Sudah Terdaftar — Mulai Belajar
                    </a>
                @else
                    {{-- Not enrolled - show mock payment form --}}
                    <form action="{{ route('pembayaran.proses') }}" method="POST">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $itemId }}">
                        <input type="hidden" name="item_kind" value="{{ $itemKind }}">
                        <button type="submit"
                                style="width: 100%; height: 52px; border-radius: 999px; background-color: #cc0000; color: white; font-weight: 700; font-size: 16px; border: none; cursor: pointer; transition: background-color 0.2s;"
                                onmouseover="this.style.backgroundColor='#a30000'"
                                onmouseout="this.style.backgroundColor='#cc0000'">
                            Bayar Sekarang (Mock)
                        </button>
                    </form>
                    <p style="font-size: 12px; color: #9ca3af; text-align: center; margin-top: 12px;">
                        ⚡ Demo: Klik untuk langsung terdaftar tanpa pembayaran
                    </p>
                @endif

                <!-- Benefits -->
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 500; color: #9ca3af;">
                        <svg style="width: 16px; height: 16px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Transaksi aman & terenkripsi
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 500; color: #9ca3af;">
                        <svg style="width: 16px; height: 16px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Garansi uang kembali 7 hari
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 500; color: #9ca3af;">
                        <svg style="width: 16px; height: 16px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Akses langsung setelah pembayaran
                    </div>
                </div>
            </div>

            <!-- Supported Methods -->
            <div class="bg-white border border-gray-100 shadow-sm" style="border-radius: 20px; padding: 28px;">
                <div style="font-size: 14px; font-weight: 600; color: #9ca3af; margin-bottom: 20px;">Metode pembayaran diterima</div>
                <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                    @foreach(['BCA', 'Mandiri', 'BNI', 'BRI', 'GoPay', 'OVO', 'DANA', 'QRIS'] as $m)
                        <div style="padding: 6px 16px; border: 1.5px solid #f3f4f6; border-radius: 999px; font-size: 13px; font-weight: 700; color: #4b5563;">{{ $m }}</div>
                    @endforeach
                </div>
            </div>
            
        </div>
    </div>
</div>

@endsection
