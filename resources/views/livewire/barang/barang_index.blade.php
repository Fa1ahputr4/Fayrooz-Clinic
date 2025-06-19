<div>
    <!-- Breadcrumbs -->
    <div class="text-sm breadcrumbs">
        <ul class="bg-[#578FCA] px-4 py-2 rounded-t-lg w-max text-white">
            <li>
                <a href="/dashboard" class="text-white">Fayrooz > Manajemen Barang</a>
            </li>
        </ul>
    </div>

    <!-- Konten -->
    <div class="bg-white p-6 rounded-lg rounded-tl-none shadow border border-[#578FCA]">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#5e4a7e]">Manajemen Barang</h2>
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
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer" wire:click="sortBy('jenis')">
                                Jenis Barang
                                @if ($sortField === 'jenis')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer"
                                wire:click="sortBy('jumlah_stok')">
                                Jumlah Stok
                                @if ($sortField === 'jumlah_stok')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer" wire:click="sortBy('satuan')">
                                Satuan
                                @if ($sortField === 'satuan')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer"
                                wire:click="sortBy('created_at')">
                                Tanggal Dibuat
                                @if ($sortField === 'created_at')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer"
                                wire:click="sortBy('updated_at')">
                                Terahir Diubah
                                @if ($sortField === 'updated_at')
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
                                <td class="py-2 px-4 border border-[#5e4a7e]">{{ $item->kode_barang }}</td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">{{ $item->nama_barang }}</td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">{{ $item->jenis }}</td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">{{ $item->jumlah_stok }}</td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">{{ $item->satuan }}</td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">
                                    {{ $item->created_at->translatedFormat('d F Y') }}</td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">
                                    {{ $item->updated_at->translatedFormat('d F Y') }}</td>

                                <td class="py-2 px-4 border border-[#5e4a7e]">
                                    <div class="flex justify-center gap-2">
                                        <button wire:click="editBarang({{ $item->id }})"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded text-sm flex items-center gap-1"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="openDeleteModal({{ $item->id }})"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm flex items-center gap-1"
                                            title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>

                                        <a wire:navigate.hover href="{{ route('barang.riwayat', $item->id) }}"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm flex items-center gap-1"
                                            title="Riwayat Masuk/Keluar">
                                            <i class="fas fa-info-circle"></i>
                                        </a>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-4 text-center text-gray-500">Tidak ada data ditemukan</td>
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
    <x-modal wire:model="isModalOpen" title="{{ $barangId ? 'Edit Barang' : 'Tambah Barang' }}"
        wire:key="modal-{{ $barangId }}">
        <form wire:submit.prevent="saveBarang">
            <div class="mb-4">
                <label for="kode_barang" class="block text-sm font-medium text-gray-700">Kode Barang</label>
                <input type="text" wire:model="kode_barang" id="kode_barang"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('kode_barang')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="nama_barang" class="block text-sm font-medium text-gray-700">Nama Barang</label>
                <input type="text" wire:model="nama_barang" id="nama_barang"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('nama_barang')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="jenis" class="block text-sm font-medium text-gray-700">Jenis</label>
                <select wire:model="jenis" id="jenis"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Pilih jenis barang</option>
                    <option value="obat">Obat</option>
                    <option value="kecantikan">Kecantikan</option>
                </select>
                @error('jenis')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="satuan" class="block text-sm font-medium text-gray-700">Satuan</label>
                <div wire:ignore>
                    <select name="satuan" id="satuan" class="form-control">
                        <option value="">Pilih satuan</option>
                        @foreach ($satuanOptions as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                @error('satuan')
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
                    Apakah Anda yakin ingin menghapus Barang <strong>{{ $nama_barang }}</strong> dengan Kode
                    <strong>{{ $kode_barang }}</strong>? Data yang sudah dihapus tidak dapat dikembalikan.
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
    <script>
        Livewire.on('pageRefresh', function() {
            // Refresh halaman setelah sukses update
            location.reload(); // Ini akan me-refresh halaman
        });

        function initSelect2Barang() {

            if ($.fn.select2 && $('#satuan').hasClass('select2-hidden-accessible')) {
                $('#satuan').select2('destroy');
            }
            $('#satuan').select2({
                width: '100%',
                tags: true,
                allowClear: true,
                placeholder: 'Pilih satuan atau ketik untuk menambah',
                createTag: function(params) {
                    return {
                        id: params.term,
                        text: params.term
                    };
                }
            });

            $('.select2-selection').css({
                'width': '100%',
                'height': '2.5rem',
                'padding': '0.5rem 0.75rem',
                'border-radius': '0.375rem',
                'font-size': '0.875rem',
                'display': 'flex',
                'align-items': 'center'
            });

            // Ambil nilai dari Livewire
            const satuanFromLivewire = @this.get('satuan');

            if (satuanFromLivewire) {
                // Jika tidak ada di option, tambahkan
                if ($('#satuan option[value="' + satuanFromLivewire + '"]').length === 0) {
                    $('#satuan').append(new Option(satuanFromLivewire, satuanFromLivewire, true, true));
                }

                // Set nilai select2
                $('#satuan').val(satuanFromLivewire).trigger('change');
            }

            // Sinkron ke Livewire saat berubah
            $('#satuan').on('change', function() {
                @this.set('satuan', $(this).val());
            });
        }

        document.addEventListener("livewire:navigated", function() {
            Livewire.on('editBarang', (data) => {
                initSelect2Barang();
            });
        });

        Livewire.on('tambahBarang', () => {
            $('#satuan').val(null).trigger('change'); // ⬅️ Reset select2 ke kosong
            initSelect2Barang();
        });

        Livewire.on('editBarang', (data) => {
            initSelect2Barang();
        });
    </script>
@endpush
