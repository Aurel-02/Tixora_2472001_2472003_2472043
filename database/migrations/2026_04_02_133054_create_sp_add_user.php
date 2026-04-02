<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("DROP PROCEDURE IF EXISTS sp_add_user");

        DB::unprepared("
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
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_add_user;");
    }
};
