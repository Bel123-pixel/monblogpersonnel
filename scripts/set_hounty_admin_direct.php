<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$hounty = DB::table('users')->where('name', 'HOUNTY Bignon')->orWhere('username', 'bignon_hounty')->first();
if (! $hounty) {
    echo "HOUNTY not found.\n"; exit(1);
}

DB::table('users')->update(['is_admin' => 0]);
DB::table('users')->where('id', $hounty->id)->update(['is_admin' => 1]);

echo "Set is_admin=1 for user id {$hounty->id}\n";
