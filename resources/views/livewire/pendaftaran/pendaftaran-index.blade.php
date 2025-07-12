<div>
    <!-- Breadcrumbs -->
    <div class="text-sm breadcrumbs">
        <div class="text-sm px-4 py-2 rounded-t-lg w-max bg-[#578FCA] text-white">
            <a href="{{ route('dashboard') }}" class="hover:underline">Fayrooz</a>
            <span class="mx-1">></span>
            <a href="{{ route('pendaftaran') }}" class="hover:underline">Pendaftaran</a>
        </div>
    </div>

    <!-- Konten -->
    <div class="bg-white p-6 rounded-lg rounded-tl-none shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#5e4a7e]">Pendaftaran</h2>
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

                    {{-- Tombol Export Excel --}}
                    @if ($tab === 'all')
                        <button wire:click="exportExcel" wire:loading.attr="disabled" wire:loading.class="opacity-50"
                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg text-sm flex items-center gap-1">
                            <span wire:loading.remove>
                                <i class="fas fa-file-excel mr-1"></i> Excel
                            </span>
                            <span wire:loading>
                                <i class="fas fa-spinner fa-spin mr-1"></i> Menyiapkan...
                            </span>
                        </button>
                    @endif

                    {{-- Input Pencarian --}}
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari..."
                        class="border border-gray-400 px-3 py-1.5 rounded-full max-w-xs" />
                </div>
            </div>
            <!-- Setelah bagian tab navigation -->
            <div class="mb-4">
                <!-- Tabs -->
                <div class="inline-flex border-b border-gray-200">
                    <button wire:click="$set('tab', 'today')"
                        class="inline-block px-4 py-2 text-sm font-medium rounded-t-lg border-b-2 transition 
                {{ $tab === 'today'
                    ? 'text-blue-600 border-blue-600'
                    : 'text-gray-500 border-transparent hover:text-blue-600 hover:border-blue-600' }}">
                        Hari Ini
                    </button>
                    <button wire:click="$set('tab', 'all')"
                        class="inline-block px-4 py-2 text-sm font-medium rounded-t-lg border-b-2 transition 
                {{ $tab === 'all'
                    ? 'text-blue-600 border-blue-600'
                    : 'text-gray-500 border-transparent hover:text-blue-600 hover:border-blue-600' }}">
                        Semua
                    </button>
                </div>

                <!-- Date Range Picker -->
                <div x-show="$wire.tab === 'all'" x-transition class="mt-6">
                    <div class="flex flex-col sm:flex-row sm:items-end items-start gap-4">
                        <x-daterange :startDate="$startDate" :endDate="$endDate" wireStart="startDate" wireEnd="endDate"
                            id="range1" />

                        <button x-show="@this.startDate && @this.endDate"
                            @click="@this.set('startDate', null); @this.set('endDate', null); $('#range1').val('');"
                            class="px-3 py-2 bg-gray-100 text-gray-700 rounded-md text-sm hover:bg-gray-200 transition">
                            <i class="fas fa-times mr-1"></i> Reset
                        </button>
                    </div>

                </div>
            </div>

            <div class="overflow-x-auto w-full">
                <table class="table w-full text-sm text-center border border-[#578FCA]">
                    <thead class="bg-[#578FCA] bg-opacity-90 text-white">
                        <tr>
                            <th class="py-3 px-4">
                                No
                            </th>
                            <x-sortable-column field="nomor_antrian" :currentField="$sortField" :currentDirection="$sortDirection"
                                label="No Antrian" />
                            <x-sortable-column field="pasien_id" :currentField="$sortField" :currentDirection="$sortDirection"
                                label="Nama Pasien" />
                            <x-sortable-column field="layanan_id" :currentField="$sortField" :currentDirection="$sortDirection"
                                label="Jenis Layanan" />
                            <x-sortable-column field="created_at" :currentField="$sortField" :currentDirection="$sortDirection"
                                label="Tanggal Kunjungan" />
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pendaftaran as $index => $p)
                            <tr class="hover:bg-[#f3eaff] transition-all" wire:key="user-{{ $p->id }}">
                                <td class="py-2 px-4 border border-gray-300">
                                    {{ $pendaftaran->firstItem() + $index }}
                                </td>
                                <td class="py-2 px-4 border border-gray-300">
                                    <div class="font-medium text-gray-800">{{ $p->nomor_antrian }}</div>
                                    <div class="text-xs text-gray-500">{{ $p->kode_pendaftaran }}</div>
                                </td>
                                <td class="py-2 px-4 border border-gray-300">
                                    <div class="font-medium text-gray-800">{{ $p->pasien->nama_lengkap }}</div>
                                    <div class="text-xs text-gray-500">{{ $p->pasien->nomor_rm }}</div>
                                </td>
                                <td class="py-2 px-4 border border-gray-300">
                                    <div class="font-medium text-gray-800">{{ $p->layanan->nama }}</div>
                                    <div class="text-xs text-gray-500">{{ $p->layananDetail->nama_layanan }}</div>
                                </td>
                                <td class="py-2 px-4 border border-gray-300">
                                    {{ \Carbon\Carbon::parse($p->tanggal_kunjungan)->locale('id')->isoFormat('D MMMM Y') }}
                                </td>

                                <td class="py-2 px-4 border border-gray-300">
                                    @if ($p->status === 'menunggu')
                                        <span
                                            class="px-2 py-1 text-xs rounded-full bg-yellow-500 text-white">Menunggu</span>
                                    @elseif ($p->status === 'diperiksa')
                                        <span
                                            class="px-2 py-1 text-xs rounded-full bg-blue-500 text-white">Diperiksa</span>
                                    @elseif ($p->status === 'selesai')
                                        <span
                                            class="px-2 py-1 text-xs rounded-full bg-green-500 text-white">Selesai</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-400 text-white">Status Tidak
                                            Dikenal</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 border border-gray-300 text-center">
                                    <div class="overflow-visible">

                                        <div x-data="{ open: false }" class="relative inline-block text-left">
                                            <button @click="open = !open"
                                                class="bg-blue-500 hover:bg-blue-900 text-white px-3 py-2 rounded text-sm flex items-center gap-1">
                                                Detail
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>

                                            <div x-show="open" @click.away="open = false"
                                                class="absolute z-10 mt-2 w-36 bg-white border border-gray-200 rounded shadow-lg overflow-hidden text-sm text-left">
                                                @if ($p->status === 'menunggu')
                                                    <button wire:click="panggilPasien({{ $p->id }})"
                                                        class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                                        <i class="fas fa-bullhorn mr-1 text-purple-500"></i> Panggil
                                                    </button>
                                                @endif
                                                <button wire:click="editPendaftaran({{ $p->id }})"
                                                    class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                                    <i class="fas fa-edit mr-1 text-yellow-500"></i> Edit
                                                </button>
                                                <button wire:click="openDeleteModal({{ $p->id }})"
                                                    class="w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600">
                                                    <i class="fas fa-trash-alt mr-1"></i> Hapus
                                                </button>
                                            </div>
                                        </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center border border-gray-300  text-gray-500">
                                    Tidak ada data ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $pendaftaran->links('vendor.livewire.tailwind') }}
            </div>
        </div>
    </div>

    <x-modal wire:model="isModalOpen" wire:key="modal-{{ $pendaftaranId }}" class="max-h-[90vh]">
        @php
            $warna = 'bg-gray-100 text-gray-400';
            $tampilNomor = '---';
            $tampilReg = '-';

            if ($noAntri) {
                $tampilNomor = $noAntri;
                $tampilReg = $kodePendaftaran;

                switch ((int) $layananId) {
                    case 1:
                        $warna = 'bg-blue-100 text-blue-700';
                        break;
                    case 2:
                        $warna = 'bg-purple-100 text-purple-700';
                        break;
                    default:
                        $warna = 'bg-gray-100 text-gray-700';
                        break;
                }
            }
        @endphp

        <!-- Header -->
        <div class="sticky top-0 z-10 bg-white px-6 pt-4 pb-2">
            <p class="uppercase text-sm font-bold text-gray-400 mb-2 text-center">
                {{ $pendaftaranId ? 'Edit Pendaftaran' : 'Tambah Pendaftaran' }}
            </p>
            <div class="w-full rounded-t-xl {{ $warna }} px-6 py-4 text-center">
                <p class="uppercase text-sm font-semibold mb-1">Nomor Antrian</p>
                <div class="text-3xl font-bold uppercase">{{ $tampilNomor }}</div>
                <div class="text-[8px] font-semibold uppercase mt-2">{{ $tampilReg }}</div>
            </div>
        </div>

        <!-- Scrollable Content -->
        <div class="overflow-y-auto px-6 py-2 max-h-[60vh]">
            <form wire:submit="savePendaftaran" class="space-y-4">
                <input type="hidden" wire:model.live="noAntri">
                <input type="hidden" wire:model="kodePendaftaran">

                <div class="space-y-4">
                    <div>
                        <label for="tgl_kunjungan" class="block text-sm font-medium text-gray-700">Tanggal
                            Kunjungan</label>
                        <input type="date" wire:model="tglKunjungan" id="tgl_kunjungan"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('tglKunjungan')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="id_pasien" class="block text-sm font-medium text-gray-700 mb-1">Pasien</label>
                        <div wire:ignore>
                            <select wire:model="pasienId" id="id_pasien"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">-- Pilih Pasien --</option>
                                @foreach ($patients as $pasien)
                                    <option value="{{ $pasien->id }}">
                                        ({{ $pasien->nomor_rm }})
                                        {{ $pasien->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('pasienId')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Kolom 2 -->
                <div class="space-y-4">
                    <div>
                        <label for="id_layanan" class="block text-sm font-medium text-gray-700">Jenis
                            Layanan</label>
                        <select wire:model.live="layananId" id="id_layanan"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">-- Pilih Layanan --</option>
                            @foreach ($daftarLayanan as $layanan)
                                <option value="{{ $layanan->id }}">{{ $layanan->nama }}</option>
                            @endforeach
                        </select>
                        @error('layananId')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="layanan_detail_id" class="block text-sm font-medium text-gray-700">Detail
                            Layanan</label>
                        <select wire:model="layananDetailId" id="layanan_detail_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            @disabled(empty($layananId))>
                            <option value="">-- Pilih Detail --</option>
                            @foreach ($daftarDetailLayanan as $detail)
                                <option value="{{ $detail->id }}" @selected($detail->id == $layananDetailId)>
                                    {{ $detail->nama_layanan }}
                                </option>
                            @endforeach
                        </select>
                        @error('layananDetailId')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div>
                    <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan</label>
                    <input wire:model="catatan" type="text" id="catatan"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('catatan')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <!-- Data Penanggung Jawab - Full Width -->
                <div class="border-t pt-4 mt-2">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Data Penanggung Jawab <span
                            class="text-gray-400">(Opsional)</span></h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nama_penanggungjawab"
                                class="block text-sm font-medium text-gray-700">Nama</label>
                            <input wire:model="namaPj" type="text" id="nama_penanggungjawab"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="nomor_penanggungjawab" class="block text-sm font-medium text-gray-700">Nomor
                                HP</label>
                            <input wire:model="kontakPj" type="text" id="nomor_penanggungjawab"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                    </div>
                </div>
            </form>
        </div>
        @if ($pendaftaranId && $pendaftaranDetail)
            <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                <div class="flex flex-col space-y-1 text-sm text-gray-600">
                    @if ($pendaftaranDetail->created_at)
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>
                                Dibuat: <span
                                    class="font-medium">{{ $pendaftaranDetail->createdBy->name ?? '-' }}</span>
                                pada {{ $pendaftaranDetail->created_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    @endif

                    @if ($pendaftaranDetail->updated_at && $pendaftaranDetail->updated_at != $pendaftaranDetail->created_at)
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span>
                                Diubah: <span
                                    class="font-medium">{{ $pendaftaranDetail->updatedBy->name ?? '-' }}</span>
                                pada {{ $pendaftaranDetail->updated_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Footer Sticky -->
        <div class="sticky bottom-0 bg-white border-t px-6 py-3 mt-2">
            <div class="flex justify-end space-x-3">
                <button type="button" wire:click="closeModal"
                    class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Batal
                </button>
                <button wire:click="savePendaftaran"
                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ $pendaftaranId ? 'Update' : 'Simpan' }}
                </button>
            </div>
        </div>
    </x-modal>

    <x-modal wire:model="isDeleteModalOpen" title="Hapus Data Pendaftaran">
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
                    Apakah Anda yakin ingin menghapus Data Pendaftaran <strong>{{ $noAntri }}</strong> dengan Kode
                    <strong>{{ $kodePendaftaran }}</strong>? Data yang sudah dihapus tidak dapat dikembalikan.
                </p>
            </div>
        </div>

        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
            <button type="button" wire:click="deletePendaftaran"
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
        function initSelect2Pasien() {
            const $select = $('#id_pasien');
            const livewireValue = @this.get('pasienId');

            // Hancurkan instance Select2 sebelumnya jika sudah terinisialisasi
            if ($.fn.select2 && $select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }

            // Inisialisasi Select2 normal tanpa tombol tambah
            $select.select2({
                width: '100%',
                placeholder: 'Pilih Pasien',
                allowClear: true,
                language: {
                    noResults: () => "Tidak ada data ditemukan."
                }
            });

            // Styling Select2 secara manual
            $('#id_pasien + .select2 .select2-selection').css({
                width: '100%',
                height: '2.3rem',
                padding: '0.5rem 0.75rem',
                fontSize: '0.875rem',
                display: 'flex',
                border: '1px solid #d1d5db',
                alignItems: 'center'
            });

            // Set nilai jika ada dari Livewire
            if (livewireValue) {
                if (!$select.find(`option[value="${livewireValue}"]`).length) {
                    $select.append(new Option(livewireValue, livewireValue, true, true));
                }
                $select.val(livewireValue).trigger('change');
            }

            // Bind perubahan ke Livewire
            $select.on('change', function() {
                const selectedId = $(this).val();
                const selectedOption = $(this).find(':selected');

                @this.set('pasienId', selectedId);
            });
        }


        document.addEventListener("DOMContentLoaded", function() {
            initSelect2Pasien();
        });

        document.addEventListener("livewire:navigated", function() {
            initSelect2Pasien();    
            Livewire.on('editPendaftaran', (data) => {
                initSelect2Pasien();
            });
        });

        Livewire.on('editPendaftaran', (data) => {
            initSelect2Pasien();
        });
    </script>
@endpush
