<x-app-layout>
    @section('title', 'Tambah Pegawai Baru')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Pegawai Baru') }}
        </h2>
    </x-slot>

    <x-slot name="subheader">
        <div class="flex items-center space-x-2">
            <a href="{{ route('master-data.pegawai.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-4xl mx-auto">
        <form method="POST" action="{{ route('master-data.pegawai.store') }}" enctype="multipart/form-data"
            id="pegawaiForm">
            @csrf

            <div class="space-y-6">
                <!-- Informasi Dasar -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Dasar</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- NIP -->
                        <div>
                            <label for="nip"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                NIP
                            </label>
                            <input type="text" id="nip" name="nip" value="{{ old('nip') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('nip')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama -->
                        <div>
                            <label for="nama"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nama Lengkap *
                            </label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('nama')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Email *
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Telepon -->
                        <div>
                            <label for="telepon"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nomor Telepon *
                            </label>
                            <input type="text" id="telepon" name="telepon" value="{{ old('telepon') }}" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('telepon')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bagian -->
                        <div>
                            <label for="bagian_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Bagian *
                            </label>
                            <select id="bagian_id" name="bagian_id" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Bagian</option>
                                @foreach ($bagian as $b)
                                    <option value="{{ $b->id }}"
                                        {{ old('bagian_id') == $b->id ? 'selected' : '' }}>
                                        {{ $b->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bagian_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jabatan -->
                        <div>
                            <label for="jabatan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Jabatan *
                            </label>
                            <input type="text" id="jabatan" name="jabatan" value="{{ old('jabatan') }}" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('jabatan')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Masuk -->
                        <div>
                            <label for="tgl_masuk"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Tanggal Masuk *
                            </label>
                            <input type="date" id="tgl_masuk" name="tgl_masuk" value="{{ old('tgl_masuk') }}"
                                required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('tgl_masuk')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Status *
                            </label>
                            <select id="status" name="status" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif
                                </option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Shift -->
                        <div>
                            <label for="shift"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Shift Kerja *
                            </label>
                            <select id="shift" name="shift" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="pagi" {{ old('shift') == 'pagi' ? 'selected' : '' }}>Pagi</option>
                                <option value="siang" {{ old('shift') == 'siang' ? 'selected' : '' }}>Siang</option>
                                <option value="malam" {{ old('shift') == 'malam' ? 'selected' : '' }}>Malam</option>
                                <option value="full_day" {{ old('shift') == 'full_day' ? 'selected' : '' }}>Full Day
                                </option>
                            </select>
                            @error('shift')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Foto -->
                        <div class="md:col-span-2">
                            <label for="foto"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Foto Profil
                            </label>
                            <input type="file" id="foto" name="foto" accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                onchange="previewImage(event)">
                            @error('foto')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <div class="mt-2">
                                <img id="foto-preview"
                                    class="h-32 w-32 rounded-lg border border-gray-300 dark:border-gray-600 hidden"
                                    alt="Preview foto">
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="md:col-span-2">
                            <label for="alamat"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Alamat Lengkap *
                            </label>
                            <textarea id="alamat" name="alamat" rows="3" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Informasi Rekening -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Rekening</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nomor Rekening -->
                        <div>
                            <label for="no_rekening"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nomor Rekening
                            </label>
                            <input type="text" id="no_rekening" name="no_rekening"
                                value="{{ old('no_rekening') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('no_rekening')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama Rekening -->
                        <div>
                            <label for="nama_rekening"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nama Pemilik Rekening
                            </label>
                            <input type="text" id="nama_rekening" name="nama_rekening"
                                value="{{ old('nama_rekening') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('nama_rekening')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bank -->
                        <div class="md:col-span-2">
                            <label for="bank"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nama Bank
                            </label>
                            <select id="bank" name="bank"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Bank</option>
                                <option value="BCA" {{ old('bank') == 'BCA' ? 'selected' : '' }}>BCA</option>
                                <option value="Mandiri" {{ old('bank') == 'Mandiri' ? 'selected' : '' }}>Mandiri
                                </option>
                                <option value="BNI" {{ old('bank') == 'BNI' ? 'selected' : '' }}>BNI</option>
                                <option value="BRI" {{ old('bank') == 'BRI' ? 'selected' : '' }}>BRI</option>
                                <option value="CIMB" {{ old('bank') == 'CIMB' ? 'selected' : '' }}>CIMB Niaga
                                </option>
                                <option value="Danamon" {{ old('bank') == 'Danamon' ? 'selected' : '' }}>Danamon
                                </option>
                                <option value="Maybank" {{ old('bank') == 'Maybank' ? 'selected' : '' }}>Maybank
                                </option>
                                <option value="Permata" {{ old('bank') == 'Permata' ? 'selected' : '' }}>Permata
                                </option>
                                <option value="OCBC" {{ old('bank') == 'OCBC' ? 'selected' : '' }}>OCBC</option>
                                <option value="Panin" {{ old('bank') == 'Panin' ? 'selected' : '' }}>Panin</option>
                                <option value="Lainnya" {{ old('bank') == 'Lainnya' ? 'selected' : '' }}>Lainnya
                                </option>
                            </select>
                            @error('bank')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Informasi Gaji & Lembur -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Gaji & Lembur</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Periode Gaji -->
                        <div>
                            <label for="periode_gaji"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Periode Gaji *
                            </label>
                            <select id="periode_gaji" name="periode_gaji" required onchange="toggleGajiFields()"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="bulanan" {{ old('periode_gaji') == 'bulanan' ? 'selected' : '' }}>
                                    Bulanan</option>
                                <option value="mingguan" {{ old('periode_gaji') == 'mingguan' ? 'selected' : '' }}>
                                    Mingguan</option>
                                <option value="harian" {{ old('periode_gaji') == 'harian' ? 'selected' : '' }}>Harian
                                </option>
                            </select>
                            @error('periode_gaji')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gaji Pokok -->
                        <div>
                            <label for="gaji_pokok"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Gaji Pokok *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400">Rp</span>
                                </div>
                                <input type="number" id="gaji_pokok" name="gaji_pokok"
                                    value="{{ old('gaji_pokok') }}" required min="0" step="1000"
                                    class="w-full px-3 py-2 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    oninput="formatCurrency(this)">
                                @error('gaji_pokok')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Gaji Harian -->
                        <div id="gaji_harian_container">
                            <label for="gaji_harian"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Gaji Harian *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400">Rp</span>
                                </div>
                                <input type="number" id="gaji_harian" name="gaji_harian"
                                    value="{{ old('gaji_harian') }}" min="0" step="1000"
                                    class="w-full px-3 py-2 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    oninput="formatCurrency(this)">
                                @error('gaji_harian')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Uang Makan -->
                        <div>
                            <label for="uang_makan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Uang Makan (per hari)
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400">Rp</span>
                                </div>
                                <input type="number" id="uang_makan" name="uang_makan"
                                    value="{{ old('uang_makan', 0) }}" min="0" step="1000"
                                    class="w-full px-3 py-2 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    oninput="formatCurrency(this)">
                                @error('uang_makan')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Uang Makan Tanggal Merah -->
                        <div>
                            <label for="uang_makan_tanggal_merah"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Uang Makan (Tanggal Merah)
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400">Rp</span>
                                </div>
                                <input type="number" id="uang_makan_tanggal_merah" name="uang_makan_tanggal_merah"
                                    value="{{ old('uang_makan_tanggal_merah', 0) }}" min="0" step="1000"
                                    class="w-full px-3 py-2 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    oninput="formatCurrency(this)">
                                @error('uang_makan_tanggal_merah')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Rate Lembur -->
                        <div>
                            <label for="rate_lembur"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Rate Lembur (per jam)
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400">Rp</span>
                                </div>
                                <input type="number" id="rate_lembur" name="rate_lembur"
                                    value="{{ old('rate_lembur', 0) }}" min="0" step="1000"
                                    class="w-full px-3 py-2 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    oninput="formatCurrency(this)">
                                @error('rate_lembur')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Rate Lembur Tanggal Merah -->
                        <div>
                            <label for="rate_lembur_tanggal_merah"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Rate Lembur (Tanggal Merah)
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400">Rp</span>
                                </div>
                                <input type="number" id="rate_lembur_tanggal_merah" name="rate_lembur_tanggal_merah"
                                    value="{{ old('rate_lembur_tanggal_merah', 0) }}" min="0" step="1000"
                                    class="w-full px-3 py-2 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    oninput="formatCurrency(this)">
                                @error('rate_lembur_tanggal_merah')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-between items-center">
                    <a href="{{ route('master-data.pegawai.index') }}"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white rounded-lg transition-colors font-medium">
                        Simpan Data Pegawai
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            // Preview image
            function previewImage(event) {
                const reader = new FileReader();
                const preview = document.getElementById('foto-preview');

                reader.onload = function() {
                    preview.src = reader.result;
                    preview.classList.remove('hidden');
                }

                if (event.target.files[0]) {
                    reader.readAsDataURL(event.target.files[0]);
                }
            }

            // Format currency display
            function formatCurrency(input) {
                // Remove non-numeric characters
                let value = input.value.replace(/[^0-9]/g, '');

                // Format with thousand separators
                if (value.length > 0) {
                    value = parseInt(value).toLocaleString('id-ID');
                }

                // Update display (but store original in data attribute)
                input.dataset.rawValue = input.value;
                // Don't actually change the input value as it's a number field
            }

            // Toggle gaji harian field
            function toggleGajiFields() {
                const periode = document.getElementById('periode_gaji').value;
                const gajiHarianContainer = document.getElementById('gaji_harian_container');
                const gajiHarianInput = document.getElementById('gaji_harian');

                if (periode === 'harian') {
                    gajiHarianContainer.style.display = 'block';
                    gajiHarianInput.required = true;
                } else {
                    gajiHarianContainer.style.display = 'none';
                    gajiHarianInput.required = false;
                    gajiHarianInput.value = '';
                }
            }

            // Auto generate NIP if empty
            document.getElementById('nip').addEventListener('blur', function() {
                if (!this.value) {
                    // Generate NIP: YYYYMM + random 4 digits
                    const now = new Date();
                    const year = now.getFullYear();
                    const month = String(now.getMonth() + 1).padStart(2, '0');
                    const random = Math.floor(1000 + Math.random() * 9000);
                    this.value = `${year}${month}${random}`;
                }
            });

            // Initial setup
            document.addEventListener('DOMContentLoaded', function() {
                toggleGajiFields();

                // Set default values if not set
                const defaultValues = {
                    uang_makan: 0,
                    uang_makan_tanggal_merah: 0,
                    rate_lembur: 0,
                    rate_lembur_tanggal_merah: 0
                };

                for (const [key, value] of Object.entries(defaultValues)) {
                    const input = document.getElementById(key);
                    if (input && !input.value) {
                        input.value = value;
                    }
                }
            });

            // Form validation
            document.getElementById('pegawaiForm').addEventListener('submit', function(e) {
                // Validate phone number
                const telepon = document.getElementById('telepon').value;
                if (!/^[0-9+\-\s]+$/.test(telepon)) {
                    e.preventDefault();
                    alert('Nomor telepon hanya boleh berisi angka, plus, minus, dan spasi.');
                    return false;
                }

                // Validate bank account if filled
                const noRekening = document.getElementById('no_rekening').value;
                const namaRekening = document.getElementById('nama_rekening').value;
                const bank = document.getElementById('bank').value;

                if ((noRekening || namaRekening || bank) && !(noRekening && namaRekening && bank)) {
                    e.preventDefault();
                    alert(
                        'Harap lengkapi semua informasi rekening (nomor rekening, nama pemilik, dan bank) jika salah satu diisi.'
                    );
                    return false;
                }

                return true;
            });
        </script>
    @endpush
</x-app-layout>
