@extends('layouts.app', ['activePage' => 'mentor'])

@section('title', $mentor['name'] . ' — 1Langkah')
@section('header_title', __('app.mentor_profile') ?? 'Profil Mentor')

@section('content')
@php
    $m = $mentor;
    $priceNumber = $m['formatted_price'] ?? 'Gratis';
    $schedules = $m['schedules'] ?? [];
    $isTodayAvailable = $m['is_today_available'] ?? false;

    // Avatar Logic
    $firstName = explode(' ', $m['name'])[0];
    $isWoman = in_array($firstName, ['Siti', 'Dewi', 'Sari', 'Rina', 'Ani', 'Nisa', 'Lina', 'Wati']);
    $genderPath = $isWoman ? 'women' : 'men';
    $picId = ($m['id'] % 70) + 1;
    $avatarUrl = $m['profile_photo'] ?? "https://randomuser.me/api/portraits/{$genderPath}/{$picId}.jpg";

    // Day labels
    $dayLabels = [
        0 => 'Minggu',
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
    ];
@endphp

<div class="w-full px-2 pb-12">
    <!-- Back Navigation -->
    <a href="{{ route('mentor') }}" class="inline-flex items-center gap-2 text-[14px] font-medium text-gray-500 hover:text-gray-900 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        {{ __('app.back_to_mentor_marketplace') }}
    </a>

    <!-- Top Banner (Red Hero) -->
    <div class="w-full bg-gradient-to-r from-[#b90000] to-[#800000] rounded-[24px] p-8 md:p-10 mb-8 flex flex-col md:flex-row items-center md:items-start md:justify-between gap-8 shadow-md relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute right-0 top-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>

        <div class="flex flex-col md:flex-row items-center md:items-center gap-6 relative z-10 w-full md:w-auto text-center md:text-left">
            <!-- Avatar -->
            <div class="w-24 h-24 md:w-28 md:h-28 rounded-full bg-white p-1.5 shadow-lg flex-shrink-0">
                <img src="{{ $avatarUrl }}" alt="{{ $m['name'] }}" class="w-full h-full rounded-full object-cover">
            </div>

            <!-- Mentor Info -->
            <div class="text-white">
                <div class="flex flex-col md:flex-row items-center gap-3 mb-1.5 justify-center md:justify-start">
                    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">{{ $m['name'] }}</h1>
                    @if($isTodayAvailable)
                        <span class="px-3 py-1 bg-[#00e676] text-[#004d40] text-[11px] font-bold rounded-full shadow-sm">{{ __('app.available') }}</span>
                    @else
                        <span class="px-3 py-1 bg-white/20 text-white text-[11px] font-bold rounded-full shadow-sm">{{ __('app.offline') }}</span>
                    @endif
                </div>
                <div class="text-red-100 text-[15px] mb-4">
                    {{ $m['role'] }}<br>
                    <span class="font-bold text-white">{{ $m['company'] ?: '-' }}</span>
                </div>
                <div class="flex items-center justify-center md:justify-start gap-3 text-[13px] text-white/90 font-medium">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <span class="font-bold text-white">{{ number_format((float) ($m['rating'] ?? 0), 1) }}</span>
                        <span class="opacity-80">({{ $m['rating_count'] ?? 0 }} {{ __('app.reviews_count') }})</span>
                    </div>
                    <span class="opacity-50">&middot;</span>
                    <span>{{ $m['sessions'] }} {{ __('app.sessions_completed') }}</span>
                </div>
            </div>
        </div>

        <!-- Pricing Header -->
        <div class="relative z-10 text-center md:text-right mt-4 md:mt-0 pt-4 md:pt-4">
            <div class="text-red-200 text-[12px] font-medium mb-0.5">{{ __('app.starting_from') }}</div>
            <div class="text-white text-3xl md:text-4xl font-extrabold tracking-tight mb-1">{{ $priceNumber }}</div>
            <div class="text-red-200 text-[12px] font-medium">{{ __('app.per_session_60_min') }}</div>
        </div>
    </div>

    <!-- Main Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Left Column -->
        <div class="lg:col-span-8 flex flex-col gap-6">
            <!-- Tentang Mentor -->
            <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-sm">
                <h3 class="text-xl font-bold text-gray-900 mb-5">{{ __('app.about_mentor') }}</h3>
                <div class="text-[15px] text-gray-600 leading-relaxed space-y-4">
                    @if(!empty($m['bio']))
                        <p>{{ $m['bio'] }}</p>
                    @else
                        <p>{{ __('app.mentor_default_bio', ['name' => $m['name'], 'role' => $m['role']]) }}</p>
                    @endif
                </div>
            </div>

            <!-- LinkedIn Profile -->
            @if(!empty($m['linkedin_url']))
            <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-sm">
                <h3 class="text-xl font-bold text-gray-900 mb-5">LinkedIn Profile</h3>
                <div class="rounded-xl overflow-hidden border border-gray-200">
                    <iframe
                        src="{{ str_replace('linkedin.com/in/', 'linkedin.com/embed/', $m['linkedin_url']) }}"
                        height="400"
                        frameborder="0"
                        allowfullscreen=""
                        title="LinkedIn Profile"
                        class="w-full">
                    </iframe>
                </div>
                <a href="{{ $m['linkedin_url'] }}" target="_blank" class="inline-flex items-center gap-2 mt-4 text-blue-600 hover:text-blue-700 text-sm font-medium">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    {{ __('app.see_linkedin_profile') }}
                </a>
            </div>
            @endif

            <!-- Bidang Keahlian -->
            <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-sm">
                <h3 class="text-xl font-bold text-gray-900 mb-5">{{ __('app.area_of_expertise') }}</h3>
                <div class="flex flex-wrap gap-2 mb-8">
                    @forelse($m['expertise'] as $skill)
                        <span class="px-4 py-1.5 bg-red-50 border border-red-100 text-[#dc2626] text-[13px] font-bold rounded-full">{{ $skill }}</span>
                    @empty
                        <span class="text-gray-400 text-sm">{{ __('app.no_expertise_added') }}</span>
                    @endforelse
                </div>

                <!-- Stats Row -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-gray-50 rounded-2xl p-4 text-center flex flex-col justify-center">
                        <div class="text-xl font-black text-red-700 mb-1">{{ $m['sessions'] }}+</div>
                        <div class="text-[11px] text-gray-500 font-medium">{{ __('app.total_sessions') }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-2xl p-4 text-center flex flex-col justify-center">
                        <div class="text-xl font-black text-red-700 mb-1">{{ number_format((float) ($m['rating'] ?? 0), 1) }}/5</div>
                        <div class="text-[11px] text-gray-500 font-medium">{{ __('app.rating') }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-2xl p-4 text-center flex flex-col justify-center">
                        <div class="text-xl font-black text-red-700 mb-1">{{ $m['rating_count'] ?? 0 }}</div>
                        <div class="text-[11px] text-gray-500 font-medium">{{ __('app.reviews') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column (Sticky Sidebar) -->
        <div class="lg:col-span-4">
            <div class="bg-white border border-gray-100 rounded-3xl p-6 lg:p-8 shadow-sm lg:sticky lg:top-24">
                <!-- Quick Contact -->
                <div class="flex items-center gap-3 mb-6 pb-6 border-b border-gray-100">
                    <img src="{{ $avatarUrl }}" alt="{{ $m['name'] }}" class="w-14 h-14 rounded-full object-cover border-2 border-gray-100">
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-gray-900 truncate">{{ $m['name'] }}</h4>
                        <p class="text-sm text-gray-500 truncate">{{ $m['role'] }}@if(!empty($m['company'])) di {{ $m['company'] }}@endif</p>
                    </div>
                </div>

                <!-- Expertise Tags -->
                @if(!empty($m['expertise']))
                <div class="mb-6">
                    <h4 class="text-[13px] font-semibold text-gray-500 mb-2">{{ __('app.area_of_expertise') }}</h4>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach(array_slice($m['expertise'], 0, 4) as $skill)
                            <span class="px-2.5 py-1 bg-red-50 border border-red-100 text-[#dc2626] text-[11px] font-medium rounded-full">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Quick Bio -->
                @if(!empty($m['bio']))
                <div class="mb-6">
                    <h4 class="text-[13px] font-semibold text-gray-500 mb-2">{{ __('app.about_mentor') }}</h4>
                    <p class="text-sm text-gray-600 line-clamp-3">{{ Str::limit(strip_tags($m['bio']), 150) }}</p>
                </div>
                @endif

                <!-- Contact Links -->
                <div class="flex gap-2 mb-6">
                    @if(!empty($m['linkedin_url']))
                    <a href="{{ $m['linkedin_url'] }}" target="_blank"
                       class="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-[#0077b5] hover:bg-[#006097] text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        LinkedIn
                    </a>
                    @endif
                    @if(!empty($m['phone']))
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $m['phone']) }}" target="_blank"
                       class="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-[#25D366] hover:bg-[#20BD5A] text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
299:                         WhatsApp
300:                     </a>
301:                     @endif
302:                 </div>
303: 
304:                 <h3 class="text-[17px] font-bold text-gray-900 mb-5">{{ __('app.available_schedule') }}</h3>
305: 
306:                 <div class="space-y-3 mb-8">
307:                     @forelse($schedules as $schedule)
308:                         <div class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 transition-colors {{ !$schedule['is_available'] ? 'opacity-60' : '' }}">
309:                             <div>
310:                                 <div class="text-[14px] font-bold text-gray-900 mb-0.5">{{ $schedule['day_name'] }}</div>
311:                                 <div class="text-[12px] text-gray-400">{{ \Carbon\Carbon::parse($schedule['start_time'])->format('H:i') }} – {{ \Carbon\Carbon::parse($schedule['end_time'])->format('H:i') }} WIB</div>
312:                             </div>
313:                             @if($schedule['is_available'])
314:                                 <span class="px-3 py-1 bg-[#f0fdf4] text-[#16a34a] text-[11px] font-bold rounded-full">{{ __('app.available') }}</span>
315:                             @else
316:                                 <span class="px-3 py-1 bg-gray-100 text-gray-500 text-[11px] font-bold rounded-full">{{ __('app.unavailable') }}</span>
317:                             @endif
318:                         </div>
319:                     @empty
320:                         <p class="text-gray-400 text-sm">{{ __('app.no_available_schedule') }}</p>
321:                     @endforelse
322:                 </div>
323: 
324:                 <div class="space-y-3 mb-8">
325:                     <div class="flex justify-between items-center text-[13px]">
326:                         <span class="text-gray-500">{{ __('app.session_duration') }}</span>
327:                         <span class="font-bold text-gray-900">{{ __('app.60_minutes') }}</span>
328:                     </div>
329:                     <div class="flex justify-between items-center text-[13px]">
330:                         <span class="text-gray-500">{{ __('app.via') }}</span>
331:                         <span class="font-bold text-gray-900">Google Meet / Zoom</span>
332:                     </div>
333:                 </div>
334: 
335:                 <div class="text-center mb-6">
336:                     <div class="text-[28px] font-extrabold text-gray-900 tracking-tight mb-1">{{ $priceNumber }}</div>
337:                     <div class="text-[12px] text-gray-400 font-medium">{{ __('app.per_session') }}</div>
338:                 </div>
339: 
340:                 <!-- Booking Form -->
341:                 <form action="{{ route('mentor.book', $m['id']) }}" method="POST" class="space-y-4 mb-4" x-data="bookingForm()">
342:                     @csrf
343:                     <input type="hidden" name="booked_date" value="{{ now()->toDateString() }}">
344:                     @php
345:                         $today = now();
346:                         $todayLabel = $dayLabels[(int)$today->format('w')] ?? $today->format('l');
347:                     @endphp
348: 
349:                     <!-- Current Date Display -->
350:                     <div class="bg-gray-50 rounded-xl p-4 text-center">
351:                         <div class="text-[12px] text-gray-500 mb-1">{{ __('app.session_for_today') }}</div>
352:                         <div class="text-[16px] font-bold text-gray-900">{{ $today->format('d M Y') }} ({{ $todayLabel }})</div>
353:                         @if($isTodayAvailable)
354:                             <span class="inline-block mt-2 px-3 py-1 bg-[#f0fdf4] text-[#16a34a] text-[11px] font-bold rounded-full">{{ __('app.mentor_available') }}</span>
355:                         @else
356:                             <span class="inline-block mt-2 px-3 py-1 bg-red-50 text-red-600 text-[11px] font-bold rounded-full">{{ __('app.not_available_today') }}</span>
357:                         @endif
358:                     </div>
359: 
360:                     <div x-show="$store.booking.available">
361:                         <label class="block text-[13px] font-medium text-gray-700 mb-1.5">{{ __('app.choose_time') }}</label>
362:                         <select name="booked_time" x-model="selectedTime" required
363:                             class="w-full rounded-lg border-gray-200 border px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
364:                             <option value="">{{ __('app.select_time') }}</option>
365:                             <template x-for="slot in $store.booking.timeSlots" :key="slot.time">
366:                                 <option :value="slot.time" :disabled="!slot.available" x-text="slot.label + (slot.available ? '' : ' {{ __('app.full_slot') }}')"></option>
367:                             </template>
368:                         </select>
369:                     </div>
370:                     <div>
371:                         <label class="block text-[13px] font-medium text-gray-700 mb-1.5">{{ __('app.notes_optional') }}</label>
372:                         <textarea name="notes" rows="2" class="w-full rounded-lg border-gray-200 border px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="{{ __('app.topics_to_discuss') }}"></textarea>
373:                     </div>
374:                     @auth
375:                     <button type="submit" x-show="$store.booking.available && selectedTime" x-transition
376:                         class="w-full bg-[#d00000] hover:bg-red-700 text-white font-bold py-3.5 rounded-full text-center transition-colors shadow-sm text-[15px]">
377:                         {{ __('app.pay_now') }}
378:                     </button>
379:                     <div x-show="$store.booking.available && !selectedTime" class="w-full bg-gray-200 text-gray-500 font-bold py-3.5 rounded-full text-center text-[15px] cursor-not-allowed">
380:                         {{ __('app.choose_time_first') }}
381:                     </div>
382:                     @else
383:                     <a href="{{ route('login') }}" class="block w-full bg-[#d00000] hover:bg-red-700 text-white font-bold py-3.5 rounded-full text-center transition-colors shadow-sm text-[15px]">
384:                         {{ __('app.login_to_book') }}
385:                     </a>
386:                     @endauth
387:                 </form>
388: 
389:                 @error('booked_date')
390:                     <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
391:                 @enderror
392:                 @error('booked_time')
393:                     <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
394:                 @enderror
395:             </div>
396:         </div>
397:     </div>
398: 
399:     <script>
400:     document.addEventListener('alpine:init', () => {
401:         Alpine.store('booking', {
402:             available: {{ $isTodayAvailable ? 'true' : 'false' }},
403:             timeSlots: [
404:                 { time: '09:00', label: '09:00 WIB', available: true },
405:                 { time: '10:00', label: '10:00 WIB', available: true },
406:                 { time: '11:00', label: '11:00 WIB', available: true },
407:                 { time: '13:00', label: '13:00 WIB', available: true },
408:                 { time: '14:00', label: '14:00 WIB', available: true },
409:                 { time: '15:00', label: '15:00 WIB', available: true },
410:                 { time: '16:00', label: '16:00 WIB', available: true },
411:                 { time: '19:00', label: '19:00 WIB', available: true },
412:                 { time: '20:00', label: '20:00 WIB', available: true },
413:                 { time: '21:00', label: '21:00 WIB', available: true },
414:             ]
415:         });
416:     });
417: 
418:     function bookingForm() {
419:         return {
420:             selectedTime: '',
421:         }
422:     }
423:     </script>
</div>
@endsection
