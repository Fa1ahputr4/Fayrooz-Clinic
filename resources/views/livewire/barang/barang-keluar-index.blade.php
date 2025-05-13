<div>
    <!-- Breadcrumbs -->
    <div class="text-sm breadcrumbs">
        <ul class="bg-[#578FCA] px-4 py-2 rounded-t-lg w-max text-white">
            <li>
                <a href="/dashboard" class="text-white">Fayrooz > Barang Keluar</a>
            </li>
        </ul>
    </div>

    <!-- Konten -->
    <div class="bg-white p-6 rounded-lg rounded-tl-none shadow border border-[#578FCA]">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#5e4a7e]">Barang Keluar</h2>
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
                                No Produk
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
                                Tanggal Keluar
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
                                Alasan
                                @if ($sortField === 'keterangan')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e]">Keterangan</th>
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
                                <td class="py-2 px-4 border border-[#5e4a7e]">{{ $item->status_keluar }}</td>
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

    <x-modal wire:model="isModalOpen" title="Tambah Barang Keluar">
        <form wire:submit.prevent="saveBarangKeluar">

            <div class="mb-4">
                <label for="tanggal_keluar" class="block text-sm font-medium text-gray-700">Tanggal Keluar</label>
                <input type="date" wire:model="tanggal_keluar" id="tanggal_keluar"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('tanggal_keluar')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status Barang Keluar</label>
                <select wire:model="status" id="status"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Pilih Status</option>
                    <option value="rusak">Rusak</option>
                    <option value="expired">Expired</option>
                    <option value="terpakai">Terpakai</option>
                    <option value="lainya">Lainya</option>
                </select>
                @error('status')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="selectedBarang" class="block text-sm font-medium text-gray-700">Barang</label>
                <select wire:model.live="selectedBarang" id="selectedBarang"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Pilih Barang</option>
                    @foreach ($itemOptions as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_barang }}</option>
                    @endforeach
                </select>
                @error('selectedBarang')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="flex items-center space-x-3">
                    <span class="text-sm text-gray-700">Dari Stok Umum</span>
                    <button type="button" wire:click="$toggle('gunakanBarangMasuk')"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-100 ease-in-out"
                        :class="{ 'bg-indigo-600': @js($gunakanBarangMasuk), 'bg-gray-300': !@js($gunakanBarangMasuk) }">
                        <span
                            class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-100 ease-in-out"
                            :class="{
                                'translate-x-6': @js($gunakanBarangMasuk),
                                'translate-x-1': !
                                    @js($gunakanBarangMasuk)
                            }">
                        </span>
                    </button>
                </label>
            </div>

            {{-- Jika toggle tidak aktif, tampilkan Rak dan lalu Barang Masuk Berdasarkan Rak --}}
            @if (!$gunakanBarangMasuk)
                <div class="mb-4">
                    <label for="selectedRak" class="block text-sm font-medium text-gray-700">Sumber Rak</label>
                    <select wire:model.live="selectedRak" id="selectedRak"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Pilih Rak</option>
                        @foreach ($rakOptions as $rakId => $label)
                            <option value="{{ $rakId }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('selectedRak')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="stok_rak_id" class="block text-sm font-medium text-gray-700">Barang Masuk di Rak
                        Ini</label>
                    <select wire:model.live="stok_rak_id" id="stok_rak_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Pilih Barang Masuk</option>
                        @foreach ($barangMasukDariRakOptions as $item)
                            <option value="{{ $item['id'] }}">
                                {{ $item['kode_masuk'] }} (exp: {{ $item['exp_date'] }}, sisa:
                                {{ $item['stok_sisa'] }} pcs)
                            </option>
                        @endforeach
                    </select>
                    @error('stok_rak_id')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            @endif

            {{-- Jika toggle aktif, tampilkan Barang Masuk langsung dari stok umum --}}
            @if ($gunakanBarangMasuk)
                <div class="mb-4">
                    <label for="barang_masuk_id" class="block text-sm font-medium text-gray-700">Sumber Barang Masuk
                        (Stok Umum)</label>
                    <select wire:model.live="barang_masuk_id" id="barang_masuk_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Pilih Asal Barang</option>
                        @foreach ($barangMasukOptions as $item)
                            <option value="{{ $item['id'] }}" @if ($item['sisa'] == 0) disabled @endif>
                                {{ $item['kode_masuk'] }} (expired date: {{ $item['exp_date'] }})
                                @if ($item['sisa'] == 0)
                                    - Stok Habis
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('barang_masuk_id')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            @endif

            <div class="mb-4">
                <label for="jumlah_barang" class="block text-sm font-medium text-gray-700">Jumlah Barang</label>
                <input type="number" wire:model.live="jumlah_barang" id="jumlah_barang"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('jumlah_barang')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror

                <p class="text-xs text-gray-500 mt-1">
                    Stok tersedia:
                    @if ($selectedRak && $stok_rak_id)
                        {{ $stok_rak_tersisa }} pcs (dari barang masuk ini di rak terpilih)
                   
                    @elseif ($barang_masuk_id)
                        {{ $stok_barang_masuk_tersisa }} pcs (dari barang masuk)
                    @else
                        -
                    @endif
                </p>
            </div>

            <div class="mb-4">
                <label for="keterangan" class="block text-sm font-medium text-gray-700"> Keterangan</label>
                <input type="text" wire:model="keterangan" id="keterangan"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('keterangan')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
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

    @push('scripts')
        <script></script>
    @endpush
