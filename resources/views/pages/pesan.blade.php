@extends('layouts.app', ['activePage' => 'pesan'])

@section('title', __('app.messages') . ' — 1Langkah')
@section('header_title', __('app.messages'))

@section('content')
<div class="w-full px-2 pb-8">
    <div class="w-full h-[calc(100vh-160px)] bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex" x-data="{ activeChat: 1, showSidebar: true }">
    
    <!-- Sidebar: Daftar Pesan -->
    <div :class="showSidebar ? 'flex' : 'hidden md:flex'" class="w-full md:w-[320px] lg:w-[380px] border-r border-gray-100 flex-shrink-0 flex-col h-full bg-gray-50/50">
        <!-- Header -->
        <div class="p-6 border-b border-gray-100 bg-white">
            <h2 class="text-xl font-extrabold text-gray-900 mb-4 tracking-tight">{{ __('app.messages') }}</h2>
            <div class="relative">
                <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input aria-label="{{ __('app.search_messages') }}" type="text" placeholder="{{ __('app.search_messages') }}" class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-full text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
            </div>
        </div>

        <!-- Contact List -->
        <div class="flex-1 overflow-y-auto scrollbar-hide px-3 py-2 space-y-1">
            <!-- Chat Item 1 (Unread) -->
            <div @click="activeChat = 1; showSidebar = false" 
                 :class="activeChat === 1 ? 'bg-red-50 border-red-100 shadow-sm' : 'border-transparent hover:bg-gray-50'" 
                 class="flex items-center gap-3 p-3 rounded-2xl cursor-pointer transition-all duration-200 border relative group">
                <div class="relative flex-shrink-0">
                    <img src="https://ui-avatars.com/api/?name=Rudi+Yesaya&background=random" class="w-11 h-11 rounded-full object-cover shadow-sm" alt="">
                    <div class="absolute -top-0.5 -right-0.5 w-3.5 h-3.5 bg-red-600 border-2 border-white rounded-full"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-0.5">
                        <div class="flex items-center gap-1.5 min-w-0">
                            <h2 class="font-bold text-gray-900 text-[14px] truncate">Rudi Yesaya</h2>
                            <span class="text-[9px] font-extrabold tracking-wide text-red-600 bg-red-100 px-1.5 py-0.5 rounded-md flex-shrink-0 uppercase">{{ __('app.mentor') }}</span>
                        </div>
                        <span class="text-[11px] font-bold text-red-600 whitespace-nowrap ml-2">09:41</span>
                    </div>
                    <p class="text-[13px] font-bold text-gray-800 truncate">{{ __('app.mock_msg_1_short') }}</p>
                </div>
            </div>

            <!-- Chat Item 2 (Read) -->
            <div @click="activeChat = 2; showSidebar = false" 
                 :class="activeChat === 2 ? 'bg-red-50 border-red-100 shadow-sm' : 'border-transparent hover:bg-gray-50'" 
                 class="flex items-center gap-3 p-3 rounded-2xl cursor-pointer transition-all duration-200 border relative group">
                <div class="relative flex-shrink-0">
                    <img src="https://ui-avatars.com/api/?name=Sarah+Johnson&background=random" class="w-11 h-11 rounded-full object-cover shadow-sm opacity-90" alt="">
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-0.5">
                        <div class="flex items-center gap-1.5 min-w-0">
                            <h2 :class="activeChat === 2 ? 'text-gray-900 font-bold' : 'text-gray-700 font-semibold'" class="text-[14px] truncate transition-colors">Sarah Johnson</h2>
                            <span class="text-[9px] font-extrabold tracking-wide text-emerald-700 bg-emerald-100 px-1.5 py-0.5 rounded-md flex-shrink-0 uppercase">{{ __('app.admin') }}</span>
                        </div>
                        <span class="text-[11px] font-medium text-gray-400 whitespace-nowrap ml-2">{{ __('app.yesterday') }}</span>
                    </div>
                    <p :class="activeChat === 2 ? 'text-gray-700' : 'text-gray-500'" class="text-[13px] font-medium truncate transition-colors">{{ __('app.mock_msg_2_short') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Chat Area -->
    <div :class="!showSidebar ? 'flex' : 'hidden md:flex'" class="flex-1 flex-col h-full bg-white relative">
        
        <!-- Chat Header -->
        <div class="h-20 border-b border-gray-100 flex items-center justify-between px-6 bg-white/80 backdrop-blur-md sticky top-0 z-10">
            <div class="flex items-center gap-4">
                <button @click="showSidebar = true" class="md:hidden p-2 -ml-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                
                <template x-if="activeChat === 1">
                    <div class="flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name=Rudi+Yesaya&background=random" class="w-10 h-10 rounded-full object-cover shadow-sm" alt="">
                        <div>
                            <div class="flex items-center gap-2">
                                <div class="font-bold text-gray-900 text-[15px]">Rudi Yesaya</div>
                                <span class="text-[10px] font-bold text-red-600 bg-red-100 px-2 py-0.5 rounded-full">{{ __('app.mentor') }}</span>
                            </div>
                            <div class="text-xs font-medium text-gray-400 mt-0.5">{{ __('app.last_seen_2_hours') }}</div>
                        </div>
                    </div>
                </template>
                <template x-if="activeChat === 2">
                    <div class="flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name=Sarah+Johnson&background=random" class="w-10 h-10 rounded-full object-cover shadow-sm" alt="">
                        <div>
                            <div class="flex items-center gap-2">
                                <div class="font-bold text-gray-900 text-[15px]">Sarah Johnson</div>
                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">{{ __('app.admin') }}</span>
                            </div>
                            <div class="text-xs font-medium text-gray-400 mt-0.5">{{ __('app.last_seen_yesterday') }}</div>
                        </div>
                    </div>
                </template>
            </div>
            
            <div class="flex items-center gap-2">
                <button class="w-9 h-9 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
                <button class="w-9 h-9 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                </button>
            </div>
        </div>

        <!-- Chat Messages -->
        <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-[#f8fafc]" style="background-image: radial-gradient(#e2e8f0 1px, transparent 1px); background-size: 24px 24px;">
            
            <!-- Chat Rudi -->
            <template x-if="activeChat === 1">
                <div class="space-y-6">
                    <!-- Date Separator -->
                    <div class="flex items-center justify-center">
                        <span class="px-4 py-1 bg-white border border-gray-100 rounded-full text-xs font-bold text-gray-400 shadow-sm">{{ __('app.today') }}</span>
                    </div>

                    <!-- Message Received -->
                    <div class="flex items-end gap-3 max-w-[85%]">
                        <img src="https://ui-avatars.com/api/?name=Rudi+Yesaya&background=random" class="w-8 h-8 rounded-full mb-1 shadow-sm" alt="">
                        <div class="flex flex-col gap-1">
                            <span class="text-[11px] font-bold text-gray-500 ml-1">Rudi Yesaya</span>
                            <div class="bg-white p-4 rounded-2xl rounded-bl-sm shadow-sm border border-gray-100">
                                <p class="text-[14px] text-gray-700 leading-relaxed">{{ __('app.mock_msg_1_full') }}</p>
                            </div>
                            <span class="text-[10px] font-medium text-gray-400 ml-1">09:38</span>
                        </div>
                    </div>

                    <!-- Message Sent -->
                    <div class="flex items-end gap-3 justify-end max-w-[85%] ml-auto">
                        <div class="flex flex-col gap-1 items-end">
                            <div class="bg-red-600 p-4 rounded-2xl rounded-br-sm shadow-[0_4px_12px_rgba(220,38,38,0.2)] text-white">
                                <p class="text-[14px] leading-relaxed">{{ __('app.mock_msg_1_reply') }}</p>
                            </div>
                            <div class="flex items-center gap-1.5 mr-1">
                                <span class="text-[10px] font-medium text-gray-400">09:40</span>
                                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7M5 13l4 4L19 7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Message Received (Latest) -->
                    <div class="flex items-end gap-3 max-w-[85%]">
                        <img src="https://ui-avatars.com/api/?name=Rudi+Yesaya&background=random" class="w-8 h-8 rounded-full mb-1 shadow-sm" alt="">
                        <div class="flex flex-col gap-1">
                            <span class="text-[11px] font-bold text-gray-500 ml-1">Rudi Yesaya</span>
                            <div class="bg-white p-4 rounded-2xl rounded-bl-sm shadow-sm border border-gray-100">
                                <p class="text-[14px] text-gray-700 leading-relaxed">{{ __('app.mock_msg_1_reply_2') }}</p>
                            </div>
                            <span class="text-[10px] font-medium text-gray-400 ml-1">09:41</span>
                        </div>
                    </div>
                </div>
            </template>
            
            <!-- Chat Sarah -->
            <template x-if="activeChat === 2">
                <div class="space-y-6">
                    <!-- Date Separator -->
                    <div class="flex items-center justify-center">
                        <span class="px-4 py-1 bg-white border border-gray-100 rounded-full text-xs font-bold text-gray-400 shadow-sm">{{ __('app.yesterday') }}</span>
                    </div>

                    <!-- Message Received -->
                    <div class="flex items-end gap-3 max-w-[85%]">
                        <img src="https://ui-avatars.com/api/?name=Sarah+Johnson&background=random" class="w-8 h-8 rounded-full mb-1 shadow-sm" alt="">
                        <div class="flex flex-col gap-1">
                            <span class="text-[11px] font-bold text-gray-500 ml-1">Sarah Johnson</span>
                            <div class="bg-white p-4 rounded-2xl rounded-bl-sm shadow-sm border border-gray-100">
                                <p class="text-[14px] text-gray-700 leading-relaxed">{{ __('app.mock_msg_2_full') }}</p>
                            </div>
                            <span class="text-[10px] font-medium text-gray-400 ml-1">15:30</span>
                        </div>
                    </div>
                </div>
            </template>

        </div>

        <!-- Message Input -->
        <div class="p-4 bg-white border-t border-gray-100">
            <div class="flex items-end gap-3">
                <button class="p-3 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                </button>
                <div class="flex-1 bg-gray-50 border border-gray-200 rounded-2xl overflow-hidden focus-within:ring-2 focus-within:ring-red-500 focus-within:border-red-500 transition-shadow">
                    <textarea aria-label="{{ __('app.type_message') }}" rows="1" class="w-full max-h-32 p-3.5 bg-transparent border-none focus:ring-0 resize-none text-[14px] leading-relaxed" placeholder="{{ __('app.type_message') }}"></textarea>
                </div>
                <button class="p-3.5 bg-red-600 hover:bg-red-700 text-white rounded-xl shadow-[0_4px_12px_rgba(220,38,38,0.3)] transition-all transform hover:-translate-y-0.5 flex-shrink-0">
                    <svg class="w-5 h-5 translate-x-px -translate-y-px" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path></svg>
                </button>
            </div>
        </div>
        
    </div>
</div>
</div>

<style>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
@endsection
