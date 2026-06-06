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
    
    

    public function store(Request $request, Post $post)
    {
        $data = $request->validate(['body' => 'required|string|max:1000']);

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'post_id' => $post->id,
            'body'    => $data['body'],
        ]);

        if ($post->user_id !== auth()->id()) {
            $post->user->notify(new CommentOnPostNotification($comment, auth()->user()));
        }

        foreach ($comment->getMentions() as $username) {
            $mentioned = User::where('username', $username)->first();
            if ($mentioned && $mentioned->id !== auth()->id()) {
                $mentioned->notify(new MentionNotification(auth()->user(), $comment->body, 'commentaire', $comment->id));
            }
        }

        return back()->with('success', 'Commentaire ajouté !');
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);
        $request->validate(['body' => 'required|string|max:1000']);
        $comment->update(['body' => $request->body]);
        return back()->with('success', 'Commentaire modifié !');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();
        return back()->with('success', 'Commentaire supprimé.');
    }
}
