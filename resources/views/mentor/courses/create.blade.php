@extends('layouts.mentor')

@section('title', __('app.create_new_course'))
@section('header_title', __('app.create_new_course'))

@section('content')
<div class="w-full px-2 pb-8">
    <div class="mb-6">
        <a href="{{ route('mentor.courses.index') }}" class="inline-flex items-center gap-2 text-[14px] text-gray-500 hover:text-gray-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            {{ __('app.back_to_course_list') }}
        </a>
    </div>

    <div class="page-title" style="margin-bottom:8px">{{ __('app.create_new_course') }}</div>
    <p style="font-size:14px;color:var(--text-muted);margin-bottom:28px">{{ __('app.create_course_desc') }}</p>

    <form method="POST" action="{{ route('mentor.courses.store') }}">
        @csrf

        {{-- Basic Info --}}
        <div class="card" style="padding:24px;margin-bottom:20px">
            <div class="section-title" style="margin-bottom:18px">{{ __('app.basic_information') }}</div>

            <div class="input-group" style="margin-bottom:16px">
                <label>{{ __('app.course_title') }} <span style="color:#cc0000">*</span></label>
                <input type="text" name="title" class="input" required value="{{ old('title') }}" placeholder="{{ __('app.ex_course_title') }}" />
                @error('title')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
            </div>

            <div class="input-group" style="margin-bottom:16px">
                <label>{{ __('app.category') }} <span style="color:#cc0000">*</span></label>
                <input type="text" name="category" class="input" required value="{{ old('category') }}" placeholder="{{ __('app.ex_category') }}" />
                @error('category')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div class="input-group" style="margin-bottom:0">
                    <label>{{ __('app.level') }} <span style="color:#cc0000">*</span></label>
                    <select name="level" class="input" required>
                        @foreach($levels as $value => $label)
                            <option value="{{ $value }}" {{ old('level') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('level')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
                </div>

                <div class="input-group" style="margin-bottom:0">
                    <label>{{ __('app.price') }} <span style="color:#cc0000">*</span></label>
                    <input type="text" name="price" class="input" required value="{{ old('price', __('app.free')) }}" />
                    @error('price')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="input-group" style="margin-bottom:0">
                <label>{{ __('app.mentor_company') }}</label>
                <input type="text" name="mentor_company" class="input" value="{{ old('mentor_company') }}" placeholder="{{ __('app.ex_company') }}" />
                @error('mentor_company')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
            </div>
        </div>

        {{-- Description --}}
        <div class="card" style="padding:24px;margin-bottom:20px">
            <div class="section-title" style="margin-bottom:18px">{{ __('app.course_description') }}</div>

            <div class="input-group" style="margin-bottom:16px">
                <label>{{ __('app.short_description') }}</label>
                <input type="text" name="short_description" class="input" value="{{ old('short_description') }}" maxlength="255" placeholder="{{ __('app.ex_course_short_desc') }}" />
                @error('short_description')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
            </div>

            <div class="input-group" style="margin-bottom:0">
                <label>{{ __('app.full_description') }}</label>
                <textarea name="description" class="input" rows="5" placeholder="{{ __('app.course_desc_placeholder') ?? 'Tuliskan materi apa saja yang akan dipelajari...' }}">{{ old('description') }}</textarea>
                @error('description')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
            </div>
        </div>

        {{-- Appearance --}}
        <div class="card" style="padding:24px;margin-bottom:24px">
            <div class="section-title" style="margin-bottom:18px">{{ __('app.visual_appearance') }}</div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div class="input-group" style="margin-bottom:0">
                    <label>{{ __('app.accent_color') }}</label>
                    <div style="display:flex;align-items:center;gap:8px">
                        <input type="color" name="color" value="{{ old('color', '#cc0000') }}" style="width:40px;height:40px;border-radius:8px;border:1px solid var(--border-light);cursor:pointer;padding:2px">
                        <input type="text" name="color_text" class="input" value="{{ old('color', '#cc0000') }}" style="flex:1" />
                    </div>
                    @error('color')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
                </div>

                <div class="input-group" style="margin-bottom:0">
                    <label>{{ __('app.badge_label') }}</label>
                    <input type="text" name="badge" class="input" value="{{ old('badge') }}" placeholder="{{ __('app.ex_badge') }}" />
                    @error('badge')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="input-group" style="margin-bottom:0">
                <label>{{ __('app.thumbnail_url') }}</label>
                <input type="url" name="thumbnail_url" class="input" value="{{ old('thumbnail_url') }}" placeholder="https://example.com/image.jpg" />
                @error('thumbnail_url')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:12px">
            <a href="{{ route('mentor.courses.index') }}" class="btn btn-outline">
            {{ __('app.cancel') }}
        </a>
            <button type="submit" class="btn btn-primary">
            {{ __('app.create_course_button') }}
        </button>
        </div>
    </form>
</div>
@endsection
