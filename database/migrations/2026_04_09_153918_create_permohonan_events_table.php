<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permohonan_events', function (Blueprint $table) {
            $table->id('id_permohonan');
            $table->unsignedInteger('id_event');
            $table->integer('id_user'); // the organizer
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            // Note: In this project, 'event' uses id_event (INT) and 'user' uses id_user (INT)
            // Manual foreign keys if needed, but let's keep it consistent with the existing migration style
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonan_events');
    }
};
