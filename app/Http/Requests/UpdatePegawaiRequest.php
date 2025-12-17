<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePegawaiRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $pegawaiId = $this->route('pegawai')->id;

        return [
            'nip' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('employees')->ignore($pegawaiId),
            ],
            'nama' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                Rule::unique('employees')->ignore($pegawaiId),
            ],
            'telepon' => 'required|string|max:15',
            'alamat' => 'required|string',
            'bagian_id' => 'required|exists:sections,id',
            'no_rekening' => 'nullable|string|max:30',
            'nama_rekening' => 'nullable|string|max:100',
            'bank' => 'nullable|string|max:50',
            'shift' => 'required|in:pagi,siang,malam,full_day',
            'gaji_pokok' => 'required|numeric|min:0',
            'periode_gaji' => 'required|in:bulanan,mingguan,harian',
            'gaji_harian' => 'required_if:periode_gaji,harian|numeric|min:0',
            'uang_makan' => 'nullable|numeric|min:0',
            'uang_makan_tanggal_merah' => 'nullable|numeric|min:0',
            'rate_lembur' => 'nullable|numeric|min:0',
            'rate_lembur_tanggal_merah' => 'nullable|numeric|min:0',
            'jabatan' => 'required|string|max:50',
            'tgl_masuk' => 'required|date',
            'status' => 'required|in:aktif,nonaktif',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'remove_foto' => 'nullable|boolean',
        ];
    }

    public function messages()
    {
        return [
            'gaji_harian.required_if' => 'Gaji harian harus diisi jika periode gaji harian.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ];
    }
}
