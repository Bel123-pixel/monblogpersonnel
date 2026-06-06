<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\LiveStream;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function dashboard()
    {
        $stats = [
            'users'    => User::count(),
            'posts'    => Post::count(),
            'comments' => Comment::count(),
            'lives'    => LiveStream::live()->count(),
        ];
        $recentUsers    = User::latest()->take(5)->get();
        $recentPosts    = Post::with('user')->latest()->take(5)->get();
        $recentComments = Comment::with(['user', 'post'])->latest()->take(5)->get();
        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentPosts', 'recentComments'));
    }

    public function users(Request $request)
    {
        $search = $request->input('search');
        $users  = User::when($search, fn($q) => $q->where('name', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%"))
            ->latest()->paginate(20);
        return view('admin.users', compact('users', 'search'));
    }

    public function toggleAdmin(User $user)
    {
        abort_if($user->id === auth()->id(), 403);
        $user->update(['is_admin' => !$user->is_admin]);
        $role = $user->is_admin ? 'administrateur' : 'utilisateur';
        return back()->with('success', "{$user->name} est maintenant $role.");
    }

    public function destroyUser(User $user)
    {
        abort_if($user->id === auth()->id(), 403);
        $user->delete();
        return back()->with('success', 'Utilisateur supprimé.');
    }

    public function posts(Request $request)
    {
        $search = $request->input('search');
        $posts  = Post::with('user')
            ->when($search, fn($q) => $q->where('title', 'like', "%$search%"))
            ->latest()->paginate(20);
        return view('admin.posts', compact('posts', 'search'));
    }

    public function destroyPost(Post $post)
    {
        $post->delete();
        return back()->with('success', 'Publication supprimée.');
    }

    public function comments(Request $request)
    {
        $comments = Comment::with(['user', 'post'])->latest()->paginate(20);
        return view('admin.comments', compact('comments'));
    }

    public function destroyComment(Comment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Commentaire supprimé.');
    }
}
