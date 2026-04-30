@extends('layout.app')
@section('title', 'Beranda — Violet')

@section('content')
    {{-- Header --}}
    <div class="sticky top-0 z-20 glass border-b border-modern px-4 py-3">
        <h1 class="text-xl font-extrabold tracking-tight">Beranda</h1>
    </div>

    {{-- Composer --}}
    <div class="p-4 border-b border-modern bg-[#0a0a0a]">
        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" class="flex gap-3">
            @csrf
            <img src="{{ Auth::user()->profile_photo_url }}" class="w-11 h-11 rounded-full object-cover shrink-0 ring-1 ring-white/10" alt="">
            <div class="flex-1">
                <textarea name="content" maxlength="250" required placeholder="Apa yang sedang terjadi?"
                    class="w-full bg-transparent text-[15px] resize-none outline-none placeholder-gray-600 min-h-[60px]"
                    oninput="document.getElementById('charCount').textContent=250-this.value.length">{{ old('content') }}</textarea>
                
                <div class="flex justify-between items-center pt-3 mt-2 border-t border-modern/50">
                    <div class="flex gap-1">
                        <label class="p-2 rounded-full hover:bg-blue-500/10 cursor-pointer transition-colors">
                            <i data-lucide="image" class="w-5 h-5 text-blue-500"></i>
                            <input type="file" name="image" accept="image/*" class="hidden">
                        </label>
                        <label class="p-2 rounded-full hover:bg-blue-500/10 cursor-pointer transition-colors">
                            <i data-lucide="paperclip" class="w-5 h-5 text-blue-500"></i>
                            <input type="file" name="file" class="hidden">
                        </label>
                    </div>
                    <div class="flex items-center gap-3">
                        <span id="charCount" class="text-xs text-gray-600 font-medium">250</span>
                        <button type="submit" class="gradient-bg text-white text-sm font-bold rounded-xl px-5 py-2 hover:opacity-90 transition-opacity shadow-lg shadow-blue-500/20">Posting</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Feed --}}
    @forelse($posts as $post)
        @include('partials.post-card')
    @empty
        <div class="text-center py-20 px-4">
            <i data-lucide="inbox" class="w-12 h-12 text-gray-700 mx-auto mb-4"></i>
            <p class="text-xl font-bold text-gray-400">Belum ada postingan</p>
            <p class="text-sm text-gray-600 mt-1">Jadilah yang pertama memposting sesuatu.</p>
        </div>
    @endforelse
@endsection