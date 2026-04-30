<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class HashtagController extends Controller
{
    public function show($tag)
    {
        // 1. Post yang kontennya langsung ada #tag
        $posts = Post::with('user', 'likes', 'comments.user', 'comments.likes')
            ->where('content', 'LIKE', "%#{$tag}%")
            ->latest()
            ->get();

        // 2. Komentar yang isinya ada #tag (bukan dari post yang sudah ke-catch di atas)
        $postIdsWithTag = $posts->pluck('id');

        $comments = Comment::with('user', 'post.user', 'likes')
            ->where('content', 'LIKE', "%#{$tag}%")
            ->when($postIdsWithTag->isNotEmpty(), function ($q) use ($postIdsWithTag) {
                $q->whereNotIn('post_id', $postIdsWithTag);
            })
            ->latest()
            ->get();

        return view('hashtag.index', compact('posts', 'comments', 'tag'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');
        
        if (empty($query)) {
            return response()->json([]);
        }

        $tags = [];

        $posts = Post::where('content', 'LIKE', "%#{$query}%")
            ->select('content')
            ->get();

        foreach ($posts as $post) {
            $this->extractTags($post->content, $query, $tags);
        }

        $comments = Comment::where('content', 'LIKE', "%#{$query}%")
            ->select('content')
            ->get();

        foreach ($comments as $comment) {
            $this->extractTags($comment->content, $query, $tags);
        }

        $result = array_values($tags);
        usort($result, fn($a, $b) => $b['count'] <=> $a['count']);

        return response()->json($result);
    }

    public function searchPage(Request $request)
    {
        $tag = $request->input('tag', '');
        
        if (empty($tag)) {
            return redirect()->route('home');
        }

        return redirect()->route('hashtag.show', $tag);
    }

    private function extractTags(string $text, string $query, array &$tags): void
    {
        preg_match_all('/#(\w+)/', $text, $matches);

        foreach ($matches[1] as $match) {
            $lowerMatch = strtolower($match);
            $lowerQuery = strtolower($query);

            if (str_contains($lowerMatch, $lowerQuery)) {
                if (!isset($tags[$lowerMatch])) {
                    $tags[$lowerMatch] = ['tag' => $match, 'count' => 0];
                }
                $tags[$lowerMatch]['count']++;
            }
        }
    }
}