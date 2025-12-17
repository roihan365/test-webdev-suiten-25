<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePegawaiRequest;
use App\Http\Requests\UpdatePegawaiRequest;
use App\Models\Employee;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Search and filter
        $search = $request->input('search');
        $bagian_id = $request->input('bagian_id');
        $status = $request->input('status');
        $shift = $request->input('shift');

        $query = Employee::with('bagian');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telepon', 'like', "%{$search}%");
            });
        }

        if ($bagian_id) {
            $query->where('bagian_id', $bagian_id);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($shift) {
            $query->where('shift', $shift);
        }

        $pegawai = $query->latest()->paginate(10);
        $bagian = Section::where('status', 'aktif')->get();

        // Statistics
        $totalPegawai = Employee::count();
        $aktifPegawai = Employee::where('status', 'aktif')->count();
        $totalGaji = 'Rp ' . number_format(Employee::where('status', 'aktif')->sum('gaji_pokok'), 0, ',', '.');
        $avgGaji = 'Rp ' . number_format(Employee::where('status', 'aktif')->avg('gaji_pokok') ?? 0, 0, ',', '.');

        return view('pages.master-data.employee.index', [
            'pegawai' => $pegawai,
            'bagian' => $bagian,
            'search' => $search,
            'bagian_id' => $bagian_id,
            'status' => $status,
            'shift' => $shift,
            'totalPegawai' => $totalPegawai,
            'aktifPegawai' => $aktifPegawai,
            'totalGaji' => $totalGaji,
            'avgGaji' => $avgGaji,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bagian = Section::where('status', 'aktif')->get();

        return view('pages.master-data.employee.create', [
            'bagian' => $bagian,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePegawaiRequest $request)
    {
        $data = $request->validated();

        // Handle foto upload
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = \Str::slug($data['nama']) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('pegawai/foto', $filename, 'public');
            $data['foto'] = $path;
        }
        // Generate NIP if empty
        if (empty($data['nip'])) {
            $data['nip'] = $this->generateNIP();
        }

        // Set default values for financial fields
        $financialDefaults = [
            'uang_makan' => 0,
            'uang_makan_tanggal_merah' => 0,
            'rate_lembur' => 0,
            'rate_lembur_tanggal_merah' => 0,
        ];

        foreach ($financialDefaults as $field => $default) {
            if (empty($data[$field])) {
                $data[$field] = $default;
            }
        }

        // If periode gaji is not harian, set gaji_harian to 0
        if ($data['periode_gaji'] !== 'harian') {
            $data['gaji_harian'] = 0;
        }

        Employee::create($data);

        return redirect()->route('master-data.pegawai.index')
            ->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $pegawai)
    {
        $absensi = $pegawai->absensi()
            ->with('pegawai.bagian')
            ->latest()
            ->limit(10)
            ->get();

        return view('pages.master-data.employee.show', [
            'pegawai' => $pegawai->load('bagian'),
            'absensi' => $absensi,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $pegawai)
    {
        $bagian = Section::where('status', 'aktif')->get();

        return view('pages.master-data.employee.edit', [
            'pegawai' => $pegawai,
            'bagian' => $bagian,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePegawaiRequest $request, Employee $pegawai)
    {
        $data = $request->validated();

        // Handle foto removal
        if ($request->has('remove_foto') && $request->remove_foto == '1') {
            if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto)) {
                Storage::disk('public')->delete($pegawai->foto);
            }
            $data['foto'] = null;
        }

        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto)) {
                Storage::disk('public')->delete($pegawai->foto);
            }

            $file = $request->file('foto');
            $filename = Str::slug($data['nama']) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('pegawai/foto', $filename, 'public');
            $data['foto'] = $path;
        }

        // If periode gaji is not harian, set gaji_harian to 0
        if ($data['periode_gaji'] !== 'harian') {
            $data['gaji_harian'] = 0;
        }

        $pegawai->update($data);

        return redirect()->route('master-data.pegawai.index')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $pegawai)
    {
        // Delete photo if exists
        if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto)) {
            Storage::disk('public')->delete($pegawai->foto);
        }

        $pegawai->delete();

        return redirect()->route('master-data.pegawai.index')
            ->with('success', 'Data pegawai berhasil dihapus.');
    }

    /**
     * Generate NIP otomatis
     */
    private function generateNIP()
    {
        $year = date('Y');
        $month = date('m');
        $lastPegawai = Employee::orderBy('id', 'desc')->first();

        if ($lastPegawai && $lastPegawai->nip) {
            $lastNIP = $lastPegawai->nip;
            $lastNumber = (int) substr($lastNIP, -4);
            $sequence = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $sequence = '0001';
        }

        return "{$year}{$month}{$sequence}";
    }

    /**
     * Export data pegawai
     */
    public function export(Request $request)
    {
        $format = $request->input('format', 'excel');
        $type = $request->input('type', 'all');

        $query = Employee::with('bagian');

        if ($type === 'aktif') {
            $query->where('status', 'aktif');
        } elseif ($type === 'nonaktif') {
            $query->where('status', 'nonaktif');
        }

        $pegawai = $query->get();

        // Logic untuk export data (Excel/PDF)
        // Implement sesuai dengan library yang digunakan (Laravel Excel, DomPDF, dll)

        return response()->json([
            'message' => 'Export feature to be implemented',
            'data_count' => $pegawai->count()
        ]);
    }

    /**
     * Toggle status pegawai
     */
    public function toggleStatus(Employee $pegawai)
    {
        $pegawai->status = $pegawai->status === 'aktif' ? 'nonaktif' : 'aktif';
        $pegawai->save();

        return redirect()->route('master-data.pegawai.index')
            ->with('success', 'Status pegawai berhasil diubah.');
    }
}
