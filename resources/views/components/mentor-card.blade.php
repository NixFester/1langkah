@php
    /** @var array $mentor  mentor record from CatalogService::mentors() */
    $m = $mentor ?? [];
    $profileUrl = route('profil-mentor', ['id' => $m['id']]);
    
    // Simulate online status for visual parity with the design
    $isOnline = $m['id'] % 2 !== 0; 
@endphp

<div class="bg-white border border-gray-100 rounded-[24px] p-4 sm:p-6 shadow-[0_2px_12px_rgb(0,0,0,0.03)] hover:shadow-lg transition-shadow flex flex-col h-full relative">
    <!-- Top Row: Avatar & Status Dot -->
    <div class="flex justify-between items-start mb-3 sm:mb-5">
        @php
            $firstName = explode(' ', $m['name'])[0];
            $isWoman = in_array($firstName, ['Siti', 'Dewi', 'Sari', 'Rina', 'Ani', 'Nisa', 'Lina', 'Wati']);
            $genderPath = $isWoman ? 'women' : 'men';
            $picId = ($m['id'] % 70) + 1; // 1 to 70
            $avatarUrl = $m['profile_photo'] ?? "https://randomuser.me/api/portraits/{$genderPath}/{$picId}.jpg";
        @endphp
        <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-white shadow-md">
            <img decoding="async" loading="lazy" src="{{ $avatarUrl }}" alt="{{ $m['name'] }}" class="w-full h-full object-cover">
        </div>
        <div class="w-3 h-3 rounded-full {{ $isOnline ? 'bg-[#00c853]' : 'bg-gray-300' }} mt-1"></div>
    </div>

    <!-- Info -->
    <h2 class="text-[19px] font-bold text-[#0f172a] mb-0.5 leading-tight">{{ $m['name'] }}</h2>
    <p class="text-[14px] text-[#64748b] mb-0.5">{{ $m['role'] }}</p>
    <p class="text-[13px] font-semibold text-[#dc2626] mb-3">{{ $m['company'] }}</p>

    <!-- Rating -->
    <div class="flex items-center gap-2 mb-3 sm:mb-4 text-[13px]">
        <div class="flex text-[#fbbf24] gap-0.5">
            @php $rating = round($m['rating']); @endphp
            @for($i=1; $i<=5; $i++)
                @if($i <= $rating)
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                @else
                    <svg class="w-4 h-4 text-gray-200" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                @endif
            @endfor
        </div>
        <span class="text-[#334155] font-medium ml-1">{{ number_format($m['rating'], 1) }}</span>
        <span class="text-gray-300">&middot;</span>
        <span class="text-[#94a3b8]">{{ $m['sessions'] }} {{ __('app.sessions') }}</span>
    </div>

    <!-- Tags -->
    <div class="flex flex-wrap items-start content-start gap-2 mb-4 sm:mb-8 sm:min-h-[56px]">
        @foreach($m['expertise'] as $skill)
            <span class="px-2.5 py-1 bg-red-50 border border-red-200 text-[#dc2626] text-[11px] font-medium rounded-full">{{ $skill }}</span>
        @endforeach
    </div>

    <!-- Footer -->
    <div class="flex items-end justify-between mt-auto pt-2">
        <div>
            <div class="text-[11px] text-[#94a3b8] mb-0.5 font-medium">{{ __('app.per_session') }}</div>
        <div class="text-[17px] font-extrabold text-[#0f172a] tracking-tight">{{ $m['formatted_price'] ?? __('app.free') }}</div>
        </div>
        <div class="flex items-center gap-2">
            @if(!empty($m['phone']))
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $m['phone']) }}" target="_blank"
               class="w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-[#25D366] hover:bg-[#25D366] hover:text-white hover:border-[#25D366] transition-colors"
               title="Chat WhatsApp">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
            </a>
            @endif
            <a href="{{ $profileUrl }}" class="w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
            </a>
            <a href="{{ $profileUrl }}" class="bg-[#d00000] hover:bg-red-700 text-white text-[15px] font-bold py-2 px-6 rounded-full transition-colors shadow-sm">
                {{ __('app.book_now') }}
            </a>
        </div>
    </div>
</div>
