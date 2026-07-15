@extends('layouts.app', ['activePage' => 'komunitas'])

@section('title', $post->title . ' — ' . __('app.community') . ' 1Langkah')
@section('header_title', __('app.community'))
@section('header_action')
    <a href="{{ route('komunitas') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-200 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        {{ __('app.back') }}
    </a>
@endsection

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- Main Post -->
    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
        <!-- Author Header -->
        <div class="p-5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <img decoding="async" loading="lazy" alt="" src="{{ $post->user->profile_photo ?? 'https://i.pravatar.cc/150?img=1' }}"
                     alt="{{ $post->user->name }}"
                     class="w-12 h-12 rounded-full object-cover">
                <div>
                    <h2 class="font-semibold text-gray-900">{{ $post->user->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-5">
            <h1 class="font-extrabold text-gray-900 text-2xl mb-4">{{ $post->title }}</h1>

            <!-- Images -->
            @if(!empty($post->image_urls))
            <div class="mb-4 grid gap-3 {{ count($post->image_urls) > 1 ? 'grid-cols-2' : '' }}">
                @foreach($post->image_urls as $index => $imageUrl)
                <a href="{{ $imageUrl }}" target="_blank" class="block rounded-xl overflow-hidden hover:opacity-90 transition-opacity" aria-label="Lihat Gambar Penuh">
                    <img decoding="async" loading="lazy" src="{{ $imageUrl }}" alt="Image {{ $index + 1 }}" class="w-full max-h-96 object-contain rounded-xl bg-gray-50">
                </a>
                @endforeach
            </div>
            @endif

            <!-- Text Content -->
            <div class="prose prose-sm sm:prose-base prose-gray max-w-none mb-6">
                <p class="text-gray-700 whitespace-pre-wrap break-words leading-relaxed">{{ $post->content }}</p>
            </div>

            <!-- Vote & Meta -->
            <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-gray-100">
                <div class="flex items-center gap-2 sm:gap-3" x-data="{}">
                    <button onclick="votePost({{ $post->id }}, 'up', this)"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg transition-colors {{ isset($userVotes['post']) && $userVotes['post'] === true ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600 hover:bg-red-50 hover:text-red-500' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                        <span class="font-semibold text-sm" id="post-upvotes">{{ $post->upvotes }}</span>
                    </button>
                    <span class="font-bold text-gray-700 px-1 sm:px-2" id="post-score-{{ $post->id }}">{{ $post->score }}</span>
                    <button onclick="votePost({{ $post->id }}, 'down', this)"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg transition-colors {{ isset($userVotes['post']) && $userVotes['post'] === false ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600 hover:bg-red-50 hover:text-red-500' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        <span class="font-semibold text-sm" id="post-downvotes">{{ $post->downvotes }}</span>
                    </button>
                </div>

                <div class="flex items-center gap-3 sm:gap-4 text-xs sm:text-sm text-gray-500">
                    <span class="flex items-center gap-1 sm:gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        {{ $post->reply_count }} {{ __('app.comments_count') }}
                    </span>
                    <button onclick="showReportModal('post', {{ $post->id }})" class="flex items-center gap-1 sm:gap-1.5 hover:text-red-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        {{ __('app.report') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reply Form -->
    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
        <h2 class="font-bold text-gray-900 mb-4">{{ __('app.write_comment') }}</h2>
        <form action="{{ route('komunitas.reply') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="post_id" value="{{ $post->id }}">

            <div class="mb-3">
                <textarea aria-label="'{{ __('app.write_comment_placeholder') }}'" name="content" rows="4" :placeholder="'{{ __('app.write_comment_placeholder') }}'"
                          class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm resize-none"
                          required>{{ old('content') }}</textarea>
                @error('content')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('app.image_optional_multiple') }}
                </label>
                <input aria-label="'https://example.com/image1.jpg, https://example.com/image2.jpg'" type="text" name="image_urls" :placeholder="'https://example.com/image1.jpg, https://example.com/image2.jpg'"
                       value="{{ old('image_urls') }}"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                <p class="text-xs text-gray-500 mt-1">{{ __('app.image_url_multiple_help') }}</p>
                @error('image_urls')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="px-5 py-2.5 bg-red-600 text-white rounded-lg font-semibold text-sm hover:bg-red-700 transition-colors">
                {{ __('app.submit_comment') }}
            </button>
        </form>
    </div>

    <!-- Replies Section -->
    <div class="space-y-4">
        <h2 class="font-bold text-gray-900 text-lg">{{ $post->reply_count }} {{ __('app.comments_count_title') }}</h2>

        @forelse($post->topLevelReplies as $reply)
        <div class="reply-container bg-white border border-gray-100 rounded-2xl p-5 shadow-sm" id="reply-{{ $reply->id }}">
            <!-- Reply Header -->
            <div class="flex items-start gap-3 mb-3">
                <img decoding="async" loading="lazy" alt="" src="{{ $reply->user->profile_photo ?? 'https://i.pravatar.cc/150?img=1' }}"
                     alt="{{ $reply->user->name }}"
                     class="w-10 h-10 rounded-full object-cover shrink-0">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-semibold text-gray-900">{{ $reply->user->name }}</span>
                        <span class="text-gray-400 text-sm">•</span>
                        <span class="text-sm text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <!-- Reply Content -->
            <div class="pl-13">
                <!-- Images -->
                @if(!empty($reply->image_urls))
                <div class="flex gap-2 mb-3 overflow-x-auto pb-1">
                    @foreach($reply->image_urls as $index => $imageUrl)
                    <a href="{{ $imageUrl }}" target="_blank" class="shrink-0 block rounded-lg overflow-hidden hover:opacity-90 transition-opacity" aria-label="Lihat Gambar Penuh">
                        <img decoding="async" loading="lazy" src="{{ $imageUrl }}" alt="Image {{ $index + 1 }}" class="h-20 w-20 object-cover rounded-lg">
                    </a>
                    @endforeach
                </div>
                @endif

                <p class="text-gray-700 whitespace-pre-wrap break-words leading-relaxed mb-3">{{ $reply->content }}</p>

                <!-- Reply Vote -->
                <div class="flex items-center gap-3" x-data="{}">
                    <button onclick="voteReply({{ $reply->id }}, 'up', this)"
                            class="flex items-center gap-1 px-2 py-1 rounded-lg text-sm transition-colors {{ isset($userVotes['replies'][$reply->id]) && $userVotes['replies'][$reply->id] === true ? 'text-red-600' : 'text-gray-500 hover:text-red-500' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                        <span class="font-medium">{{ $reply->upvotes }}</span>
                    </button>
                    <span class="text-sm font-bold text-gray-600" id="reply-score-{{ $reply->id }}">{{ $reply->score }}</span>
                    <button onclick="voteReply({{ $reply->id }}, 'down', this)"
                            class="flex items-center gap-1 px-2 py-1 rounded-lg text-sm transition-colors {{ isset($userVotes['replies'][$reply->id]) && $userVotes['replies'][$reply->id] === false ? 'text-red-600' : 'text-gray-500 hover:text-red-500' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        <span class="font-medium">{{ $reply->downvotes }}</span>
                    </button>
                </div>

                <!-- Nested Replies -->
                @if($reply->children->count() > 0)
                <div class="mt-4 ml-4 pl-4 border-l-2 border-gray-100 space-y-4">
                    @foreach($reply->children as $child)
                    <div class="bg-gray-50 rounded-xl p-4" id="reply-{{ $child->id }}">
                        <div class="flex items-start gap-3 mb-2">
                            <img decoding="async" loading="lazy" alt="" src="{{ $child->user->profile_photo ?? 'https://i.pravatar.cc/150?img=1' }}"
                                 alt="{{ $child->user->name }}"
                                 class="w-8 h-8 rounded-full object-cover shrink-0">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-semibold text-gray-900 text-sm">{{ $child->user->name }}</span>
                                    <span class="text-gray-400 text-xs">•</span>
                                    <span class="text-xs text-gray-500">{{ $child->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        @if(!empty($child->image_urls))
                        <div class="flex gap-2 mb-2 overflow-x-auto pb-1 ml-11">
                            @foreach($child->image_urls as $index => $imageUrl)
                            <a href="{{ $imageUrl }}" target="_blank" class="shrink-0 block rounded-lg overflow-hidden hover:opacity-90 transition-opacity" aria-label="Lihat Gambar Penuh">
                                <img decoding="async" loading="lazy" src="{{ $imageUrl }}" alt="Image {{ $index + 1 }}" class="h-16 w-16 object-cover rounded-lg">
                            </a>
                            @endforeach
                        </div>
                        @endif

                        <p class="text-gray-700 text-sm whitespace-pre-wrap break-words leading-relaxed ml-11 mb-2">{{ $child->content }}</p>

                        <!-- Child Vote -->
                        <div class="flex items-center gap-2 ml-11" x-data="{}">
                            <button onclick="voteReply({{ $child->id }}, 'up', this)"
                                    class="flex items-center gap-1 px-2 py-0.5 rounded text-xs transition-colors {{ isset($userVotes['replies'][$child->id]) && $userVotes['replies'][$child->id] === true ? 'text-red-600' : 'text-gray-500 hover:text-red-500' }}">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                <span class="font-medium">{{ $child->upvotes }}</span>
                            </button>
                            <span class="text-xs font-bold text-gray-600" id="reply-score-{{ $child->id }}">{{ $child->score }}</span>
                            <button onclick="voteReply({{ $child->id }}, 'down', this)"
                                    class="flex items-center gap-1 px-2 py-0.5 rounded text-xs transition-colors {{ isset($userVotes['replies'][$child->id]) && $userVotes['replies'][$child->id] === false ? 'text-red-600' : 'text-gray-500 hover:text-red-500' }}">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                <span class="font-medium">{{ $child->downvotes }}</span>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white border border-gray-100 rounded-2xl p-6 md:p-8 shadow-sm text-center">
            <p class="text-gray-500">{{ __('app.no_comments_yet') }}</p>
        </div>
        @endforelse
    </div>

</div>

@push('scripts')
<script>
// Vote on post
async function votePost(postId, voteType, buttonElement) {
    try {
        const response = await fetch('{{ route('komunitas.vote') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                votable_id: postId,
                votable_type: 'post',
                vote_type: voteType
            })
        });

        const data = await response.json();

        if (data.success) {
            const oldUpvotes = parseInt(document.getElementById('post-upvotes').textContent);
            const oldDownvotes = parseInt(document.getElementById('post-downvotes').textContent);

            document.getElementById('post-score-' + postId).textContent = data.score;
            document.getElementById('post-upvotes').textContent = data.upvotes;
            document.getElementById('post-downvotes').textContent = data.downvotes;

            // Update button styles
            const upBtn = buttonElement.closest('div').querySelector('button:first-child');
            const downBtn = buttonElement.closest('div').querySelector('button:last-child');

            upBtn.classList.remove('bg-red-100', 'text-red-600');
            downBtn.classList.remove('bg-red-100', 'text-red-600');
            upBtn.classList.add('bg-gray-100', 'text-gray-600');
            downBtn.classList.add('bg-gray-100', 'text-gray-600');

            if (voteType === 'up' && data.upvotes > oldUpvotes) {
                upBtn.classList.remove('bg-gray-100', 'text-gray-600');
                upBtn.classList.add('bg-red-100', 'text-red-600');
            } else if (voteType === 'down' && data.downvotes > oldDownvotes) {
                downBtn.classList.remove('bg-gray-100', 'text-gray-600');
                downBtn.classList.add('bg-red-100', 'text-red-600');
            }
        }
    } catch (error) {
        console.error('Vote error:', error);
        alert('{{ __('app.vote_error') }}');
    }
}

// Vote on reply
async function voteReply(replyId, voteType, buttonElement) {
    try {
        const response = await fetch('{{ route('komunitas.vote') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                votable_id: replyId,
                votable_type: 'reply',
                vote_type: voteType
            })
        });

        const data = await response.json();

        if (data.success) {
            const scoreEl = document.getElementById('reply-score-' + replyId);
            if (scoreEl) {
                scoreEl.textContent = data.score;
            }

            // Update button styles
            const container = buttonElement.closest('div');
            const upBtn = container.querySelector('button:first-child');
            const downBtn = container.querySelector('button:last-child');

            const oldUpvotes = parseInt(upBtn.querySelector('span').textContent);
            const oldDownvotes = parseInt(downBtn.querySelector('span').textContent);

            upBtn.querySelector('span').textContent = data.upvotes;
            downBtn.querySelector('span').textContent = data.downvotes;

            upBtn.classList.remove('text-red-600');
            downBtn.classList.remove('text-red-600');
            upBtn.classList.add('text-gray-500');
            downBtn.classList.add('text-gray-500');

            if (voteType === 'up' && data.upvotes > oldUpvotes) {
                upBtn.classList.remove('text-gray-500');
                upBtn.classList.add('text-red-600');
            } else if (voteType === 'down' && data.downvotes > oldDownvotes) {
                downBtn.classList.remove('text-gray-500');
                downBtn.classList.add('text-red-600');
            }
        }
    } catch (error) {
        console.error('Vote error:', error);
        alert('{{ __('app.vote_error') }}');
    }
}

