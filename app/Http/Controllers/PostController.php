<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::with('user', 'comments', 'likes')
            ->latest()
            ->get();
        return view('home', compact('posts'));
    }

    public function store(Request $request)
    {
        // Validasi: konten wajib & maks 250 karakter
        $validated = $request->validate([
            'content' => 'required|max:250',
            'image'   => 'nullable|image|max:2048',
            'file'    => 'nullable|file|max:5120',
        ]);

        // Upload gambar jika ada
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')
                ->store('posts/images', 'public');
        }

        // Upload file jika ada
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $validated['file_path'] = $file->store('posts/files', 'public');
            $validated['file_name'] = $file->getClientOriginalName(); // Simpan nama asli
        }

        // Tambahkan ID user yang sedang login
        $validated['user_id'] = Auth::id();

        // Simpan ke database
        Post::create($validated);

        return back()->with('success', 'Postingan berhasil dibuat!');
    }

    public function show($id)
    {
        $post = Post::with([
            'user', 
            'comments' => function($query) {
                $query->whereNull('parent_id')->with(['user', 'likes', 'children.user', 'children.likes', 'children.children.user']);
            }, 
            'likes'
        ])->findOrFail($id);
        
        return view('posts.show', compact('post'));
    }

    /**
     * Menampilkan form edit postingan.
     * Hanya pemilik postingan yang boleh mengakses (autorisasi).
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses.');
        }
        return view('posts.edit', compact('post'));
    }

    /**
     * Memperbarui postingan yang sudah ada.
     * Jika ada gambar/file baru, yang lama dihapus dan diganti.
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $validated = $request->validate([
            'content' => 'required|max:250',
            'image'   => 'nullable|image|max:2048',
            'file'    => 'nullable|file|max:5120',
        ]);

        // Ganti gambar jika ada upload baru
        if ($request->hasFile('image')) {
            if ($post->image_path) Storage::delete($post->image_path);
            $validated['image_path'] = $request->file('image')
                ->store('posts/images', 'public');
        }

        // Ganti file jika ada upload baru
        if ($request->hasFile('file')) {
            if ($post->file_path) Storage::delete($post->file_path);
            $file = $request->file('file');
            $validated['file_path'] = $file->store('posts/files', 'public');
            $validated['file_name'] = $file->getClientOriginalName();
        }

        $post->update($validated);
        return redirect()->route('posts.show', $post->id)
            ->with('success', 'Postingan berhasil diperbarui!');
    }

    /**
     * Menghapus postingan.
     * File gambar & file akan otomatis terhapus via model event (booted).
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses.');
        }
        $post->delete();
        return redirect('/')->with('success', 'Postingan berhasil dihapus.');
    }
}