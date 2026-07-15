@extends('layouts.app')

@section('title', isset($course) ? __('app.manage_courses_title') : __('app.add_new_course'))

@section('content')
<div x-data="{
    chapters: [],
    addChapter() {
        this.chapters.push({ title: '', lessons: 1, duration: '', video_url: '', thumbnail_url: '', description: '' });
    },
    removeChapter(index) {
        this.chapters.splice(index, 1);
    }
}" class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <x-page-header
        :title="isset($course) ? __('app.manage_courses_title') . ': ' . $course->title : __('app.add_new_course')"
        :description="isset($course) ? __('app.course_form_edit_desc') : __('app.course_form_create_desc')"
    >
        <x-slot:actionSlot>
            <a href="{{ route('admin.courses') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-5 rounded-full text-sm transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('app.back') }}
            </a>
        </x-slot:actionSlot>
    </x-page-header>

    <x-flash-messages />

    <!-- COURSE DETAILS FORM CARD -->
    <x-form-card :title="__('app.course_details')">
        <form method="POST" action="{{ isset($course) ? route('admin.courses.update', $course) : route('admin.courses.store') }}" class="space-y-6">
            @csrf
            @if(isset($course))
                @method('PATCH')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Title -->
                <div class="lg:col-span-2">
                    <x-form-input
                        name="title"
                        :label="__('app.course_title')"
                        :placeholder="__('app.enter_course_title')"
                        :required="true"
                        :value="$course->title ?? null"
                    />
                </div>

                <!-- Category -->
                <x-form-input
                    name="category"
                    :label="__('app.category')"
                    :placeholder="__('app.example_programming')"
                    :required="true"
                    :value="$course->category ?? null"
                />

                <!-- Mentor Name -->
                <x-form-input
                    name="mentor_name"
                    :label="__('app.mentor_name')"
                    :placeholder="__('app.mentor_name_placeholder')"
                    :required="true"
                    :value="$course->mentor_name ?? null"
                />

                <!-- Mentor Company -->
                <x-form-input
                    name="mentor_company"
                    :label="__('app.mentor_company')"
                    :placeholder="__('app.mentor_company_placeholder')"
                    :required="true"
                    :value="$course->mentor_company ?? null"
                />

                <!-- Level -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.level') }} <span class="text-red-500">*</span></label>
                    <select aria-label="Level" name="level" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 cursor-pointer transition-colors">
                        <option value="">{{ __('app.select_level') }}</option>
                        <option value="Beginner" {{ old('level', $course->level ?? '') === 'Beginner' ? 'selected' : '' }}>Beginner</option>
                        <option value="Intermediate" {{ old('level', $course->level ?? '') === 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                        <option value="Advanced" {{ old('level', $course->level ?? '') === 'Advanced' ? 'selected' : '' }}>Advanced</option>
                    </select>
                </div>

                <!-- Price -->
                <x-form-input
                    name="price"
                    :label="__('app.price')"
                    :placeholder="__('app.price_example')"
                    :required="true"
                    :value="$course->price ?? null"
                />

                <!-- Color -->
                <x-form-input
                    name="color"
                    :label="__('app.color_optional')"
                    :placeholder="__('app.hex_example')"
                    :value="$course->color ?? null"
                />
            </div>

            <div class="grid grid-cols-1 gap-6">
                <x-form-input
                    name="short_description"
                    :label="__('app.short_description')"
                    :placeholder="__('app.course_short_desc_placeholder')"
                    :value="$course->short_description ?? null"
                />

                <x-form-input
                    name="description"
                    type="textarea"
                    :label="__('app.full_description')"
                    :rows="4"
                    :placeholder="__('app.course_full_desc_placeholder')"
                    :value="$course->description ?? null"
                />
            </div>

            <!-- Hidden chapters data for submission -->
            <template x-for="(chapter, index) in chapters" :key="index">
                <div>
                    <input type="hidden" :name="'chapters[' + index + '][title]'" :value="chapter.title">
                    <input type="hidden" :name="'chapters[' + index + '][lessons]'" :value="chapter.lessons">
                    <input type="hidden" :name="'chapters[' + index + '][duration]'" :value="chapter.duration">
                    <input type="hidden" :name="'chapters[' + index + '][video_url]'" :value="chapter.video_url">
                    <input type="hidden" :name="'chapters[' + index + '][thumbnail_url]'" :value="chapter.thumbnail_url">
                    <input type="hidden" :name="'chapters[' + index + '][description]'" :value="chapter.description">
                </div>
            </template>

            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-[#cc0000] hover:bg-red-700 text-white font-bold py-3 px-8 rounded-full text-sm transition-colors shadow-lg shadow-red-200 w-full sm:w-auto">
                    {{ isset($course) ? __('app.save_changes') : __('app.add_course_plus') }}
                </button>
            </div>
        </form>
    </x-form-card>

    @if(isset($course))
    <!-- CHAPTERS SECTION -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- ADD CHAPTER -->
        <x-form-card :title="__('app.add_new_chapter')" :subtitle="__('app.video_thumbnail_optional')" class="lg:col-span-1 h-fit">
            <form method="POST" action="{{ route('admin.courses.chapters.store', $course) }}" class="space-y-4">
                @csrf
                <x-form-input name="title" :label="__('app.chapter_title')" :placeholder="__('app.example_html')" :required="true" />
                <x-form-input name="lessons" type="number" :label="__('app.lesson_count')" placeholder="5" :required="true" />
                <x-form-input name="duration" :label="__('app.total_duration')" :placeholder="__('app.example_duration')" :required="true" />
                <x-form-input name="video_url" type="url" label="Video URL" placeholder="https://youtube.com/watch?v=xxx" />
                <x-form-input name="thumbnail_url" type="url" label="Thumbnail URL" placeholder="https://contoh.com/thumbnail.jpg" :value="$course->thumbnail_url ?? null" />
                <x-form-input name="description" type="textarea" :label="__('app.description')" :rows="2" :placeholder="__('app.chapter_desc_placeholder')" />
                <div class="pt-2">
                    <button type="submit" class="bg-gray-900 hover:bg-black text-white font-bold py-3 px-6 rounded-full text-sm transition-colors shadow-lg w-full flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        {{ __('app.add_chapter') }}
                    </button>
                </div>
            </form>
        </x-form-card>

        <!-- LIST CHAPTERS -->
        <x-form-card :title="__('app.chapters_list')" class="lg:col-span-2">
            @if($course->chapters->isEmpty())
                <x-empty-state :message="__('app.no_chapters_data')" icon="book" />
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/30 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                                <th class="px-6 py-4 font-bold">{{ __('app.thumbnail') }}</th>
                                <th class="px-6 py-4 font-bold">{{ __('app.chapter_title') }}</th>
                                <th class="px-6 py-4 font-bold text-center">{{ __('app.lessons') }}</th>
                                <th class="px-6 py-4 font-bold text-center">{{ __('app.duration') }}</th>
                                <th class="px-6 py-4 font-bold text-center">{{ __('app.video') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($course->chapters as $index => $chapter)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="w-12 h-8 rounded bg-gray-100 overflow-hidden flex items-center justify-center">
                                        @if($chapter->thumbnail_url)
                                            <img decoding="async" loading="lazy" alt="" src="{{ $chapter->thumbnail_url }}" class="w-full h-full object-cover" alt="">
                                        @else
                                            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center text-xs font-bold flex-shrink-0">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900">{{ $chapter->title }}</div>
                                            @if($chapter->description)
                                                <div class="text-xs text-gray-400 mt-0.5">{{ Str::limit($chapter->description, 40) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-blue-50 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-md">{{ $chapter->lessons }} {{ __('app.lesson') }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm text-gray-500">{{ $chapter->duration }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($chapter->video_url)
                                        <a href="{{ $chapter->video_url }}" target="_blank" class="text-blue-600 hover:text-blue-800 inline-flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </a>
                                    @else
                                        <span class="text-gray-300">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-form-card>

    </div>
    @endif

</div>
@endsection
