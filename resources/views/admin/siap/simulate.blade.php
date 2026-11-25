@extends('layouts.app', ['activePage' => 'admin.siap.simulate'])

@section('title', __('app.siap_simulate_menu') . ' - Admin')
@section('header_title', __('app.siap_simulate_menu'))

@section('content')
<div class="mx-auto max-w-4xl px-1 py-4 sm:px-2">
    <x-flash-messages />

    <!-- Modal Popup Dialog Explaining Temporary Simulation -->
    <div class="flex min-h-[70vh] items-center justify-center rounded-3xl bg-stone-900/5 p-4 backdrop-blur-[1px] sm:p-8">
        <div role="dialog" aria-modal="true" aria-labelledby="sim-modal-title"
            class="w-full max-w-xl rounded-3xl border border-stone-200 bg-white p-6 shadow-2xl md:p-8">
            <div class="mb-5 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-red-50 text-[#cc0000]">
                        <x-icon name="target" class="h-6 w-6" />
                    </span>
                    <div>
                        <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-[11px] font-extrabold uppercase tracking-wider text-amber-800">
                            {{ __('app.siap_sim_modal_badge') }}
                        </span>
                        <h1 id="sim-modal-title" class="mt-1 font-display text-xl font-bold text-gray-900 sm:text-2xl">
                            {{ __('app.siap_sim_modal_title') }}
                        </h1>
                    </div>
                </div>
            </div>

            <p class="text-sm leading-relaxed text-gray-600">
                {{ __('app.siap_sim_modal_intro') }}
            </p>

            <ul class="my-5 space-y-3">
                <li class="flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-3.5 text-sm text-amber-950">
                    <x-icon name="shieldCheck" class="mt-0.5 h-5 w-5 shrink-0 text-amber-700" />
                    <span>{{ __('app.siap_sim_modal_point_temp') }}</span>
                </li>
                <li class="flex items-start gap-3 rounded-2xl border border-blue-200 bg-blue-50 p-3.5 text-sm text-blue-950">
                    <x-icon name="sparkles" class="mt-0.5 h-5 w-5 shrink-0 text-blue-700" />
                    <span>{{ __('app.siap_sim_modal_point_replay') }}</span>
                </li>
                <li class="flex items-start gap-3 rounded-2xl border border-stone-200 bg-stone-50 p-3.5 text-sm text-stone-800">
                    <x-icon name="creditCard" class="mt-0.5 h-5 w-5 shrink-0 text-stone-700" />
                    <span>{{ __('app.siap_sim_modal_point_sandbox') }}</span>
                </li>
            </ul>

            <div class="mb-6 rounded-xl border px-3.5 py-2.5 text-xs font-semibold {{ ($sandboxConfigured && $sandboxWebhookConfigured) ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : 'border-red-200 bg-red-50 text-red-800' }}">
                {{ ($sandboxConfigured && $sandboxWebhookConfigured) ? '✓ ' . __('app.siap_sim_sandbox_ready') : '⚠ ' . __('app.siap_sim_sandbox_missing') }}
            </div>

            <div class="flex flex-wrap items-center justify-end gap-3">
                <a href="{{ route('admin.siap.index') }}"
                    class="inline-flex min-h-11 items-center justify-center rounded-xl border border-stone-200 bg-white px-5 py-2.5 text-sm font-semibold text-stone-700 transition hover:bg-stone-50">
                    {{ __('app.siap_sim_cancel_btn') }}
                </a>
                <form action="{{ route('admin.siap.simulate.start') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit"
                        class="inline-flex min-h-11 cursor-pointer items-center justify-center gap-2 rounded-xl bg-[#cc0000] px-6 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-red-700">
                        <span>{{ __('app.siap_sim_start_btn') }}</span>
                        <x-icon name="arrowRight" class="h-4 w-4" />
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
