<div>
    <!-- Breadcrumbs -->
    <div class="text-sm breadcrumbs">
        <div class="text-sm px-4 py-2 rounded-t-lg w-max bg-[#578FCA] text-white">
            <a href="{{ route('dashboard') }}" class="hover:underline">Fayrooz</a>
            <span class="mx-1">></span>
            <a href="{{ route('antrian') }}" class="hover:underline">Pemeriksaan Pasien</a>
        </div>
    </div>

    <!-- Konten -->
    <div class="bg-white p-6 rounded-lg rounded-tl-none shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#5e4a7e]">Pemeriksaan Pasien</h2>
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
            <!-- Setelah bagian tab navigation -->
            <div class="mb-4">
                <!-- Tabs -->
                {{-- <div class="inline-flex border-b border-gray-200">
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
                </div> --}}

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

            <div class="overflow-x-auto w-full" wire:poll.3s>
                <table class="table w-full text-sm text-center border border-[#578FCA]">
                    <thead class="bg-[#578FCA] bg-opacity-90 text-white">
                        <tr>
                            <th class="py-3 px-4">
                                No
                            </th>
                            <th class="py-3 px-4">
                                No Antrian
                            </th>
                            <th class="py-3 px-4">
                                Nama Pasien
                            </th>
                            <th class="py-3 px-4">
                                Jenis Layanan
                            </th>
                            <th class="py-3 px-4">
                                Tanggal Kunjungan
                            </th>
                            <th class="py-3 px-4">
                                Status
                            </th>
                            <th class="py-3 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pendaftaran as $index => $p)
                            <tr class="hover:bg-[#f3eaff] transition-all" wire:key="pendaftaran-{{ $p->id }}">
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
                                        <span class="px-2 py-1 text-xs rounded bg-yellow-500 text-white">Menunggu</span>
                                    @elseif ($p->status === 'diperiksa')
                                        <span class="px-2 py-1 text-xs rounded bg-blue-500 text-white">Diperiksa</span>
                                    @elseif ($p->status === 'selesai')
                                        <span class="px-2 py-1 text-xs rounded bg-green-500 text-white">Selesai</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded bg-gray-400 text-white">Status Tidak
                                            Dikenal</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 border border-gray-300">
                                    <div class="flex justify-center gap-2">
                                        <button wire:click="editPendaftaran({{ $p->id }})"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm flex items-center gap-1"
                                            title="Info">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                        @if (($p->status === 'diperiksa' && $p->layanan_id === 1) || $p->layanan_id === 1)
                                            <a wire:navigate.hover href="{{ route('periksa-umum', ['id' => $p->id]) }}"
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded text-sm flex items-center gap-1"
                                                title="Periksa Umum">
                                                <i class="fas fa-stethoscope"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('periksa-beautycare', ['id' => $p->id]) }}"
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded text-sm flex items-center gap-1"
                                                title="Periksa Beautycare">
                                                <i class="fas fa-stethoscope"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center text-gray-500 border border-gray-300">Belum Ada Pendaftaran Hari Ini</td>
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

    <x-modal wire:model="isModalOpen" wire:key="modal-{{ $id_pendaftaran }}">
        @php
            $warna = 'bg-gray-100 text-gray-400'; // Default abu
            $tampilNomor = '---';
            $tampilReg = '-';

            if ($nomor_antrian) {
                $tampilNomor = $nomor_antrian;
                $tampilReg = $kode_pendaftaran;

                switch ((int) $id_layanan) {
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
        <p class="uppercase text-sm font-bold text-gray-400 mb-2 text-center -mt-6">
            {{ $id_pendaftaran ? 'Edit Pendaftaran' : 'Tambah Pendaftaran' }}
        </p>
        <div class="w-full rounded-t-xl {{ $warna }} px-6 py-6 text-center ">
            <p class="uppercase text-sm font-semibold mb-2">
                Nomor Antrian
            </p>
            <div class="text-4xl font-bold uppercase">
                {{ $tampilNomor }}
            </div>
            <div class="text-[8px] font-semibold uppercase mt-4">
                {{ $tampilReg }}
            </div>
        </div>

        <!-- Form Content -->
        <form class="px-6 py-4">
            <div class="space-y-4">
                <input type="hidden" wire:model.live="nomor_antrian">
                <input type="hidden" wire:model="kode_pendaftaran">

                <div>
                    <label for="tgl_kunjungan" class="block text-sm font-medium text-gray-700">Tanggal
                        Kunjungan</label>
                    <input type="date" wire:model="tgl_kunjungan" id="tgl_kunjungan"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        disabled>
                    @error('tgl_kunjungan')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="jenis_pasien" class="block text-sm font-medium text-gray-700">Jenis Pasien</label>
                    <select wire:model.live="jenis_pasien" id="jenis_pasien"
                        class="pointer-events-none mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        disabled>
                        <option value="">-- Pilih Jenis Pasien --</option>
                        <option value="lama">Pasien Lama</option>
                        <option value="baru">Pasien Baru</option>
                    </select>
                    @error('jenis_pasien')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="id_pasien" class="block text-sm font-medium text-gray-700">Pasien</label>
                    <select wire:model="id_pasien" id="id_pasien"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        disabled>
                        <option value="">-- Pilih Pasien --</option>
                        @foreach ($patients as $pasien)
                            <option value="{{ $pasien->id }}" @if ($pasien->id == $id_pasien) selected @endif>
                                ({{ $pasien->nomor_rm }})
                                - {{ $pasien->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_pasien')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>


                <div>
                    <label for="id_layanan" class="block text-sm font-medium text-gray-700">Jenis Layanan</label>
                    <select wire:model.live="id_layanan" id="id_layanan"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        disabled <option value="">-- Pilih Layanan --</option>
                        @foreach ($daftarLayanan as $layanan)
                            <option value="{{ $layanan->id }}">{{ $layanan->nama }}</option>
                        @endforeach
                    </select>
                    @error('id_layanan')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="layanan_detail_id" class="block text-sm font-medium text-gray-700">Detail
                        Layanan</label>
                    <select wire:model="layanan_detail_id" id="layanan_detail_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        @disabled(empty($id_layanan)) disabled>
                        <option value="">-- Pilih Detail --</option>
                        @foreach ($daftarDetailLayanan as $detail)
                            <option value="{{ $detail->id }}" @selected($detail->id == $layanan_detail_id)>
                                {{ $detail->nama_layanan }}
                            </option>
                        @endforeach
                    </select>
                    @error('layanan_detail_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan</label>
                    <input wire:model="catatan" type="text" id="catatan"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        disabled>
                    @error('catatan')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" wire:click="closeModal"
                        class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Batal
                    </button>
                    {{-- <button type="submit"
                        class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        {{ $id_pendaftaran ? 'Update' : 'Tambah' }}
                    </button> --}}
                </div>
            </div>
        </form>
    </x-modal>

</div>

{{-- <div x-data="{ tab: 'riwayat' }" class="max-w-4xl mx-auto mt-8">
    <!-- Tabs -->
    <div class="flex bg-gray-100 rounded-t-xl overflow-hidden border border-b-0 border-gray-200">
        <template x-for="item in ['riwayat', 'lab', 'pemeriksaan', 'tindakan', 'resep', 'simpan']" :key="item">
            <button
                @click="tab = item"
                class="px-5 py-3 text-sm font-medium transition duration-200"
                :class="{
                    'bg-white text-gray-900 font-semibold border border-b-0 border-gray-200 rounded-t-xl': tab === item,
                    'text-gray-500 hover:text-gray-700': tab !== item
                }"
                x-text="item.charAt(0).toUpperCase() + item.slice(1)">
            </button>
        </template>
    </div>

    <!-- Tab Content -->
    <div class="bg-white rounded-b-xl shadow p-6 border border-t-0 border-gray-200">
        <div x-show="tab === 'riwayat'" x-cloak>
            <label class="block text-gray-700 font-semibold mb-1">Riwayat</label>
            <textarea class="w-full border border-gray-300 rounded-md p-2" rows="4" placeholder="Masukkan riwayat pasien..."></textarea>
        </div>

        <div x-show="tab === 'lab'" x-cloak>
            <label class="block text-gray-700 font-semibold mb-1">Hasil Lab</label>
            <textarea class="w-full border border-gray-300 rounded-md p-2" rows="4" placeholder="Masukkan hasil lab..."></textarea>
        </div>

        <div x-show="tab === 'pemeriksaan'" x-cloak>
            <label class="block text-gray-700 font-semibold mb-1">Pemeriksaan Dokter</label>
            <textarea class="w-full border border-gray-300 rounded-md p-2" rows="4" placeholder="Masukkan hasil pemeriksaan..."></textarea>
        </div>

        <div x-show="tab === 'tindakan'" x-cloak>
            <label class="block text-gray-700 font-semibold mb-1">Tindakan</label>
            <textarea class="w-full border border-gray-300 rounded-md p-2" rows="4" placeholder="Masukkan tindakan yang dilakukan..."></textarea>
        </div>

        <div x-show="tab === 'resep'" x-cloak>
            <label class="block text-gray-700 font-semibold mb-1">Resep</label>
            <textarea class="w-full border border-gray-300 rounded-md p-2" rows="4" placeholder="Masukkan resep obat..."></textarea>
        </div>

        <div x-show="tab === 'simpan'" x-cloak>
            <button class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                Simpan Rekam Medis
            </button>
        </div>
    </div>
</div> --}}
