<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Absensi;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AbsensiSeeder extends Seeder
{
    public function run()
    {
        $pegawaiIds = Employee::pluck('id')->toArray();
        $today = Carbon::today();

        $absensiData = [];

        // Buat data absensi untuk 7 hari terakhir
        for ($i = 0; $i < 7; $i++) {
            $tanggal = $today->copy()->subDays($i);

            foreach ($pegawaiIds as $pegawaiId) {
                // Random status untuk variasi data
                $status = $this->getRandomStatus();

                $jamMasuk = null;
                $jamPulang = null;
                $hariKerja = 0;
                $jamLembur = 0.00;

                if ($status === 'hadir') {
                    // Generate random jam masuk dan jam pulang
                    $jamMasuk = $this->generateRandomTime('08:00', '09:30');
                    $jamPulang = $this->generateRandomTime('16:00', '23:00');

                    // Hitung lembur berdasarkan jam pulang
                    list($hariKerja, $jamLembur) = $this->calculateOvertime($jamPulang);
                } elseif ($status === 'izin' || $status === 'cuti' || $status === 'sakit') {
                    // 70% chance untuk ada keterangan
                    if (rand(1, 10) <= 7) {
                        $keterangan = $this->getKeteranganByStatus($status);
                    }
                }

                // Random keterangan untuk beberapa data
                $keterangan = null;
                if (in_array($status, ['izin', 'cuti', 'sakit']) && rand(1, 10) <= 7) {
                    $keterangan = $this->getKeteranganByStatus($status);
                }

                $absensiData[] = [
                    'pegawai_id' => $pegawaiId,
                    'tanggal' => $tanggal,
                    'status' => $status,
                    'keterangan' => $keterangan,
                    'jam_masuk' => $jamMasuk,
                    'jam_pulang' => $jamPulang,
                    'hari_kerja' => $hariKerja,
                    'jam_lembur' => $jamLembur,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert data absensi
        foreach (array_chunk($absensiData, 100) as $chunk) {
            Attendance::insert($chunk);
        }

        $this->command->info('Data absensi untuk 7 hari terakhir berhasil ditambahkan!');
    }

    /**
     * Generate random status dengan probabilitas tertentu
     */
    private function getRandomStatus()
    {
        $statuses = [
            'hadir' => 70, // 70% chance hadir
            'izin' => 10,  // 10% chance izin
            'cuti' => 8,   // 8% chance cuti
            'sakit' => 7,  // 7% chance sakit
            'alpha' => 5,  // 5% chance alpha
        ];

        $total = array_sum($statuses);
        $random = rand(1, $total);

        $current = 0;
        foreach ($statuses as $status => $probability) {
            $current += $probability;
            if ($random <= $current) {
                return $status;
            }
        }

        return 'hadir'; // fallback
    }

    /**
     * Generate random time dalam range tertentu
     */
    private function generateRandomTime($start, $end)
    {
        $start = strtotime($start);
        $end = strtotime($end);

        $randomTime = rand($start, $end);

        return date('H:i', $randomTime);
    }

    /**
     * Hitung lembur berdasarkan jam pulang
     */
    private function calculateOvertime($jamPulang)
    {
        $jamPulang = \Carbon\Carbon::parse($jamPulang);
        $jamPulangHour = $jamPulang->hour;
        $jamPulangMinute = $jamPulang->minute;

        // Perhitungan lembur sesuai aturan
        $hariKerja = 1;
        $jamLembur = 0.00;

        if ($jamPulangHour <= 17) {
            // Pulang ≤ 17:00 = 1 hari, 0 jam lembur
            $hariKerja = 1;
            $jamLembur = 0.00;
        } elseif ($jamPulangHour <= 21) {
            // Pulang ≤ 21:00 = 1 hari, (jamPulang - 17) jam lembur
            $hariKerja = 1;
            $jamLembur = $jamPulangHour - 17;
            // Tambah menit jika ada
            if ($jamPulangMinute > 0 && $jamLembur < 4) {
                $jamLembur += $jamPulangMinute / 60;
                $jamLembur = round($jamLembur, 2);
            }
        } else {
            // Pulang > 21:00
            if ($jamPulangHour <= 24) {
                // Pulang ≤ 24:00 = 2 hari, (jamPulang - 21) jam lembur
                $hariKerja = 2;
                $jamLembur = $jamPulangHour - 21;
                // Tambah menit jika ada
                if ($jamPulangMinute > 0) {
                    $jamLembur += $jamPulangMinute / 60;
                    $jamLembur = round($jamLembur, 2);
                }
            } else {
                // Untuk jam > 24:00 (tidak akan terjadi dengan data kita)
                $hariKerja = 1;
                $jamLembur = 0;
            }
        }

        return [$hariKerja, $jamLembur];
    }

    /**
     * Get keterangan berdasarkan status
     */
    private function getKeteranganByStatus($status)
    {
        $keterangan = [
            'izin' => [
                'Ijin keperluan keluarga',
                'Ijin acara keluarga',
                'Ijin keperluan pribadi',
                'Ijin ke luar kota',
            ],
            'cuti' => [
                'Cuti tahunan',
                'Cuti melahirkan',
                'Cuti bersama',
                'Cuti sakit',
            ],
            'sakit' => [
                'Sakit demam',
                'Sakit flu',
                'Periksa dokter',
                'Istirahat sakit',
            ],
        ];

        if (isset($keterangan[$status])) {
            $options = $keterangan[$status];
            return $options[array_rand($options)];
        }

        return null;
    }
}
