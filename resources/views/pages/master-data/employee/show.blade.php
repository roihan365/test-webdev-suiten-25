<x-app-layout>
    @section('title', 'data pegawai')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('data pegawai') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="space-y-6">
            <!-- Profile Header -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-6">
                    <!-- Foto -->
                    <div class="flex-shrink-0">
                        @if ($pegawai->foto)
                            <img src="{{ asset('storage/' . $pegawai->foto) }}" alt="{{ $pegawai->nama }}"
                                class="h-32 w-32 rounded-lg border-4 border-white dark:border-gray-700 shadow-lg">
                        @else
                            <div
                                class="h-32 w-32 rounded-lg border-4 border-white dark:border-gray-700 shadow-lg bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                <span class="text-blue-600 dark:text-blue-400 text-4xl font-bold">
                                    {{ substr($pegawai->nama, 0, 1) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Info Dasar -->
                    <div class="flex-1 text-center md:text-left">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pegawai->nama }}</h2>
                        <p class="text-gray-600 dark:text-gray-400">{{ $pegawai->nip }}</p>
                        <div class="mt-2 flex flex-wrap gap-2 justify-center md:justify-start">
                            <span
                                class="px-3 py-1 rounded-full text-sm font-medium {{ $pegawai->status == 'aktif' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                {{ ucfirst($pegawai->status) }}
                            </span>
                            <span
                                class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                {{ $pegawai->jabatan }}
                            </span>
                            <span
                                class="px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300">
                                {{ $pegawai->bagian->name ?? '-' }}
                            </span>
                            @php
                                $shiftLabels = [
                                    'pagi' => 'Pagi',
                                    'siang' => 'Siang',
                                    'malam' => 'Malam',
                                    'full_day' => 'Full Day',
                                ];
                            @endphp
                            <span
                                class="px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                Shift: {{ $shiftLabels[$pegawai->shift] ?? $pegawai->shift }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Informasi Kontak -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Kontak</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $pegawai->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telepon</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $pegawai->telepon }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $pegawai->alamat }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal
                                Masuk</label>
                            <p class="mt-1 text-gray-900 dark:text-white">
                                {{ $pegawai->tgl_masuk->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Informasi Rekening -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Rekening</h3>
                    @if ($pegawai->no_rekening && $pegawai->nama_rekening && $pegawai->bank)
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bank</label>
                                <p class="mt-1 text-gray-900 dark:text-white">{{ $pegawai->bank }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor
                                    Rekening</label>
                                <p class="mt-1 text-gray-900 dark:text-white">{{ $pegawai->no_rekening }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Pemilik
                                    Rekening</label>
                                <p class="mt-1 text-gray-900 dark:text-white">{{ $pegawai->nama_rekening }}</p>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <p class="mt-4 text-gray-500 dark:text-gray-400">Informasi rekening belum diisi</p>
                        </div>
                    @endif
                </div>

                <!-- Informasi Gaji -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 lg:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Gaji & Lembur</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Gaji Pokok -->
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gaji
                                Pokok</label>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ 'Rp ' . number_format($pegawai->gaji_pokok, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Periode: {{ ucfirst($pegawai->periode_gaji) }}
                            </p>
                            @if ($pegawai->periode_gaji == 'harian')
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Harian: {{ 'Rp ' . number_format($pegawai->gaji_harian, 0, ',', '.') }}
                                </p>
                            @endif
                        </div>

                        <!-- Uang Makan -->
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Uang
                                Makan</label>
                            <p class="text-xl font-bold text-gray-900 dark:text-white">
                                {{ 'Rp ' . number_format($pegawai->uang_makan, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Normal: {{ 'Rp ' . number_format($pegawai->uang_makan, 0, ',', '.') }}/hari
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Tanggal Merah:
                                {{ 'Rp ' . number_format($pegawai->uang_makan_tanggal_merah, 0, ',', '.') }}/hari
                            </p>
                        </div>

                        <!-- Rate Lembur -->
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rate
                                Lembur</label>
                            <p class="text-xl font-bold text-gray-900 dark:text-white">
                                {{ 'Rp ' . number_format($pegawai->rate_lembur, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Normal: {{ 'Rp ' . number_format($pegawai->rate_lembur, 0, ',', '.') }}/jam
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Tanggal Merah:
                                {{ 'Rp ' . number_format($pegawai->rate_lembur_tanggal_merah, 0, ',', '.') }}/jam
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Absensi Terbaru -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Riwayat Absensi Terbaru</h3>
                    <a href="{{ route('absensi.index', ['search' => $pegawai->nama]) }}"
                        class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                        Lihat semua →
                    </a>
                </div>

                @if ($absensi->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Jam Masuk
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Jam Pulang
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Lembur (H+J)
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($absensi as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $item->tanggal->translatedFormat('d F Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusColors = [
                                                    'hadir' =>
                                                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                                    'izin' =>
                                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                                    'cuti' =>
                                                        'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                                    'sakit' =>
                                                        'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
                                                    'alpha' =>
                                                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                                ];
                                            @endphp
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$item->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $item->jam_masuk ? \Carbon\Carbon::parse($item->jam_masuk)->format('H:i') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $item->jam_pulang ? \Carbon\Carbon::parse($item->jam_pulang)->format('H:i') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            @if ($item->jam_masuk && $item->jam_pulang && $item->status == 'hadir')
                                                @php
                                                    $jamPulang = \Carbon\Carbon::parse($item->jam_pulang);
                                                    $jamPulangHour = $jamPulang->hour;

                                                    if ($jamPulangHour <= 17) {
                                                        $hariKerja = 1;
                                                        $jamLembur = 0;
                                                    } elseif ($jamPulangHour <= 21) {
                                                        $hariKerja = 1;
                                                        $jamLembur = $jamPulangHour - 17;
                                                    } else {
                                                        if ($jamPulangHour <= 24) {
                                                            $hariKerja = 2;
                                                            $jamLembur = $jamPulangHour - 21;
                                                        } else {
                                                            $hariKerja = floor($jamPulangHour / 24) + 1;
                                                            $sisaJam = $jamPulangHour % 24;
                                                            if ($sisaJam <= 4) {
                                                                $jamLembur = $sisaJam;
                                                            } else {
                                                                $hariKerja++;
                                                                $jamLembur = $sisaJam - 4;
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                <span class="font-semibold text-blue-600 dark:text-blue-400">
                                                    {{ $hariKerja }} + {{ $jamLembur }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="mt-4 text-gray-500 dark:text-gray-400">Belum ada data absensi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
