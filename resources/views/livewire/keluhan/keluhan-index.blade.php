<div>
    <!-- Breadcrumbs -->
    <div class="text-sm breadcrumbs">
        <div class="text-sm px-4 py-2 rounded-t-lg w-max bg-[#578FCA] text-white">
            <a href="{{ route('dashboard') }}" class="hover:underline">Fayrooz</a>
            <span class="mx-1">></span>
            <a href="{{ route('keluhan') }}" class="hover:underline">Data Keluhan</a>
        </div>
    </div>

    <!-- Konten -->
    <div class="bg-white p-6 rounded-lg rounded-tl-none shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#5e4a7e]">Manajemen Keluhan</h2>
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
                        class="border border-gray-400 px-3 py-1.5 rounded-full w-full" />
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
                                Jenis Layanan
                            </th>
                            <th class="py-3 px-4">Deskripsi</th>
                            <th class="py-3 px-4">Dibuat Oleh</th>
                            <th class="py-3 px-4">Terakhir Diubah</th>
                            <th class="py-3 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($keluhan as $index => $k)
                            <tr class="hover:bg-[#f3eaff] transition-all" wire:key="user-{{ $k->id }}">
                                <td class="py-2 px-4 border border-gray-300">
                                    {{ $keluhan->firstItem() + $index }}
                                </td>
                                <td class="py-2 px-4 border border-gray-300">{{ $k->nama }}</td>
                                <td class="py-2 px-4 border border-gray-300">{{ $k->layanan->nama }}</td>
                                <td class="py-2 px-4 border border-gray-300">{{ $k->deskripsi ?? '-' }}</td>
                                <td class="py-2 px-4 border border-gray-300">
                                    <div class="font-medium text-gray-800">{{ $k->createdBy->name ?? '-' }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($k->created_at)->locale('id')->isoFormat('D MMMM Y HH:mm') }}
                                    </div>
                                </td>
                                <td class="py-2 px-4 border border-gray-300">
                                    <div class="font-medium text-gray-800">{{ $k->updatedBy->name ?? '-' }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($k->updated_at)->locale('id')->isoFormat('D MMMM Y HH:mm') }}
                                    </div>
                                </td>
                                <td class="py-2 px-4 border border-gray-300">
                                    <div class="flex justify-center gap-2">
                                        <button wire:click="editKeluhan({{ $k->id }})"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded text-sm flex items-center gap-1"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="openDeleteModal({{ $k->id }})"
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
                {{ $keluhan->links('vendor.livewire.tailwind') }}
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <x-modal wire:model="isModalOpen" title="{{ $keluhanId ? 'Edit Keluhan' : 'Tambah Keluhan' }}"
        wire:key="modal-{{ $keluhanId }}">
        <form wire:submit.prevent="saveKeluhan">
            <div class="space-y-4">
                <!-- Nama Layanan -->
                <div>
                    <label for="nama_keluhan" class="block text-sm font-medium text-gray-700">Nama Keluhan</label>
                    <input wire:model.live="namaKeluhan" type="text" id="nama_keluhan"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('namaKeluhan')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="layanan_id" class="block text-sm font-medium text-gray-700">Jenis Layanan</label>
                    <select wire:model.live="layananId" id="layanan_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Pilih Jenis Layanan</option>
                        @foreach ($categories as $layanan)
                            <option value="{{ $layanan->id }}">{{ $layanan->nama }}</option>
                        @endforeach
                    </select>
                    @error('layananId')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea wire:model="deskripsi" id="deskripsi" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    @error('deskripsi')
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
                    {{ $keluhanId ? 'Simpan Perubahan' : 'Tambah Keluhan' }}
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Delete Confirmation Modal -->
    <x-modal wire:model="isDeleteModalOpen" title="Hapus Keluhan">
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
                    Apakah Anda yakin ingin menghapus Data keluhan <strong>{{ $namaKeluhan }}</strong> ? Data yang sudah dihapus tidak dapat dikembalikan.
                </p>
            </div>
        </div>

        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
            <button type="button" wire:click="deleteKeluhan"
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
