<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'event';

    protected $primaryKey = 'id_event';

    public $timestamps = false;

    const DELETED_AT = 'deleted_at';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id_user',
        'id_kategori',
        'nama_event',
        'waktu_pelaksanaan',
        'deskripsi',
        'lokasi_event',
        'tanggal_pelaksanaan',
        'poster',
        'deleted_at',
    ];
}
