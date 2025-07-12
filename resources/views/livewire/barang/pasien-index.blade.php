<div>
    {{-- BreadCrumbs    --}}
    <div class="text-sm breadcrumbs">
        <div class="text-sm px-4 py-2 rounded-t-lg w-max bg-[#578FCA] text-white">
            <a href="{{ route('dashboard') }}" class="hover:underline">Fayrooz</a>
            <span class="mx-1">></span>
            <a href="{{ route('pasien') }}" class="hover:underline">Data Pasien</a>
        </div>
    </div>

    {{-- Konten --}}
    <div class="bg-white p-6 rounded-lg rounded-tl-none shadow ">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#5e4a7e]">Data Pasien</h2>
            <a wire:navigate.hover href="{{ route('pasien-tambah') }}"
                class="bg-blue-500 hover:bg-blue-900 text-white px-3 py-2 rounded text-sm flex items-center">
                Tambah Data
            </a>
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
                    {{-- Tombol Export Excel --}}
                    <button wire:click="exportExcel" wire:loading.attr="disabled" wire:loading.class="opacity-50"
                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg text-sm flex items-center gap-1">
                        <span wire:loading.remove>
                            <i class="fas fa-file-excel mr-1"></i> Excel
                        </span>
                        <span wire:loading>
                            <i class="fas fa-spinner fa-spin mr-1"></i> Menyiapkan...
                        </span>
                    </button>
                    {{-- Input Pencarian --}}
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari..."
                        class="border border-gray-400 px-3 py-1.5 rounded-full w-full" />
                </div>
            </div>

            {{-- Tabel Pasien --}}
            <div class="overflow-x-auto  w-full">
                <table class="table  w-full text-sm text-center border border-[#578FCA]">
                    <thead class="bg-[#578FCA] bg-opacity-90 text-white">
                        <tr>
                            <th class="py-3 px-4 cursor-pointer">
                                No
                            </th>
                            <x-sortable-column field="nama_lengkap" :currentField="$sortField" :currentDirection="$sortDirection"
                                label="Nama Pasien" />
                            <x-sortable-column field="jenis_kelamin" :currentField="$sortField" :currentDirection="$sortDirection"
                                label="Jenis Kelamin" />
                            <x-sortable-column field="usia" :currentField="$sortField" :currentDirection="$sortDirection" label="Usia" />
                            <th class="py-3 px-4">Telepon</th>
                            <th class="py-3 px-4">Alamat</th>
                            <th class="py-3 px-4">Rekam Medis</th>
                            <th class="py-3 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($patients as $index => $p)
                            <tr class="hover:bg-[#f3eaff] transition-all" wire:key="pasien-{{ $p->id }}">
                                <td class="py-2 px-4 border border-gray-300">
                                    {{ $patients->firstItem() + $index }}
                                </td>
                                <td class="py-2 px-4 border border-gray-200 text-left">
                                    <div class="font-medium text-gray-800">{{ $p->nama_lengkap }}</div>
                                    <div class="text-sm text-gray-500">{{ $p->nomor_rm }}</div>
                                </td>
                                <td class="py-2 px-4 border border-gray-300">
                                    {{ $p->jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan' }}
                                </td>
                                <td class="py-2 px-4 border border-gray-300">{{ $p->usia ?: '-' }}</td>
                                <td class="py-2 px-4 border border-gray-300">{{ $p->no_telepon ?: '-' }}</td>
                                <td class="py-2 px-4 border border-gray-300">{{ $p->alamat ?: '-' }}</td>
                                <td class="py-2 px-4 border border-gray-300 text-center">
                                    <div class="flex flex-col items-center space-y-2">
                                        <div class="w-36">
                                            <a wire:navigate.hover href="{{ route('rekmed-umum', ['id' => $p->id]) }}"
                                                class=" w-full inline-flex items-center justify-center bg-[#0ABAB5] hover:bg-[#03A6A1] text-white px-3 py-1.5 rounded text-sm">
                                                <i class="fas fa-file-alt mr-2"></i> Umum
                                            </a>
                                        </div>

                                        <div class="w-36">
                                            <a wire:navigate.hover
                                                href="{{ route('rekmed-beautycare', ['id' => $p->id]) }}"
                                                class=" w-full inline-flex items-center justify-center bg-[#C084FC] hover:bg-[#A855F7] text-white px-3 py-1.5 rounded text-sm">
                                                <i class="fas fa-spa mr-2"></i> Beautycare
                                            </a>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-2 px-4 border border-gray-300">
                                    <div class="flex justify-center gap-2">
                                        <a wire:navigate.hover href="{{ route('pasien-edit', ['id' => $p->id]) }}"
                                            class="{{ $isDokter ? 'bg-blue-500 hover:bg-blue-600' : 'bg-yellow-500 hover:bg-yellow-600' }} text-white px-3 py-2 rounded text-sm flex items-center gap-1"
                                            title="{{ $isDokter ? 'Lihat Detail' : 'Edit' }}">
                                            <i class="fas {{ $isDokter ? 'fa-eye' : 'fa-edit' }}"></i>
                                        </a>

                                        @if (auth()->user()->role == 'admin')
                                            <button wire:click="openDeleteModal({{ $p->id }})"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm flex items-center gap-1"
                                                title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        @endif
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

            {{-- Paginasi --}}
            <div class="mt-4">
                {{ $patients->links('vendor.livewire.tailwind') }}
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus Data Pasien --}}
    <x-modal wire:model="isDeleteModalOpen" title="Hapus Data Pasien">
        <div class="sm:flex sm:items-start">
            <div
                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin menghapus Data Pasien <strong>{{ $nama }}</strong> dengan Kode
                    <strong>{{ $noRm }}</strong>? Data yang sudah dihapus tidak dapat dikembalikan.
                </p>
            </div>
        </div>

        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
            <button type="button" wire:click="deletePatient"
                class=" w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                Hapus
            </button>
            <button type="button" wire:click="closeDeleteModal"
                class="mt-3  w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                Batal
            </button>
        </div>
    </x-modal>
</div>
