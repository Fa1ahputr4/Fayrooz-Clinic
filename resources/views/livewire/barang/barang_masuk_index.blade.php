<div>
    <!-- Breadcrumbs -->
    <div class="text-sm breadcrumbs">
        <ul class="bg-[#578FCA] px-4 py-2 rounded-t-lg w-max text-white">
            <li>
                <a href="/dashboard" class="text-white">Fayrooz > Barang Masuk</a>
            </li>
        </ul>
    </div>

    <!-- Konten -->
    <div class="bg-white p-6 rounded-lg rounded-tl-none shadow border border-[#578FCA]">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#5e4a7e]">Barang Masuk</h2>
            <button wire:click.prevent="openModal"
                class="bg-blue-500 hover:bg-blue-900 text-white px-3 py-2 rounded text-sm flex items-center">Tambah
                Data</button>
        </div>

        <div>
            <div class="flex justify-between items-center mb-4">
                <div>
                    <select wire:model.live="perPage" class="border rounded rounded-lg">
                        <option value="10">10 entri</option>
                        <option value="25">25 entri</option>
                        <option value="50">50 entri</option>
                        <option value="100">100 entri</option>
                    </select>
                </div>
                <div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="(ID,Nama,Username)"
                        class="input input-bordered w-full max-w-xs rounded-lg" />
                </div>
            </div>

            <div class="overflow-hidden rounded-lg">
                <table class="table w-full text-sm text-center border border-[#2DAA9E]">
                    <thead class="bg-[#578FCA] bg-opacity-90 text-white">
                        <tr>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer">
                                No
                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer"
                                wire:click="sortBy('kode_barang')">
                                Kode Barang
                                @if ($sortField === 'kode_barang')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer"
                                wire:click="sortBy('nama_barang')">
                                Nama Barang
                                @if ($sortField === 'nama_barang')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer" wire:click="sortBy('jumlah')">
                                Jumlah masuk
                                @if ($sortField === 'jumlah')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer"
                                wire:click="sortBy('tanggal_masuk')">
                                Tanggal Masuk
                                @if ($sortField === 'tanggal_masuk')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer"
                                wire:click="sortBy('keterangan')">
                                Keterangan
                                @if ($sortField === 'keterangan')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $index => $item)
                            <tr class="hover:bg-[#f3eaff] transition-all" wire:key="barang-{{ $item->id }}">
                                <td class="py-2 px-4 border border-[#5e4a7e]">
                                    {{ $items->firstItem() + $index }}
                                </td>
                                <td class="py-2 px-4 border border-[#5e4a7e]"> {{ $item->barang->kode_barang ?? '-' }}
                                </td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">{{ $item->barang->nama_barang }}</td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">{{ $item->jumlah }}</td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">
                                    {{ \Carbon\Carbon::parse($item->tanggal_masuk)->translatedFormat('d F Y') }}
                                </td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">{{ $item->keterangan }}</td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">
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
                                        <button wire:click="detailBarang({{ $item->id }})"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm flex items-center gap-1"
                                            title="Detail">
                                            <i class="fas fa-info-circle"></i>
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
        <form wire:submit.prevent="saveBarang">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kolom Kiri -->
                <div class="space-y-4">
                    <div>
                        <label for="kode_masuk" class="block text-sm font-medium text-gray-700">Kode Barang Masuk</label>
                        <input type="text" wire:model="kode_masuk" id="kode_masuk"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            readonly>
                        @error('kode_masuk')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
    
                    <div>
                        <label for="barangId" class="block text-sm font-medium text-gray-700">Barang</label>
                        <select wire:model="barangId" id="barangId"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Pilih Barang</option>
                            @foreach ($itemOptions as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_barang }}</option>
                            @endforeach
                        </select>
                        @error('barangId')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
    
                    <div>
                        <label for="jumlah_masuk" class="block text-sm font-medium text-gray-700">Jumlah Barang</label>
                        <input type="number" wire:model="jumlah_masuk" id="jumlah_masuk"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('jumlah_masuk')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
    
                    <div x-data="{
                        displayHarga: '',
                        formatRupiah(angka) {
                            if (!angka) return '';
                            angka = angka.toString().replace(/\D/g, '');
                            return new Intl.NumberFormat('id-ID').format(angka);
                        },
                        init() {
                            this.$watch('totalHargaLivewire', value => {
                                this.displayHarga = this.formatRupiah(value);
                            });
                            this.displayHarga = this.formatRupiah(this.totalHargaLivewire);
                        },
                        updateHarga(e) {
                            let angka = e.target.value.replace(/\D/g, '');
                            this.displayHarga = this.formatRupiah(angka);
                            this.totalHargaLivewire = angka;
                        },
                        totalHargaLivewire: @entangle('total_harga')
                    }" x-init="init()">
                        <label for="total_harga" class="block text-sm font-medium text-gray-700">Total Harga</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                            <input type="text" id="total_harga" x-model="displayHarga" @input="updateHarga"
                                class="pl-10 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                inputmode="numeric">
                        </div>
                        @error('total_harga')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
    
                <!-- Kolom Kanan -->
                <div class="space-y-4">
                    <div>
                        <label for="batch_no" class="block text-sm font-medium text-gray-700">Nomor Batch</label>
                        <input type="text" wire:model="batch_no" id="batch_no"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('batch_no')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
    
                    <div>
                        <label for="exp_date" class="block text-sm font-medium text-gray-700">Exp date</label>
                        <input type="date" wire:model="exp_date" id="exp_date"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('exp_date')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
    
                    <div>
                        <label for="tanggal_masuk" class="block text-sm font-medium text-gray-700">Tanggal Masuk</label>
                        <input type="date" wire:model="tanggal_masuk" id="tanggal_masuk"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('tanggal_masuk')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
    
                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                        <input type="text" wire:model="keterangan" id="keterangan"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('keterangan')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
    
            <!-- Tombol Aksi -->
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse mt-4">
                <button type="submit"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#5e4a7e] text-base font-medium text-white hover:bg-[#4b3a65] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Simpan
                </button>
                <button type="button" @click="isOpen = false" wire:click="closeModal"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
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
                    Apakah Anda yakin ingin menghapus data dengan kode masuk <strong>{{ $kode_masuk }}</strong>? Data yang sudah dihapus akan dipindahkan ke sampah.
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
