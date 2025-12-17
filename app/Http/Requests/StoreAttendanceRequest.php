<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tanggal' => 'required|date',
            'pegawai_id' => 'required|array',
            'pegawai_id.*' => 'exists:employees,id',
            'status' => 'required|array',
            'status.*' => 'in:hadir,izin,cuti,sakit,alpha',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string|max:500',
            'jam_masuk' => 'nullable|array',
            'jam_masuk.*' => 'nullable|date_format:H:i',
            'jam_pulang' => 'nullable|array',
            'jam_pulang.*' => 'nullable|date_format:H:i',
            'override' => 'nullable|boolean',
            'auto_alpha' => 'nullable|boolean',
        ];
    }
}
