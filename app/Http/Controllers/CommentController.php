<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

/**
 * CommentController - Menangani CRUD komentar:
 * menambah, mengedit, menghapus komentar pada postingan.
 */
class CommentController extends Controller
{
    /**
     * Menyimpan komentar baru pada postingan tertentu.
     */
    /**
     * Menyimpan komentar baru (top-level atau reply).
     * Support nested comments dengan parent_id.
     */
    public function store(Request $request, $postId, $parentId = null)
    {
        $validated = $request->validate([
            'content' => 'required|max:250',
            'image'   => 'nullable|image|max:2048',
            'file'    => 'nullable|file|max:5120',
        ]);

        // Upload gambar komentar jika ada
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')
                ->store('comments/images', 'public');
        }

        // Upload file komentar jika ada
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $validated['file_path'] = $file->store('comments/files', 'public');
            $validated['file_name'] = $file->getClientOriginalName();
        }

        $validated['post_id'] = $postId;
        $validated['user_id'] = Auth::id();
        $validated['parent_id'] = $parentId;

        Comment::create($validated);

        return back()->with('success', $parentId ? 'Balasan berhasil dikirim!' : 'Komentar berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit komentar.
     * Hanya pemilik komentar yang boleh mengedit.
     */
    public function edit($id)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses.');
        }
        return view('comments.edit', compact('comment'));
    }

    /**
     * Memperbarui komentar yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $validated = $request->validate([
            'content' => 'required|max:250',
            'image'   => 'nullable|image|max:2048',
            'file'    => 'nullable|file|max:5120',
        ]);

        if ($request->hasFile('image')) {
            if ($comment->image_path) Storage::delete($comment->image_path);
            $validated['image_path'] = $request->file('image')
                ->store('comments/images', 'public');
        }

        if ($request->hasFile('file')) {
            if ($comment->file_path) Storage::delete($comment->file_path);
            $file = $request->file('file');
            $validated['file_path'] = $file->store('comments/files', 'public');
            $validated['file_name'] = $file->getClientOriginalName();
        }

        $comment->update($validated);

        return redirect()->route('posts.show', $comment->post_id)
            ->with('success', 'Komentar berhasil diperbarui!');
    }

    /**
     * Menghapus komentar.
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses.');
        }
        $postId = $comment->post_id; // Simpan ID post sebelum komentar dihapus
        $comment->delete();

        return redirect()->route('posts.show', $postId)
            ->with('success', 'Komentar berhasil dihapus.');
    }
}