// Report Modal Functions
function showReportModal(type, id) {
    document.getElementById('reportable_type').value = type;
    document.getElementById('reportable_id').value = id;
    document.getElementById('reportModal').classList.remove('hidden');
}

function hideReportModal() {
    document.getElementById('reportModal').classList.add('hidden');
}

async function submitReport() {
    const form = document.getElementById('reportForm');
    const formData = new FormData(form);

    try {
        const response = await fetch('/api/reports', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            alert('{{ __('app.report_success') }}');
            hideReportModal();
            document.getElementById('report_reason').value = '';
            document.getElementById('report_description').value = '';
        } else {
            alert(data.message || '{{ __('app.report_fail') }}');
        }
    } catch (error) {
        console.error('Report error:', error);
        alert('{{ __('app.report_error') }}');
    }
}
</script>

<!-- Report Modal -->
<div id="reportModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/50" onclick="hideReportModal()"></div>
    <div class="relative bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl">
        <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('app.report_content') }}</h2>
        <p class="text-sm text-gray-600 mb-4">{{ __('app.report_content_desc') }}</p>

        <form id="reportForm">
            <input type="hidden" name="reportable_type" id="reportable_type" value="">
            <input type="hidden" name="reportable_id" id="reportable_id" value="">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.reason') }}</label>
                <select name="reason" id="report_reason" class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" required>
                    <option value="">{{ __('app.select_reason') }}</option>
                    <option value="spam">{{ __('app.spam') }}</option>
                    <option value="harassment">{{ __('app.harassment') }}</option>
                    <option value="inappropriate_content">{{ __('app.inappropriate_content') }}</option>
                    <option value="misinformation">{{ __('app.misinformation') }}</option>
                    <option value="copyright">{{ __('app.copyright_violation') }}</option>
                    <option value="other">{{ __('app.other') }}</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.description_optional') }}</label>
                <textarea name="description" id="report_description" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" :placeholder="'{{ __('app.provide_details') }}'"></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="hideReportModal()" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                    {{ __('app.cancel') }}
                </button>
                <button type="button" onclick="submitReport()" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors">
                    {{ __('app.submit_report') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endpush
@endsection
