<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Tes - {{ $quiz->title ?? 'Tes Psikologi' }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9fafb; color: #111827; padding: 24px; margin: 0;">
    <div style="max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 16px; border: 1px solid #e5e7eb; overflow: hidden;">
        <div style="background: #cc0000; color: #ffffff; padding: 24px;">
            <div style="font-size: 12px; text-transform: uppercase; letter-spacing: 1px; opacity: 0.9;">
                {{ $isFullReport ? 'Laporan Lengkap Hasil Tes & Kepribadian' : 'Hasil Preview Tes Psikologi' }}
            </div>
            <h1 style="margin: 6px 0 0 0; font-size: 22px;">{{ $quiz->title ?? 'Tes Psikologi Global' }}</h1>
        </div>

        <div style="padding: 24px;">
            <p style="margin-top: 0;">Halo <strong>{{ $attempt->participant_name ?? $attempt->user?->name }}</strong>,</p>

            @php
                $profile = $attempt->personality_profile;
            @endphp

            <div style="background: #f3f4f6; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                <div style="font-size: 12px; color: #6b7280;">ID Peserta / Attempt: <strong>QUIZ-{{ $attempt->id }}</strong> | Email Google: <strong>{{ $attempt->google_email ?? $attempt->user?->email }}</strong></div>
                <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">TTL: <strong>{{ $attempt->formatted_ttl }}</strong> | No. WA: <strong>{{ $attempt->whatsapp ?? '-' }}</strong></div>
                <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">Alamat: <strong>{{ $attempt->address ?? '-' }}</strong></div>
            </div>

            <div style="border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                <div style="font-size: 12px; color: #6b7280; text-transform: uppercase; font-weight: bold;">Tipe Kepribadian & Skor</div>
                <h2 style="margin: 6px 0 2px 0; font-size: 18px; color: #111827;">{{ $profile['archetype'] }} ({{ number_format((float) $attempt->score, 0) }}%)</h2>
                @if(!empty($profile['tagline']))
                    <div style="font-size: 13px; color: #4b5563; font-style: italic; margin-bottom: 8px;">{{ $profile['tagline'] }}</div>
                @endif
                <p style="font-size: 14px; color: #374151; line-height: 1.6; margin-bottom: 0;">
                    @if($isFullReport)
                        {{ $profile['full_summary'] }}
                    @else
                        {{ $profile['preview'] }}
                    @endif
                </p>

                @if($isFullReport)
                    @if(!empty($profile['superpower']))
                        <div style="margin-top: 12px; padding: 10px 12px; background: #eef2ff; border-radius: 8px; font-size: 13px;">
                            <strong>⚡ Superpower Utama:</strong> {{ $profile['superpower'] }}
                        </div>
                    @endif
                    @if(!empty($profile['careers']))
                        <div style="margin-top: 8px; font-size: 13px; color: #374151;">
                            <strong>🎯 Rekomendasi Karir:</strong> {{ implode(', ', $profile['careers']) }}
                        </div>
                    @endif
                @endif
            </div>

            @if(!$isFullReport)
                <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                    <h3 style="margin: 0 0 6px 0; font-size: 15px; color: #92400e;">🔒 Rangkuman Penuh & Rincian Semua Jawaban Masih Terkunci</h3>
                    <p style="margin: 0 0 12px 0; font-size: 13px; color: #78350f; line-height: 1.5;">
                        Saat ini Anda berada dalam mode <strong>Preview</strong>. Untuk membuka analisis kepribadian secara penuh, melihat rincian seluruh jawaban Anda, serta mengerjakan seluruh soal tanpa batas kunci, silakan lakukan pembayaran sebesar <strong>Rp {{ number_format($attempt->payment_amount ?: 30000, 0, ',', '.') }}</strong>.
                    </p>
                    <a href="{{ route('quiz.result', $attempt) }}" style="display: inline-block; background: #cc0000; color: #ffffff; text-decoration: none; font-weight: bold; font-size: 13px; padding: 10px 18px; border-radius: 999px;">
                        Buka Hasil Penuh Sekarang (Rp {{ number_format($attempt->payment_amount ?: 30000, 0, ',', '.') }})
                    </a>
                </div>
            @else
                @if($quiz && $quiz->questions->count() > 0)
                <h3 style="font-size: 16px; margin-bottom: 12px;">Rincian Semua Jawaban Anda</h3>
                @foreach($quiz->questions->sortBy('order') as $idx => $q)
                    @php
                        $userAnsId = $attempt->answers[$q->id] ?? null;
                        $selectedAns = $q->answers->firstWhere('id', (int) $userAnsId);
                        $correctAns = $q->answers->firstWhere('is_correct', true);
                    @endphp
                    <div style="border-bottom: 1px solid #e5e7eb; padding: 10px 0; font-size: 13px;">
                        <div><strong>{{ $idx + 1 }}. {{ $q->question }}</strong></div>
                        <div style="color: #4b5563; margin-top: 4px;">
                            Jawaban Anda: <strong>{{ $selectedAns?->answer_text ?? ($userAnsId ?: 'Tidak dijawab') }}</strong>
                            @if($correctAns)
                                | Kunci: <strong>{{ $correctAns->answer_text }}</strong>
                            @endif
                        </div>
                    </div>
                @endforeach
                @endif
            @endif

            <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #9ca3af; text-align: center;">
                Email ini dikirim otomatis oleh platform 1Langkah ke akun Google Anda ({{ $attempt->google_email ?? $attempt->user?->email }}).
            </div>
        </div>
    </div>
</body>
</html>
