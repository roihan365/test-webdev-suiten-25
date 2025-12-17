<x-app-layout>
    @section('title', 'Edit data bagian')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit data bagian') }}
        </h2>
    </x-slot>

    <x-slot name="subheader">
        <div class="flex items-center space-x-2">
            <a href="{{ route('master-data.bagian.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

        <div class="max-w-2xl mx-auto">
            <form method="POST" action="{{ route('master-data.bagian.update', $bagian->id) }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Form Section -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Informasi Bagian</h3>

                        <div class="space-y-6">
                            <!-- Name -->
                            <div>
                                <label for="name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Nama Bagian *
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name', $bagian->name) }}"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('name')
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
                                    <option value="aktif"
                                        {{ old('status', $bagian->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif"
                                        {{ old('status', $bagian->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif
                                    </option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Info -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6">
                        <h4 class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-4">Informasi Bagian</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Jumlah Pegawai</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $bagian->pegawai()->count() }} orang</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Dibuat Pada</p>
                                <p class="text-sm text-gray-900 dark:text-white">
                                    {{ $bagian->created_at->translatedFormat('d F Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Terakhir Diubah</p>
                                <p class="text-sm text-gray-900 dark:text-white">
                                    {{ $bagian->updated_at->translatedFormat('d F Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-between items-center">
                        <a href="{{ route('master-data.bagian.index') }}"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white rounded-lg transition-colors font-medium">
                            Update Bagian
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </x-app-layout>
