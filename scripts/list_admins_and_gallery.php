<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\PostImage;

$admins = User::where('is_admin', true)->get();

echo "Admins:\n";
foreach ($admins as $a) {
    echo "- {$a->id} | {$a->name} | {$a->email}\n";
}

echo "\nPostImage count: " . PostImage::count() . "\n";

foreach (\App\Models\Post::with('images')->get() as $p) {
    echo "Post {$p->id} ({$p->title}) images: " . $p->images->count() . "\n";
    foreach ($p->images as $img) {
        echo "  - {$img->id} | {$img->image}\n";
    }
}
