@extends('layouts.app')

@section('title', 'Kalender')

@section('content')
@php
    // Build calendar data
    $today = now();
    $currentYear = $currentYear ?? $today->year;
    $currentMonth = $currentMonth ?? $today->month;

    $firstDayOfMonth = \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1);
    $daysInMonth = $firstDayOfMonth->daysInMonth;
    $startDayOfWeek = $firstDayOfMonth->dayOfWeek; // 0 = Sunday

    $monthNameID = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][$currentMonth - 1] ?? $firstDayOfMonth->format('F');

    // Encode enrolled IDs for client-side filtering
    $enrolledCourseIds = array_column($userEnrolledCourses ?? [], 'id');
    $enrolledBootcampIds = array_column($userEnrolledBootcamps ?? [], 'id');
    $registeredEventIds = $userRegisteredEvents ?? [];

    // Helper functions
    function getTypeColor($type, $color = null) {
        if ($color) return $color;
        return match($type) {
            'bootcamp' => '#cc0000',
            'online' => '#3b82f6',
            'offline' => '#10b981',
            'hybrid' => '#8b5cf6',
            default => '#f59e0b',
        };
    }

    function getTypeBgColor($type, $color = null) {
        if ($color) return $color . '15';
        return match($type) {
            'bootcamp' => '#fef2f2',
            'online' => '#eff6ff',
            'offline' => '#ecfdf5',
            'hybrid' => '#f5f3ff',
            default => '#fffbeb',
        };
    }
@endphp

