<div>
    <!-- Breadcrumbs -->
    <div class="text-sm breadcrumbs">
        <div class="text-sm px-4 py-2 rounded-t-lg w-max bg-[#578FCA] text-white">
            <a href="{{ route('dashboard') }}" class="hover:underline">Fayrooz</a>
            <span class="mx-1">></span>
            <a href="{{ route('stok-rak') }}" class="hover:underline">Data Stok Rak</a>
        </div>
    </div>

    <!-- Konten -->
    <div class="bg-white p-6 rounded-lg rounded-tl-none shadow border border-[#578FCA]">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#5e4a7e]">Manajemen Stok Rak</h2>
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
                <table class="table-auto w-full text-sm text-center border border-[#2DAA9E]">
                    <thead class="bg-[#578FCA] bg-opacity-90 text-white">
                        <tr>
                            <th class="py-3 px-4">
                                No
                            </th>
                            <th class="py-3 px-4">
                                Nama Rak
                            </th>

                            <th class="py-3 px-4">
                                Nama Barang
                            </th>
                            <th class="py-3 px-4">
                                Jumlah Stok
                            </th>
                            <th class="py-3 px-4">Status Barang</th>
                            <th class="py-3 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $index => $item)
                            <tr class="hover:bg-[#f3eaff] transition-all" wire:key="barang-{{ $item->rak_id }}">
                                <td class="py-2 px-4 border border-gray-300">
                                    {{ $items->firstItem() + $index }}
                                </td>
                                <td class="py-2 px-4 border border-gray-300">
                                    <div class="font-medium text-gray-800">{{ $item->nama_rak }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->kode_rak }}</div>
                                </td>
                                <td class="py-2 px-4 border border-gray-300">{{ $item->barang->nama_barang }}</td>
                                <td class="py-2 px-4 border border-gray-300">
                                    <div
                                        class="relative w-full bg-gray-200 rounded-full h-5 overflow-hidden border border-gray-300">
                                        <div class="absolute left-0 top-0 h-full {{ $item->warna_bar }}"
                                            style="width: {{ $item->persentase }}%"></div>
                                        <div
                                            class="absolute inset-0 flex items-center justify-center text-xs font-semibold text-[#5e4a7e]">
                                            {{ $item->jumlah_stok }}/{{ $item->kapasitas }}
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2 px-4 border border-gray-300">
                                    <span
                                        class="text-xs font-semibold px-2 py-1 rounded 
                                        @if ($item->jumlah_stok == 0) bg-gray-400 text-white
                                        @elseif ($item->status_barang == 'Ada Barang Expired') bg-red-600 text-white 
                                        @elseif ($item->status_barang == 'Hampir Expired') bg-yellow-400 text-black 
                                        @else bg-green-500 text-white @endif">
                                        @if ($item->jumlah_stok == 0)
                                            Tidak Ada Barang
                                        @else
                                            {{ $item->status_barang }}
                                        @endif
                                    </span>
                                </td>
                                <td class="py-2 px-4 border border-gray-300">
                                    <div class="flex justify-center gap-2">
                                        <button wire:click="tambahStok({{ $item->id }})"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm flex items-center gap-1"
                                            title="Tambah Stok">
                                            <i class="fas fa-plus"></i>
                                        </button>

                                        <a wire:navigate.hover href="{{ route('stok-rak.detail', $item->id) }}"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm flex items-center gap-1"
                                            title="Riwayat Masuk/Keluar">
                                            <i class="fas fa-info-circle"></i>
                                        </a>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-4 text-center text-gray-500">Tidak ada data ditemukan</td>
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
    <x-modal wire:model="isModalOpen" title="Tambah Stok" wire:key="modal-{{ $stok_rak_id }}">
        <form>
            <div class="mb-4">
                <label for="barang_masuk_id" class="block text-sm font-medium text-gray-700">Pilih Stok Barang</label>
                <select wire:model="barang_masuk_id" wire:change="updateMaxJumlah"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">Pilih Barang Masuk</option>

                    {{-- Tampilkan stok yang masih tersedia dulu --}}
                    @foreach ($barangMasuks as $masuk)
                        @php
                            $sisaBarang = $stokTersisa[$masuk->id] ?? 0;
                        @endphp
                        @if ($sisaBarang > 0)
                            <option value="{{ $masuk->id }}">
                                {{ $masuk->kode_masuk }} - {{ $masuk->barang->nama_barang }}
                                (Sisa: {{ $sisaBarang }} pcs)
                            </option>
                        @endif
                    @endforeach

                    {{-- Tampilkan yang stoknya habis (disabled) di bagian bawah --}}
                    @foreach ($barangMasuks as $masuk)
                        @php
                            $sisaBarang = $stokTersisa[$masuk->id] ?? 0;
                        @endphp
                        @if ($sisaBarang <= 0)
                            <option value="{{ $masuk->id }}" disabled>
                                {{ $masuk->kode_masuk }} - {{ $masuk->barang->nama_barang }}
                                (Habis)
                            </option>
                        @endif
                    @endforeach
                </select>

                @error('barang_masuk_id')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="jumlah_barang" class="block text-sm font-medium text-gray-700">Jumlah Barang</label>
                <input type="number" wire:model.live="jumlah_barang" id="jumlah_barang" min="1"
                    max="{{ $maxJumlah ?? '' }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @if ($warningJumlah)
                    <p class="text-red-600 text-xs mt-1">{{ $warningJumlah }}</p>
                @endif
                @error('jumlah_barang')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror

                @if ($barang_masuk_id)
                    <p class="text-xs text-gray-500 mt-1">
                        Stok tersedia: {{ $maxJumlah ?? 0 }} pcs
                    </p>
                @endif
            </div>

            <div class="mb-4">
                <label for="tgl_masuk" class="block text-sm font-medium text-gray-700"> Tanggal masuk rak</label>
                <input type="date" wire:model.live="tgl_masuk" id="tgl_masuk"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('tgl_masuk')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
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
                <button wire:click.prevent="saveStok({{ $rak_id }})"
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

    <!-- Delete Confirmation Modal -->
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
                    Apakah Anda yakin ingin menghapus Rak <strong>{{ $nama_rak }}</strong> dengan Kode
                    <strong>{{ $kode_rak }}</strong>? Data yang sudah dihapus tidak dapat dikembalikan.
                </p>
            </div>
        </div>

        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
            <button type="button" wire:click="deleteStokRak"
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
@endpush
