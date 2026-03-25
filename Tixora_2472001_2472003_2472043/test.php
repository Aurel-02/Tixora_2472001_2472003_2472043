<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$users = \Illuminate\Support\Facades\DB::table('user')->select('email', 'password')->get()->toArray();
$admins = \Illuminate\Support\Facades\DB::table('admin')->select('email', 'password', 'role')->get()->toArray();

file_put_contents('auth_output.json', json_encode(['users' => $users, 'admins' => $admins], JSON_PRETTY_PRINT));
echo "Done\n";