<div class="w-full px-2 pb-8 space-y-6" x-data="{
    showPopup: false,
    selectedDay: null,
    selectedEvents: [],
    currentYear: {{ $currentYear }},
    currentMonth: {{ $currentMonth }},
    monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
    monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
    filterMode: 'all', // 'all' or 'mine'

    // User's enrolled IDs
    enrolledCourseIds: {{ json_encode($enrolledCourseIds) }},
    enrolledBootcampIds: {{ json_encode($enrolledBootcampIds) }},
    registeredEventIds: {{ json_encode($registeredEventIds) }},

    // All events from server
    allEvents: {{ json_encode($allCalendarEvents ?? []) }},

    get events() {
        if (this.filterMode === 'mine') {
            return this.allEvents.filter(e => {
                if (e.source === 'bootcamp') {
                    return this.enrolledBootcampIds.includes(e.id);
                } else if (e.source === 'event') {
                    return this.registeredEventIds.includes(e.id);
                }
                return false;
            });
        }
        return this.allEvents;
    },

    get eventsByDay() {
        const map = {};
        this.events.forEach(e => {
            if (!map[e.day]) map[e.day] = [];
            map[e.day].push(e);
        });
        return map;
    },

    get upcomingEvents() {
        const today = new Date();
        const currentYear = this.currentYear;
        const currentMonth = this.currentMonth;

        return this.events.filter(e => {
            const eventDate = new Date(currentYear, currentMonth - 1, e.day);
            const diffDays = Math.ceil((eventDate - today) / (1000 * 60 * 60 * 24));
            return diffDays >= 0 && diffDays <= 30;
        }).sort((a, b) => a.day - b.day);
    },

    openPopup(day, events) {
        this.selectedDay = day;
        this.selectedEvents = events;
        this.showPopup = true;
    },

    closePopup() {
        this.showPopup = false;
    },

    prevMonth() {
        if (this.currentMonth === 1) {
            this.currentMonth = 12;
            this.currentYear--;
        } else {
            this.currentMonth--;
        }
        this.navigate();
    },

    nextMonth() {
        if (this.currentMonth === 12) {
            this.currentMonth = 1;
            this.currentYear++;
        } else {
            this.currentMonth++;
        }
        this.navigate();
    },

    navigate() {
        window.location.href = '/kalender?year=' + this.currentYear + '&month=' + this.currentMonth;
    }
}">

    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 class="font-extrabold text-gray-900 tracking-tight" style="font-size: 28px;">Kalender</h1>
            <p class="text-sm text-gray-500 mt-1 font-medium">Jadwal belajar & deadline kamu</p>
        </div>
        <div style="display: flex; align-items: center; gap: 20px; font-weight: bold; font-size: 13px;">
            <div style="display: flex; align-items: center; gap: 8px;"><div style="border-radius: 999px; width: 10px; height: 10px; background-color: #cc0000;"></div><span class="text-gray-600">Bootcamp</span></div>
            <div style="display: flex; align-items: center; gap: 8px;"><div style="border-radius: 999px; width: 10px; height: 10px; background-color: #3b82f6;"></div><span class="text-gray-600">Event</span></div>
            <div style="display: flex; align-items: center; gap: 8px;"><div style="border-radius: 999px; width: 10px; height: 10px; background-color: #10b981;"></div><span class="text-gray-600">Offline</span></div>
        </div>
    </div>

    <!-- Month Navigation -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm" style="display: flex; align-items: center; justify-content: space-between; padding: 14px;">
        <button @click="prevMonth()" style="padding: 8px; color: #9ca3af; border-radius: 12px; cursor: pointer; background: transparent; border: none;" class="hover:bg-gray-100 transition-colors">
            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>
        <h2 class="font-extrabold text-gray-900 tracking-tight" style="font-size: 18px;" x-text="monthNames[currentMonth - 1] + ' ' + currentYear">{{ $monthNameID }} {{ $currentYear }}</h2>
        <button @click="nextMonth()" style="padding: 8px; color: #9ca3af; border-radius: 12px; cursor: pointer; background: transparent; border: none;" class="hover:bg-gray-100 transition-colors">
            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>
    </div>

    <div class="calendar-layout" style="display: flex; gap: 24px; align-items: flex-start;">

        <!-- Left: Calendar Grid -->
        <div class="bg-white border border-gray-100 shadow-md overflow-hidden" style="flex: 1; min-width: 0; border-radius: 24px;">
            <!-- Days header -->
            <div style="display: grid; grid-template-columns: repeat(7, 1fr); border-bottom: 1px solid #f3f4f6;">
                @foreach(['MIN', 'SEN', 'SEL', 'RAB', 'KAM', 'JUM', 'SAB'] as $day)
                    <div style="padding: 16px 0; text-align: center; font-weight: 800; color: #9ca3af; letter-spacing: 0.05em; font-size: 11px;">{{ $day }}</div>
                @endforeach
            </div>

            <!-- Grid cells -->
            <div class="calendar-grid" style="display: grid; grid-template-columns: repeat(7, 1fr);">
                {{-- Empty cells before day 1 --}}
                @for($i = 0; $i < $startDayOfWeek; $i++)
                    <div class="border-r border-b border-gray-50" style="background-color: #fffafb;"></div>
                @endfor

                {{-- Day 1 to daysInMonth --}}
                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $isToday = ($day === $today->day && $currentMonth === $today->month && $currentYear === $today->year);
                        $isSunday = (($startDayOfWeek + $day - 1) % 7 === 0);
                    @endphp
                    <div class="border-r border-b border-gray-50 p-1 sm:p-2 flex flex-col relative hover:bg-gray-50 transition-colors cursor-pointer {{ $isToday ? 'bg-red-50' : '' }}"
                         :class="{ 'bg-red-50': {{ $isToday ? 'true' : 'false' }} }"
                         style="{{ $isSunday && !$isToday ? 'background-color: #fffafb;' : '' }}">
                        <span class="font-extrabold text-gray-900 text-center mb-1"
                              :class="{{ $isToday ? "'text-red-600'" : "'text-gray-900'" }}"
                              style="font-size: 13px;">{{ $day }}</span>
                        @if($isToday)
                        <div class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></div>
                        @endif

                        {{-- Events rendered client-side via Alpine --}}
                        <div class="day-events space-y-1 overflow-hidden hide-scrollbar" :data-day="{{ $day }}">
                            <!-- Populated by Alpine -->
                        </div>
                    </div>
                @endfor

                {{-- Empty cells after last day --}}
                @php
                    $totalCells = $startDayOfWeek + $daysInMonth;
                    $remainingCells = (7 - ($totalCells % 7)) % 7;
                    if ($remainingCells > 0 && $remainingCells < 7) {
                        for ($i = 0; $i < $remainingCells; $i++) {
                            echo '<div class="border-b border-gray-50' . ($i < $remainingCells - 1 ? ' border-r' : '') . '" style="background-color: #fffafb;"></div>';
                        }
                    }
                @endphp
            </div>
        </div>

        <!-- Right: Agenda Panel -->
        <div style="width: 380px; flex-shrink: 0; display: flex; flex-direction: column; gap: 24px;">

            <!-- Filter Tabs -->
            <div class="bg-white border border-gray-100 shadow-md rounded-2xl p-2">
                <div style="display: flex; gap: 4px;">
                    <button @click="filterMode = 'all'"
                            :class="filterMode === 'all' ? 'bg-red-600 text-white shadow-sm' : 'bg-transparent text-gray-500 hover:bg-gray-100'"
                            class="flex-1 py-2.5 px-4 rounded-xl text-sm font-bold transition-all">
                        Semua
                    </button>
                    <button @click="filterMode = 'mine'"
                            :class="filterMode === 'mine' ? 'bg-red-600 text-white shadow-sm' : 'bg-transparent text-gray-500 hover:bg-gray-100'"
                            class="flex-1 py-2.5 px-4 rounded-xl text-sm font-bold transition-all">
                        Milik Saya
                    </button>
                </div>
            </div>

            <!-- Agenda Terdekat (Full Height) -->
            <div class="bg-white border border-gray-100 shadow-md flex-1" style="border-radius: 24px; padding: 32px; min-height: 500px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
                    <h3 class="font-extrabold text-gray-900 tracking-tight" style="font-size: 20px;">Agenda</h3>
                    <span class="text-sm text-gray-500 font-medium" x-text="events.length + ' agenda'"></span>
                </div>

                <!-- Empty State -->
                <div x-show="events.length === 0" class="flex flex-col items-center justify-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-gray-500 text-center font-medium">
                        <span x-show="filterMode === 'all'">Tidak ada agenda bulan ini</span>
                        <span x-show="filterMode === 'mine'">Kamu belum terdaftar di agenda apapun. <a href="/kursus" class="text-red-600 hover:underline">Daftar kursus</a> atau <a href="/bootcamp/online" class="text-red-600 hover:underline">bootcamp</a> untuk melihat jadwalmu.</span>
                    </p>
                </div>

                <!-- Events List -->
                <div x-show="events.length > 0" style="display: none; display: flex; flex-direction: column; gap: 16px; max-height: 600px; overflow-y: auto;" class="agenda-list">
                    <template x-for="(event, index) in events" :key="index">
                        <a :href="event.url"
                           class="flex items-start gap-4 group cursor-pointer p-4 rounded-2xl transition-all hover:shadow-md mb-4"
                           :style="'background-color: ' + (event.color ? event.color + '08' : '#fef2f2') + '; border: 1px solid ' + (event.color ? event.color + '20' : '#fecaca') + ';'"
                           x-transition>
                            <div class="w-14 h-14 rounded-2xl flex flex-col items-center justify-center text-white flex-shrink-0 group-hover:scale-105 transition-transform shadow-sm"
                                 :style="'background-color: ' + (event.color || '#cc0000') + ';'">
                                <span class="font-extrabold leading-none" style="font-size: 22px;" x-text="event.day"></span>
                                <span class="font-bold leading-none mt-0.5" style="font-size: 11px;" x-text="monthNamesShort[currentMonth - 1]"></span>
                            </div>
                            <div class="flex-1 mt-1 min-w-0">
                                <h4 class="font-bold text-gray-900 group-hover:text-red-600 transition-colors leading-tight mb-1" style="font-size: 15px;" x-text="event.title"></h4>
                                <p class="text-sm text-gray-500 font-medium" x-text="event.time || ''"></p>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-bold"
                                          :style="'background-color: ' + (event.color ? event.color + '15' : '#fef2f2') + '; color: ' + (event.color || '#cc0000') + ';'"
                                          x-text="event.type === 'bootcamp' ? 'Bootcamp' : (event.type || 'Event')">
                                    </span>
                                    <span x-show="event.source === 'bootcamp' && enrolledBootcampIds.includes(event.id)" class="px-2 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">Terdaftar</span>
                                    <span x-show="event.source === 'event' && registeredEventIds.includes(event.id)" class="px-2 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">Terdaftar</span>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-5 group-hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </template>
                </div>
            </div>

        </div>
    </div>

    <!-- Popup Modal for multiple events on same day -->
    <div x-show="showPopup"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center"
         style="display: none;">
        <!-- Backdrop -->
        <div @click="closePopup()" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

        <!-- Modal Content -->
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 overflow-hidden"
             x-show="showPopup"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95">

            <!-- Header -->
            <div class="bg-gradient-to-r from-red-600 to-red-700 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-xl" x-text="'Agenda ' + selectedDay + ' ' + '{{ $monthNameID }}'"></h3>
                        <p class="text-red-100 text-sm mt-1" x-text="selectedEvents.length + ' agenda'"></p>
                    </div>
                    <button @click="closePopup()" class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Events List -->
            <div class="p-4 max-h-96 overflow-y-auto">
                <template x-for="(event, index) in selectedEvents" :key="index">
                    <a :href="event.url"
                       class="flex items-center gap-4 p-4 rounded-xl mb-2 hover:opacity-80 transition-all cursor-pointer"
                       :style="'background-color: ' + (event.color ? event.color + '15' : '#fef2f2') + ';'"
                       x-transition>
                        <div class="w-3 h-3 rounded-full flex-shrink-0"
                             :style="'background-color: ' + (event.color || '#cc0000') + ';'"></div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-gray-900 truncate" x-text="event.title"></p>
                            <p class="text-xs text-gray-500 mt-0.5" x-text="event.time || ''"></p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </template>
            </div>
        </div>
    </div>

    <!-- Update calendar day cells based on filtered events -->
    <div x-init="
        $watch('events', value => updateCalendarCells(value));
        updateCalendarCells(events);
    "></div>

