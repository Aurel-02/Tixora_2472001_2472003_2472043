<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "--- USER TABLE ---\n";
print_r(\Illuminate\Support\Facades\DB::table('user')->select('email', 'password')->get()->toArray());
echo "\n--- ADMIN TABLE ---\n";
print_r(\Illuminate\Support\Facades\DB::table('admin')->select('email', 'password', 'role')->get()->toArray());
