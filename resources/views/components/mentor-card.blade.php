@php
    /** @var array $mentor  mentor record from CatalogService::mentors() */
    $m = $mentor ?? [];
    $profileUrl = route('profil-mentor', ['id' => $m['id']]);
@endphp
<div class="card mentor-card">
    <a href="{{ $profileUrl }}" style="display:block;text-decoration:none;color:inherit">
        <div style="width:100%;aspect-ratio:1;background:linear-gradient(135deg,{{ $m['color'] }},{{ $m['color'] }}cc);display:flex;align-items:center;justify-content:center">
            <div class="avatar-lg" style="background:rgba(255,255,255,.2);font-size:22px">{{ $m['initials'] }}</div>
        </div>
    </a>
    <div class="card-body" style="padding:16px">
        <div class="mentor-name">{{ $m['name'] }}</div>
        <div class="mentor-role">{{ $m['role'] }}</div>
        <div class="mentor-company">{{ $m['company'] }}</div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:12px">
            <span class="mentor-price">{{ $m['price'] }}</span>
            <a href="{{ $profileUrl }}" class="btn btn-primary btn-sm">Book</a>
        </div>
    </div>
</div>
