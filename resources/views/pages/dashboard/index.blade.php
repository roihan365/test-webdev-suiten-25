<x-app-layout>
    @section('title', 'Dashboard')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Overview') }}
        </h2>
    </x-slot>

    <x-slot name="subheader">
        Selamat datang di sistem management karyawan dan absensi
        <span class="text-sm text-gray-600 dark:text-gray-400 block mt-1">
            {{ now()->translatedFormat('l, d F Y') }}
        </span>
    </x-slot>

    <div class="space-y-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Pegawai -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Pegawai</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalPegawai }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $totalBagian }} bagian</span>
                </div>
            </div>

            <!-- Hadir Hari Ini -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Hadir Hari Ini</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $hadirHariIni }}</p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <span
                        class="text-sm font-medium {{ $persentaseKehadiran >= 80 ? 'text-green-600 dark:text-green-400' : ($persentaseKehadiran >= 60 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">
                        {{ $persentaseKehadiran }}% kehadiran
                    </span>
                </div>
            </div>

            <!-- Izin & Cuti -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Izin / Cuti</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ $izinHariIni + $cutiHariIni }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $totalPegawai > 0 ? round((($izinHariIni + $cutiHariIni) / $totalPegawai) * 100, 1) : 0 }}%
                        dari total
                    </span>
                </div>
            </div>

            <!-- Alpha -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Alpha</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $alphaHariIni }}</p>
                    </div>
                    <div class="p-3 bg-red-100 dark:bg-red-900 rounded-lg">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <span
                        class="text-sm font-medium {{ $alphaHariIni == 0 ? 'text-green-600 dark:text-green-400' : ($alphaHariIni <= 5 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">
                        {{ $alphaHariIni == 0 ? '0% Alpha' : ($totalPegawai > 0 ? round(($alphaHariIni / $totalPegawai) * 100, 1) . '% Alpha' : '0% Alpha') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Charts and Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Attendance Chart -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Statistik Kehadiran Minggu Ini</h3>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ now()->startOfWeek()->translatedFormat('d M') }} -
                        {{ now()->endOfWeek()->translatedFormat('d M Y') }}
                    </span>
                </div>

                @if ($absensiMingguIni->count() > 0)
                    <div class="h-64">
                        <!-- Simple Bar Chart using Tailwind -->
                        <div class="h-full flex flex-col">
                            <div class="flex-1 flex items-end space-x-2 pb-8">
                                @foreach ($absensiMingguIni as $absensi)
                                    <div class="flex-1 flex flex-col items-center">
                                        <div class="w-full max-w-16 mx-auto relative">
                                            <!-- Total Bar (Background) -->
                                            <div class="w-full h-32 bg-gray-200 dark:bg-gray-700 rounded-t-lg"></div>

                                            <!-- Hadir Bar -->
                                            @php
                                                $total =
                                                    $absensi->hadir + $absensi->izin + $absensi->cuti + $absensi->alpha;
                                                $hadirHeight = $total > 0 ? ($absensi->hadir / $total) * 100 : 0;
                                            @endphp
                                            <div class="absolute bottom-0 left-0 right-0 bg-green-500 rounded-t-lg transition-all duration-300"
                                                style="height: {{ $hadirHeight }}%"
                                                title="Hadir: {{ $absensi->hadir }}"></div>

                                            <!-- Izin Bar -->
                                            @php
                                                $izinHeight = $total > 0 ? ($absensi->izin / $total) * 100 : 0;
                                            @endphp
                                            <div class="absolute bottom-0 left-0 right-0 bg-yellow-500 rounded-t-lg transition-all duration-300"
                                                style="height: {{ $izinHeight }}%; transform: translateY(-{{ $hadirHeight }}%)"
                                                title="Izin: {{ $absensi->izin }}"></div>
                                        </div>
                                        <div class="mt-2 text-xs text-gray-600 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($absensi->tanggal)->translatedFormat('D') }}
                                        </div>
                                        <div class="text-xs font-medium text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($absensi->tanggal)->format('d') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Legend -->
                            <div class="flex justify-center space-x-6 mt-4">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                    <span class="text-xs text-gray-600 dark:text-gray-400">Hadir</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                                    <span class="text-xs text-gray-600 dark:text-gray-400">Izin/Cuti</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-gray-400 rounded-full mr-2"></div>
                                    <span class="text-xs text-gray-600 dark:text-gray-400">Alpha</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="h-64 flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <p class="text-lg mb-2">Belum ada data absensi minggu ini</p>
                        <p class="text-sm">Data akan muncul setelah input absensi</p>
                    </div>
                @endif
            </div>

            <!-- Recent Activity & Top Performers -->
            <div class="space-y-6">
                <!-- Recent Activity -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Aktivitas Terbaru</h3>
                    <div class="space-y-4">
                        @if ($aktivitasTerbaru->count() > 0)
                            @foreach ($aktivitasTerbaru as $absensi)
                                <div class="flex items-start space-x-3">
                                    @php
                                        $statusColors = [
                                            'hadir' =>
                                                'bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400',
                                            'izin' =>
                                                'bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400',
                                            'cuti' => 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400',
                                            'sakit' =>
                                                'bg-orange-100 dark:bg-orange-900 text-orange-600 dark:text-orange-400',
                                            'alpha' => 'bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400',
                                        ];
                                        $statusIcons = [
                                            'hadir' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                            'izin' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                            'cuti' =>
                                                'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                                            'sakit' =>
                                                'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                                            'alpha' => 'M6 18L18 6M6 6l12 12',
                                        ];
                                    @endphp
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-8 h-8 rounded-full {{ $statusColors[$absensi->status] ?? 'bg-gray-100 dark:bg-gray-700' }} flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="{{ $statusIcons[$absensi->status] ?? 'M12 4v16m8-8H4' }}" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $absensi->pegawai->nama }}
                                            <span class="font-normal text-gray-600 dark:text-gray-400">
                                                - {{ ucfirst($absensi->status) }}
                                                @if ($absensi->jam_masuk && $absensi->status == 'hadir')
                                                    ({{ $absensi->jam_masuk }})
                                                @endif
                                            </span>
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $absensi->created_at->diffForHumans() }}
                                            @if ($absensi->keterangan)
                                                • {{ Str::limit($absensi->keterangan, 30) }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Belum ada aktivitas hari ini
                                </p>
                            </div>
                        @endif
                    </div>
                    @if ($aktivitasTerbaru->count() > 0)
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('absensi.index') }}"
                                class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 flex items-center">
                                Lihat semua aktivitas
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Top Performers -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Pegawai Terbaik Bulan Ini</h3>
                    <div class="space-y-4">
                        @if ($topPegawai->count() > 0)
                            @foreach ($topPegawai as $index => $absensi)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            @if ($absensi->pegawai->foto)
                                                <img class="h-10 w-10 rounded-full"
                                                    src="{{ asset('storage/' . $absensi->pegawai->foto) }}"
                                                    alt="{{ $absensi->pegawai->nama }}">
                                            @else
                                                <div
                                                    class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                                    <span class="text-blue-600 dark:text-blue-400 font-medium">
                                                        {{ substr($absensi->pegawai->nama, 0, 1) }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $absensi->pegawai->nama }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $absensi->pegawai->jabatan }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-semibold text-green-600 dark:text-green-400">
                                            {{ $absensi->total_hadir }} hari
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $absensi->pegawai->total_hari_kerja > 0
                                                ? round(($absensi->total_hadir / $absensi->pegawai->total_hari_kerja) * 100)
                                                : 0 }}%
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Belum ada data kehadiran bulan
                                    ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Summary -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Quick Actions -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('absensi.create') }}"
                        class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors group">
                        <div
                            class="p-3 bg-blue-100 dark:bg-blue-800 rounded-lg mr-4 group-hover:bg-blue-200 dark:group-hover:bg-blue-700 transition-colors">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">Input Absensi</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Masukkan data kehadiran</p>
                        </div>
                    </a>

                    <a href="{{ route('master-data.pegawai.create') }}"
                        class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors group">
                        <div
                            class="p-3 bg-green-100 dark:bg-green-800 rounded-lg mr-4 group-hover:bg-green-200 dark:group-hover:bg-green-700 transition-colors">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">Tambah Pegawai</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Tambahkan pegawai baru</p>
                        </div>
                    </a>

                    <a href="{{ route('absensi.laporan') }}"
                        class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors group">
                        <div
                            class="p-3 bg-purple-100 dark:bg-purple-800 rounded-lg mr-4 group-hover:bg-purple-200 dark:group-hover:bg-purple-700 transition-colors">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">Generate Laporan</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Buat laporan absensi</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Today's Summary -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Ringkasan Hari Ini</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Total Pegawai Aktif</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $totalPegawai }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Hadir</span>
                        <span
                            class="text-sm font-medium text-green-600 dark:text-green-400">{{ $hadirHariIni }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Izin</span>
                        <span
                            class="text-sm font-medium text-yellow-600 dark:text-yellow-400">{{ $izinHariIni }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Cuti</span>
                        <span class="text-sm font-medium text-blue-600 dark:text-blue-400">{{ $cutiHariIni }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Alpha</span>
                        <span class="text-sm font-medium text-red-600 dark:text-red-400">{{ $alphaHariIni }}</span>
                    </div>
                    <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Persentase Kehadiran</span>
                            <span
                                class="text-sm font-bold {{ $persentaseKehadiran >= 80 ? 'text-green-600 dark:text-green-400' : ($persentaseKehadiran >= 60 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">
                                {{ $persentaseKehadiran }}%
                            </span>
                        </div>
                        <!-- Progress Bar -->
                        <div class="mt-2 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="h-2 rounded-full {{ $persentaseKehadiran >= 80 ? 'bg-green-500' : ($persentaseKehadiran >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                style="width: {{ min($persentaseKehadiran, 100) }}%">
                            </div>
                        </div>
                    </div>
                </div>

                @if ($absensiHariIni)
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('absensi.index', ['tanggal' => now()->format('Y-m-d')]) }}"
                            class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Lihat Detail Absensi Hari Ini
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Simple animation for progress bars
            document.addEventListener('DOMContentLoaded', function() {
                const progressBars = document.querySelectorAll('.h-2 > div');
                progressBars.forEach(bar => {
                    const width = bar.style.width;
                    bar.style.width = '0%';
                    setTimeout(() => {
                        bar.style.width = width;
                    }, 300);
                });

                // Update time every minute
                function updateTime() {
                    const now = new Date();
                    const timeElement = document.querySelector('#current-time');
                    if (timeElement) {
                        timeElement.textContent = now.toLocaleTimeString('id-ID', {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    }
                }

                setInterval(updateTime, 60000);
                updateTime();
            });
        </script>
    @endpush
</x-app-layout>
