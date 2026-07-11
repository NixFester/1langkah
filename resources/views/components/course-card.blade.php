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

    // Format students count
    $studentsStr = number_format($c['students']);
@endphp
<a href="{{ $detailUrl }}" class="group block bg-white rounded-xl overflow-hidden border border-gray-100 hover:shadow-lg transition-all duration-300 relative flex flex-col h-full">
    
    <!-- Image Section -->
    <div class="relative h-48 w-full bg-gray-100 overflow-hidden">
        @if(!empty($c['thumbnail']))
            <img src="{{ $c['thumbnail'] }}" alt="{{ $c['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @else
            <div class="w-full h-full" style="background:linear-gradient(135deg,{{ $c['color'] }},{{ $c['color'] }}dd);"></div>
        @endif

        <!-- Top Badges -->
        <div class="absolute top-3 left-3 flex items-center gap-2 z-10">
            @if($aiPick)
                <span class="bg-purple-100 text-purple-700 text-xs font-bold px-2.5 py-1 rounded-full">AI Pick</span>
            @elseif(!empty($c['badge']))
                @if($c['badge'] === 'Bestseller')
                    <span class="bg-[#FFF4ED] text-[#F97316] text-xs font-bold px-2.5 py-1 rounded-full">{{ $c['badge'] }}</span>
                @elseif($c['badge'] === 'New')
                    <span class="bg-red-50 text-red-600 text-xs font-bold px-2.5 py-1 rounded-full">{{ $c['badge'] }}</span>
                @elseif($c['badge'] === 'Hot')
                    <span class="bg-rose-50 text-rose-600 text-xs font-bold px-2.5 py-1 rounded-full">{{ $c['badge'] }}</span>
                @else
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2.5 py-1 rounded-full">{{ $c['badge'] }}</span>
                @endif
            @endif
            <span class="bg-white text-gray-800 text-xs font-bold px-2.5 py-1 rounded-full shadow-sm">{{ $c['level'] }}</span>
        </div>

        <!-- Bookmark Button -->
        <button class="absolute top-3 right-3 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-sm text-yellow-500 hover:scale-110 transition-transform z-10">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
        </button>

        <!-- Progress Bar at bottom of image -->
        @if($c['progress'] > 0)
            <div class="absolute bottom-0 left-0 w-full h-1.5 bg-gray-200">
                <div class="h-full bg-emerald-500" style="width: {{ $c['progress'] }}%"></div>
            </div>
        @endif
    </div>

    <!-- Body Section -->
    <div class="p-4 flex flex-col flex-grow">
        <!-- Category Pill -->
        <div class="mb-3">
            @php
                // Assign colors based on category loosely
                $catColor = 'text-red-600 bg-red-50';
                if ($c['category'] === 'Design') $catColor = 'text-pink-600 bg-pink-50';
                if ($c['category'] === 'AI') $catColor = 'text-red-600 bg-red-50';
                if ($c['category'] === 'Data') $catColor = 'text-orange-600 bg-orange-50';
            @endphp
            <span class="inline-block px-2.5 py-0.5 rounded-md text-xs font-bold {{ $catColor }}">
                {{ $c['category'] }}
            </span>
        </div>

        <!-- Title -->
        <h3 class="font-bold text-gray-900 text-base leading-tight mb-2 line-clamp-2">{{ $c['title'] }}</h3>
        
        <!-- Mentor Info -->
        <p class="text-xs text-gray-500 mb-3">{{ $c['mentor'] }} · {{ $c['mentorCompany'] }}</p>

        <!-- Rating -->
        <div class="flex items-center gap-1.5 mb-4">
            <div class="flex text-yellow-400">
                @for($i=1; $i<=5; $i++)
                    @if($i <= floor($c['rating']))
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    @else
                        <svg class="w-3.5 h-3.5 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    @endif
                @endfor
            </div>
            <span class="text-xs font-semibold text-gray-700">{{ $c['rating'] }}</span>
            <span class="text-xs text-gray-400">({{ $studentsStr }})</span>
        </div>

        <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
            <!-- Duration -->
            <div class="flex items-center text-gray-400 text-xs font-medium gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                {{ $c['duration'] ?? '24h' }}
            </div>
            
            <!-- Price or Progress -->
            @if($c['progress'] > 0)
                <span class="text-sm font-bold text-emerald-500">{{ $c['progress'] }}% {{ __('app.done') }}</span>
            @else
                @if(is_numeric($c['price']) && $c['price'] > 0)
                    <span class="text-base font-bold text-gray-900">Rp {{ number_format($c['price'], 0, ',', '.') }}</span>
                @elseif(is_numeric($c['price']) && $c['price'] == 0 || empty($c['price']) || strtolower($c['price']) === 'gratis')
                    <span class="text-base font-bold text-emerald-600">{{ __('app.free') }}</span>
                @else
                    <span class="text-base font-bold text-gray-900">{{ $c['price'] }}</span>
                @endif
            @endif
        </div>
    </div>
</a>
