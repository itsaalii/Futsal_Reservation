<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Reservasi;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'no_hp',
        'email',
        'status_member',
        'role',
        'id_lapangan',
        'rfid_uid',
        'gaji_pokok',
        'tunjangan',
        'tarif_per_shift',
        'password',
        'membership_type',
        'membership_status',
        'membership_proof',
        'membership_expires_at',
        'membership_free_hour_used',
        'membership_last_booking_at',
    ];

    public function reservasis()
    {
        return $this->hasMany(Reservasi::class, 'id_user', 'id');
    }

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class, 'id_lapangan', 'id');
    }

    public function defaultShifts()
    {
        return $this->hasMany(DefaultShift::class, 'id_user', 'id');
    }

    public function jadwalShifts()
    {
        return $this->hasMany(JadwalShift::class, 'id_user', 'id');
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'id_user', 'id');
    }

    public function penggajians()
    {
        return $this->hasMany(Penggajian::class, 'id_user', 'id');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStaff(): bool
    {
        return in_array($this->role, ['superadmin', 'admin']);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
