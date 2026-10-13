<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    //
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_user',
        'id_lapangan',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'total_harga',
        'discount_amount',
        'status',
        'metode_pembayaran',
        'bukti_pembayaran',
        'catatan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class, 'id_lapangan', 'id');
    }
}
