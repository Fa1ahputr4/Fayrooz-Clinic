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
            <h2 class="text-2xl font-semibold text-[#5e4a7e]">Riwayat Masuk {{ $rak->nama_rak }}</h2>
        </div>

        <div>
            <div class="flex justify-between items-center mb-4">
                <div>
                    <select wire:model.live="perPage1" class="border rounded rounded-lg">
                        <option value="5">5 entri</option>
                        <option value="10">10 entri</option>
                        <option value="25">25 entri</option>
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
                            <th class="py-3 px-4 border">No</th>
                            <th class="py-3 px-4 border">Kode Masuk Barang</th>
                            <th class="py-3 px-4 border">Jumlah Masuk</th>
                            <th class="py-3 px-4 border">Tanggal Masuk Rak</th>
                            <th class="py-3 px-4 border">Tanggal Expired</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stokRakDetails as $index => $item)
                            <tr class="hover:bg-[#f3eaff] transition-all" wire:key="{{ $item->id }}">
                                <td class="py-2 px-4 border">
                                    {{ $stokRakDetails->firstItem() + $index }}
                                </td>
                                <td class="py-2 px-4 border">{{ $item->barang_masuk->kode_masuk }}</td>
                                <td class="py-2 px-4 border">{{ $item->jumlah_barang }}</td>
                                <td class="py-2 px-4 border">{{ $item->created_at->translatedFormat('d F Y') }}</td>
                                <td class="py-2 px-4 border">
                                    {{ $item->barang_masuk->exp_date ? \Carbon\Carbon::parse($item->exp_date)->translatedFormat('d F Y') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center text-gray-500">Tidak ada data ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $stokRakDetails->links() }}
                </div>

            </div>
        </div>
    </div>

    
</div>

@push('scripts')
@endpush
