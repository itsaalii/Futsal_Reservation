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

    /**
     * Cek apakah sesi reservasi sedang berlangsung saat ini.
     */
    public function isSedangBerjalan(): bool
    {
        if (!in_array($this->status, ['disetujui', 'dibayar'])) {
            return false;
        }

        $now = \Carbon\Carbon::now();
        $start = \Carbon\Carbon::parse($this->tanggal . ' ' . $this->jam_mulai);
        $end = \Carbon\Carbon::parse($this->tanggal . ' ' . $this->jam_selesai);

        if ($end->lessThanOrEqualTo($start)) {
            $end->addDay();
        }

        return $now->gte($start) && $now->lt($end);
    }

    /**
     * Cek apakah sesi reservasi belum dimulai (jadwal di masa mendatang).
     */
    public function isBelumBerjalan(): bool
    {
        if (!in_array($this->status, ['disetujui', 'dibayar'])) {
            return false;
        }

        $now = \Carbon\Carbon::now();
        $start = \Carbon\Carbon::parse($this->tanggal . ' ' . $this->jam_mulai);

        return $now->lt($start);
    }
}
