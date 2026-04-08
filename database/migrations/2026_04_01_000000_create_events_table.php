<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('event', function (Blueprint $table) {
            $table->id('id_event');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_kategori')->nullable();

            $table->string('nama_event');
            $table->time('waktu_pelaksanaan');
            $table->text('deskripsi');
            $table->string('lokasi_event');
            $table->date('tanggal_pelaksanaan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('event');
    }
};
