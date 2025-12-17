<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Section;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik untuk dashboard
        $totalPegawai = Employee::count();
        $totalBagian = Section::count();

        // Hitung absensi hari ini
        $today = Carbon::today();
        $absensiHariIni = Attendance::whereDate('tanggal', $today)->get();

        $hadirHariIni = $absensiHariIni->where('status', 'hadir')->count();
        $izinHariIni = $absensiHariIni->where('status', 'izin')->count();
        $cutiHariIni = $absensiHariIni->where('status', 'cuti')->count();
        $alphaHariIni = $absensiHariIni->where('status', 'alpha')->count();

        // Persentase kehadiran
        $persentaseKehadiran = $totalPegawai > 0
            ? round(($hadirHariIni / $totalPegawai) * 100, 2)
            : 0;

        // Data untuk chart kehadiran minggu ini
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $startOfDay = Carbon::now()->startOfDay();
        $endOfDay = Carbon::now()->endOfDay();

        $absensiHariIni = Attendance::whereBetween('tanggal', [$startOfDay, $endOfDay])
            ->selectRaw('DATE(tanggal) as tanggal, 
                        COUNT(CASE WHEN status = "hadir" THEN 1 END) as hadir,
                        COUNT(CASE WHEN status = "izin" THEN 1 END) as izin,
                        COUNT(CASE WHEN status = "cuti" THEN 1 END) as cuti,
                        COUNT(CASE WHEN status = "alpha" THEN 1 END) as alpha')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();
        $absensiMingguIni = Attendance::whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->selectRaw('DATE(tanggal) as tanggal, 
                        COUNT(CASE WHEN status = "hadir" THEN 1 END) as hadir,
                        COUNT(CASE WHEN status = "izin" THEN 1 END) as izin,
                        COUNT(CASE WHEN status = "cuti" THEN 1 END) as cuti,
                        COUNT(CASE WHEN status = "alpha" THEN 1 END) as alpha')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // Aktivitas terbaru (absensi hari ini)
        $aktivitasTerbaru = Attendance::with('pegawai')
            ->whereDate('tanggal', $today)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Pegawai dengan kehadiran terbaik bulan ini
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $topPegawai = Attendance::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->where('status', 'hadir')
            ->selectRaw('pegawai_id, COUNT(*) as total_hadir')
            ->groupBy('pegawai_id')
            ->orderBy('total_hadir', 'desc')
            ->with('pegawai')
            ->limit(5)
            ->get();

        return view('pages.dashboard.index', [
            'totalPegawai' => $totalPegawai,
            'totalBagian' => $totalBagian,
            'hadirHariIni' => $hadirHariIni,
            'izinHariIni' => $izinHariIni,
            'cutiHariIni' => $cutiHariIni,
            'alphaHariIni' => $alphaHariIni,
            'persentaseKehadiran' => $persentaseKehadiran,
            'absensiHariIni' => $absensiHariIni,
            'absensiMingguIni' => $absensiMingguIni,
            'aktivitasTerbaru' => $aktivitasTerbaru,
            'topPegawai' => $topPegawai,
        ]);
    }
}
