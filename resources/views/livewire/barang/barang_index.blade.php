<div>
    <!-- Breadcrumbs -->
    <div class="text-sm breadcrumbs">
        <div class="text-sm px-4 py-2 rounded-t-lg w-max bg-[#578FCA] text-white">
            <a href="{{ route('dashboard') }}" class="hover:underline">Fayrooz</a>
            <span class="mx-1">></span>
            <a href="{{ route('barang') }}" class="hover:underline">Data Barang</a>
        </div>
    </div>

    <!-- Konten -->
    <div class="bg-white p-6 rounded-lg rounded-tl-none shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#5e4a7e]">Manajemen Barang</h2>
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
                <table class="table w-full text-sm text-center border border-[#2DAA9E]">
                    <thead class="bg-[#578FCA] bg-opacity-90 text-white">
                        <tr>
                            <th class="py-3 px-4 ">
                                No
                            </th>
                            <x-sortable-column field="nama_barang" :currentField="$sortField" :currentDirection="$sortDirection"
                                label="Nama Barang" />
                            <x-sortable-column field="jenis" :currentField="$sortField" :currentDirection="$sortDirection"
                                label="Jenis Layanan" />
                            <th class="py-3 px-4 ">
                                Satuan
                            </th>
                            <th class="py-3 px-4">
                                Dibuat Oleh
                            </th>
                            <th class="py-3 px-4 ">
                                Terahir Diubah
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
                                <td class="py-2 px-4 border border-gray-300">
                                    <div class="font-medium text-gray-800">{{ $item->nama_barang }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->kode_barang }}</div>
                                </td>
                                <td class="py-2 px-4 border border-gray-300">{{ $item->jenis }}</td>
                                <td class="py-2 px-4 border border-gray-300">{{ $item->satuan }}</td>
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

                                        {{-- <a wire:navigate.hover href="{{ route('barang.riwayat', $item->id) }}"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm flex items-center gap-1"
                                            title="Riwayat Masuk/Keluar">
                                            <i class="fas fa-info-circle"></i>
                                        </a> --}}

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
    <x-modal wire:model="isDeleteModalOpen" title="{{ $canDelete ? 'Konfirmasi Hapus' : 'Tidak Dapat Dihapus' }}">
        <div class="sm:flex sm:items-start">
            <div
                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full {{ $canDelete ? 'bg-red-100' : 'bg-yellow-100' }} sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 {{ $canDelete ? 'text-red-600' : 'text-yellow-600' }}"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                @if ($canDelete)
                    <p class="text-sm text-gray-500">
                        Apakah Anda yakin ingin menghapus Barang <strong>{{ $nama_barang }}</strong> dengan Kode
                        <strong>{{ $kode_barang }}</strong>? Data yang sudah dihapus tidak dapat dikembalikan.
                    </p>
                @else
                    <p class="text-sm text-gray-500">
                        Barang <strong>{{ $nama_barang }}</strong> dengan Kode <strong>{{ $kode_barang }}</strong>
                        tidak dapat dihapus karena:
                    </p>
                    <ul class="list-disc list-inside text-sm text-red-500 mt-2">
                        @if ($existsInStokRak)
                            <li>Masih terdapat stok di rak (melalui data barang masuk)</li>
                        @endif
                        @if ($existsInBarangMasuk)
                            <li>Terdapat dalam data barang masuk</li>
                        @endif
                    </ul>
                @endif
            </div>
        </div>

        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
            @if ($canDelete)
                <button type="button" wire:click="deleteBarang"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Hapus
                </button>
            @endif
            <button type="button" wire:click="closeDeleteModal"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                {{ $canDelete ? 'Batal' : 'Tutup' }}
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
