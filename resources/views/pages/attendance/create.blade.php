<x-app-layout>
    @section('title', 'Input Absensi')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Input Absensi') }}
        </h2>
    </x-slot>

    <x-slot name="subheader">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                Input data absensi pegawai
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('absensi.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Form Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <form method="GET" action="{{ route('absensi.create') }}" class="space-y-4" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Tanggal -->
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tanggal Absensi *
                        </label>
                        <input type="date" id="tanggal" name="tanggal" value="{{ request('tanggal', $today) }}"
                            required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Bagian -->
                    <div>
                        <label for="bagian_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Filter Bagian
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
                    <button type="button" onclick="window.location.href='{{ route('absensi.create') }}'"
                        class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                        Reset Filter
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white rounded-lg transition-colors">
                        Filter Pegawai
                    </button>
                </div>
            </form>
        </div>

        <!-- Overtime Guide -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                        Panduan Perhitungan Lembur:
                    </h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300 space-y-1">
                        <p>• Pulang ≤ 17:00 → <strong>1 + 0</strong> (1 hari, 0 jam lembur)</p>
                        <p>• Pulang ≤ 21:00 → <strong>1 + (jamPulang - 17)</strong> (1 hari, X jam lembur)</p>
                        <p>• Pulang > 21:00 → <strong>2 + (jamPulang - 21)</strong> (2 hari, X jam lembur)</p>
                        <p class="mt-2 text-xs">Contoh: Pulang 23:00 = 2 + 1 (2 hari, 1 jam lembur)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Warning if data exists -->
        @if ($absensiHariIni && !request()->has('override'))
            <div
                class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                            Data absensi untuk tanggal
                            {{ \Carbon\Carbon::parse(request('tanggal', $today))->translatedFormat('d F Y') }} sudah
                            ada!
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                            <p>
                                Jika Anda melanjutkan, data yang sudah ada akan ditimpa. Silakan centang opsi "Override
                                existing data" jika ingin melanjutkan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Form -->
        <form method="POST" action="{{ route('absensi.store') }}" id="absensiForm">
            @csrf

            <input type="hidden" name="tanggal" value="{{ request('tanggal', $today) }}">

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        Daftar Pegawai -
                        {{ \Carbon\Carbon::parse(request('tanggal', $today))->translatedFormat('l, d F Y') }}
                    </h3>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <input id="selectAll" type="checkbox"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="selectAll" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                Pilih Semua
                            </label>
                        </div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $pegawai->count() }} pegawai ditemukan
                        </span>
                    </div>
                </div>

                @if ($pegawai->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th scope="col" class="w-12 px-4 py-3">
                                        <input id="selectAllHeader" type="checkbox"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    </th>
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
                                        Lembur (H+J)
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Catatan
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($pegawai as $index => $p)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700"
                                        data-pegawai-id="{{ $p->id }}">
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <input type="checkbox" name="pegawai_id[{{ $index }}]"
                                                value="{{ $p->id }}"
                                                class="pegawai-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                                onchange="toggleRowInputs(this, {{ $index }})">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if ($p->foto)
                                                        <img class="h-10 w-10 rounded-full"
                                                            src="{{ asset('storage/' . $p->foto) }}"
                                                            alt="{{ $p->nama }}">
                                                    @else
                                                        <div
                                                            class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                                            <span class="text-blue-600 dark:text-blue-400 font-medium">
                                                                {{ substr($p->nama, 0, 1) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $p->nama }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $p->nip }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $p->bagian->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <select name="status[{{ $index }}]"
                                                class="status-select w-full px-2 py-1 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                onchange="updateRowStatus(this, {{ $index }})">
                                                <option value="hadir">Hadir</option>
                                                <option value="izin">Izin</option>
                                                <option value="cuti">Cuti</option>
                                                <option value="sakit">Sakit</option>
                                                <option value="alpha">Alpha</option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="time" name="jam_masuk[{{ $index }}]"
                                                class="jam-masuk-input w-full px-2 py-1 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                disabled>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="time" name="jam_pulang[{{ $index }}]"
                                                class="jam-pulang-input w-full px-2 py-1 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                disabled onchange="calculateOvertime(this, {{ $index }})">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div
                                                class="lembur-display text-sm font-medium text-blue-600 dark:text-blue-400 text-center">
                                                -
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="text" name="keterangan[{{ $index }}]"
                                                placeholder="Catatan..."
                                                class="keterangan-input w-full px-2 py-1 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                maxlength="500" disabled>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Options -->
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 space-y-4">
                        <!-- Auto Alpha Option -->
                        <div class="flex items-center">
                            <input id="auto_alpha" name="auto_alpha" type="checkbox"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="auto_alpha" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                Otomatis tandai sebagai Alpha untuk pegawai yang tidak dipilih
                            </label>
                        </div>

                        <!-- Override Option -->
                        @if ($absensiHariIni)
                            <div class="flex items-center">
                                <input id="override" name="override" type="checkbox"
                                    class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                <label for="override" class="ml-2 text-sm text-red-700 dark:text-red-300 font-medium">
                                    Override existing data (Data yang sudah ada akan ditimpa)
                                </label>
                            </div>
                        @endif

                        <!-- Form Actions -->
                        <div
                            class="flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('absensi.index') }}"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white rounded-lg transition-colors font-medium">
                                Simpan Absensi
                            </button>
                        </div>
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 3.75a4.5 4.5 0 01-4.5 4.5m0-4.5a4.5 4.5 0 014.5-4.5" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Tidak ada pegawai</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Tidak ada pegawai aktif yang ditemukan dengan filter yang dipilih.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('absensi.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white rounded-lg transition-colors">
                                Reset Filter
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            // Select All functionality
            document.getElementById('selectAll').addEventListener('change', function() {
                document.getElementById('selectAllHeader').checked = this.checked;
                toggleAllRows(this.checked);
            });

            document.getElementById('selectAllHeader').addEventListener('change', function() {
                document.getElementById('selectAll').checked = this.checked;
                toggleAllRows(this.checked);
            });

            function toggleAllRows(checked) {
                const checkboxes = document.querySelectorAll('.pegawai-checkbox');
                checkboxes.forEach((checkbox, index) => {
                    checkbox.checked = checked;
                    toggleRowInputs(checkbox, index);
                });
            }

            function toggleRowInputs(checkbox, index) {
                const row = checkbox.closest('tr');
                const jamMasuk = row.querySelector('.jam-masuk-input');
                const jamPulang = row.querySelector('.jam-pulang-input');
                const keterangan = row.querySelector('.keterangan-input');
                const statusSelect = row.querySelector('.status-select');
                const lemburDisplay = row.querySelector('.lembur-display');

                if (checkbox.checked) {
                    jamMasuk.disabled = false;
                    jamPulang.disabled = false;
                    keterangan.disabled = false;

                    // Set default time for status 'hadir'
                    if (statusSelect.value === 'hadir') {
                        jamMasuk.value = '08:00';
                        jamPulang.value = '17:00';
                        calculateOvertime(jamPulang, index);
                    } else {
                        jamMasuk.value = '';
                        jamPulang.value = '';
                        lemburDisplay.textContent = '-';
                    }
                } else {
                    jamMasuk.disabled = true;
                    jamPulang.disabled = true;
                    keterangan.disabled = true;
                    jamMasuk.value = '';
                    jamPulang.value = '';
                    keterangan.value = '';
                    lemburDisplay.textContent = '-';
                }
            }

            function updateRowStatus(select, index) {
                const row = select.closest('tr');
                const checkbox = row.querySelector('.pegawai-checkbox');
                const jamMasuk = row.querySelector('.jam-masuk-input');
                const jamPulang = row.querySelector('.jam-pulang-input');
                const lemburDisplay = row.querySelector('.lembur-display');

                if (select.value === 'hadir' && checkbox.checked) {
                    jamMasuk.disabled = false;
                    jamPulang.disabled = false;

                    // Set default time for 'hadir' status
                    if (!jamMasuk.value) jamMasuk.value = '08:00';
                    if (!jamPulang.value) {
                        jamPulang.value = '17:00';
                        calculateOvertime(jamPulang, index);
                    }
                } else if (checkbox.checked) {
                    jamMasuk.disabled = false;
                    jamPulang.disabled = false;
                    jamMasuk.value = '';
                    jamPulang.value = '';
                    lemburDisplay.textContent = '-';
                }
            }

            function calculateOvertime(input, index) {
                const row = input.closest('tr');
                const jamMasuk = row.querySelector('.jam-masuk-input').value;
                const jamPulang = input.value;
                const lemburDisplay = row.querySelector('.lembur-display');

                if (jamMasuk && jamPulang) {
                    // Parse jam pulang
                    const [pulangHour, pulangMinute] = jamPulang.split(':').map(Number);

                    // Perhitungan lembur baru berdasarkan petunjuk
                    let hariKerja = 1;
                    let jamLembur = 0;

                    if (pulangHour <= 17) {
                        // Pulang ≤ 17:00 = 1 hari, 0 jam lembur
                        hariKerja = 1;
                        jamLembur = 0;
                    } else if (pulangHour <= 21) {
                        // Pulang ≤ 21:00 = 1 hari, (jamPulang - 17) jam lembur
                        hariKerja = 1;
                        jamLembur = pulangHour - 17;
                        // Tambah menit jika ada
                        if (pulangMinute > 0 && jamLembur < 4) {
                            // Untuk perhitungan lebih akurat
                            jamLembur += pulangMinute / 60;
                            jamLembur = Math.round(jamLembur * 100) / 100; // 2 decimal places
                        }
                    } else {
                        // Pulang > 21:00
                        if (pulangHour <= 24) {
                            // Pulang ≤ 24:00 = 2 hari, (jamPulang - 21) jam lembur
                            hariKerja = 2;
                            jamLembur = pulangHour - 21;
                            // Tambah menit jika ada
                            if (pulangMinute > 0) {
                                jamLembur += pulangMinute / 60;
                                jamLembur = Math.round(jamLembur * 100) / 100;
                            }
                        } else {
                            // Untuk jam > 24:00 (jika ada input seperti 25:00)
                            hariKerja = Math.floor(pulangHour / 24) + 1;
                            const sisaJam = pulangHour % 24;
                            if (sisaJam <= 4) {
                                jamLembur = sisaJam;
                                if (pulangMinute > 0) {
                                    jamLembur += pulangMinute / 60;
                                    jamLembur = Math.round(jamLembur * 100) / 100;
                                }
                            } else {
                                hariKerja++;
                                jamLembur = sisaJam - 4;
                                if (pulangMinute > 0) {
                                    jamLembur += pulangMinute / 60;
                                    jamLembur = Math.round(jamLembur * 100) / 100;
                                }
                            }
                        }
                    }

                    // Tampilkan hasil perhitungan
                    if (jamLembur === 0) {
                        lemburDisplay.textContent = `${hariKerja} + 0`;
                    } else {
                        lemburDisplay.textContent = `${hariKerja} + ${jamLembur}`;
                    }

                    // Tambah tooltip untuk detail
                    lemburDisplay.title = `${hariKerja} hari kerja, ${jamLembur} jam lembur`;

                    // Set data attribute untuk form submission jika diperlukan
                    input.setAttribute('data-hari-kerja', hariKerja);
                    input.setAttribute('data-jam-lembur', jamLembur);

                    // Show notification if overtime is significant
                    if (hariKerja > 1 || jamLembur >= 4) {
                        showOvertimeNotification(index, hariKerja, jamLembur);
                    }
                } else {
                    lemburDisplay.textContent = '-';
                }
            }

            function showOvertimeNotification(index, hariKerja, jamLembur) {
                // Create notification element
                const notification = document.createElement('div');
                notification.className =
                    'fixed top-4 right-4 bg-yellow-100 dark:bg-yellow-900 border border-yellow-300 dark:border-yellow-700 text-yellow-800 dark:text-yellow-200 px-4 py-3 rounded-lg shadow-lg z-50 transition-all duration-300';
                notification.innerHTML = `
            <div class="flex items-center">
                <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span>Pegawai #${index + 1}: Lembur ${hariKerja} hari + ${jamLembur} jam</span>
            </div>
        `;

                document.body.appendChild(notification);

                // Remove notification after 3 seconds
                setTimeout(() => {
                    notification.classList.add('opacity-0');
                    setTimeout(() => {
                        document.body.removeChild(notification);
                    }, 300);
                }, 3000);
            }

            // Form validation
            document.getElementById('absensiForm').addEventListener('submit', function(e) {
                const checkboxes = document.querySelectorAll('.pegawai-checkbox:checked');
                if (checkboxes.length === 0) {
                    e.preventDefault();
                    alert('Pilih setidaknya satu pegawai untuk diinput absensinya.');
                    return false;
                }

                // Validate time inputs for 'hadir' status
                const rows = document.querySelectorAll('tr[data-pegawai-id]');
                let hasError = false;

                rows.forEach(row => {
                    const checkbox = row.querySelector('.pegawai-checkbox');
                    if (checkbox.checked) {
                        const statusSelect = row.querySelector('.status-select');
                        const jamMasuk = row.querySelector('.jam-masuk-input');
                        const jamPulang = row.querySelector('.jam-pulang-input');

                        if (statusSelect.value === 'hadir') {
                            if (!jamMasuk.value) {
                                jamMasuk.classList.add('border-red-500');
                                hasError = true;
                            }
                            if (!jamPulang.value) {
                                jamPulang.classList.add('border-red-500');
                                hasError = true;
                            }

                            // Validate jam pulang > jam masuk
                            if (jamMasuk.value && jamPulang.value) {
                                if (jamPulang.value <= jamMasuk.value) {
                                    alert('Jam pulang harus lebih besar dari jam masuk');
                                    hasError = true;
                                }
                            }
                        }
                    }
                });

                if (hasError) {
                    e.preventDefault();
                    alert('Harap isi semua field yang diperlukan untuk pegawai yang dipilih.');
                    return false;
                }
            });

            // Auto-submit filter form when date changes
            document.getElementById('tanggal').addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        </script>
    @endpush
</x-app-layout>
