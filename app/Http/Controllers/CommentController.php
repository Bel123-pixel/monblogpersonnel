<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Notifications\CommentOnPostNotification;
use App\Notifications\MentionNotification;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Post $post)
    {
        $data = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'post_id' => $post->id,
            'body'    => $data['content'],
        ]);

        // Notifier le propriétaire du post
        if ($post->user_id !== auth()->id()) {
            $post->user->notify(new CommentOnPostNotification($comment, auth()->user()));
        }

        // Notifier les utilisateurs mentionnés (@username)
        $mentions = $comment->getMentions();
        if (!empty($mentions)) {
            $mentionedUsers = User::whereIn('username', $mentions)
                ->where('id', '!=', auth()->id())
                ->get();

            foreach ($mentionedUsers as $mentioned) {
                $mentioned->notify(new MentionNotification(
                    auth()->user(),
                    $comment->body,
                    'commentaire',
                    $comment->id,
                ));
            }
        }

        return back();
    }

    // Autocomplétion @username
    public function mentionSearch(Request $request)
    {
        $q = $request->input('q', '');
        $users = User::where('username', 'like', "%{$q}%")
            ->orWhere('name', 'like', "%{$q}%")
            ->limit(6)
            ->get(['id', 'name', 'username', 'avatar']);

        return response()->json($users->map(fn($u) => [
            'username'   => $u->username,
            'name'       => $u->name,
            'avatar_url' => $u->avatar_url,
        ]));
    }
}