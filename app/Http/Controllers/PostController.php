<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
   public function index()
{
    $posts = Post::with('user')->latest()->get();
    
    // On change 'welcome' par 'home' ici
    return view('home', compact('posts'));
}

    public function show(string $slug)
    {
        $post = Post::with([
            'user',
            'comments.user',
            'comments.replies.user'
        ])->where('slug', $slug)->firstOrFail();

        return view('posts.show', compact('post'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'status'  => 'nullable|in:draft,published',
        ]);

        $data['user_id'] = auth()->id();
        $data['status'] = $data['status'] ?? 'published';

        if ($request->hasFile('image')) {
            $filename = 'post_' . time() . '.' . $request->file('image')->extension();
            $request->file('image')->storeAs('public/posts', $filename);
            $data['image'] = $filename;
        }

        // IMPORTANT: slug simple si tu n’as pas encore système slug
        $data['slug'] = \Str::slug($data['title']) . '-' . time();

        $post = Post::create($data);

        return redirect()->route('posts.show', $post->slug)
            ->with('success', 'Publication créée !');
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'status'  => 'nullable|in:draft,published',
        ]);

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::delete('public/posts/' . $post->image);
            }

            $filename = 'post_' . time() . '.' . $request->file('image')->extension();
            $request->file('image')->storeAs('public/posts', $filename);
            $data['image'] = $filename;
        }

        $post->update($data);

        return redirect()->route('posts.show', $post->slug)
            ->with('success', 'Publication mise à jour !');
    }

    public function destroy(Post $post)
    {
        if ($post->image) {
            Storage::delete('public/posts/' . $post->image);
        }

        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Publication supprimée.');
    }
}
