<x-app-layout>
    @section('title', 'data pegawai')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('data pegawai') }}
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
            <a href="{{ route('master-data.bagian.edit', $bagian->id) }}"
                class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 dark:bg-yellow-500 dark:hover:bg-yellow-600 text-white rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="space-y-6">
            <!-- Bagian Header -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $bagian->name }}</h2>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <span
                                class="px-3 py-1 rounded-full text-sm font-medium {{ $bagian->status == 'aktif' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                {{ ucfirst($bagian->status) }}
                            </span>
                            <span
                                class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                ID: {{ $bagian->id }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <div class="flex items-center space-x-2">
                            <div class="text-right">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Total Pegawai</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pegawai->total() }}</p>
                            </div>
                            <div
                                class="h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                <span class="text-blue-600 dark:text-blue-400 text-lg font-bold">
                                    {{ $pegawai->total() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian Information -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Basic Info -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Dasar</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <p class="mt-1 text-gray-900 dark:text-white">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $bagian->status == 'aktif' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                    {{ ucfirst($bagian->status) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dibuat
                                Pada</label>
                            <p class="mt-1 text-gray-900 dark:text-white">
                                {{ $bagian->created_at->translatedFormat('d F Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Terakhir
                                Diubah</label>
                            <p class="mt-1 text-gray-900 dark:text-white">
                                {{ $bagian->updated_at->translatedFormat('d F Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Statistik</h3>
                    <div class="space-y-4">
                        @php
                            $totalPegawai = $pegawai->total();
                            $aktifPegawai = $bagian->pegawai()->where('status', 'aktif')->count();
                            $nonaktifPegawai = $totalPegawai - $aktifPegawai;
                            $persentaseAktif = $totalPegawai > 0 ? round(($aktifPegawai / $totalPegawai) * 100, 1) : 0;
                        @endphp
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total
                                Pegawai</label>
                            <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $totalPegawai }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pegawai
                                Aktif</label>
                            <p class="mt-1 text-xl font-semibold text-green-600 dark:text-green-400">
                                {{ $aktifPegawai }} ({{ $persentaseAktif }}%)</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pegawai
                                Nonaktif</label>
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $nonaktifPegawai }}</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Aksi Cepat</h3>
                    <div class="space-y-3">
                        <a href="{{ route('master-data.pegawai.create', ['bagian_id' => $bagian->id]) }}"
                            class="flex items-center justify-center w-full px-4 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Pegawai
                        </a>
                        <form action="{{ route('master-data.bagian.toggle-status', $bagian->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="flex items-center justify-center w-full px-4 py-2 {{ $bagian->status == 'aktif' ? 'bg-yellow-600 hover:bg-yellow-700 dark:bg-yellow-500 dark:hover:bg-yellow-600' : 'bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600' }} text-white rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if ($bagian->status == 'aktif')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    @endif
                                </svg>
                                {{ $bagian->status == 'aktif' ? 'Nonaktifkan Bagian' : 'Aktifkan Bagian' }}
                            </button>
                        </form>
                        <a href="{{ route('master-data.bagian.edit', $bagian->id) }}"
                            class="flex items-center justify-center w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Bagian
                        </a>
                    </div>
                </div>
            </div>

            <!-- Daftar Pegawai -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Daftar Pegawai di Bagian Ini</h3>
                    <a href="{{ route('master-data.pegawai.index', ['bagian_id' => $bagian->id]) }}"
                        class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                        Lihat semua →
                    </a>
                </div>

                @if ($pegawai->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Nama Pegawai
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        NIP
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Jabatan
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($pegawai as $p)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    @if ($p->foto)
                                                        <img class="h-8 w-8 rounded-full"
                                                            src="{{ asset('storage/' . $p->foto) }}"
                                                            alt="{{ $p->nama }}">
                                                    @else
                                                        <div
                                                            class="h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                                            <span
                                                                class="text-blue-600 dark:text-blue-400 text-xs font-medium">
                                                                {{ substr($p->nama, 0, 1) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $p->nama }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $p->nip }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $p->jabatan }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $p->status == 'aktif' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                                {{ ucfirst($p->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('master-data.pegawai.show', $p->id) }}"
                                                class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $pegawai->withQueryString()->links() }}
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 3.75a4.5 4.5 0 01-4.5 4.5m0-4.5a4.5 4.5 0 014.5-4.5" />
                        </svg>
                        <p class="mt-4 text-gray-500 dark:text-gray-400">Belum ada pegawai di bagian ini</p>
                        <div class="mt-6">
                            <a href="{{ route('master-data.pegawai.create', ['bagian_id' => $bagian->id]) }}"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Pegawai
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
