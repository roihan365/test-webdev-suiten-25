<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip',
        'nama',
        'email',
        'telepon',
        'alamat',
        'bagian_id',
        'no_rekening',
        'nama_rekening',
        'bank',
        'shift',
        'gaji_pokok',
        'periode_gaji',
        'gaji_harian',
        'uang_makan',
        'uang_makan_tanggal_merah',
        'rate_lembur',
        'rate_lembur_tanggal_merah',
        'jabatan',
        'tgl_masuk',
        'status',
        'foto',
    ];

    protected $casts = [
        'tgl_masuk' => 'date',
        'gaji_pokok' => 'decimal:2',
        'gaji_harian' => 'decimal:2',
        'uang_makan' => 'decimal:2',
        'uang_makan_tanggal_merah' => 'decimal:2',
        'rate_lembur' => 'decimal:2',
        'rate_lembur_tanggal_merah' => 'decimal:2',
    ];

    public function bagian()
    {
        return $this->belongsTo(Section::class, 'bagian_id', 'id');
    }

    public function absensi()
    {
        return $this->hasMany(Attendance::class, 'pegawai_id', 'id');
    }

    // Scope untuk pegawai aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Accessor untuk format currency
    public function getFormattedGajiPokokAttribute()
    {
        return 'Rp ' . number_format($this->gaji_pokok, 0, ',', '.');
    }

    public function getFormattedGajiHarianAttribute()
    {
        return 'Rp ' . number_format($this->gaji_harian, 0, ',', '.');
    }

    // Method untuk menghitung gaji berdasarkan periode
    public function hitungGajiPeriode($periode = null)
    {
        $periode = $periode ?? $this->periode_gaji;

        switch ($periode) {
            case 'bulanan':
                return $this->gaji_pokok;
            case 'mingguan':
                return $this->gaji_pokok / 4; // Approx
            case 'harian':
                return $this->gaji_harian;
            default:
                return $this->gaji_pokok;
        }
    }
}
