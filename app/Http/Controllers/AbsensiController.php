<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Section;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Filter parameters
        $search = $request->input('search');
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $status = $request->input('status');
        $bagian_id = $request->input('bagian_id');

        $query = Attendance::with(['pegawai.bagian'])
            ->whereDate('tanggal', $tanggal);

        // Search by pegawai name or NIP
        if ($search) {
            $query->whereHas('pegawai', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status) {
            $query->where('status', $status);
        }

        // Filter by bagian
        if ($bagian_id) {
            $query->whereHas('pegawai', function ($q) use ($bagian_id) {
                $q->where('bagian_id', $bagian_id);
            });
        }

        $absensi = $query->latest()->paginate(20);
        $bagian = Section::all();

        // Summary statistics for the selected date
        $summary = Attendance::whereDate('tanggal', $tanggal)
            ->selectRaw('
                COUNT(*) as total,
                COUNT(CASE WHEN status = "hadir" THEN 1 END) as hadir,
                COUNT(CASE WHEN status = "izin" THEN 1 END) as izin,
                COUNT(CASE WHEN status = "cuti" THEN 1 END) as cuti,
                COUNT(CASE WHEN status = "alpha" THEN 1 END) as alpha,
                COUNT(CASE WHEN status = "sakit" THEN 1 END) as sakit
            ')
            ->first();

        return view('pages.attendance.index', [
            'absensi' => $absensi,
            'bagian' => $bagian,
            'search' => $search,
            'tanggal' => $tanggal,
            'status' => $status,
            'bagian_id' => $bagian_id,
            'summary' => $summary,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $tanggal = $request->input('tanggal', date('Y-m-d'));

        // Filter pegawai aktif
        $query = Employee::where('status', 'aktif')->with('bagian');

        // Filter by bagian
        if ($request->has('bagian_id') && $request->bagian_id) {
            $query->where('bagian_id', $request->bagian_id);
        }

        // Search by name or NIP
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                    ->orWhere('nip', 'like', "%{$request->search}%");
            });
        }

        $pegawai = $query->get();
        $bagian = Section::where('status', 'aktif')->get();

        // Check if attendance already exists for this date
        $absensiHariIni = Attendance::whereDate('tanggal', $tanggal)->exists();

        return view('pages.attendance.create', [
            'pegawai' => $pegawai,
            'bagian' => $bagian,
            'today' => $tanggal,
            'absensiHariIni' => $absensiHariIni,
            'request' => $request,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttendanceRequest $request)
    {
        $tanggal = $request->input('tanggal');

        // Check if attendance already exists for this date
        $existingAbsensi = Attendance::whereDate('tanggal', $tanggal)->exists();

        if ($existingAbsensi && !$request->has('override')) {
            return redirect()->back()
                ->with('warning', 'Absensi untuk tanggal ini sudah ada. Centang "Override" untuk menimpa data.')
                ->withInput();
        }

        // If override is checked, delete existing attendance for this date
        if ($existingAbsensi && $request->has('override')) {
            Attendance::whereDate('tanggal', $tanggal)->delete();
        }

        // Process attendance data
        $pegawaiIds = $request->input('pegawai_id', []);
        $statuses = $request->input('status', []);
        $keterangans = $request->input('keterangan', []);
        $jamMasuks = $request->input('jam_masuk', []);
        $jamPulangs = $request->input('jam_pulang', []);

        $absensiData = [];

        foreach ($pegawaiIds as $index => $pegawaiId) {
            if (isset($statuses[$index])) {
                $hariKerja = 0;
                $jamLembur = 0.00;

                // Hitung lembur jika status hadir dan ada jam pulang
                if ($statuses[$index] === 'hadir' && isset($jamPulangs[$index]) && $jamPulangs[$index]) {
                    $jamPulang = \Carbon\Carbon::parse($jamPulangs[$index]);
                    $jamPulangHour = $jamPulang->hour;
                    $jamPulangMinute = $jamPulang->minute;

                    // Perhitungan lembur baru
                    if ($jamPulangHour <= 17) {
                        $hariKerja = 1;
                        $jamLembur = 0.00;
                    } elseif ($jamPulangHour <= 21) {
                        $hariKerja = 1;
                        $jamLembur = $jamPulangHour - 17;
                        if ($jamPulangMinute > 0 && $jamLembur < 4) {
                            $jamLembur += $jamPulangMinute / 60;
                            $jamLembur = round($jamLembur, 2);
                        }
                    } else {
                        if ($jamPulangHour <= 24) {
                            $hariKerja = 2;
                            $jamLembur = $jamPulangHour - 21;
                            if ($jamPulangMinute > 0) {
                                $jamLembur += $jamPulangMinute / 60;
                                $jamLembur = round($jamLembur, 2);
                            }
                        } else {
                            $hariKerja = floor($jamPulangHour / 24) + 1;
                            $sisaJam = $jamPulangHour % 24;
                            if ($sisaJam <= 4) {
                                $jamLembur = $sisaJam;
                                if ($jamPulangMinute > 0) {
                                    $jamLembur += $jamPulangMinute / 60;
                                    $jamLembur = round($jamLembur, 2);
                                }
                            } else {
                                $hariKerja++;
                                $jamLembur = $sisaJam - 4;
                                if ($jamPulangMinute > 0) {
                                    $jamLembur += $jamPulangMinute / 60;
                                    $jamLembur = round($jamLembur, 2);
                                }
                            }
                        }
                    }
                }

                $absensiData[] = [
                    'pegawai_id' => $pegawaiId,
                    'tanggal' => $tanggal,
                    'status' => $statuses[$index],
                    'keterangan' => $keterangans[$index] ?? null,
                    'jam_masuk' => $jamMasuks[$index] ?? null,
                    'jam_pulang' => $jamPulangs[$index] ?? null,
                    'hari_kerja' => $hariKerja,
                    'jam_lembur' => $jamLembur,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert in batch
        if (!empty($absensiData)) {
            Attendance::insert($absensiData);
        }

        // Create attendance records for absent employees (alpha)
        if ($request->has('auto_alpha')) {
            $allPegawaiIds = Employee::where('status', 'aktif')->pluck('id')->toArray();
            $recordedPegawaiIds = array_column($absensiData, 'pegawai_id');
            $absentPegawaiIds = array_diff($allPegawaiIds, $recordedPegawaiIds);

            $alphaData = [];
            foreach ($absentPegawaiIds as $pegawaiId) {
                $alphaData[] = [
                    'pegawai_id' => $pegawaiId,
                    'tanggal' => $tanggal,
                    'status' => 'alpha',
                    'hari_kerja' => 0,
                    'jam_lembur' => 0.00,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (!empty($alphaData)) {
                Attendance::insert($alphaData);
            }
        }

        return redirect()->route('absensi.index', ['tanggal' => $tanggal])
            ->with('success', 'Data absensi berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $absensi)
    {
        return view('pages.absensi.show', [
            'absensi' => $absensi->load(['pegawai.bagian']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $absensi)
    {
        return view('pages.attendance.edit', [
            'absensi' => $absensi,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttendanceRequest $request, Attendance $absensi)
    {
        $data = $request->validated();

        // Jika status bukan 'hadir', set jam masuk dan jam pulang ke null
        if ($data['status'] !== 'hadir') {
            $data['jam_masuk'] = null;
            $data['jam_pulang'] = null;
            $data['hari_kerja'] = 0;
            $data['jam_lembur'] = 0.00;
        } else {
            // Validasi jam untuk status hadir
            if (empty($data['jam_masuk']) || empty($data['jam_pulang'])) {
                return redirect()->back()
                    ->with('error', 'Jam masuk dan jam pulang harus diisi untuk status Hadir.')
                    ->withInput();
            }

            // Validasi jam pulang > jam masuk
            if ($data['jam_pulang'] <= $data['jam_masuk']) {
                return redirect()->back()
                    ->with('error', 'Jam pulang harus lebih besar dari jam masuk.')
                    ->withInput();
            }

            // Hitung lembur untuk status hadir
            $this->hitungDanSimpanLembur($absensi, $data);
        }

        $absensi->update($data);

        return redirect()->route('absensi.index', ['tanggal' => $absensi->tanggal->format('Y-m-d')])
            ->with('success', 'Data absensi berhasil diperbarui.');
    }

    // Method untuk menghitung dan menyimpan lembur
    private function hitungDanSimpanLembur($absensi, &$data)
    {
        $jamPulang = \Carbon\Carbon::parse($data['jam_pulang']);
        $jamPulangHour = $jamPulang->hour;
        $jamPulangMinute = $jamPulang->minute;

        // Perhitungan lembur baru
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
                // Untuk jam > 24:00 (jika ada)
                $hariKerja = floor($jamPulangHour / 24) + 1;
                $sisaJam = $jamPulangHour % 24;
                if ($sisaJam <= 4) {
                    $jamLembur = $sisaJam;
                    if ($jamPulangMinute > 0) {
                        $jamLembur += $jamPulangMinute / 60;
                        $jamLembur = round($jamLembur, 2);
                    }
                } else {
                    $hariKerja++;
                    $jamLembur = $sisaJam - 4;
                    if ($jamPulangMinute > 0) {
                        $jamLembur += $jamPulangMinute / 60;
                        $jamLembur = round($jamLembur, 2);
                    }
                }
            }
        }

        // Simpan hasil perhitungan
        $data['hari_kerja'] = $hariKerja;
        $data['jam_lembur'] = $jamLembur;

        // Update absensi record
        $absensi->hari_kerja = $hariKerja;
        $absensi->jam_lembur = $jamLembur;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $absensi)
    {
        $tanggal = $absensi->tanggal->format('Y-m-d');
        $absensi->delete();

        return redirect()->route('absensi.index', ['tanggal' => $tanggal])
            ->with('success', 'Data absensi berhasil dihapus.');
    }

    /**
     * Laporan absensi
     */
    public function laporan(Request $request)
    {
        // Filter parameters
        $bulan = $request->input('bulan', date('Y-m'));
        $tahun = $request->input('tahun', date('Y'));
        $bagian_id = $request->input('bagian_id');

        // Parse bulan and tahun
        if ($bulan) {
            $startDate = Carbon::parse($bulan . '-01')->startOfMonth();
            $endDate = Carbon::parse($bulan . '-01')->endOfMonth();
            $selectedYear = Carbon::parse($bulan)->year;
            $selectedMonth = Carbon::parse($bulan)->month;
        } else {
            $startDate = Carbon::create($tahun, 1, 1)->startOfYear();
            $endDate = Carbon::create($tahun, 12, 31)->endOfYear();
            $selectedYear = $tahun;
            $selectedMonth = null;
        }

        // Query untuk laporan
        $query = Employee::where('status', 'aktif')
            ->with(['bagian', 'absensi' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal', [$startDate, $endDate]);
            }]);

        if ($bagian_id) {
            $query->where('bagian_id', $bagian_id);
        }

        $pegawai = $query->get();

        // Hitung statistik untuk setiap pegawai
        $pegawai->each(function ($p) use ($startDate, $endDate) {
            $absensiBulanIni = $p->absensi;

            $p->total_hari_kerja = $this->getWorkingDays($startDate, $endDate);
            $p->total_hadir = $absensiBulanIni->where('status', 'hadir')->count();
            $p->total_izin = $absensiBulanIni->where('status', 'izin')->count();
            $p->total_cuti = $absensiBulanIni->where('status', 'cuti')->count();
            $p->total_sakit = $absensiBulanIni->where('status', 'sakit')->count();
            $p->total_alpha = $absensiBulanIni->where('status', 'alpha')->count();
            $p->persentase_kehadiran = $p->total_hari_kerja > 0
                ? round(($p->total_hadir / $p->total_hari_kerja) * 100, 2)
                : 0;
        });

        // Summary untuk laporan
        $summary = [
            'total_pegawai' => $pegawai->count(),
            'total_hadir' => $pegawai->sum('total_hadir'),
            'total_izin' => $pegawai->sum('total_izin'),
            'total_cuti' => $pegawai->sum('total_cuti'),
            'total_sakit' => $pegawai->sum('total_sakit'),
            'total_alpha' => $pegawai->sum('total_alpha'),
            'rata_rata_persentase' => $pegawai->avg('persentase_kehadiran') ?? 0,
        ];

        $bagian = Section::all();

        return view('pages.absensi.laporan', [
            'pegawai' => $pegawai,
            'bagian' => $bagian,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'bagian_id' => $bagian_id,
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth,
            'summary' => $summary,
        ]);
    }

    /**
     * Export laporan absensi
     */
    public function exportLaporan(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $bagian_id = $request->input('bagian_id');
        $format = $request->input('format', 'pdf');

        // Logic untuk export laporan
        // Implement sesuai dengan library yang digunakan (Laravel Excel, DomPDF, dll)

        return response()->json(['message' => 'Export laporan feature to be implemented']);
    }

    /**
     * Rekap absensi per pegawai
     */
    public function rekapPegawai(Employee $pegawai, Request $request)
    {
        $bulan = $request->input('bulan', date('Y-m'));

        $startDate = Carbon::parse($bulan . '-01')->startOfMonth();
        $endDate = Carbon::parse($bulan . '-01')->endOfMonth();

        $absensi = $pegawai->absensi()
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal')
            ->get();

        // Hitung statistik
        $totalHariKerja = $this->getWorkingDays($startDate, $endDate);
        $totalHadir = $absensi->where('status', 'hadir')->count();
        $totalIzin = $absensi->where('status', 'izin')->count();
        $totalCuti = $absensi->where('status', 'cuti')->count();
        $totalSakit = $absensi->where('status', 'sakit')->count();
        $totalAlpha = $absensi->where('status', 'alpha')->count();

        $persentaseKehadiran = $totalHariKerja > 0
            ? round(($totalHadir / $totalHariKerja) * 100, 2)
            : 0;

        return view('pages.absensi.rekap-pegawai', [
            'pegawai' => $pegawai,
            'absensi' => $absensi,
            'bulan' => $bulan,
            'totalHariKerja' => $totalHariKerja,
            'totalHadir' => $totalHadir,
            'totalIzin' => $totalIzin,
            'totalCuti' => $totalCuti,
            'totalSakit' => $totalSakit,
            'totalAlpha' => $totalAlpha,
            'persentaseKehadiran' => $persentaseKehadiran,
        ]);
    }

    /**
     * Hitung hari kerja (exclude weekends)
     */
    private function getWorkingDays($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        $days = 0;

        while ($start->lte($end)) {
            // Skip weekends (Saturday = 6, Sunday = 0)
            if (!$start->isWeekend()) {
                $days++;
            }
            $start->addDay();
        }

        return $days;
    }

    /**
     * Bulk update status absensi
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'absensi_ids' => 'required|array',
            'absensi_ids.*' => 'exists:absensi,id',
            'status' => 'required|in:hadir,izin,cuti,sakit,alpha',
            'keterangan' => 'nullable|string|max:500',
        ]);

        Attendance::whereIn('id', $request->absensi_ids)
            ->update([
                'status' => $request->status,
                'keterangan' => $request->keterangan,
            ]);

        return redirect()->back()
            ->with('success', 'Status absensi berhasil diperbarui untuk ' . count($request->absensi_ids) . ' record.');
    }
}
