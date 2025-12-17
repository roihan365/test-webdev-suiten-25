<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSectionRequest;
use App\Http\Requests\UpdateSectionRequest;
use App\Models\Section;
use Illuminate\Http\Request;

class BagianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = Section::withCount('pegawai');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($status) {
            $query->where('status', $status);
        }

        $bagian = $query->latest()->paginate(10);

        // Statistics
        $totalBagian = Section::count();
        $aktifBagian = Section::where('status', 'aktif')->count();

        return view('pages.master-data.section.index', [
            'bagian' => $bagian,
            'search' => $search,
            'status' => $status,
            'totalBagian' => $totalBagian,
            'aktifBagian' => $aktifBagian,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.master-data.section.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSectionRequest $request)
    {
        $data = $request->validated();
        Section::create($data);

        return redirect()->route('master-data.bagian.index')
            ->with('success', 'Data bagian berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $bagian)
    {
        $pegawai = $bagian->pegawai()->paginate(10);

        return view('pages.master-data.section.show', [
            'bagian' => $bagian,
            'pegawai' => $pegawai,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $bagian)
    {
        return view('pages.master-data.section.edit', [
            'bagian' => $bagian,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSectionRequest $request, Section $bagian)
    {
        $data = $request->validated();
        $bagian->update($data);

        return redirect()->route('master-data.bagian.index')
            ->with('success', 'Data bagian berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $bagian)
    {
        // Cek apakah bagian masih memiliki pegawai
        if ($bagian->pegawai()->count() > 0) {
            return redirect()->route('master-data.bagian.index')
                ->with('error', 'Tidak dapat menghapus bagian yang masih memiliki pegawai.');
        }

        $bagian->delete();

        return redirect()->route('master-data.bagian.index')
            ->with('success', 'Data bagian berhasil dihapus.');
    }

    /**
     * Toggle status bagian
     */
    public function toggleStatus(Section $bagian)
    {
        $bagian->status = $bagian->status === 'aktif' ? 'nonaktif' : 'aktif';
        $bagian->save();

        return redirect()->route('master-data.bagian.index')
            ->with('success', 'Status bagian berhasil diubah.');
    }
}
