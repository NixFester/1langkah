<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Asesmen SIAP - {{ $attempt->assessment?->title ?? 'Asesmen' }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f7f8fa; color: #111827; padding: 24px; margin: 0;">
    <div style="max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 16px; border: 1px solid #e5e7eb; overflow: hidden;">
        <div style="background: #E50000; color: #ffffff; padding: 24px;">
            <div style="font-size: 12px; text-transform: uppercase; letter-spacing: 1px; opacity: 0.9;">
                {{ $isFullReport ? 'Laporan Lengkap Asesmen SIAP' : 'Hasil Preview Asesmen SIAP' }}
            </div>
            <h1 style="margin: 6px 0 0 0; font-size: 22px;">{{ $attempt->assessment?->title ?? 'Asesmen SIAP 1Langkah' }}</h1>
        </div>

        <div style="padding: 24px;">
            <p style="margin-top: 0;">Halo <strong>{{ $attempt->participant_name ?? $attempt->user?->name }}</strong>,</p>

            @php
                $scores = $attempt->scores ?? [];
                $topScore = $scores[0] ?? null;
                $dominant = $attempt->dominant_profile ?? [];
                $categoriesBySlug = $attempt->assessment?->categories?->keyBy('slug') ?? collect();
                $topCategory = $topScore ? $categoriesBySlug->get($topScore['slug'] ?? '') : null;
            @endphp

            <div style="background: #f3f4f6; border-radius: 12px; padding: 16px; margin-bottom: 20px; font-size: 12px; color: #4b5563;">
                <div>ID Asesmen: <strong>SIAP-{{ $attempt->id }}</strong> | Email Google: <strong>{{ $attempt->google_email ?? $attempt->user?->email }}</strong></div>
                <div style="margin-top: 4px;">TTL: <strong>{{ $attempt->formatted_ttl }}</strong> | No. WA: <strong>{{ $attempt->whatsapp ?? '-' }}</strong></div>
                <div style="margin-top: 4px;">Alamat: <strong>{{ $attempt->address ?? '-' }}</strong></div>
            </div>

            @if($topScore)
            <div style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                <div style="font-size: 11px; color: #6b7280; text-transform: uppercase; font-weight: bold;">Kecenderungan Dominan</div>
                <h2 style="margin: 6px 0 4px 0; font-size: 18px; color: #111827;">
                    {{ $dominant['label'] ?? $topScore['name'] }} ({{ $topScore['percentage'] }}%)
                </h2>
                @if($topCategory)
                    <p style="font-size: 14px; color: #374151; line-height: 1.6; margin: 8px 0 0 0;">
                        {{ $topCategory->description }}
                    </p>
                @endif
            </div>
            @endif

            @if(!$isFullReport)
                <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                    <h3 style="margin: 0 0 6px 0; font-size: 15px; color: #92400e;">Hasil Lengkap Masih Terkunci</h3>
                    <p style="margin: 0 0 12px 0; font-size: 13px; color: #78350f; line-height: 1.5;">
                        Saat ini Anda berada dalam mode <strong>Preview</strong> (Dominasi #1). Buka hasil lengkap untuk melihat seluruh diagram kecenderungan, panduan pengembangan diri, rekomendasi jurusan &amp; karier, serta Sertifikat Asesmen SIAP.
                    </p>
                    <a href="{{ route('siap.results', $attempt) }}" style="display: inline-block; background: #E50000; color: #ffffff; text-decoration: none; font-weight: bold; font-size: 13px; padding: 10px 18px; border-radius: 10px;">
                        Lihat Hasil &amp; Buka Akses Penuh
                    </a>
                </div>
            @else
                <h3 style="font-size: 16px; margin-bottom: 10px;">Ringkasan Seluruh Kecenderungan</h3>
                @foreach($scores as $idx => $item)
                    <div style="border-bottom: 1px solid #e5e7eb; padding: 8px 0; font-size: 13px;">
                        <strong>#{{ $idx + 1 }} {{ $item['name'] }}</strong> — {{ $item['percentage'] }}%
                    </div>
                @endforeach

                @if(!empty($recommendations))
                    <h3 style="font-size: 16px; margin: 20px 0 10px 0;">Langkah Selanjutnya untuk Mengembangkan Potensimu</h3>
                    @foreach($recommendations as $row)
                        <div style="background: #fff5f5; border: 1px solid #fecaca; border-radius: 8px; padding: 10px 12px; margin-bottom: 8px; font-size: 13px;">
                            <div style="font-weight: bold; color: #991b1b;">{{ $row['focus'] }}</div>
                            <div style="color: #374151; margin-top: 4px;">{{ $row['recommendation'] }}</div>
                        </div>
                    @endforeach
                @endif

                <div style="margin-top: 20px;">
                    <a href="{{ route('siap.results', $attempt) }}" style="display: inline-block; background: #E50000; color: #ffffff; text-decoration: none; font-weight: bold; font-size: 13px; padding: 10px 18px; border-radius: 10px;">
                        Buka Laporan &amp; Sertifikat di 1Langkah
                    </a>
                </div>
            @endif

            <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #9ca3af; text-align: center;">
                SIAP — Sistem Integrasi Asesmen dan Pengembangan • 1Langkah<br>
                Dikirim otomatis ke {{ $attempt->google_email ?? $attempt->user?->email }}.
            </div>
        </div>
    </div>
</body>
</html>
