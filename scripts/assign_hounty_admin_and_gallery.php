<?php
// Script pour rendre HOUNTY seul admin, réaffecter posts et ajouter images de vêtements
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Support\Facades\DB;

echo "== Assign HOUNTY as sole admin and attach gallery images ==\n";

// Find HOUNTY by name or username
$hounty = User::where('name', 'HOUNTY Bignon')->orWhere('username', 'bignon_hounty')->first();
if (! $hounty) {
    echo "HOUNTY not found. Aborting.\n";
    exit(1);
}

DB::transaction(function () use ($hounty) {
    // Revoke admin for all
    \App\Models\User::query()->update(['is_admin' => false]);
    // Grant admin only to HOUNTY
    $hounty->is_admin = true;
    $hounty->save();

    echo "Set user {$hounty->id} ({$hounty->email}) as sole admin.\n";

    // Reassign all posts to HOUNTY and update titles/content to clothing topics
    $posts = Post::all();
    $clothes = [
        ['title' => 'Nouvelle collection: Robes éthiques 2026', 'content' => 'Découvrez notre sélection de robes écologiques et confortables.'],
        ['title' => 'Comment choisir un manteau durable', 'content' => 'Guide pour choisir un manteau chaud et respectueux de l\'environnement.'],
        ['title' => 'Entretien des tissus naturels', 'content' => 'Conseils pour garder vos vêtements naturels comme neufs.'],
    ];

    foreach ($posts as $i => $post) {
        $idx = $i % count($clothes);
        $post->user_id = $hounty->id;
        $post->title = $clothes[$idx]['title'];
        $post->content = $clothes[$idx]['content'];
        $post->save();

        // Attach images from public/uploads if available
        $files = [
            'public/uploads/1780814998_Capture d\'écran 2026-06-06 211421.png',
            'public/uploads/1780816057_Capture d\'écran 2026-04-05 221455.png',
        ];

        foreach ($files as $f) {
            if (file_exists(base_path($f))) {
                PostImage::create([
                    'post_id' => $post->id,
                    'image' => $f,
                    'caption' => 'Photo vêtement',
                ]);
            }
        }
    }

    echo "Reassigned posts to HOUNTY and attached gallery images where possible.\n";
});

echo "Done.\n";