</div>

<style>
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.calendar-grid {
    grid-auto-rows: 90px;
}
@media (min-width: 640px) {
    .calendar-grid {
        grid-auto-rows: 110px;
    }
}
[x-cloak] {
    display: none !important;
}
.agenda-list::-webkit-scrollbar {
    display: none;
}
.agenda-list {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>

@push('scripts')
<script>
function updateCalendarCells(events) {
    // Group events by day
    const eventsByDay = {};
    events.forEach(e => {
        if (!eventsByDay[e.day]) eventsByDay[e.day] = [];
        eventsByDay[e.day].push(e);
    });

    // Clear all day cells
    document.querySelectorAll('.day-events').forEach(cell => {
        const day = parseInt(cell.getAttribute('data-day'));
        cell.innerHTML = '';

        const dayEvents = eventsByDay[day] || [];
        dayEvents.forEach((event, i) => {
            const color = event.color || '#cc0000';
            const bgColor = color + '15';

            if (dayEvents.length === 1) {
                // Single event: direct link
                cell.innerHTML += `
                    <a href="${event.url}" class="block px-1.5 py-1 rounded font-bold truncate text-center hidden sm:block transition-all hover:opacity-80"
                       style="background-color: ${bgColor}; color: ${color}; font-size: 10px; text-decoration: none;">
                        ${event.title.length > 20 ? event.title.substring(0, 20) + '...' : event.title}
                    </a>
                    <a href="${event.url}" class="rounded-full mx-auto sm:hidden mt-0.5 block" style="width: 6px; height: 6px; background-color: ${color};"></a>
                `;
            } else {
                // Multiple events: show popup on click
                if (i < 2) {
                    cell.innerHTML += `
                        <button onclick="openDayPopup(${day}, ${JSON.stringify(dayEvents).replace(/"/g, '&quot;')})"
                                class="w-full px-1.5 py-1 rounded font-bold truncate text-center transition-all hover:opacity-80 hidden sm:block"
                                style="background-color: ${bgColor}; color: ${color}; font-size: 10px; border: none; cursor: pointer; text-align: center;">
                            ${event.title.length > 20 ? event.title.substring(0, 20) + '...' : event.title}
                        </button>
                        <button onclick="openDayPopup(${day}, ${JSON.stringify(dayEvents).replace(/"/g, '&quot;')})"
                                class="rounded-full mx-auto sm:hidden mt-0.5 block"
                                style="width: 6px; height: 6px; background-color: ${color}; border: none; cursor: pointer;"></button>
                    `;
                }
            }
        });

        if (dayEvents.length > 2) {
            cell.innerHTML += `
                <button onclick="openDayPopup(${day}, ${JSON.stringify(dayEvents).replace(/"/g, '&quot;')})"
                        class="w-full text-center text-xs text-gray-500 font-medium hover:text-gray-700 transition-colors py-0.5">
                    +${dayEvents.length - 2} lagi
                </button>
            `;
        }
    });
}

// Global function for calendar cell clicks
function openDayPopup(day, events) {
    const alpineComponent = document.querySelector('[x-data]').__x.$data;
    alpineComponent.openPopup(day, events);
}

// Make updateCalendarCells available globally
window.updateCalendarCells = updateCalendarCells;
</script>
@endpush
@endsection
