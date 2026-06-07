<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Post;

$posts = Post::select('id','image')->limit(20)->get();
foreach ($posts as $p) {
    echo $p->id . ' | ' . ($p->image ?? 'NULL') . PHP_EOL;
}
