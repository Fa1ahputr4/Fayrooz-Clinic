<div>
    <!-- Breadcrumbs -->
    <div class="text-sm breadcrumbs">
        <ul class="bg-[#3b82f6] px-4 py-2 rounded-t-lg w-max text-white">
            <li>
                <a href="/dashboard" class="text-white">Fayrooz > Pendaftaran</a>
            </li>
        </ul>
    </div>

    <!-- Konten -->
    <div class="bg-white p-6 rounded-lg rounded-tl-none shadow border border-[#3b82f6]">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#5e4a7e]">Pendaftaran</h2>
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
                <table class="table w-full text-sm text-center border border-[#5e4a7e]">
                    <thead class="bg-[#3b82f6] bg-opacity-90 text-white">
                        <tr>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer">
                                No
                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer"
                                wire:click="sortBy('kode_layanan')">
                                Kode Pendaftaran
                                @if ($sortField === 'kode_layanan')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer"
                                wire:click="sortBy('kode_layanan')">
                                No Antrian
                                @if ($sortField === 'kode_layanan')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer"
                                wire:click="sortBy('nama_layanan')">
                                Nama Pasien
                                @if ($sortField === 'nama_layanan')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer"
                                wire:click="sortBy('layanan_id')">
                                Jenis Layanan
                                @if ($sortField === 'layanan_id')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer"
                                wire:click="sortBy('harga_layanan')">
                                Tanggal Kunjungan
                                @if ($sortField === 'harga_layanan')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer"
                                wire:click="sortBy('is_active')">
                                Status
                                @if ($sortField === 'is_active')
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
                        @forelse ($pendaftaran as $index => $p)
                            <tr class="hover:bg-[#f3eaff] transition-all" wire:key="user-{{ $p->id }}">
                                <td class="py-2 px-4 border border-[#5e4a7e]">
                                    {{ $pendaftaran->firstItem() + $index }}
                                </td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">{{ $p->kode_pendaftaran }}</td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">{{ $p->nomor_antrian }}</td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">
                                    {{ $p->pasien->nomor_rm . ' - ' . $p->pasien->nama_lengkap }}
                                </td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">{{ $p->layanan->nama }}</td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">
                                    {{ \Carbon\Carbon::parse($p->tanggal_kunjungan)->translatedFormat('d F Y') }}
                                </td>

                                <td class="py-2 px-4 border border-[#5e4a7e]">
                                    @if ($p->status === 'menunggu')
                                        <span class="px-2 py-1 text-xs rounded bg-yellow-500 text-white">Dalam
                                            Antrean</span>
                                    @elseif ($p->status === 'diperiksa')
                                        <span class="px-2 py-1 text-xs rounded bg-blue-500 text-white">Diperiksa</span>
                                    @elseif ($p->status === 'selesai')
                                        <span class="px-2 py-1 text-xs rounded bg-green-500 text-white">Selesai</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded bg-gray-400 text-white">Status Tidak
                                            Dikenal</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">
                                    <div class="flex justify-center gap-2">
                                        <button wire:click="editPendaftaran({{ $p->id }})"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded text-sm flex items-center gap-1"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="openDeleteModal({{ $p->id }})"
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
        <form wire:submit.prevent="savePendaftaran" class="px-6 py-4">
            <div class="space-y-4">
                <input type="hidden" wire:model.live="nomor_antrian">
                <input type="hidden" wire:model="kode_pendaftaran">

                <div>
                    <label for="tgl_kunjungan" class="block text-sm font-medium text-gray-700">Tanggal
                        Kunjungan</label>
                    <input type="date" wire:model="tgl_kunjungan" id="tgl_kunjungan"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('tgl_kunjungan')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="jenis_pasien" class="block text-sm font-medium text-gray-700">Jenis Pasien</label>
                    <select wire:model.live="jenis_pasien" id="jenis_pasien"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
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
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"  @disabled(empty($jenis_pasien))>
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
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">-- Pilih Layanan --</option>
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
                        @disabled(empty($id_layanan))>
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
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('catatan')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" wire:click="closeModal"
                        class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Batal
                    </button>
                    <button type="submit"
                        class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        {{ $id_pendaftaran ? 'Update' : 'Tambah' }}
                    </button>
                </div>
            </div>
        </form>
    </x-modal>



</div>
