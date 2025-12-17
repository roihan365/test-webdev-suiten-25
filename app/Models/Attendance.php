<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'pegawai_id',
        'tanggal',
        'status',
        'keterangan',
        'jam_masuk',
        'jam_pulang',
        'hari_kerja',
        'jam_lembur',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Employee::class, 'pegawai_id', 'id');
    }

    // Scope untuk hari ini
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal', today());
    }

    // Scope untuk bulan ini
    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year);
    }
}
