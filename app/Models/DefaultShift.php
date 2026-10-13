<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultShift extends Model
{
    use HasFactory;

    protected $table = 'default_shifts';

    protected $fillable = [
        'id_user',
        'id_lapangan',
        'hari',
        'shift',
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
