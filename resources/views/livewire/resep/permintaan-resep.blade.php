<div>
    <!-- Breadcrumbs -->
    <div class="text-sm breadcrumbs">
        <div class="text-sm px-4 py-2 rounded-t-lg w-max bg-[#578FCA] text-white">
            <a href="{{ route('dashboard') }}" class="hover:underline">Fayrooz</a>
            <span class="mx-1">></span>
            <a href="{{ route('permintaan-resep') }}" class="hover:underline">Permintaan Resep</a>
        </div>
    </div>

    <!-- Konten -->
    <div class="bg-white p-6 rounded-lg rounded-tl-none shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#5e4a7e]">Permintaan Resep</h2>
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
            <div class="mb-6 border-b border-gray-200">
                <div class="inline-flex border-b border-gray-200">
                    <button wire:click="$set('activeTab', 'obat')"
                        class="inline-block px-4 py-2 text-sm font-medium rounded-t-lg border-b-2 transition
                            {{ $activeTab === 'obat'
                                ? 'text-blue-600 border-blue-600'
                                : 'text-gray-500 border-transparent hover:text-blue-600 hover:border-blue-600' }}">
                        Resep Obat
                    </button>
                    <button wire:click="$set('activeTab', 'produk')"
                        class="inline-block px-4 py-2 text-sm font-medium rounded-t-lg border-b-2 transition
                            {{ $activeTab === 'produk'
                                ? 'text-blue-600 border-blue-600'
                                : 'text-gray-500 border-transparent hover:text-blue-600 hover:border-blue-600' }}">
                        Resep Produk Kecantikan
                    </button>
                </div>

            </div>

            @if ($activeTab === 'obat')
                <div class="overflow-x-auto w-full">
                    <table class="table w-full text-sm text-center border border-[#578FCA]">
                        <thead class="bg-[#578FCA] bg-opacity-90 text-white">
                            <tr>
                                <th class="py-3 px-4 ">
                                    No
                                </th>
                                <th class="py-3 px-4 ">
                                    Nama Pasien

                                </th>
                                <th class="py-3 px-4 ">
                                    Jenis Layanan

                                </th>
                                <th class="py-3 px-4 ">
                                    Status

                                </th>
                                <th class="py-3 px-4 ">
                                    Lihat Resep

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($histories as $h)
                                <tr class="hover:bg-[#f3eaff] transition-all">
                                    <td class="py-2 px-4 border border-gray-300">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-4 border border-gray-300">
                                        <div class="font-medium text-gray-800">{{ $h->pasien->nama_lengkap }}</div>
                                        <div class="text-xs text-gray-500">{{ $h->pasien->nomor_rm }}</div>
                                    </td>
                                    <td class="py-2 px-4 border border-gray-300">
                                        <div class="font-medium text-gray-800">{{ $h->pendaftaran->layanan->nama }}</div>
                                        <div class="text-xs text-gray-500">{{ $h->pendaftaran->layananDetail->nama_layanan }}</div>
                                    </td>
                                    <td class="py-2 px-4 border border-gray-300">
                                        @php
                                            $status = $h->resepPasien->first()->status ?? '-';
                                            $colorClass = match ($status) {
                                                'permintaan' => 'bg-blue-500 text-white',
                                                'dikonfirmasi' => 'bg-green-500 text-white',
                                                default => 'bg-gray-100 text-gray-800',
                                            };
                                        @endphp

                                        <span class="px-2 py-1 rounded-full text-sm {{ $colorClass }}">
                                            {{ $status }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-4 border border-gray-300">
                                        <div class="flex justify-center gap-2">
                                            <a wire:navigate.hover
                                                href="{{ route('permintaan-resep-detail', ['id' => $h->id]) }}"
                                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm flex items-center gap-1"
                                                title="Periksa Umum">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-gray-500">Tidak ada permintaan
                                        resep.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>

                </div>
            @elseif ($activeTab === 'produk')
                <div class="overflow-x-auto w-full">
                    <table class="table w-full text-sm text-center border border-[#578FCA]">
                        <thead class="bg-[#578FCA] bg-opacity-90 text-white">
                            <tr>
                                <th class="py-3 px-4">
                                    No
                                </th>
                                <th class="py-3 px-4">
                                    Nama Pasien
                                </th>
                                <th class="py-3 px-4">
                                    Jenis Layanan
                                </th>
                                <th class="py-3 px-4">
                                    Status
                                </th>
                                <th class="py-3 px-4">
                                    Lihat Resep
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($historiesBc as $h)
                                <tr class="hover:bg-[#f3eaff] transition-all">
                                    <td class="py-2 px-4 border border-gray-300">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-4 border border-gray-300">
                                        <div class="font-medium text-gray-800">{{ $h->pasien->nama_lengkap }}</div>
                                        <div class="text-xs text-gray-500">{{ $h->pasien->nomor_rm }}</div>
                                    </td>
                                    <td class="py-2 px-4 border border-gray-300">
                                        <div class="font-medium text-gray-800">{{ $h->pendaftaran->layanan->nama }}</div>
                                        <div class="text-xs text-gray-500">{{ $h->pendaftaran->layananDetail->nama_layanan }}</div>
                                    </td>
                                    <td class="py-2 px-4 border border-gray-300">
                                        @php
                                            $status = $h->resepProdukBc->first()->status ?? '-';
                                            $colorClass = match ($status) {
                                                'permintaan' => 'bg-blue-500 text-white',
                                                'dikonfirmasi' => 'bg-green-500 text-white',
                                                default => 'bg-gray-100 text-gray-800',
                                            };
                                        @endphp

                                        <span class="px-2 py-1 rounded-full text-sm {{ $colorClass }}">
                                            {{ $status }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-4 border border-gray-300">
                                        <div class="flex justify-center gap-2">
                                            <a wire:navigate.hover
                                                href="{{ route('permintaan-produkbc-detail', ['id' => $h->id]) }}"
                                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm flex items-center gap-1"
                                                title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3 border border-gray-300 text-gray-500">
                                        Tidak ada permintaan
                                        resep.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>

                </div>
            @endif
            <div class="mt-4">
                {{ $histories->links('vendor.livewire.tailwind') }}

            </div>
        </div>
    </div>
</div>
