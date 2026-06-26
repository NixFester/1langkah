@php
    /**
     * Course card (used on landing, kursus, kursus-saya, dashboard recommendations)
     *
     * @var array $course  course record from CatalogService::courses()
     * @var bool  $aiPick  if true, override the badge area with an "AI Pick" purple badge
     */
    $course = $course ?? [];
    $aiPick = $aiPick ?? false;

    $c = $course;
    $detailUrl = route('detail-kursus', ['id' => $c['id']]);

    $badges = [];
    if (! $aiPick && ! empty($c['badge'])) {
        $badges[] = view('components.badge', ['text' => $c['badge'], 'type' => 'gold'])->render();
    }
    if ($aiPick) {
        $badges[] = view('components.badge', ['text' => 'AI Pick', 'type' => 'purple'])->render();
    }
    $badges[] = view('components.badge', ['text' => $c['level'], 'type' => 'dark'])->render();
@endphp
<a href="{{ $detailUrl }}" class="card course-card" style="text-decoration:none;color:inherit">
    <div class="course-card-img" style="background:linear-gradient(135deg,{{ $c['color'] }},{{ $c['color'] }}dd)">
        <div class="badges">{!! implode('', $badges) !!}</div>
        @if($c['progress'] > 0)
            <span style="position:absolute;bottom:10px;right:10px;color:#fff;font-size:12px;font-weight:600">{{ $c['progress'] }}% done</span>
        @else
            <span class="price-tag">{{ $c['price'] }}</span>
        @endif
    </div>
    <div class="course-card-body">
        <div class="course-card-title">{{ $c['title'] }}</div>
        <div class="course-card-meta">{{ $c['mentor'] }} · {{ $c['mentorCompany'] }}</div>
        @if($c['progress'] > 0)
            <div class="progress-bar" style="margin-bottom:8px"><div class="progress-fill" style="width:{{ $c['progress'] }}%"></div></div>
            <div style="font-size:11px;color:var(--text-light)">{{ $c['progress'] }}% selesai</div>
        @else
            <div class="course-card-footer">
                <div>
                    <x-stars :rating="$c['rating']" />
                    <span style="font-size:11px;color:var(--text-light);margin-left:4px">{{ $c['rating'] }}</span>
                </div>
                <span style="font-size:11px;color:var(--text-light)">{{ number_format($c['students'] / 1000, 1) }}k siswa</span>
            </div>
        @endif
    </div>
</a>
