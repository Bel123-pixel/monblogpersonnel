<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$rows = DB::table('users')->select('id','name','email','username','is_admin')->get();
foreach ($rows as $r) {
    echo "{$r->id} | {$r->name} | {$r->email} | username={$r->username} | is_admin={$r->is_admin}\n";
}
