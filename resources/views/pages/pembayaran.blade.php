@extends('layouts.app', ['activePage' => 'pembayaran'])

@section('title', 'Pembayaran — 1Langkah')

@section('content')
@php
    $i = $item;
    // Use a consistent color and title regardless of the item kind
    $color = $i['color'] ?? '#667eea';
    $title = $i['title'] ?? ($i['name'] ?? 'Paket');
    $subtitle = $i['mentor'] ?? ($i['company'] ?? '');
    $price = $i['price'] ?? 'Rp 0';
@endphp

<div class="page-title" style="margin-bottom:24px">Pembayaran</div>

<div class="grid-2" style="gap:32px">
    <div>
        <div class="card" style="padding:24px;margin-bottom:20px">
            <div class="section-title" style="margin-bottom:16px">Metode Pembayaran</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px" x-data="{ selected: 'transfer' }">
                <div class="payment-method-option" :class="{ 'selected': selected === 'transfer' }" @click="selected = 'transfer'">
                    <div class="method-icon">&#128179;</div><div style="font-size:13px;font-weight:600">Transfer Bank</div>
                </div>
                <div class="payment-method-option" :class="{ 'selected': selected === 'cc' }" @click="selected = 'cc'">
                    <div class="method-icon">&#128179;</div><div style="font-size:13px;font-weight:600">Kartu Kredit</div>
                </div>
                <div class="payment-method-option" :class="{ 'selected': selected === 'ewallet' }" @click="selected = 'ewallet'">
                    <div class="method-icon">&#128241;</div><div style="font-size:13px;font-weight:600">GoPay / OVO</div>
                </div>
                <div class="payment-method-option" :class="{ 'selected': selected === 'qris' }" @click="selected = 'qris'">
                    <div class="method-icon">&#127974;</div><div style="font-size:13px;font-weight:600">QRIS</div>
                </div>
            </div>
        </div>
        <div class="card" style="padding:24px">
            <div class="section-title" style="margin-bottom:16px">Kode Promo</div>
            <form style="display:flex;gap:12px">
                <input class="input" name="promo" placeholder="Masukkan kode promo" style="flex:1" />
                <button type="submit" class="btn btn-outline">Terapkan</button>
            </form>
        </div>
    </div>
    <div>
        <div class="payment-summary">
            <div class="section-title" style="margin-bottom:16px">Ringkasan Pesanan</div>
            <div style="display:flex;gap:14px;padding:14px 0;border-bottom:1px solid var(--border-light);margin-bottom:12px">
                <div style="width:80px;height:56px;border-radius:var(--radius-sm);background:linear-gradient(135deg,{{ $color }},{{ $color }}cc);flex-shrink:0"></div>
                <div><div style="font-size:14px;font-weight:600">{{ $title }}</div><div style="font-size:12px;color:var(--text-muted)">{{ $subtitle }}</div></div>
            </div>
            <div class="payment-item"><span style="color:var(--text-muted)">Harga kursus</span><span>{{ $price }}</span></div>
            <div class="payment-item"><span style="color:var(--text-muted)">Pajak (PPN 11%)</span><span>Rp 87.900</span></div>
            <div class="payment-item"><span style="color:var(--success)">Diskon early bird</span><span style="color:var(--success)">-Rp 100.000</span></div>
            <div class="payment-item total"><span>Total</span><span>{{ $price }}</span></div>
            <a href="{{ route('kursus-saya') }}" class="btn btn-primary btn-lg btn-full" style="margin-top:20px;text-decoration:none;display:flex">Bayar Sekarang</a>
            <div style="margin-top:16px;display:flex;gap:12px;flex-wrap:wrap;justify-content:center">
                @foreach(['30 Hari Garansi','Akses Selamanya','Sertifikat'] as $f)
                    <div style="display:flex;align-items:center;gap:4px;font-size:11px;color:var(--text-light)"><span style="color:var(--success)">&#10003;</span> {{ $f }}</div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endsection
