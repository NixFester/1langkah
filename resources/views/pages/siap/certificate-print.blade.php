<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $certificate->title }} — 1Langkah</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 24px; color: #1f2937; }
        .actions { max-width: 900px; margin: 0 auto 16px; display: flex; justify-content: flex-end; gap: 10px; }
        .btn { background: #E50000; color: #fff; border: none; padding: 10px 20px; border-radius: 10px; font-weight: 700; cursor: pointer; text-decoration: none; font-size: 13px; }
        .btn-secondary { background: #fff; color: #1f2937; border: 1px solid #d1d5db; }
        .sheet { max-width: 900px; margin: 0 auto; background: #fff; border: 8px solid #E50000; border-radius: 20px; padding: 48px 56px; box-shadow: 0 20px 50px rgba(0,0,0,0.08); position: relative; }
        .inner-border { border: 2px solid #fecaca; border-radius: 12px; padding: 40px; text-align: center; }
        .brand { font-size: 28px; font-weight: 800; letter-spacing: -1px; margin-bottom: 6px; }
        .brand span { color: #E50000; }
        .subtitle { font-size: 12px; letter-spacing: 3px; text-transform: uppercase; color: #6b7280; margin-bottom: 28px; }
        .title { font-size: 32px; font-weight: 800; color: #111827; margin: 0 0 12px; }
        .lead { font-size: 14px; color: #4b5563; margin: 0 0 14px; }
        .recipient { font-size: 30px; font-weight: 800; color: #E50000; margin: 8px 0 18px; border-bottom: 2px solid #f3f4f6; padding-bottom: 12px; display: inline-block; min-width: 320px; }
        .desc { font-size: 15px; line-height: 1.7; color: #374151; max-width: 640px; margin: 0 auto 24px; }
        .profile-pill { display: inline-block; background: #FFF0EF; color: #991b1b; border: 1px solid #fecaca; padding: 8px 18px; border-radius: 999px; font-weight: 700; font-size: 14px; margin-bottom: 32px; }
        .footer { display: flex; justify-content: space-between; align-items: flex-end; text-align: left; border-top: 1px solid #e5e7eb; padding-top: 20px; font-size: 12px; color: #6b7280; }
        @media print {
            body { background: #fff; padding: 0; }
            .no-print { display: none !important; }
            .sheet { box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="actions no-print">
        <a href="{{ route('siap.certificates') }}" class="btn btn-secondary">Kembali</a>
        <button onclick="window.print()" class="btn">Cetak / Simpan PDF</button>
    </div>

    @php
        $attempt = $certificate->certifiable;
        $participantName = $attempt?->participant_name ?? $certificate->user?->name ?? 'Peserta';
        $assessmentTitle = $attempt?->assessment?->title ?? 'Asesmen SIAP';
    @endphp

    <div class="sheet">
        <div class="inner-border">
            <div class="brand"><span>1</span>Langkah</div>
            <div class="subtitle">SIAP — Sistem Integrasi Asesmen dan Pengembangan</div>

            <h1 class="title">SERTIFIKAT ASESMEN</h1>
            <p class="lead">Diberikan dengan bangga kepada:</p>

            <div class="recipient">{{ $participantName }}</div>

            <p class="desc">
                Telah menyelesaikan <strong>{{ $assessmentTitle }}</strong> pada program SIAP 1Langkah untuk mengeksplorasi kecenderungan potensi diri, gaya belajar, serta arah pengembangan akademik dan karier.
            </p>

            @if($certificate->description)
                <div class="profile-pill">{{ $certificate->description }}</div>
            @endif

            <p style="font-size: 12px; font-weight: 700; color: #92400e; background: #fffbeb; border: 1px solid #fde68a; display: inline-block; padding: 6px 14px; border-radius: 999px; margin: 0 0 24px 0;">
                Berlaku untuk peserta Psikogeometrik saja.
            </p>

            <div class="footer">
                <div>
                    <div>No. Sertifikat: <strong>{{ $certificate->certificate_number }}</strong></div>
                    <div>Kode Verifikasi: <strong>{{ $certificate->verification_code }}</strong></div>
                </div>
                <div style="text-align: right;">
                    <div>Diterbitkan pada {{ $certificate->issued_date?->translatedFormat('d F Y') }}</div>
                    <div style="margin-top: 8px; font-weight: 700; color: #111827;">Tim Asesmen SIAP 1Langkah</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
