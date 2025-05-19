<div>
    <!-- Breadcrumbs -->
    <div class="text-sm breadcrumbs">
        <ul class="bg-[#3b82f6] px-4 py-2 rounded-t-lg w-max text-white">
            <li>
                <a href="/dashboard" class="text-white">Fayrooz > Data Pasien</a>
            </li>
        </ul>
    </div>

    <!-- Konten -->
    <div class="bg-white p-6 rounded-lg rounded-tl-none shadow border border-[#3b82f6]">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#5e4a7e]">Data Pasien</h2>
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

            <div class="overflow-auto w-full rounded-lg">
                <table class="table w-full text-sm text-center border border-[#5e4a7e]">
                    <thead class="bg-[#3b82f6] bg-opacity-90 text-white">
                        <tr>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer">
                                No
                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer"
                                wire:click="sortBy('nomor_rm')">
                                Nomor Rekam medis
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
                                Nama Pasein
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
                                Jenis Kelamin
                                @if ($sortField === 'jenis_kelamin')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer" wire:click="sortBy('usia')">
                                Usia
                                @if ($sortField === 'usia')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer"
                                wire:click="sortBy('no_telepon')">
                                Nomor Telepon
                                @if ($sortField === 'no_telepon')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer" wire:click="sortBy('alamat')">
                                Alamat
                                @if ($sortField === 'alamat')
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
                        @forelse ($patients as $index => $patient)
                            <tr class="hover:bg-[#f3eaff] transition-all" wire:key="user-{{ $patient->id }}">
                                <td class="py-2 px-4 border border-[#5e4a7e]">
                                    {{ $patients->firstItem() + $index }}
                                </td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">{{ $patient->nomor_rm }}</td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">{{ $patient->nama_lengkap }}</td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">{{ $patient->jenis_kelamin }}</td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">{{ $patient->usia }}</td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">{{ $patient->no_telepon }}</td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">{{ $patient->alamat }}</td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">
                                    <div class="flex justify-center gap-2">
                                        <a wire:navigate.hover href="{{ route('pasien-edit', ['id' => $patient->id]) }}"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded text-sm flex items-center gap-1"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button wire:click="openDeleteModal({{ $patient->id }})"
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
                {{ $patients->links('vendor.livewire.tailwind') }}
            </div>
        </div>
    </div>

    <x-modal wire:model="isDeleteModalOpen" title="Hapus User">
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
                    Apakah Anda yakin ingin menghapus Data Pasien <strong>{{ $nama_lengkap }}</strong> dengan Kode
                    <strong>{{ $nomor_rm }}</strong>? Data yang sudah dihapus tidak dapat dikembalikan.
                </p>
            </div>
        </div>

        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
            <button type="button" wire:click="deletePatient"
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
