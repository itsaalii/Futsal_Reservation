<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    use HasFactory;

    protected $table = 'lapangans';

    protected $fillable = [
        'nama',
        'tipe_lantai',
        'deskripsi',
        'tarif_siang',
        'tarif_malam',
        'status',
        'gambar',
    ];

    public function reservasis()
    {
        return $this->hasMany(Reservasi::class, 'id_lapangan', 'id');
    }

    public function defaultShifts()
    {
        return $this->hasMany(DefaultShift::class, 'id_lapangan', 'id');
    }

    public function jadwalShifts()
    {
        return $this->hasMany(JadwalShift::class, 'id_lapangan', 'id');
    }

    public function pegawai()
    {
        return $this->hasMany(User::class, 'id_lapangan', 'id');
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'id_lapangan', 'id');
    }
}
