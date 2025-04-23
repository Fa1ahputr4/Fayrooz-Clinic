<div>
    <!-- Breadcrumbs -->
    <div class="text-sm breadcrumbs">
        <ul class="bg-[#3b82f6] px-4 py-2 rounded-t-lg w-max text-white">
            <li>
                <a href="/dashboard" class="text-white">Fayrooz > Layanan</a>
            </li>
        </ul>
    </div>

    <!-- Konten -->
    <div class="bg-white p-6 rounded-lg rounded-tl-none shadow border border-[#3b82f6]">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#5e4a7e]">Manajemen Layanan</h2>
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
                                Kode
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
                                Nama Layanan
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
                                Harga Layanan
                                @if ($sortField === 'harga_layanan')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#5e4a7e] cursor-pointer"
                                wire:click="sortBy('deskripsi_layanan')">
                                Deskripsi Layanan
                                @if ($sortField === 'deskripsi_layanan')
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
                        @forelse ($services as $index => $service)
                            <tr class="hover:bg-[#f3eaff] transition-all" wire:key="user-{{ $service->id }}">
                                <td class="py-2 px-4 border border-[#5e4a7e]">
                                    {{ $services->firstItem() + $index }}
                                </td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">{{ $service->kode_layanan }}</td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">{{ $service->nama_layanan }}</td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">{{ $service->layanan->nama }}</td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">{{ $service->harga_layanan }}</td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">{{ $service->deskripsi_layanan }}</td>

                                <td class="py-2 px-4 border border-[#5e4a7e]">
                                    @if ($service->is_active === 1)
                                        <span class="px-2 py-1 text-xs rounded bg-green-500 text-white">Aktif</span>
                                    @elseif ($service->is_active === 0)
                                        <span class="px-2 py-1 text-xs rounded bg-red-500 text-white">Tidak Aktif</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded bg-gray-400 text-white">Status Tidak
                                            Dikenal</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 border border-[#5e4a7e]">
                                    <div class="flex justify-center gap-2">
                                        <button wire:click="editService({{ $service->id }})"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded text-sm flex items-center gap-1"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="openDeleteModal({{ $service->id }})"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm flex items-center gap-1"
                                            title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center text-gray-500">Tidak ada data ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>

            <div class="mt-4">
                {{ $services->links('vendor.livewire.tailwind') }}
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <x-modal wire:model="isModalOpen" title="{{ $serviceId ? 'Edit Layanan' : 'Tambah Layanan' }}"
        wire:key="modal-{{ $serviceId }}">
        <form wire:submit.prevent="saveService">
            <div class="space-y-4">
                <!-- Select Jenis Layanan -->
                <div>
                    <label for="layanan_id" class="block text-sm font-medium text-gray-700">Jenis Layanan</label>
                    <select wire:model="layanan_id" id="layanan_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Pilih Jenis Layanan</option>
                        @foreach ($categories as $layanan)
                            <option value="{{ $layanan->id }}">{{ $layanan->nama }}</option>
                        @endforeach
                    </select>
                    @error('layanan_id')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Kode Layanan -->
                <div>
                    <label for="kode_layanan" class="block text-sm font-medium text-gray-700">Kode Layanan</label>
                    <input wire:model="kode_layanan" type="text" id="kode_layanan"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('kode_layanan')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Nama Layanan -->
                <div>
                    <label for="nama_layanan" class="block text-sm font-medium text-gray-700">Nama Layanan</label>
                    <input wire:model="nama_layanan" type="text" id="nama_layanan"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('nama_layanan')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Harga Layanan -->
                <div>
                    <label for="harga_layanan" class="block text-sm font-medium text-gray-700">Harga Layanan</label>
                    <div class="relative mt-1 rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">Rp</span>
                        </div>
                        <input wire:model="harga_layanan" type="number" id="harga_layanan"
                            class="block w-full rounded-md border-gray-300 pl-10 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    @error('harga_layanan')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="deskripsi_layanan" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea wire:model="deskripsi_layanan" id="deskripsi_layanan" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    @error('deskripsi_layanan')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Status -->

                <div x-data="{ is_active: @entangle('is_active') }" class="mb-4 flex items-center">
                    <label for="is_active" class="mr-4 text-sm font-medium text-gray-700">Status</label>
                
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" x-model="is_active" id="is_active" class="sr-only peer">
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-500 rounded-full peer peer-checked:bg-green-500 transition duration-300">
                        </div>
                        <div
                            class="absolute left-0.5 top-0.5 w-5 h-5 bg-white border rounded-full transition-all duration-300 peer-checked:translate-x-full peer-checked:border-white">
                        </div>
                    </label>
                
                    <span class="ml-3 text-sm font-medium"
                        :class="{ 'text-green-600': is_active, 'text-red-600': !is_active }"
                        x-text="is_active ? 'Aktif' : 'Tidak Aktif'">
                    </span>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" wire:click="closeModal"
                    class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Batal
                </button>
                <button type="submit"
                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ $serviceId ? 'Simpan Perubahan' : 'Tambah Layanan' }}
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Delete Confirmation Modal -->
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
                    Apakah Anda yakin ingin menghapus Layanan <strong>{{ $nama_layanan }}</strong> dengan Kode
                    <strong>{{ $kode_layanan }}</strong>? Data yang sudah dihapus tidak dapat dikembalikan.
                </p>
            </div>
        </div>

        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
            <button type="button" wire:click="deleteService"
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
