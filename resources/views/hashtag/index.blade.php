@extends('layout.app')
@section('title', '#{{ $tag }} — Violet')

@section('content')
    <div class="sticky top-0 z-20 glass border-b border-subtle px-4 py-3">
        <a href="{{ route('home') }}" class="text-pink-400 hover:text-pink-300 text-sm font-semibold flex items-center gap-1 mb-1">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
        </a>
        <h1 class="text-xl font-extrabold tracking-tight gradient-text inline-block">#{{ $tag }}</h1>
        <p class="text-xs text-gray-600 mt-0.5">
            {{ $posts->count() }} postingan · {{ $comments->count() }} komentar
        </p>
    </div>

    {{-- POSTINGAN --}}
    @if($posts->count() > 0)
        <div class="px-4 pt-4 pb-2">
            <div class="flex items-center gap-2 mb-3">
                <i data-lucide="file-text" class="w-4 h-4 text-gray-600"></i>
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Postingan</span>
            </div>
            @foreach($posts as $post)
                @include('partials.post-card', ['post' => $post])
            @endforeach
        </div>
    @endifz

    {{-- KOMENTAR --}}
    @if($comments->count() > 0)
        <div class="px-4 pt-4 {{ $posts->count() > 0 ? 'border-t border-subtle' : '' }}">
            <div class="flex items-center gap-2 mb-3">
                <i data-lucide="message-circle" class="w-4 h-4 text-gray-600"></i>
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Komentar</span>
            </div>

            @foreach($comments as $comment)
                <a href="{{ route('posts.show', $comment->post_id) }}" class="block">
                    <div class="flex gap-3 p-4 border-b border-subtle hover:bg-white/[0.02] transition-colors bg-pink-500/[0.04] border-l-2 border-l-pink-500/30 rounded-r-xl mb-1">
                        <img src="{{ $comment->user->profile_photo_url }}" 
                             class="w-9 h-9 rounded-full object-cover shrink-0 ring-1 ring-pink-500/20" alt="">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 text-sm flex-wrap">
                                <span class="font-bold text-gray-200">{{ $comment->user->name }}</span>
                                <span class="text-gray-600 text-xs">· {{ $comment->created_at->diffForHumans() }}</span>
                                <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-full bg-pink-500/20 text-pink-400 border border-pink-500/10">#{{ $tag }}</span>
                            </div>
                            <p class="post-body text-[14px] mt-1 leading-relaxed text-gray-200">{!! linkHashtags($comment->content) !!}</p>
                            <div class="flex items-center gap-2 mt-2 text-[11px] text-gray-600">
                                <span class="flex items-center gap-1">
                                    <i data-lucide="file-text" class="w-3 h-3"></i>
                                    {{ $comment->post->user->name }}
                                </span>
                                <span>·</span>
                                <span>{{ str()->limit(strip_tags($comment->post->content), 50) }}</span>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-gray-700 shrink-0 mt-3"></i>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

    {{-- KOSONG --}}
    @if($posts->count() === 0 && $comments->count() === 0)
        <div class="text-center py-20 px-4 fade-in">
            <div class="w-16 h-16 rounded-3xl bg-white/[0.03] flex items-center justify-center mx-auto mb-4">
                <i data-lucide="search-x" class="w-7 h-7 text-gray-700"></i>
            </div>
            <p class="text-lg font-bold text-gray-400">Tidak ada hasil</p>
            <p class="text-sm text-gray-600 mt-1">Tidak ditemukan postingan atau komentar dengan <span class="text-pink-400">#{{ $tag }}</span></p>
        </div>
    @endif
@endsection