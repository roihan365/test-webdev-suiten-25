<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,izin,cuti,sakit,alpha',
            'keterangan' => 'nullable|string|max:500',
            'jam_masuk' => [
                'nullable',
                'required_if:status,hadir',
                'date_format:H:i',
            ],
            'jam_pulang' => [
                'nullable',
                'required_if:status,hadir',
                'date_format:H:i',
                'after:jam_masuk',
            ],
        ];
    }

    public function messages()
    {
        return [
            'tanggal.required' => 'Tanggal absensi harus diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',

            'status.required' => 'Status absensi harus dipilih.',
            'status.in' => 'Status absensi tidak valid.',

            'keterangan.max' => 'Keterangan maksimal 500 karakter.',

            'jam_masuk.required_if' => 'Jam masuk harus diisi untuk status Hadir.',
            'jam_masuk.date_format' => 'Format jam masuk tidak valid (HH:mm).',

            'jam_pulang.required_if' => 'Jam pulang harus diisi untuk status Hadir.',
            'jam_pulang.date_format' => 'Format jam pulang tidak valid (HH:mm).',
            'jam_pulang.after' => 'Jam pulang harus lebih besar dari jam masuk.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $this->all();

            // Validasi khusus untuk status 'hadir'
            if ($data['status'] === 'hadir') {
                // Validasi jam masuk dan jam pulang
                if (empty($data['jam_masuk'])) {
                    $validator->errors()->add('jam_masuk', 'Jam masuk harus diisi untuk status Hadir.');
                }

                if (empty($data['jam_pulang'])) {
                    $validator->errors()->add('jam_pulang', 'Jam pulang harus diisi untuk status Hadir.');
                }

                // Validasi jam pulang > jam masuk
                if (!empty($data['jam_masuk']) && !empty($data['jam_pulang'])) {
                    $jamMasuk = strtotime($data['jam_masuk']);
                    $jamPulang = strtotime($data['jam_pulang']);

                    if ($jamPulang <= $jamMasuk) {
                        $validator->errors()->add('jam_pulang', 'Jam pulang harus lebih besar dari jam masuk.');
                    }

                    // Validasi jam masuk minimal 00:00 dan maksimal 23:59
                    if ($jamMasuk < strtotime('00:00') || $jamMasuk > strtotime('23:59')) {
                        $validator->errors()->add('jam_masuk', 'Jam masuk harus antara 00:00 - 23:59.');
                    }

                    // Validasi jam pulang minimal 00:00 dan maksimal 23:59
                    if ($jamPulang < strtotime('00:00') || $jamPulang > strtotime('23:59')) {
                        $validator->errors()->add('jam_pulang', 'Jam pulang harus antara 00:00 - 23:59.');
                    }

                    // Validasi maksimal jam kerja (misal: maksimal 48 jam)
                    $selisihJam = ($jamPulang - $jamMasuk) / 3600;
                    if ($selisihJam > 48) {
                        $validator->errors()->add('jam_pulang', 'Maksimal jam kerja adalah 48 jam.');
                    }
                }
            } else {
                // Untuk status selain hadir, pastikan jam masuk dan jam pulang kosong
                if (!empty($data['jam_masuk'])) {
                    $validator->errors()->add('jam_masuk', 'Jam masuk hanya diisi untuk status Hadir.');
                }

                if (!empty($data['jam_pulang'])) {
                    $validator->errors()->add('jam_pulang', 'Jam pulang hanya diisi untuk status Hadir.');
                }
            }

            // Validasi tanggal tidak lebih dari hari ini
            $tanggal = strtotime($data['tanggal']);
            $hariIni = strtotime(date('Y-m-d'));

            if ($tanggal > $hariIni) {
                $validator->errors()->add('tanggal', 'Tanggal absensi tidak boleh lebih dari hari ini.');
            }

            // Validasi tanggal minimal 1 tahun yang lalu
            $satuTahunLalu = strtotime('-1 year');
            if ($tanggal < $satuTahunLalu) {
                $validator->errors()->add('tanggal', 'Tanggal absensi tidak boleh lebih dari 1 tahun yang lalu.');
            }
        });
    }
}
