<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $primaryKey = 'id_event';

    public $timestamps = false;

    protected $fillable = [
        'id_admin',
        'id_kategori',
        'nama_event',
        'waktu_pelaksanaan',
        'deskripsi',
        'lokasi_event',
        'tanggal_pelaksanaan'
    ];
}
