<div>
    <!-- Breadcrumbs -->
    <div class="text-sm breadcrumbs">
        <ul class="bg-[#578FCA] px-4 py-2 rounded-t-lg w-max text-white">
            <li>
                <a href="/dashboard" class="text-white">Fayrooz > Barang > Riwayat Barang</a>
            </li>
        </ul>
    </div>

    <!-- Konten -->
    <div class="bg-white p-6 rounded-lg rounded-tl-none shadow border border-[#578FCA]">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#5e4a7e]">Riwayat {{ $rak->nama_rak }}</h2>
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
                        class="border border-gray-400 px-3 py-1.5 rounded-full w-full" />
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="mb-4">
                <div class="inline-flex border-b border-gray-200">
                    <button wire:click="$set('tab', 'barangMasuk')"
                        class="inline-block px-4 py-2 text-sm font-medium rounded-t-lg border-b-2 transition 
                        {{ $tab === 'barangMasuk'
                            ? 'text-blue-600 border-blue-600'
                            : 'text-gray-500 border-transparent hover:text-blue-600 hover:border-blue-600' }}">
                        Barang Masuk
                    </button>
                    <button wire:click="$set('tab', 'barangKeluar')"
                        class="inline-block px-4 py-2 text-sm font-medium rounded-t-lg border-b-2 transition 
                        {{ $tab === 'barangKeluar'
                            ? 'text-blue-600 border-blue-600'
                            : 'text-gray-500 border-transparent hover:text-blue-600 hover:border-blue-600' }}">
                        Barang Keluar
                    </button>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="overflow-x-auto w-full">
                @if ($tab === 'barangMasuk')
                    {{-- TABEL BARANG MASUK --}}
                    <table class="table w-full text-sm text-center border border-[#578FCA]">
                        <thead class="bg-[#578FCA] bg-opacity-90 text-white">
                            <tr>
                                <th class="py-3 px-4 border">No</th>
                                <th class="py-3 px-4 border">Kode Barang Masuk</th>
                                <th class="py-3 px-4 border">Sisa/Jumlah</th>
                                <th class="py-3 px-4 border">Tanggal Masuk</th>
                                <th class="py-3 px-4 border">Expired</th>
                                <th class="py-3 px-4 border">Status</th>
                                <th class="py-3 px-4 border">Dibuat Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($stokRakDetails as $index => $item)
                                <tr class="hover:bg-[#f3eaff] transition-all">
                                    <td class="py-2 px-4 border">{{ $stokRakDetails->firstItem() + $index }}</td>
                                    <td class="py-2 px-4 border">{{ $item->barang_masuk->kode_masuk }}</td>
                                    <td class="py-2 px-4 border">{{ $item->jumlah_sisa }}/{{ $item->jumlah_barang }}</td>
                                    <td class="py-2 px-4 border">{{ $item->created_at->translatedFormat('d F Y') }}</td>
                                    <td class="py-2 px-4 border">
                                        {{ $item->barang_masuk->exp_date ? \Carbon\Carbon::parse($item->barang_masuk->exp_date)->translatedFormat('d F Y') : '-' }}
                                    </td>
                                    <td class="py-2 px-4 border">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $item->status_class ?? 'bg-gray-200 text-gray-800' }}">
                                            {{ $item->status ?? 'Tidak Ada Status' }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-4 border">{{ $item->createdBy->name ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-4 text-center text-gray-500">Tidak ada data barang masuk</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @elseif ($tab === 'barangKeluar')
                    {{-- TABEL BARANG KELUAR --}}
                    <table class="table w-full text-sm text-center border border-[#578FCA]">
                        <thead class="bg-[#578FCA] bg-opacity-90 text-white">
                            <tr>
                                <th class="py-3 px-4 border">No</th>
                                <th class="py-3 px-4 border">Kode Barang Masuk</th>
                                <th class="py-3 px-4 border">Jumlah Keluar</th>
                                <th class="py-3 px-4 border">Tanggal Keluar</th>
                                <th class="py-3 px-4 border">Status</th>
                                <th class="py-3 px-4 border">Dibuat Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($barangKeluarDetails as $index => $item)
                                <tr class="hover:bg-[#f3eaff] transition-all">
                                    <td class="py-2 px-4 border">{{ $barangKeluarDetails->firstItem() + $index }}</td>
                                    <td class="py-2 px-4 border">{{ $item->stok_rak->barang_masuk->kode_masuk ?? '-' }}</td>
                                    <td class="py-2 px-4 border">{{ $item->jumlah }}</td>
                                    <td class="py-2 px-4 border">{{ $item->created_at->translatedFormat('d F Y') }}</td>
                                    <td class="py-2 px-4 border">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $item->status_keluar ?? 'Diproses' }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-4 border">{{ $item->createdBy->name ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-4 text-center text-gray-500">Tidak ada data barang keluar</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @endif

                {{-- Pagination --}}
                <div class="mt-4">
                    @if($tab === 'barangMasuk')
                        {{ $stokRakDetails->links('vendor.livewire.tailwind') }}
                    @else
                        {{ $barangKeluarDetails->links('vendor.livewire.tailwind') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>