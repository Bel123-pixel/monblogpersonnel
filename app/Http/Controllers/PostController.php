<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'edit', 'update', 'destroy', 'submitLike']);
    }

    public function index()
    {
        $posts = Post::with('user', 'likes', 'images')->published()->orderBy('created_at', 'desc')->get();
        return view('home', compact('posts'));
    }

    public function create()
    {
        abort_unless(auth()->user()->is_admin, 403);
        return view('posts.create');
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->is_admin, 403);

        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string|min:20',
            'image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $imagePath = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $file->extension();
            Storage::disk('public')->putFileAs('posts', $file, $fileName);
            $imagePath = 'storage/posts/' . $fileName;
        }

        Post::create([
            'user_id' => auth()->id(),
            'title'   => $request->input('title'),
            'content' => $request->input('content'),
            'image'   => $imagePath,
            'status'  => 'published',
        ]);

        return redirect()->route('admin.posts')->with('success', 'Publication créée !');
    }

    public function show(Post $post)
    {
        $post->load(['user', 'comments.user', 'comments.replies.user']);
        return view('posts.show', compact('post'));
    }

    public function submitLike($id)
    {
        $user = auth()->user() ?? \App\Models\User::find(1);
        $post = Post::findOrFail($id);

        $like = $post->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $post->likes()->create(['user_id' => $user->id]);
            $liked = true;
        }

        return response()->json([
            'liked'       => $liked,
            'likes_count' => $post->likes()->count()
        ]);
    }

    public function edit(Post $post)
    {
        abort_unless(auth()->user()->is_admin, 403);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        abort_unless(auth()->user()->is_admin, 403);

        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'status'  => 'nullable|in:draft,published',
        ]);

        unset($data['image']);

        if ($request->hasFile('image')) {
            $result = Cloudinary::upload($request->file('image')->getRealPath(), [
                'folder' => 'bellevieshop/posts'
            ]);
            $data['image'] = $result->getSecurePath();
        }

        $post->update($data);

        return redirect()->route('admin.posts')->with('success', 'Publication mise à jour !');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        if (auth()->user()->is_admin) {
            return redirect()->route('admin.posts')->with('success', 'Publication supprimée.');
        }

        return redirect()->route('home')->with('success', 'Publication supprimée.');
    }
}