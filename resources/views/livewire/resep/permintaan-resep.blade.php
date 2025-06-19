<div>
    <!-- Breadcrumbs -->
    <div class="text-sm breadcrumbs">
        <ul class="bg-[#3b82f6] px-4 py-2 rounded-t-lg w-max text-white">
            <li>
                <a href="/dashboard" class="text-white">Fayrooz > Permintaan Resep</a>
            </li>
        </ul>
    </div>

    <!-- Konten -->
    <div class="bg-white p-6 rounded-lg rounded-tl-none shadow border border-[#3b82f6]">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#5e4a7e]">Permintaan Resep</h2>
            {{-- <button wire:click.prevent="openModal"
                class="bg-blue-500 hover:bg-blue-900 text-white px-3 py-2 rounded text-sm flex items-center">Tambah
                Data</button> --}}
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
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer">
                                Nama Pasien

                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer">
                                Status

                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer">
                                Jenis Layanan

                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer">
                                Lihat Resep

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($histories as $h)
                            <tr class="hover:bg-[#f3eaff] transition-all">
                                <td class="py-2 px-4 border">{{ $loop->iteration }}</td>
                                <td class="py-2 px-4 border">{{ $h->pendaftaran->pasien->nama_lengkap ?? '-' }}
                                </td>
                                <td class="py-2 px-4 border">{{ $h->resepPasien->first()->status ?? '-' }}</td>
                                <td class="py-2 px-4 border">{{ $h->pendaftaran->layanan->nama ?? '-' }}</td>
                                <td class="py-2 px-4 border">
                                    <div class="flex justify-center gap-2">
                                        <a wire:navigate.hover
                                            href="{{ route('permintaan-resep-detail', ['id' => $h->id]) }}"
                                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded text-sm flex items-center gap-1"
                                            title="Periksa Umum">
                                            <i class="fas fa-stethoscope"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3 text-gray-500">Tidak ada permintaan resep.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>

            <div class="mt-4">
                {{ $histories->links('vendor.livewire.tailwind') }}

            </div>
        </div>
    </div>
</div>
