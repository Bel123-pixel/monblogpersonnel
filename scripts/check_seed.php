<?php
// Script simple pour vérifier les enregistrements créés par DemoResetSeeder
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Post;

echo "== Vérification seed DemoResetSeeder ==\n";
echo "Users count: " . User::count() . PHP_EOL;
$admin = User::where('email', 'admin@monblog.com')->first();
if ($admin) {
    echo "Admin exists: " . $admin->email . " (id " . $admin->id . ")\n";
} else {
    echo "Admin not found.\n";
}

echo "\nSample users:\n";
foreach (User::limit(10)->get() as $u) {
    echo "- {$u->id} | {$u->name} | {$u->email} | username={$u->username} | is_admin=" . ($u->is_admin ? '1' : '0') . PHP_EOL;
}

echo "\nPosts count: " . Post::count() . PHP_EOL;
echo "Posts list:\n";
foreach (Post::limit(20)->get() as $p) {
    echo "- {$p->id} | {$p->title} | user_id={$p->user_id} | status={$p->status} | views={$p->views}" . PHP_EOL;
}
