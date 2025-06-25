<div>
    <!-- Breadcrumbs -->
    <div class="text-sm breadcrumbs">
        <ul class="bg-[#3b82f6] px-4 py-2 rounded-t-lg w-max text-white">
            <li>
                <a href="/dashboard" class="text-white">Fayrooz > Rekam Medis</a>
            </li>
        </ul>
    </div>

    <!-- Konten -->
    <div class="bg-white p-6 rounded-lg rounded-tl-none shadow border border-[#3b82f6]">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#5e4a7e]">Data Rekam Medis</h2>
            <a wire:navigate.hover href="{{ route('pasien-tambah') }}"
                class="bg-blue-500 hover:bg-blue-900 text-white px-3 py-2 rounded text-sm flex items-center">
                Tambah Data
            </a>

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
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari..."
                        class="input input-bordered w-full max-w-xs rounded-full" />
                </div>
            </div>

            <div class="overflow-visible w-full rounded-lg">
                <table class="table w-full text-sm text-center border border-[#5e4a7e]">
                    <thead class="bg-[#3b82f6] bg-opacity-90 text-white">
                        <tr>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer">
                                No
                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer"
                                wire:click="sortBy('nomor_rm')">
                                Tanggal Kunjungan
                                @if ($sortField === 'nomor_rm')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer"
                                wire:click="sortBy('nama_lengkap')">
                                Jenis Layanan
                                @if ($sortField === 'nama_lengkap')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer"
                                wire:click="sortBy('jenis_kelamin')">
                                Diagnosa
                                @if ($sortField === 'jenis_kelamin')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer" wire:click="sortBy('usia')">
                                Tindakan
                                @if ($sortField === 'usia')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rekamMedis as $index => $rm)
                            <tr>
                                <td class="py-2 px-4 border border-[#5e4a7e]">
                                    {{ ($rekamMedis->firstItem() ?? 0) + $index }}
                                </td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">
                                    {{ $rm->pendaftaran && $rm->pendaftaran->tanggal_kunjungan
                                        ? \Carbon\Carbon::parse($rm->pendaftaran->tanggal_kunjungan)->format('d M Y')
                                        : '-' }}
                                </td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">
                                    {{ $rm->pendaftaran->layanandetail->nama_layanan ?? '-' }}
                                </td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">
                                    {{ $rm->diagnosa->nama ?? '-' }}
                                </td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">
                                    {{ $rm->tindakan ?? '-' }}
                                </td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('rekmed-umum-detail', $rm->id) }}"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                            Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-gray-500">
                                    Tidak ada data rekam medis.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>

            <div class="mt-4">
                                {{ $rekamMedis->links('vendor.livewire.tailwind') }}
            </div>
        </div>
    </div>
</div>
