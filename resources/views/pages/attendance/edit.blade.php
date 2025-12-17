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
                Edit data absensi pegawai
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('absensi.index', ['tanggal' => $absensi->tanggal->format('Y-m-d')]) }}"
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

    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <form method="POST" action="{{ route('absensi.update', $absensi->id) }}" id="editAbsensiForm">
                @csrf
                @method('PUT')

                <!-- Error Messages -->
                @if ($errors->any())
                    <div
                        class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                    Terdapat {{ $errors->count() }} kesalahan yang harus diperbaiki
                                </h3>
                                <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Success Message -->
                @if (session('success'))
                    <div
                        class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="space-y-6">
                    <!-- Pegawai Info -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Pegawai</h3>
                        <div class="flex items-center space-x-4">
                            @if ($absensi->pegawai->foto)
                                <img class="h-16 w-16 rounded-full"
                                    src="{{ asset('storage/' . $absensi->pegawai->foto) }}"
                                    alt="{{ $absensi->pegawai->nama }}">
                            @else
                                <div
                                    class="h-16 w-16 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                    <span class="text-blue-600 dark:text-blue-400 text-2xl font-medium">
                                        {{ substr($absensi->pegawai->nama, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                            <div>
                                <h4 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    {{ $absensi->pegawai->nama }}</h4>
                                <p class="text-gray-600 dark:text-gray-400">{{ $absensi->pegawai->nip }}</p>
                                <p class="text-gray-600 dark:text-gray-400">
                                    {{ $absensi->pegawai->bagian->name ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tanggal Absensi *
                        </label>
                        <input type="date" id="tanggal" name="tanggal"
                            value="{{ old('tanggal', $absensi->tanggal->format('Y-m-d')) }}" required
                            max="{{ date('Y-m-d') }}" min="{{ date('Y-m-d', strtotime('-1 year')) }}"
                            class="w-full px-3 py-2 border {{ $errors->has('tanggal') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('tanggal')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Status *
                        </label>
                        <select id="status" name="status" required onchange="toggleTimeInputs()"
                            class="w-full px-3 py-2 border {{ $errors->has('status') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="hadir" {{ old('status', $absensi->status) == 'hadir' ? 'selected' : '' }}>
                                Hadir</option>
                            <option value="izin" {{ old('status', $absensi->status) == 'izin' ? 'selected' : '' }}>
                                Izin</option>
                            <option value="cuti" {{ old('status', $absensi->status) == 'cuti' ? 'selected' : '' }}>
                                Cuti</option>
                            <option value="sakit" {{ old('status', $absensi->status) == 'sakit' ? 'selected' : '' }}>
                                Sakit</option>
                            <option value="alpha" {{ old('status', $absensi->status) == 'alpha' ? 'selected' : '' }}>
                                Alpha</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Time Inputs -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="jam_masuk"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Jam Masuk
                            </label>
                            <input type="time" id="jam_masuk" name="jam_masuk"
                                value="{{ old('jam_masuk', $absensi->jam_masuk) }}"
                                class="w-full px-3 py-2 border {{ $errors->has('jam_masuk') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                onchange="calculateOvertime()">
                            @error('jam_masuk')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jam_pulang"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Jam Pulang
                            </label>
                            <input type="time" id="jam_pulang" name="jam_pulang"
                                value="{{ old('jam_pulang', $absensi->jam_pulang) }}"
                                class="w-full px-3 py-2 border {{ $errors->has('jam_pulang') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                onchange="calculateOvertime()">
                            @error('jam_pulang')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Overtime Calculation -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4" id="overtimeContainer">
                        <h4 class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-2">Perhitungan Lembur</h4>
                        <p class="text-sm text-blue-700 dark:text-blue-400" id="overtimeResult">
                            @if ($absensi->jam_masuk && $absensi->jam_pulang && $absensi->status == 'hadir')
                                @php
                                    $jamPulang = \Carbon\Carbon::parse($absensi->jam_pulang);
                                    $jamPulangHour = $jamPulang->hour;
                                    $jamPulangMinute = $jamPulang->minute;

                                    // Perhitungan lembur baru
                                    if ($jamPulangHour <= 17) {
                                        $hariKerja = 1;
                                        $jamLembur = 0;
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
                                @endphp
                                Total lembur: <span class="font-bold">{{ $hariKerja }} + {{ $jamLembur }}</span>
                                ({{ $hariKerja }} hari kerja, {{ $jamLembur }} jam lembur)
                            @else
                                Masukkan jam masuk dan jam pulang untuk menghitung lembur
                            @endif
                        </p>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="keterangan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Catatan
                        </label>
                        <textarea id="keterangan" name="keterangan" rows="3"
                            class="w-full px-3 py-2 border {{ $errors->has('keterangan') ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('keterangan', $absensi->keterangan) }}</textarea>
                        <div class="flex justify-between mt-1">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Maksimal 500 karakter.
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400" id="charCount">
                                {{ strlen(old('keterangan', $absensi->keterangan)) }}/500
                            </p>
                        </div>
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Data Info -->
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Data Saat Ini</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Tanggal:</span>
                                <span
                                    class="ml-2 font-medium text-gray-900 dark:text-white">{{ $absensi->tanggal->translatedFormat('d F Y') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Status:</span>
                                <span
                                    class="ml-2 font-medium {{ $absensi->status == 'hadir'
                                        ? 'text-green-600 dark:text-green-400'
                                        : ($absensi->status == 'izin'
                                            ? 'text-yellow-600 dark:text-yellow-400'
                                            : ($absensi->status == 'cuti'
                                                ? 'text-blue-600 dark:text-blue-400'
                                                : ($absensi->status == 'sakit'
                                                    ? 'text-orange-600 dark:text-orange-400'
                                                    : 'text-red-600 dark:text-red-400'))) }}">
                                    {{ ucfirst($absensi->status) }}
                                </span>
                            </div>
                            @if ($absensi->jam_masuk && $absensi->jam_pulang)
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">Jam Masuk:</span>
                                    <span
                                        class="ml-2 font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">Jam Pulang:</span>
                                    <span
                                        class="ml-2 font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($absensi->jam_pulang)->format('H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('absensi.index', ['tanggal' => $absensi->tanggal->format('Y-m-d')]) }}"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white rounded-lg transition-colors font-medium">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Enable/disable time inputs based on status
            const statusSelect = document.getElementById('status');
            const jamMasukInput = document.getElementById('jam_masuk');
            const jamPulangInput = document.getElementById('jam_pulang');
            const overtimeContainer = document.getElementById('overtimeContainer');
            const overtimeResult = document.getElementById('overtimeResult');
            const keteranganInput = document.getElementById('keterangan');
            const charCount = document.getElementById('charCount');

            function toggleTimeInputs() {
                if (statusSelect.value === 'hadir') {
                    jamMasukInput.disabled = false;
                    jamMasukInput.required = true;
                    jamPulangInput.disabled = false;
                    jamPulangInput.required = true;
                    overtimeContainer.classList.remove('hidden');

                    // Set default values if empty
                    if (!jamMasukInput.value) jamMasukInput.value = '08:00';
                    if (!jamPulangInput.value) jamPulangInput.value = '17:00';

                    calculateOvertime();
                } else {
                    jamMasukInput.disabled = true;
                    jamMasukInput.required = false;
                    jamMasukInput.value = '';
                    jamPulangInput.disabled = true;
                    jamPulangInput.required = false;
                    jamPulangInput.value = '';
                    overtimeContainer.classList.add('hidden');
                    overtimeResult.textContent = 'Masukkan jam masuk dan jam pulang untuk menghitung lembur';
                }
            }

            function calculateOvertime() {
                const jamMasuk = jamMasukInput.value;
                const jamPulang = jamPulangInput.value;

                if (jamMasuk && jamPulang && statusSelect.value === 'hadir') {
                    // Parse jam pulang
                    const [pulangHour, pulangMinute] = jamPulang.split(':').map(Number);

                    // Perhitungan lembur baru
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
                            jamLembur += pulangMinute / 60;
                            jamLembur = Math.round(jamLembur * 100) / 100;
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
                            // Untuk jam > 24:00
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

                    // Tampilkan hasil
                    if (jamLembur === 0) {
                        overtimeResult.innerHTML =
                            `Total lembur: <span class="font-bold">${hariKerja} + 0</span> (${hariKerja} hari kerja, 0 jam lembur)`;
                    } else {
                        overtimeResult.innerHTML =
                            `Total lembur: <span class="font-bold">${hariKerja} + ${jamLembur}</span> (${hariKerja} hari kerja, ${jamLembur} jam lembur)`;
                    }

                    // Validasi jam masuk dan jam pulang
                    if (jamMasuk && jamPulang) {
                        const jamMasukTime = new Date(`2000-01-01T${jamMasuk}`);
                        const jamPulangTime = new Date(`2000-01-01T${jamPulang}`);

                        if (jamPulangTime <= jamMasukTime) {
                            overtimeResult.innerHTML +=
                                '<br><span class="text-red-600 dark:text-red-400 text-sm">⚠️ Jam pulang harus lebih besar dari jam masuk</span>';
                            jamPulangInput.classList.add('border-red-500');
                        } else {
                            jamPulangInput.classList.remove('border-red-500');
                        }
                    }
                } else {
                    overtimeResult.textContent = 'Masukkan jam masuk dan jam pulang untuk menghitung lembur';
                }
            }

            // Character counter for keterangan
            function updateCharCount() {
                const length = keteranganInput.value.length;
                charCount.textContent = `${length}/500`;

                if (length > 500) {
                    charCount.classList.add('text-red-600', 'dark:text-red-400');
                    keteranganInput.classList.add('border-red-500');
                } else {
                    charCount.classList.remove('text-red-600', 'dark:text-red-400');
                    keteranganInput.classList.remove('border-red-500');
                }
            }

            // Limit karakter untuk keterangan
            function limitCharCount() {
                if (keteranganInput.value.length > 500) {
                    keteranganInput.value = keteranganInput.value.substring(0, 500);
                    updateCharCount();
                }
            }

            // Form validation
            document.getElementById('editAbsensiForm').addEventListener('submit', function(e) {
                const status = statusSelect.value;
                const jamMasuk = jamMasukInput.value;
                const jamPulang = jamPulangInput.value;
                const keterangan = keteranganInput.value;

                // Reset error classes
                document.querySelectorAll('.border-red-500').forEach(el => {
                    el.classList.remove('border-red-500');
                });

                let hasError = false;

                if (status === 'hadir') {
                    if (!jamMasuk) {
                        jamMasukInput.classList.add('border-red-500');
                        hasError = true;
                    }

                    if (!jamPulang) {
                        jamPulangInput.classList.add('border-red-500');
                        hasError = true;
                    }

                    if (jamMasuk && jamPulang) {
                        const jamMasukTime = new Date(`2000-01-01T${jamMasuk}`);
                        const jamPulangTime = new Date(`2000-01-01T${jamPulang}`);

                        if (jamPulangTime <= jamMasukTime) {
                            jamPulangInput.classList.add('border-red-500');
                            hasError = true;
                            alert('Jam pulang harus lebih besar dari jam masuk.');
                        }
                    }
                }

                if (keterangan.length > 500) {
                    keteranganInput.classList.add('border-red-500');
                    hasError = true;
                    alert('Keterangan maksimal 500 karakter.');
                }

                if (hasError) {
                    e.preventDefault();
                    return false;
                }

                return true;
            });

            // Initial setup
            document.addEventListener('DOMContentLoaded', function() {
                toggleTimeInputs();
                updateCharCount();

                // Add event listeners
                keteranganInput.addEventListener('input', function() {
                    updateCharCount();
                    limitCharCount();
                });

                // Set max date for tanggal input
                const today = new Date().toISOString().split('T')[0];
                const oneYearAgo = new Date();
                oneYearAgo.setFullYear(oneYearAgo.getFullYear() - 1);
                const oneYearAgoStr = oneYearAgo.toISOString().split('T')[0];

                document.getElementById('tanggal').setAttribute('max', today);
                document.getElementById('tanggal').setAttribute('min', oneYearAgoStr);
            });
        </script>
    @endpush
</x-app-layout>
