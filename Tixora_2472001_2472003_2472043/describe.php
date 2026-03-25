<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$columns = \Illuminate\Support\Facades\DB::select('DESCRIBE user');
$out = "";
foreach ($columns as $column) {
    $out .= $column->Field . ' : ' . $column->Type . "\n";
}
file_put_contents('out.txt', $out);
