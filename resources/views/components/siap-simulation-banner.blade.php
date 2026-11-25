@if(session()->has('siap_simulation'))
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-amber-300 bg-amber-50 px-4 py-3 text-amber-950 shadow-sm">
        <div class="flex min-w-0 items-start gap-2.5 text-xs sm:text-sm">
            <span class="mt-0.5 shrink-0 rounded-full bg-amber-200 px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-wider text-amber-900">
                {{ __('app.siap_sim_banner_badge') }}
            </span>
            <span class="font-medium leading-relaxed">{{ __('app.siap_sim_banner_text') }}</span>
        </div>
        <form action="{{ route('siap.simulation.exit') }}" method="POST" class="m-0 shrink-0">
            @csrf
            <button type="submit"
                class="cursor-pointer rounded-xl bg-amber-900 px-3.5 py-2 text-xs font-bold text-white transition hover:bg-black">
                {{ __('app.siap_sim_exit_btn') }}
            </button>
        </form>
    </div>
@endif
