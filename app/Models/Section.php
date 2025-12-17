<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];

    public function pegawai()
    {
        return $this->hasMany(Employee::class, 'bagian_id', 'id');
    }

    // Scope untuk bagian aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}
