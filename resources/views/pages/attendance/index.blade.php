<x-app-layout>
    @section('title', 'Data Absensi')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Absensi') }}
        </h2>
    </x-slot>

    <x-slot name="subheader">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                Kelola data absensi pegawai
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('absensi.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Input Absensi
                </a>
                <a href="{{ route('absensi.laporan') }}"
                    class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 dark:bg-purple-500 dark:hover:bg-purple-600 text-white rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Laporan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Filter Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <form method="GET" action="{{ route('absensi.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Tanggal -->
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tanggal
                        </label>
                        <input type="date" id="tanggal" name="tanggal"
                            value="{{ request('tanggal', date('Y-m-d')) }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Bagian -->
                    <div>
                        <label for="bagian_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Bagian
                        </label>
                        <select id="bagian_id" name="bagian_id"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Bagian</option>
                            @foreach ($bagian as $b)
                                <option value="{{ $b->id }}"
                                    {{ request('bagian_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Status
                        </label>
                        <select id="status" name="status"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status</option>
                            <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="cuti" {{ request('status') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                            <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="alpha" {{ request('status') == 'alpha' ? 'selected' : '' }}>Alpha</option>
                        </select>
                    </div>

                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Cari Nama/NIP
                        </label>
                        <div class="relative">
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                placeholder="Cari pegawai..."
                                class="w-full px-3 py-2 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <button type="button" onclick="window.location.href='{{ route('absensi.index') }}'"
                        class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                        Reset Filter
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white rounded-lg transition-colors">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        @if (isset($summary))
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="text-center">
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $summary->total }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="text-center">
                        <p class="text-2xl font-semibold text-green-600 dark:text-green-400">{{ $summary->hadir }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Hadir</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="text-center">
                        <p class="text-2xl font-semibold text-yellow-600 dark:text-yellow-400">{{ $summary->izin }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Izin</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="text-center">
                        <p class="text-2xl font-semibold text-blue-600 dark:text-blue-400">{{ $summary->cuti }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Cuti</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="text-center">
                        <p class="text-2xl font-semibold text-orange-600 dark:text-orange-400">{{ $summary->sakit }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Sakit</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="text-center">
                        <p class="text-2xl font-semibold text-red-600 dark:text-red-400">{{ $summary->alpha }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Alpha</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Data Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    Data Absensi -
                    {{ \Carbon\Carbon::parse(request('tanggal', date('Y-m-d')))->translatedFormat('l, d F Y') }}
                </h3>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $absensi->total() }} data ditemukan
                </div>
            </div>

            @if ($absensi->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    No
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Nama Pegawai
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Bagian
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Jam Masuk
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Jam Pulang
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Total Lembur (Hari + Jam)
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Catatan
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($absensi as $index => $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ ($absensi->currentPage() - 1) * $absensi->perPage() + $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if ($item->pegawai->foto)
                                                    <img class="h-10 w-10 rounded-full"
                                                        src="{{ asset('storage/' . $item->pegawai->foto) }}"
                                                        alt="{{ $item->pegawai->nama }}">
                                                @else
                                                    <div
                                                        class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                                        <span class="text-blue-600 dark:text-blue-400 font-medium">
                                                            {{ substr($item->pegawai->nama, 0, 1) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $item->pegawai->nama }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $item->pegawai->nip }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $item->pegawai->bagian->name ?? '-' }}
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
                                                'alpha' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
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

                                                // Perhitungan lembur baru
                                                if ($jamPulangHour <= 17) {
                                                    // Pulang ≤ 17:00 = 1 hari, 0 jam lembur
                                                    $hariKerja = 1;
                                                    $jamLembur = 0;
                                                } elseif ($jamPulangHour <= 21) {
                                                    // Pulang ≤ 21:00 = 1 hari, (jamPulang - 17) jam lembur
                                                    $hariKerja = 1;
                                                    $jamLembur = $jamPulangHour - 17;
                                                } else {
                                                    // Pulang > 21:00
                                                    if ($jamPulangHour <= 24) {
                                                        // Pulang ≤ 24:00 = 2 hari, (jamPulang - 21) jam lembur
                                                        $hariKerja = 2;
                                                        $jamLembur = $jamPulangHour - 21;
                                                    } else {
                                                        // Untuk jam > 24:00 (jika ada)
                                                        $hariKerja = floor($jamPulangHour / 24) + 1;
                                                        $jamLembur = $jamPulangHour % 24;
                                                        if ($jamLembur > 4) {
                                                            $hariKerja++;
                                                            $jamLembur -= 4;
                                                        }
                                                    }
                                                }
                                            @endphp
                                            <span class="font-semibold text-blue-600 dark:text-blue-400">
                                                {{ $hariKerja }} + {{ $jamLembur }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">(hari + jam)</span>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $item->keterangan ? Str::limit($item->keterangan, 30) : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('absensi.edit', $item->id) }}"
                                                class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('absensi.destroy', $item->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete(this)"
                                                    class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $absensi->withQueryString()->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Tidak ada data absensi</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Belum ada data absensi untuk tanggal
                        {{ \Carbon\Carbon::parse(request('tanggal', date('Y-m-d')))->translatedFormat('d F Y') }}.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('absensi.create', ['tanggal' => request('tanggal', date('Y-m-d'))]) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Input Absensi
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function confirmDelete(button) {
                if (confirm('Apakah Anda yakin ingin menghapus data absensi ini?')) {
                    button.closest('form').submit();
                }
            }

            // Auto-submit form when date changes
            document.getElementById('tanggal').addEventListener('change', function() {
                if (this.value) {
                    this.closest('form').submit();
                }
            });
        </script>
    @endpush
</x-app-layout>
