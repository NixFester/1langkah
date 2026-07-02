@extends('layouts.app', ['activePage' => 'komunitas'])

@section('title', 'Komunitas — 1Langkah')
@section('header_title', 'Komunitas')
@section('header_action')
    <a href="{{ route('komunitas.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg font-semibold text-sm hover:bg-red-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Buat Post
    </a>
@endsection

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="font-extrabold text-gray-900 tracking-tight" style="font-size: 28px;">Komunitas</h1>
            <p class="text-sm text-gray-500 mt-1 font-medium">Bagikan ide, tanya jawab, dan diskusikan topik menarik</p>
        </div>
    </div>

    <!-- Search & Filter Section -->
    <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">
        <form method="GET" action="{{ route('komunitas') }}" class="flex flex-col sm:flex-row gap-3">
            <!-- Search -->
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari post..." class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
            </div>

            <!-- Sort Dropdown -->
            <div class="relative min-w-[160px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" style="top: 50%;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path></svg>
                <select name="sort" class="w-full pl-9 pr-8 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm appearance-none bg-white cursor-pointer">
                    <option value="newest" {{ ($sort ?? 'newest') === 'newest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ ($sort ?? 'newest') === 'oldest' ? 'selected' : '' }}>Terlama</option>
                    <option value="popular" {{ ($sort ?? 'newest') === 'popular' ? 'selected' : '' }}>Terpopuler</option>
                    <option value="most_commented" {{ ($sort ?? 'newest') === 'most_commented' ? 'selected' : '' }}>Paling Banyak Komentar</option>
                </select>
                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>

            <button type="submit" class="px-5 py-2.5 bg-red-600 text-white rounded-lg font-semibold text-sm hover:bg-red-700 transition-colors whitespace-nowrap">
                Filter
            </button>
        </form>
    </div>

    <!-- Posts List -->
    @if($posts->count() > 0)
    <div class="space-y-4">
        @foreach($posts as $post)
        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex gap-4">
                <!-- Vote Column -->
                <div class="flex flex-col items-center gap-1 pt-1" x-data="{}">
                    <button onclick="votePost({{ $post->id }}, 'up', this)"
                            class="p-1.5 rounded-lg hover:bg-red-50 transition-colors {{ isset($userVotes[$post->id]) && $userVotes[$post->id] === true ? 'text-red-600' : 'text-gray-400 hover:text-red-500' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                    </button>
                    <span class="font-bold text-sm text-gray-700" id="vote-score-{{ $post->id }}">{{ $post->score }}</span>
                    <button onclick="votePost({{ $post->id }}, 'down', this)"
                            class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors {{ isset($userVotes[$post->id]) && $userVotes[$post->id] === false ? 'text-gray-600' : 'text-gray-400 hover:text-gray-600' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                </div>

                <!-- Content Column -->
                <div class="flex-1 min-w-0">
                    <!-- Author Info -->
                    <div class="flex items-center gap-2 mb-2">
                        <img src="{{ $post->user->profile_photo ?? 'https://i.pravatar.cc/150?img=1' }}"
                             alt="{{ $post->user->name }}"
                             class="w-6 h-6 rounded-full object-cover">
                        <span class="text-sm font-medium text-gray-700">{{ $post->user->name }}</span>
                        <span class="text-gray-300">•</span>
                        <span class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</span>
                    </div>

                    <!-- Title -->
                    <a href="{{ route('komunitas.show', $post->id) }}" class="block group">
                        <h3 class="font-bold text-gray-900 text-lg mb-2 group-hover:text-red-600 transition-colors line-clamp-2">
                            {{ $post->title }}
                        </h3>
                    </a>

                    <!-- Content Preview -->
                    <p class="text-sm text-gray-600 mb-3 line-clamp-3">
                        {{ Str::limit(strip_tags($post->content), 200) }}
                    </p>

                    <!-- Images Preview -->
                    @if(!empty($post->image_urls))
                    <div class="flex gap-2 mb-3 overflow-x-auto pb-1">
                        @foreach(array_slice($post->image_urls, 0, 3) as $index => $imageUrl)
                        <a href="{{ $imageUrl }}" target="_blank" class="shrink-0 block rounded-lg overflow-hidden hover:opacity-90 transition-opacity">
                            <img src="{{ $imageUrl }}" alt="Image {{ $index + 1 }}" class="h-20 w-20 object-cover rounded-lg">
                        </a>
                        @endforeach
                        @if(count($post->image_urls) > 3)
                        <div class="shrink-0 w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center text-sm font-medium text-gray-500">
                            +{{ count($post->image_urls) - 3 }}
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Meta Info -->
                    <div class="flex items-center gap-4 text-sm text-gray-500">
                        <a href="{{ route('komunitas.show', $post->id) }}" class="flex items-center gap-1.5 hover:text-red-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            <span class="font-medium">{{ $post->reply_count }} komentar</span>
                        </a>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                            <span class="font-medium">{{ $post->upvotes }}</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="flex justify-center pt-4">
        {{ $posts->links() }}
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white border border-gray-100 rounded-2xl p-12 shadow-sm text-center">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <h3 class="font-bold text-gray-900 text-xl mb-2">Belum Ada Post</h3>
        <p class="text-gray-500 max-w-md mx-auto mb-6">
            @if($search)
                Tidak ada post yang cocok dengan "{{ $search }}". Coba kata kunci lain.
            @else
                Jadilah yang pertama membuat post di komunitas ini!
            @endif
        </p>
        <a href="{{ route('komunitas.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 text-white rounded-lg font-semibold text-sm hover:bg-red-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Buat Post Pertama
        </a>
    </div>
    @endif

</div>

@push('scripts')
<script>
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
            document.getElementById('vote-score-' + postId).textContent = data.score;

            // Update button styles
            const container = buttonElement.closest('div');
            const upBtn = container.querySelector('button:first-child');
            const downBtn = container.querySelector('button:last-child');

            // Remove previous states
            upBtn.classList.remove('text-red-600');
            downBtn.classList.remove('text-gray-600');
            upBtn.classList.add('text-gray-400');
            downBtn.classList.add('text-gray-400');

            if (data.score > parseInt(document.getElementById('vote-score-' + postId).textContent.replace('-', ''))) {
                // Was upvoted
                upBtn.classList.remove('text-gray-400');
                upBtn.classList.add('text-red-600');
            } else if (data.score < parseInt(document.getElementById('vote-score-' + postId).textContent)) {
                // Was downvoted
                downBtn.classList.remove('text-gray-400');
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
