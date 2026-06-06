<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Reply;
use App\Models\User;
use App\Notifications\MentionNotification;
use App\Notifications\ReplyOnCommentNotification;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Comment $comment)
    {
        $data = $request->validate(['body' => 'required|string|max:1000']);

        $reply = Reply::create([
            'user_id'    => auth()->id(),
            'comment_id' => $comment->id,
            'body'       => $data['body'],
        ]);

        if ($comment->user_id !== auth()->id()) {
            $comment->user->notify(new ReplyOnCommentNotification($reply, auth()->user(), $comment));
        }

        foreach ($reply->getMentions() as $username) {
            $mentioned = User::where('username', $username)->first();
            if ($mentioned && $mentioned->id !== auth()->id()) {
                $mentioned->notify(new MentionNotification(auth()->user(), $reply->body, 'réponse', $reply->id));
            }
        }

        return back()->with('success', 'Réponse ajoutée !');
    }

    public function update(Request $request, Reply $reply)
    {
        $this->authorize('update', $reply);
        $request->validate(['body' => 'required|string|max:1000']);
        $reply->update(['body' => $request->body]);
        return back()->with('success', 'Réponse modifiée !');
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('delete', $reply);
        $reply->delete();
        return back()->with('success', 'Réponse supprimée.');
    }
}
