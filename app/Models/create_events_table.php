<?php

namespace App\Models;

class create_events_table
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('nama_event');
            $table->string('lokasi');
            $table->date('tanggal');
            $table->integer('harga');
            $table->timestamps();
        });
    }
}
