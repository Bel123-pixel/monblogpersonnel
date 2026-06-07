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
        $posts = Post::with('user', 'likes')->published()->orderBy('created_at', 'desc')->get();
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

        $image = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9\.\-_]/', '-', $file->getClientOriginalName());
            // Store in storage/app/public/posts so it's accessible via storage link
            $file->storeAs('public/posts', $fileName);
            $image = $fileName; // store filename, accessor will resolve URL
        }

        Post::create([
            'user_id' => auth()->id(),
            'title'   => $request->input('title'),
            'content' => $request->input('content'),
            'image'   => $image,
            'status'  => 'published',
        ]);

        if (auth()->user()->is_admin) {
            return redirect()->route('admin.posts')->with('success', 'Publication créée !');
        }

        return redirect()->route('home');
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
            'liked' => $liked,
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

        if ($request->hasFile('image')) {
            // Delete previous image whether it was stored in public/uploads or storage/app/public/posts
            if ($post->image) {
                if (str_starts_with($post->image, 'uploads/')) {
                    $old = public_path($post->image);
                    if (file_exists($old)) {
                        @unlink($old);
                    }
                } else {
                    Storage::delete('public/posts/' . $post->image);
                }
            }

            $filename = 'post_' . time() . '.' . $request->file('image')->extension();
            $request->file('image')->storeAs('public/posts', $filename);
            $data['image'] = $filename;
        }

        $post->update($data);

        if (auth()->user()->is_admin) {
            return redirect()->route('admin.posts')->with('success', 'Publication mise à jour !');
        }

        return redirect()->route('home')->with('success', 'Publication mise à jour !');
    }

    public function destroy(Post $post)
    {
        if ($post->image) {
            if (str_starts_with($post->image, 'uploads/')) {
                $old = public_path($post->image);
                if (file_exists($old)) {
                    @unlink($old);
                }
            } else {
                Storage::delete('public/posts/' . $post->image);
            }
        }

        $post->delete();

        if (auth()->user()->is_admin) {
            return redirect()->route('admin.posts')->with('success', 'Publication supprimée.');
        }

        return redirect()->route('home')->with('success', 'Publication supprimée.');
    }
}