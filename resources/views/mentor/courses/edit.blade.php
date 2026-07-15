@extends('layouts.mentor')

@section('title', __('app.edit_course'))

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('mentor.courses.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            {{ __('app.back') }}
        </a>
    </div>

    <x-flash-messages />

    <div class="space-y-6">
        {{-- Course Info Form --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ __('app.edit_course_colon') }} {{ $course->title }}</h1>

            <form method="POST" action="{{ route('mentor.courses.update', $course) }}" class="space-y-6">
                @csrf
                @method('PATCH')

                {{-- Basic Info --}}
                <div class="space-y-4">
                    <h2 class="font-semibold text-gray-900 border-b pb-2">{{ __('app.basic_information') }}</h2>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.course_title') }} *</label>
                        <input aria-label="Title" type="text" name="title" required value="{{ old('title', $course->title) }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.category') }} *</label>
                            <input aria-label="Category" type="text" name="category" required value="{{ old('category', $course->category) }}"
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('category')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.level') }} *</label>
                            <select aria-label="Level" name="level" required class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @foreach($levels as $value => $label)
                                    <option value="{{ $value }}" {{ old('level', $course->level) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('level')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.price') }} *</label>
                            <input aria-label="Price" type="text" name="price" required value="{{ old('price', $course->price) }}"
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.mentor_company') }}</label>
                            <input aria-label="Mentor Company" type="text" name="mentor_company" value="{{ old('mentor_company', $course->mentor_company) }}"
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('mentor_company')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.short_description') }}</label>
                        <input aria-label="Short Description" type="text" name="short_description" value="{{ old('short_description', $course->short_description) }}" maxlength="255"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('short_description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.full_description') }}</label>
                        <textarea aria-label="Description" name="description" rows="5"
                                  class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $course->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                        {{ __('app.save_changes') }}
                    </button>
                </div>
            </form>
        </div>

        {{-- Chapters Management --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="font-semibold text-gray-900 mb-4">{{ __('app.course_chapters') }}</h2>

            {{-- Add Chapter Form --}}
            <form method="POST" action="{{ route('mentor.courses.chapters.store', $course) }}" class="mb-6 p-4 bg-gray-50 rounded-lg">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="col-span-2">
                        <input aria-label="{{ __('app.chapter_title') }}" type="text" name="title" required placeholder="{{ __('app.chapter_title') }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <input aria-label="{{ __('app.lesson_count') }}" type="number" name="lessons" value="1" min="1" placeholder="{{ __('app.lesson_count') }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <input aria-label="{{ __('app.duration_example') }}" type="text" name="duration" placeholder="{{ __('app.duration_example') }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div class="mt-3">
                    <textarea aria-label="{{ __('app.description_optional') }}" name="description" placeholder="{{ __('app.description_optional') }}" rows="2"
                              class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <button type="submit" class="mt-3 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium">
                    {{ __('app.add_chapter') }}
                </button>
            </form>

            {{-- Chapters List --}}
            @forelse($course->chapters->sortBy('id') as $chapter)
            <div class="border border-gray-200 rounded-lg p-4 mb-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h2 class="font-medium text-gray-900">{{ $chapter->title }}</h2>
                        <p class="text-sm text-gray-500">{{ $chapter->lessons }} lessons • {{ $chapter->duration ?? 'N/A' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <form method="POST" action="{{ route('mentor.courses.chapters.destroy', [$course, $chapter]) }}" class="inline" onsubmit="return confirm('{{ __('app.delete_chapter_confirm') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">{{ __('app.delete') }}</button>
                        </form>
                    </div>
                </div>

                {{-- Videos --}}
                <div class="ml-4 pl-4 border-l-2 border-gray-100 space-y-2">
                    @forelse($chapter->videos->sortBy('order') as $video)
                    <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $video->title }}</p>
                                <p class="text-xs text-gray-500">{{ $video->duration ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('mentor.courses.chapters.videos.destroy', [$course, $chapter, $video]) }}" class="inline" onsubmit="return confirm('{{ __('app.delete_video_confirm') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">{{ __('app.delete') }}</button>
                        </form>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 italic">{{ __('app.no_video_yet') }}</p>
                    @endforelse

                    {{-- Add Video Form --}}
                    <form method="POST" action="{{ route('mentor.courses.chapters.videos.store', [$course, $chapter]) }}" class="mt-3">
                        @csrf
                        <div class="flex gap-2">
                            <input aria-label="{{ __('app.video_title') }}" type="text" name="title" required placeholder="{{ __('app.video_title') }}" class="flex-1 text-sm border border-gray-200 rounded-lg px-3 py-2">
                            <input aria-label="{{ __('app.video_url') }}" type="url" name="video_url" required placeholder="{{ __('app.video_url') }}" class="flex-1 text-sm border border-gray-200 rounded-lg px-3 py-2">
                            <input aria-label="{{ __('app.duration') }}" type="text" name="duration" placeholder="{{ __('app.duration') }}" class="w-24 text-sm border border-gray-200 rounded-lg px-3 py-2">
                            <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">+</button>
                        </div>
                    </form>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-8">{{ __('app.no_chapters_yet') }}</p>
            @endforelse
        </div>

        {{-- Resources --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="font-semibold text-gray-900 mb-4">{{ __('app.course_resources') }}</h2>

            <form method="POST" action="{{ route('mentor.courses.resources.store', $course) }}" class="mb-6 p-4 bg-gray-50 rounded-lg">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input aria-label="{{ __('app.resource_title') }}" type="text" name="title" required placeholder="{{ __('app.resource_title') }}"
                           class="border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <select aria-label="Type" name="type" required class="border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="pdf">PDF</option>
                        <option value="zip">ZIP</option>
                        <option value="video">Video</option>
                        <option value="link">Link</option>
                        <option value="github">GitHub</option>
                        <option value="file">File</option>
                    </select>
                    <input aria-label="{{ __('app.url') }}" type="url" name="url" required placeholder="{{ __('app.url') }}"
                           class="border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <input aria-label="{{ __('app.file_size_bytes_optional') }}" type="number" name="file_size" placeholder="{{ __('app.file_size_bytes_optional') }}"
                           class="border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <button type="submit" class="mt-3 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium">
                    {{ __('app.add_resource') }}
                </button>
            </form>

            @forelse($course->courseResources as $resource)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg mb-2">
                <div class="flex items-center gap-3">
                    <span class="px-2 py-1 text-xs font-medium rounded bg-gray-200 text-gray-700 uppercase">{{ $resource->type }}</span>
                    <span class="text-sm font-medium text-gray-800">{{ $resource->title }}</span>
                </div>
                <form method="POST" action="{{ route('mentor.courses.resources.destroy', [$course, $resource]) }}" class="inline" onsubmit="return confirm('{{ __('app.delete_resource_confirm') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">{{ __('app.delete') }}</button>
                </form>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">{{ __('app.no_resource_yet') }}</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
