<div>
    <!-- Breadcrumbs -->
    <div class="text-sm breadcrumbs">
        <div class="text-sm px-4 py-2 rounded-t-lg w-max bg-[#578FCA] text-white">
            <a href="{{ route('dashboard') }}" class="hover:underline">Fayrooz</a>
            <span class="mx-1">></span>
            <a href="{{ route('whatsapp-api') }}" class="hover:underline">Data Whatsapp API</a>
        </div>
    </div>

    <!-- Konten -->
    <div class="bg-white p-6 rounded-lg rounded-tl-none shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#5e4a7e]">Pengaturan WA API</h2>
            <button wire:click.prevent="openModal"
                class="bg-blue-500 hover:bg-blue-900 text-white px-3 py-2 rounded text-sm flex items-center">Tambah
                Data</button>
        </div>

        <div>
            <div class="flex justify-between items-center mb-4 flex-wrap gap-2">
                {{-- Data Entries --}}
                <div>
                    <select wire:model.live="perPage" class="border border-gray-400 rounded-lg px-2 py-1 pr-5">
                        <option value="10">10 entri</option>
                        <option value="25">25 entri</option>
                        <option value="50">50 entri</option>
                        <option value="100">100 entri</option>
                    </select>

                </div>

                {{-- Kolom Pencarian dan Tombol Export --}}
                <div class="flex items-center gap-2 flex-wrap">
                    {{-- Input Pencarian --}}
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari..."
                        class="border border-gray-400 px-3 py-1.5 rounded-full max-w-xs" />
                </div>
            </div>

            <div class="overflow-x-auto w-full">
                <table class="table w-full text-sm text-center border border-[#578FCA]">
                    <thead class="bg-[#578FCA] bg-opacity-90 text-white">
                        <tr>
                            <th class="py-3 px-4">
                                No
                            </th>
                            <th class="py-3 px-4">
                                Nama
                            </th>
                            <th class="py-3 px-4">
                                URL API
                            </th>
                            <th class="py-3 px-4">Token</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($waApi as $index => $w)
                            <tr class="hover:bg-[#f3eaff] transition-all" wire:key="user-{{ $w->id }}">
                                <td class="py-2 px-4 border border-gray-300">
                                    {{ $loop->iteration }} </td>
                                <td class="py-2 px-4 border border-gray-300">{{ $w->nama }}</td>
                                <td class="py-2 px-4 border border-gray-300">{{ $w->base_url }}</td>
                                <td class="py-2 px-4 border border-gray-300">{{ $w->token }}</td>
                                <td class="py-2 px-4 border border-gray-300">
                                    @if ($w->active)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                            <i class="fas fa-circle mr-1.5 text-[8px] text-green-500"></i>
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                            <i class="fas fa-circle mr-1.5 text-[8px] text-red-500"></i>
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 border border-gray-300">
                                    <div class="flex justify-center gap-2">
                                        @if (!$w->active)
                                            <button wire:click="setActive({{ $w->id }})"
                                                class="inline-flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white px-2.5 py-1 rounded-md text-xs font-medium shadow-sm transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1"
                                                title="Jadikan Aktif">
                                                <i class="fas fa-check-circle mr-1.5 text-xs"></i>
                                                Aktifkan
                                            </button>
                                        @else
                                            <button wire:click="setInactive({{ $w->id }})"
                                                class="inline-flex items-center justify-center bg-amber-500 hover:bg-amber-600 text-white px-2.5 py-1 rounded-md text-xs font-medium shadow-sm transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-1"
                                                title="Nonaktifkan">
                                                <i class="fas fa-times-circle mr-1.5 text-xs"></i>
                                                Nonaktifkan
                                            </button>
                                        @endif
                                        <button wire:click="edit({{ $w->id }})"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded text-sm flex items-center gap-1"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="openDeleteModal({{ $w->id }})"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm flex items-center gap-1"
                                            title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-4 text-center text-gray-500">Tidak ada data ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>

            <div class="mt-4">
            </div>
        </div>
    </div>

    <x-modal wire:model="showCannotActivateModal" maxWidth="md">
        <div class="p-2">
            <div class="flex items-start">
                <!-- Icon Warning -->
                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-red-100 mr-4">
                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <div class="flex-1">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Peringatan</h3>
                    <p class="text-sm text-gray-600">
                        Tidak bisa mengaktifkan lebih dari satu API. Silakan nonaktifkan API yang sedang aktif terlebih
                        dahulu.
                    </p>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button wire:click="$set('showCannotActivateModal', false)"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </x-modal>

    <!-- Modal Form -->
    <x-modal wire:model="isModalOpen" title="{{ $waId ? 'Edit Data API' : 'Tambah Data API' }}"
        wire:key="modal-{{ $waId }}">
        <form wire:submit.prevent="save">
            <div class="space-y-4">
                <!-- Nama Layanan -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input wire:model.live="nama" type="text" id="nama"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('nama')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="url_api" class="block text-sm font-medium text-gray-700">Url Api</label>
                    <input wire:model.live="urlApi" type="text" id="url_api"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('urlApi')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="token" class="block text-sm font-medium text-gray-700">Token</label>
                    <input wire:model.live="token" type="text" id="token"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('token')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" wire:click="closeModal"
                    class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Batal
                </button>
                <button type="submit"
                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ $waId ? 'Simpan Perubahan' : 'Tambah Data' }}
                </button>
            </div>
        </form>
    </x-modal>

    <x-modal wire:model="showWarningModal" maxWidth="md">
        <div class="p-2">
            <div class="flex items-start">
                <!-- Icon Warning -->
                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-orange-100 mr-4">
                    <svg class="h-6 w-6 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <div class="flex-1">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Bisa Menghapus</h3>
                    <p class="text-sm text-gray-600">
                        Data API yang sedang aktif tidak bisa dihapus. Silakan nonaktifkan dulu konfigurasi ini sebelum
                        menghapus.
                    </p>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button @click="$wire.set('showWarningModal', false)"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-150">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </x-modal>


    <!-- Delete Confirmation Modal -->
    <x-modal wire:model="deleteModal" title="Hapus Data API">
        <div class="sm:flex sm:items-start">
            <div
                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin menghapus Data API <strong>{{ $nama }}</strong> ? Data yang
                    sudah dihapus tidak dapat dikembalikan.
                </p>
            </div>
        </div>

        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
            <button type="button" wire:click="delete"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                Hapus
            </button>
            <button type="button" wire:click="closeDeleteModal"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                Batal
            </button>
        </div>
    </x-modal>
</div>
