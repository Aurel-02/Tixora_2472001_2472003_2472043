<?php
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

DB::unprepared("DROP PROCEDURE IF EXISTS sp_add_user;");

$procedure = "
    CREATE PROCEDURE sp_add_user(
        IN p_nama_lengkap VARCHAR(255),
        IN p_email VARCHAR(255),
        IN p_no_telp VARCHAR(20),
        IN p_password VARCHAR(255),
        IN p_role VARCHAR(20)
    )
    BEGIN
        INSERT INTO user (nama_lengkap, email, no_telp, password, role)
        VALUES (p_nama_lengkap, p_email, p_no_telp, p_password, p_role);
    END;
";

DB::unprepared($procedure);
echo "Stored procedure recreated successfully with correct table name 'user'.\n";
