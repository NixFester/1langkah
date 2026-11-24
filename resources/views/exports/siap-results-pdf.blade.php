<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Hasil Asesmen SIAP — 1Langkah</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111827; margin: 24px; font-size: 12px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #e5e7eb; padding-bottom: 16px; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; margin: 0 0 4px 0; }
        .subtitle { color: #4b5563; margin: 2px 0; }
        .btn { background: #E50000; color: #fff; border: none; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #d1d5db; padding: 8px 10px; text-align: left; vertical-align: top; }
        th { background-color: #f3f4f6; font-weight: bold; text-transform: uppercase; font-size: 11px; }
        .badge-pass { color: #15803d; font-weight: bold; }
        .badge-preview { color: #b45309; font-weight: bold; }
        @media print { .no-print { display: none !important; } body { margin: 0; } }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <div>
            <h1 class="title">Laporan Data Diri &amp; Hasil Asesmen SIAP</h1>
            <p class="subtitle"><strong>Cakupan:</strong> {{ $attempts->count() }} peserta asesmen selesai</p>
            <p class="subtitle"><strong>Dicetak pada:</strong> {{ now()->format('d/m/Y H:i') }}</p>
        </div>
        <div class="no-print">
            <button class="btn" onclick="window.print()">Cetak / Simpan PDF</button>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID</th>
                <th>Nama &amp; Email Google</th>
                <th>TTL &amp; Alamat</th>
                <th>WhatsApp</th>
                <th>Asesmen</th>
                <th>Profil Dominan</th>
                <th>Status Akses</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attempts as $idx => $attempt)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td>SIAP-{{ $attempt->id }}</td>
                    <td>
                        <strong>{{ $attempt->participant_name ?? $attempt->user?->name ?? '-' }}</strong><br>
                        <small>{{ $attempt->google_email ?? $attempt->user?->email ?? '-' }}</small>
                    </td>
                    <td>
                        {{ $attempt->formatted_ttl }}<br>
                        <small>{{ $attempt->address ?? '-' }}</small>
                    </td>
                    <td>{{ $attempt->whatsapp ?? '-' }}</td>
                    <td>{{ $attempt->assessment?->title ?? '-' }}</td>
                    <td>
                        <strong>{{ $attempt->dominant_profile['label'] ?? '-' }}</strong><br>
                        <small>Skor tertinggi: {{ $attempt->scores[0]['percentage'] ?? 0 }}%</small>
                    </td>
                    <td>
                        @if($attempt->is_full_unlocked)
                            <span class="badge-pass">FULL AKSES</span>
                        @else
                            <span class="badge-preview">PREVIEW</span>
                        @endif
                    </td>
                    <td>{{ $attempt->completed_at?->format('d/m/Y H:i') ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="9" style="text-align: center; padding: 20px;">Belum ada data hasil asesmen SIAP.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
