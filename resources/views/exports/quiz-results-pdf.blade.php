<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Hasil Quiz{{ $quiz ? ' - ' . $quiz->title : ' (Semua Quiz)' }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111827; margin: 24px; font-size: 12px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #e5e7eb; padding-bottom: 16px; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; margin: 0 0 4px 0; }
        .subtitle { color: #4b5563; margin: 2px 0; }
        .actions { display: flex; gap: 8px; }
        .btn { background: #dc2626; color: #fff; border: none; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer; text-decoration: none; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #d1d5db; padding: 8px 10px; text-align: left; vertical-align: top; }
        th { background-color: #f3f4f6; font-weight: bold; text-transform: uppercase; font-size: 11px; }
        .badge-pass { color: #15803d; font-weight: bold; }
        .badge-fail { color: #b91c1c; font-weight: bold; }
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <div>
            <h1 class="title">Laporan Data Diri & Hasil Quiz</h1>
            @if($quiz)
                <p class="subtitle"><strong>Quiz:</strong> {{ $quiz->title }} ({{ strtoupper(str_replace('_', ' ', $quiz->type)) }})</p>
                <p class="subtitle"><strong>Kursus:</strong> {{ $quiz->course->title ?? '-' }} | <strong>Passing Score:</strong> {{ $quiz->passing_score }}%</p>
            @else
                <p class="subtitle"><strong>Cakupan:</strong> Rekap Seluruh Peserta & Hasil Quiz ({{ $attempts->count() }} data)</p>
            @endif
            <p class="subtitle"><strong>Dicetak pada:</strong> {{ now()->format('d/m/Y H:i') }}</p>
        </div>
        <div class="actions no-print">
            <button class="btn" onclick="window.print()">Cetak / Simpan PDF</button>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Attempt / User</th>
                <th>Nama & Email Google</th>
                <th>TTL</th>
                <th>Alamat</th>
                <th>No. WA</th>
                @if(!$quiz)
                <th>Quiz / Kursus</th>
                @endif
                <th>Skor & Kepribadian</th>
                <th>Status Akses</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attempts as $index => $attempt)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>QUIZ-{{ $attempt->id }}<br><small>UID: {{ $attempt->user_id }}</small></td>
                <td>
                    <strong>{{ $attempt->participant_name ?? $attempt->user?->name ?? '-' }}</strong><br>
                    <small>{{ $attempt->google_email ?? $attempt->user?->email ?? '-' }}</small>
                </td>
                <td>{{ $attempt->formatted_ttl }}</td>
                <td>{{ $attempt->address ?? '-' }}</td>
                <td>{{ $attempt->whatsapp ?? '-' }}</td>
                @if(!$quiz)
                <td>
                    <strong>{{ $attempt->quiz?->title ?? ucfirst(str_replace('_', ' ', $attempt->test_type)) }}</strong><br>
                    <small>{{ $attempt->quiz?->course?->title ?? ($attempt->quiz?->is_global ? 'Tes Psikologi Global' : ($attempt->testable?->title ?? '-')) }}</small>
                </td>
                @endif
                <td>
                    <strong>{{ number_format((float) $attempt->score, 1) }}%</strong> ({{ is_array($attempt->answers) ? count($attempt->answers) : $attempt->correct_answers }}/{{ $attempt->total_questions }} soal)<br>
                    <small>{{ $attempt->personality_profile['archetype'] ?? '-' }}</small>
                </td>
                <td>
                    @if($attempt->is_paid)
                        <span class="badge-pass">LUNAS (Rp {{ number_format($attempt->payment_amount ?: 30000, 0, ',', '.') }})</span>
                    @else
                        <span class="badge-fail">PREVIEW</span>
                    @endif
                </td>
                <td>{{ $attempt->completed_at?->format('d/m/Y H:i') ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ !$quiz ? 10 : 9 }}" style="text-align: center; padding: 20px;">Belum ada data hasil quiz.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
