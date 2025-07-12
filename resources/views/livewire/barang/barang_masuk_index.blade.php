<div>
    <!-- Breadcrumbs -->
    <div class="text-sm breadcrumbs">
        <div class="text-sm px-4 py-2 rounded-t-lg w-max bg-[#578FCA] text-white">
            <a href="{{ route('dashboard') }}" class="hover:underline">Fayrooz</a>
            <span class="mx-1">></span>
            <a href="{{ route('barang-masuk') }}" class="hover:underline">Data Barang Masuk</a>
        </div>
    </div>

    <!-- Konten -->
    <div class="bg-white p-6 rounded-lg rounded-tl-none shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#5e4a7e]">Barang Masuk</h2>
            <button wire:click.prevent="openModal"
                class="bg-blue-500 hover:bg-blue-900 text-white px-3 py-2 rounded text-sm flex items-center">Tambah
                Data</button>
        </div>

        <div>
            <div class="flex justify-between items-center mb-4 flex-wrap gap-2">
                <!-- Bagian Kiri - Entri dan Date Range -->
                <div class="flex items-center gap-2 flex-wrap">
                    <!-- Data Entries -->
                    <select wire:model.live="perPage" class="border border-gray-400 rounded-lg px-2 py-1 pr-5">
                        <option value="10">10 entri</option>
                        <option value="25">25 entri</option>
                        <option value="50">50 entri</option>
                        <option value="100">100 entri</option>
                    </select>

                    <!-- Date Range -->
                    <x-daterange :startDate="$startDate" :endDate="$endDate" wireStart="startDate" wireEnd="endDate"
                        id="range1" />

                    <!-- Reset Button -->
                    <button x-show="@this.startDate && @this.endDate"
                        @click="@this.set('startDate', null); @this.set('endDate', null); $('#range1').val('');"
                        class="px-3 py-2 bg-gray-100 text-gray-700 rounded-md text-sm hover:bg-gray-200 transition">
                        <i class="fas fa-times mr-1"></i> Reset
                    </button>
                </div>

                <!-- Bagian Kanan - Pencarian -->
                <div class="flex items-center gap-2">
                     <button wire:click="exportExcel" wire:loading.attr="disabled" wire:loading.class="opacity-50"
                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg text-sm flex items-center gap-1">
                            <span wire:loading.remove>
                                <i class="fas fa-file-excel mr-1"></i> Excel
                            </span>
                            <span wire:loading>
                                <i class="fas fa-spinner fa-spin mr-1"></i> Menyiapkan...
                            </span>
                        </button>
                    <!-- Input Pencarian -->
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari..."
                        class="border border-gray-400 px-3 py-1.5 rounded-full w-full" />
                </div>
            </div>

            <div class="overflow-x-auto w-full">
                <table class="table w-full text-sm text-center border border-[#2DAA9E]">
                    <thead class="bg-[#578FCA] bg-opacity-90 text-white">
                        <tr>
                            <th class="py-3 px-4">
                                No
                            </th>
                            <x-sortable-column field="kode_masuk" :currentField="$sortField" :currentDirection="$sortDirection"
                                label="Kode Masuk" />
                            <x-sortable-column field="id_barang" :currentField="$sortField" :currentDirection="$sortDirection"
                                label="Nama Barang" />
                            <x-sortable-column field="jumlah" :currentField="$sortField" :currentDirection="$sortDirection" label="Jumlah" />
                            <x-sortable-column field="tanggal_masuk" :currentField="$sortField" :currentDirection="$sortDirection"
                                label="Tanggal Masuk" />
                            <th class="py-3 px-4">
                                Keterangan
                            </th>
                            <th class="py-3 px-4">
                                Dibuat Oleh
                            </th>
                            <th class="py-3 px-4">
                                Terakhir Diubah
                            </th>
                            <th class="py-3 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $index => $item)
                            <tr class="hover:bg-[#f3eaff] transition-all" wire:key="barang-{{ $item->id }}">
                                <td class="py-2 px-4 border border-gray-300">
                                    {{ $items->firstItem() + $index }}
                                </td>
                                <td class="py-2 px-4 border border-gray-300">{{ $item->kode_masuk }}</td>
                                <td class="py-2 px-4 border border-gray-300">
                                    <div class="font-medium text-gray-800">{{ $item->barang->nama_barang }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->barang->kode_barang }}</div>
                                </td>
                                <td class="py-2 px-4 border border-gray-300">{{ $item->jumlah }}</td>
                                <td class="py-2 px-4 border border-gray-300">
                                    {{ \Carbon\Carbon::parse($item->tanggal_masuk)->locale('id')->isoFormat('D MMMM Y ') }}
                                </td>
                                <td class="py-2 px-4 border border-gray-300">{{ $item->keterangan ?? '-' }}</td>
                                <td class="py-2 px-4 border border-gray-300">
                                    <div class="font-medium text-gray-800">{{ $item->createdBy->name ?? '-' }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($item->created_at)->locale('id')->isoFormat('D MMMM Y HH:mm') }}
                                    </div>
                                </td>
                                <td class="py-2 px-4 border border-gray-300">
                                    <div class="font-medium text-gray-800">{{ $item->updatedBy->name ?? '-' }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($item->updated_at)->locale('id')->isoFormat('D MMMM Y HH:mm') }}
                                    </div>
                                </td>
                                <td class="py-2 px-4 border border-gray-300">
                                    <div class="flex justify-center items-center gap-2">
                                        <button wire:click="editBarangMasuk({{ $item->id }})"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded text-sm flex items-center gap-1"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="openDeleteModal({{ $item->id }})"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm flex items-center gap-1"
                                            title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="py-4 text-center text-gray-500">Tidak ada data ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>

            <div class="mt-4">
                {{ $items->links('vendor.livewire.tailwind') }}
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <x-modal wire:model="isModalOpen" title="{{ $isEdit ? 'Edit Barang Masuk' : 'Tambah Barang Masuk' }}">
    <form wire:submit.prevent="saveBarang" class="space-y-4">
        <!-- Kode Barang Masuk -->
        <div>
            <label for="kode_masuk" class="block text-sm font-medium text-gray-700 mb-1">Kode Barang Masuk</label>
            <input type="text" wire:model="kode_masuk" id="kode_masuk"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                readonly>
            @error('kode_masuk')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Pilih Barang -->
        <div>
            <label for="barangId" class="block text-sm font-medium text-gray-700 mb-1">Barang</label>
            <select wire:model="barangId" id="barangId"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">Pilih Barang</option>
                @foreach ($itemOptions as $item)
                    <option value="{{ $item->id }}">{{ $item->nama_barang }}</option>
                @endforeach
            </select>
            @error('barangId')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Jumlah Barang -->
        <div>
            <label for="jumlah_masuk" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Barang</label>
            <input type="number" wire:model="jumlah_masuk" id="jumlah_masuk"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('jumlah_masuk')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Nomor Batch -->
        <div>
            <label for="batch_no" class="block text-sm font-medium text-gray-700 mb-1">Nomor Batch</label>
            <input type="text" wire:model="batch_no" id="batch_no"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('batch_no')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tanggal Expired -->
        <div>
            <label for="exp_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Expired</label>
            <input type="date" wire:model="exp_date" id="exp_date"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('exp_date')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tanggal Masuk -->
        <div>
            <label for="tanggal_masuk" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Masuk</label>
            <input type="date" wire:model="tanggal_masuk" id="tanggal_masuk"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('tanggal_masuk')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Keterangan -->
        <div>
            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
            <textarea wire:model="keterangan" id="keterangan" rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            @error('keterangan')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tombol Aksi -->
        <div class="pt-4 flex justify-end space-x-3">
            <button type="button" wire:click="closeModal"
                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Batal
            </button>
            <button type="submit"
                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#5e4a7e] hover:bg-[#4b3a65] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Simpan
            </button>
        </div>
    </form>
</x-modal>

    <x-modal wire:model="isDeleteModalOpen" title="Hapus Data Barang">
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
                    Apakah Anda yakin ingin menghapus data dengan kode masuk <strong>{{ $kode_masuk }}</strong>? Data
                    yang sudah dihapus akan dipindahkan ke sampah.
                </p>
            </div>
        </div>

        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
            <button type="button" wire:click="deleteBarang"
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

@push('scripts')
    <script></script>
@endpush
