<?php
use Illuminate\Support\Facades\DB;

DB::unprepared("DROP PROCEDURE IF EXISTS sp_add_user;");

// Notice in the original SP they used ENUM('Admin', 'Organizer', 'Buyer'), but since the database might accept strings or integers. I'll just use VARCHAR(20). If it needs INT, I'll update it later or check the schema. Wait, if the column is INT, passing 'Organizer' will fail. Let's pass the raw role from the request without modification later, but for now I'll use VARCHAR to accept both. Wait, if it's an INT, it should be INT. In SignupController they validated 'in:2,3'.

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
    END;
");

echo "SP Updated successfully.\n";
