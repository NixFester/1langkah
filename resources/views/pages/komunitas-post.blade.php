@extends('layouts.app', ['activePage' => 'komunitas'])

@section('title', $post->title . ' — Komunitas 1Langkah')
@section('header_title', 'Komunitas')
@section('header_action')
    <a href="{{ route('komunitas') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-200 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali
    </a>
@endsection

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- Main Post -->
    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
        <!-- Author Header -->
        <div class="p-5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <img src="{{ $post->user->profile_photo ?? 'https://i.pravatar.cc/150?img=1' }}"
                     alt="{{ $post->user->name }}"
                     class="w-12 h-12 rounded-full object-cover">
                <div>
                    <h4 class="font-semibold text-gray-900">{{ $post->user->name }}</h4>
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
                <a href="{{ $imageUrl }}" target="_blank" class="block rounded-xl overflow-hidden hover:opacity-90 transition-opacity">
                    <img src="{{ $imageUrl }}" alt="Image {{ $index + 1 }}" class="w-full max-h-96 object-contain rounded-xl bg-gray-50">
                </a>
                @endforeach
            </div>
            @endif

            <!-- Text Content -->
            <div class="prose prose-gray max-w-none mb-6">
                <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $post->content }}</p>
            </div>

            <!-- Vote & Meta -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <div class="flex items-center gap-3" x-data="{}">
                    <button onclick="votePost({{ $post->id }}, 'up', this)"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg transition-colors {{ isset($userVotes['post']) && $userVotes['post'] === true ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600 hover:bg-red-50 hover:text-red-500' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                        <span class="font-semibold text-sm" id="post-upvotes">{{ $post->upvotes }}</span>
                    </button>
                    <span class="font-bold text-gray-700 px-2" id="post-score-{{ $post->id }}">{{ $post->score }}</span>
                    <button onclick="votePost({{ $post->id }}, 'down', this)"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg transition-colors {{ isset($userVotes['post']) && $userVotes['post'] === false ? 'bg-gray-200 text-gray-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        <span class="font-semibold text-sm" id="post-downvotes">{{ $post->downvotes }}</span>
                    </button>
                </div>

                <div class="flex items-center gap-4 text-sm text-gray-500">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        {{ $post->reply_count }} komentar
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Reply Form -->
    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
        <h3 class="font-bold text-gray-900 mb-4">Tulis Komentar</h3>
        <form action="{{ route('komunitas.reply') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="post_id" value="{{ $post->id }}">

            <div class="mb-3">
                <textarea name="content" rows="4" placeholder="Tulis komentar kamu..."
                          class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm resize-none"
                          required>{{ old('content') }}</textarea>
                @error('content')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Gambar (opsional) - Pisahkan dengan koma untuk multiple
                </label>
                <input type="text" name="image_urls" placeholder="https://example.com/image1.jpg, https://example.com/image2.jpg"
                       value="{{ old('image_urls') }}"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                <p class="text-xs text-gray-500 mt-1">Masukkan URL gambar, pisahkan dengan koma untuk multiple gambar</p>
                @error('image_urls')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="px-5 py-2.5 bg-red-600 text-white rounded-lg font-semibold text-sm hover:bg-red-700 transition-colors">
                Kirim Komentar
            </button>
        </form>
    </div>

    <!-- Replies Section -->
    <div class="space-y-4">
        <h3 class="font-bold text-gray-900 text-lg">{{ $post->reply_count }} Komentar</h3>

        @forelse($post->topLevelReplies as $reply)
        <div class="reply-container bg-white border border-gray-100 rounded-2xl p-5 shadow-sm" id="reply-{{ $reply->id }}">
            <!-- Reply Header -->
            <div class="flex items-start gap-3 mb-3">
                <img src="{{ $reply->user->profile_photo ?? 'https://i.pravatar.cc/150?img=1' }}"
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
                    <a href="{{ $imageUrl }}" target="_blank" class="shrink-0 block rounded-lg overflow-hidden hover:opacity-90 transition-opacity">
                        <img src="{{ $imageUrl }}" alt="Image {{ $index + 1 }}" class="h-20 w-20 object-cover rounded-lg">
                    </a>
                    @endforeach
                </div>
                @endif

                <p class="text-gray-700 whitespace-pre-wrap leading-relaxed mb-3">{{ $reply->content }}</p>

                <!-- Reply Vote -->
                <div class="flex items-center gap-3" x-data="{}">
                    <button onclick="voteReply({{ $reply->id }}, 'up', this)"
                            class="flex items-center gap-1 px-2 py-1 rounded-lg text-sm transition-colors {{ isset($userVotes['replies'][$reply->id]) && $userVotes['replies'][$reply->id] === true ? 'text-red-600' : 'text-gray-500 hover:text-red-500' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                        <span class="font-medium">{{ $reply->upvotes }}</span>
                    </button>
                    <span class="text-sm font-bold text-gray-600" id="reply-score-{{ $reply->id }}">{{ $reply->score }}</span>
                    <button onclick="voteReply({{ $reply->id }}, 'down', this)"
                            class="flex items-center gap-1 px-2 py-1 rounded-lg text-sm transition-colors {{ isset($userVotes['replies'][$reply->id]) && $userVotes['replies'][$reply->id] === false ? 'text-gray-600' : 'text-gray-500 hover:text-gray-600' }}">
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
                            <img src="{{ $child->user->profile_photo ?? 'https://i.pravatar.cc/150?img=1' }}"
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
                            <a href="{{ $imageUrl }}" target="_blank" class="shrink-0 block rounded-lg overflow-hidden hover:opacity-90 transition-opacity">
                                <img src="{{ $imageUrl }}" alt="Image {{ $index + 1 }}" class="h-16 w-16 object-cover rounded-lg">
                            </a>
                            @endforeach
                        </div>
                        @endif

                        <p class="text-gray-700 text-sm whitespace-pre-wrap leading-relaxed ml-11 mb-2">{{ $child->content }}</p>

                        <!-- Child Vote -->
                        <div class="flex items-center gap-2 ml-11" x-data="{}">
                            <button onclick="voteReply({{ $child->id }}, 'up', this)"
                                    class="flex items-center gap-1 px-2 py-0.5 rounded text-xs transition-colors {{ isset($userVotes['replies'][$child->id]) && $userVotes['replies'][$child->id] === true ? 'text-red-600' : 'text-gray-500 hover:text-red-500' }}">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                <span class="font-medium">{{ $child->upvotes }}</span>
                            </button>
                            <span class="text-xs font-bold text-gray-600" id="reply-score-{{ $child->id }}">{{ $child->score }}</span>
                            <button onclick="voteReply({{ $child->id }}, 'down', this)"
                                    class="flex items-center gap-1 px-2 py-0.5 rounded text-xs transition-colors {{ isset($userVotes['replies'][$child->id]) && $userVotes['replies'][$child->id] === false ? 'text-gray-600' : 'text-gray-500 hover:text-gray-600' }}">
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
        <div class="bg-white border border-gray-100 rounded-2xl p-8 shadow-sm text-center">
            <p class="text-gray-500">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
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
            document.getElementById('post-score-' + postId).textContent = data.score;
            document.getElementById('post-upvotes').textContent = data.upvotes;
            document.getElementById('post-downvotes').textContent = data.downvotes;

            // Update button styles
            const upBtn = buttonElement.closest('div').querySelector('button:first-child');
            const downBtn = buttonElement.closest('div').querySelector('button:last-child');

            upBtn.classList.remove('bg-red-100', 'text-red-600');
            downBtn.classList.remove('bg-gray-200', 'text-gray-700');
            upBtn.classList.add('bg-gray-100', 'text-gray-600');
            downBtn.classList.add('bg-gray-100', 'text-gray-600');

            if (data.upvotes > {{ $post->upvotes }}) {
                upBtn.classList.remove('bg-gray-100', 'text-gray-600');
                upBtn.classList.add('bg-red-100', 'text-red-600');
            } else if (data.downvotes > {{ $post->downvotes }}) {
                downBtn.classList.remove('bg-gray-100', 'text-gray-600');
                downBtn.classList.add('bg-gray-200', 'text-gray-700');
            }
        }
    } catch (error) {
        console.error('Vote error:', error);
        alert('Terjadi kesalahan saat memberikan vote. Pastikan Anda sudah login.');
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

            upBtn.classList.remove('text-red-600');
            downBtn.classList.remove('text-gray-600');
            upBtn.classList.add('text-gray-500');
            downBtn.classList.add('text-gray-500');

            if (data.upvotes > parseInt(upBtn.querySelector('span').textContent)) {
                upBtn.classList.remove('text-gray-500');
                upBtn.classList.add('text-red-600');
            } else if (data.downvotes > parseInt(downBtn.querySelector('span').textContent)) {
                downBtn.classList.remove('text-gray-500');
                downBtn.classList.add('text-gray-600');
            }
        }
    } catch (error) {
        console.error('Vote error:', error);
        alert('Terjadi kesalahan saat memberikan vote. Pastikan Anda sudah login.');
    }
}
</script>
@endpush
@endsection
