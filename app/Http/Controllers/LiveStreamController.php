<?php
namespace App\Http\Controllers;

use App\Models\LiveStream;
use Illuminate\Http\Request;

class LiveStreamController extends Controller
{
    

    public function index()
    {
        $liveStreams  = LiveStream::with('user')->live()->latest()->paginate(12);
        $endedStreams = LiveStream::with('user')->where('status', 'ended')->latest()->take(6)->get();
        return view('live.index', compact('liveStreams', 'endedStreams'));
    }

    public function show(LiveStream $liveStream)
    {
        return view('live.show', compact('liveStream'));
    }

    public function create()
    {
        return view('live.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'video_url'   => 'required|url',
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data['user_id']    = auth()->id();
        $data['status']     = 'live';
        $data['started_at'] = now();

        if ($request->hasFile('thumbnail')) {
            $filename = 'live_' . time() . '.' . $request->file('thumbnail')->extension();
            $request->file('thumbnail')->storeAs('public/lives', $filename);
            $data['thumbnail'] = $filename;
        }

        $live = LiveStream::create($data);
        return redirect()->route('live.show', $live)->with('success', 'Votre live a démarré !');
    }

    public function end(LiveStream $liveStream)
    {
        abort_unless(auth()->id() === $liveStream->user_id || auth()->user()->is_admin, 403);
        $liveStream->update(['status' => 'ended', 'ended_at' => now()]);
        return redirect()->route('live.index')->with('success', 'Live terminé.');
    }

    public function destroy(LiveStream $liveStream)
{
    // Seul l'administrateur ou le créateur du live peut le supprimer
    if (auth()->user()->is_admin || auth()->id() === $liveStream->user_id) {
        $liveStream->delete();
        return back()->with('success', 'Le live a été supprimé avec succès.');
    }
    return back()->with('error', 'Action non autorisée.');
}

}

