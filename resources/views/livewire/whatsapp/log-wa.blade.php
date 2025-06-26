<div>
    <!-- Breadcrumbs -->
    <div class="text-sm breadcrumbs">
        <ul class="bg-[#3b82f6] px-4 py-2 rounded-t-lg w-max text-white">
            <li>
                <a href="/dashboard" class="text-white">Fayrooz > Log WhatsApp</a>
            </li>
        </ul>
    </div>

    <!-- Konten -->
    <div class="bg-white p-6 rounded-lg rounded-tl-none shadow border border-[#3b82f6]">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#5e4a7e]">Log WhatsApp</h2>
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
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari (ID, Nama, Nomor)"
                        class="input input-bordered w-full max-w-xs rounded-lg" />
                </div>
            </div>

            <!-- Tab Navigation dengan Style -->
            <div class="mb-6 border-b border-gray-200">
                <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500">
                    <li class="mr-2">
                        <button wire:click="$set('activeTab', 'umum')"
                            class="@if ($activeTab === 'umum') text-blue-600 border-b-2 border-blue-600 @endif inline-block px-4 py-2 rounded-t-lg hover:text-blue-600 hover:border-blue-600 transition-colors">
                            Rekmed Umum
                        </button>
                    </li>
                    <li>
                        <button wire:click="$set('activeTab', 'bc')"
                            class="@if ($activeTab === 'bc') text-blue-600 border-b-2 border-blue-600 @endif inline-block px-4 py-2 rounded-t-lg hover:text-blue-600 hover:border-blue-600 transition-colors">
                            Rekmed BC
                        </button>
                    </li>
                </ul>
            </div>

            <div class="overflow-hidden rounded-lg">
                <table class="table w-full text-sm text-center border border-[#5e4a7e]">
                    <thead class="bg-[#3b82f6] bg-opacity-90 text-white">
                        <tr>
                            <th class="py-3 px-4 border border-[#5e4a7e]">No</th>
                            <th class="py-3 px-4 border border-[#5e4a7e]">ID Rekam Medis</th>
                            <th class="py-3 px-4 border border-[#5e4a7e]">Nama Pasien</th>
                            <th class="py-3 px-4 border border-[#5e4a7e]">Nomor WhatsApp</th>
                            <th class="py-3 px-4 border border-[#5e4a7e]">Waktu Kirim</th>
                            <th class="py-3 px-4 border border-[#5e4a7e]">Status</th>
                            <th class="py-3 px-4 border border-[#5e4a7e]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $index => $log)
                            <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-blue-50">
                                <td class="py-3 px-4 border border-[#5e4a7e]">{{ $loop->iteration }}</td>
                                <td class="py-3 px-4 border border-[#5e4a7e]">
                                    {{ $activeTab === 'umum' ? $log->rekmed_umum_id : $log->rekmed_bc_id }}
                                </td>
                                <td class="py-3 px-4 border border-[#5e4a7e]">
                                    {{ $log->nama_pasien ?? 'N/A' }}
                                </td>
                                <td class="py-3 px-4 border border-[#5e4a7e]">
                                    {{ $log->nomor_wa }}
                                </td>
                                <td class="py-3 px-4 border border-[#5e4a7e]">
                                    {{ $log->waktu_kirim ? \Carbon\Carbon::parse($log->waktu_kirim)->format('d M Y H:i') : '-' }}
                                </td>
                                <td class="py-3 px-4 border border-[#5e4a7e]">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs text-white
                                            {{ $log->status === 'sukses' ? 'bg-green-500' : ($log->status === 'gagal' ? 'bg-red-500' : 'bg-gray-400') }}">
                                        {{ ucfirst($log->status) }}
                                    </span>
                                </td>

                                <td class="py-3 px-4 border border-[#5e4a7e]">
                                    <div class="flex justify-center space-x-2">
                                        <button wire:click="infoDetail({{ $log->id }})"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm flex items-center gap-1"
                                            title="Info">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                        <button wire:click="openDeleteModal({{ $log->id }})"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm flex items-center gap-1"
                                            title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-4 px-4 border border-[#5e4a7e] text-gray-500">
                                    Tidak ada data log WhatsApp
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $logs->links('vendor.livewire.tailwind') }}
            </div>
        </div>
    </div>

    <x-modal wire:model="isModalOpen" title="Detail Log WhatsApp" wire:key="modal-{{ $selectedLog?->id }}"
        max-width="2xl">
        <form wire:submit.prevent="save">
            @if ($selectedLog)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700 mt-4">
                    <!-- Kolom-kolom informasi -->
                    <div>
                        <label class="font-semibold">ID Rekam Medis:</label>
                        <div class="text-gray-900">
                            {{ $activeTab === 'umum' ? $selectedLog->rekmed_umum_id ?? 'N/A' : $selectedLog->rekmed_bc_id ?? 'N/A' }}
                        </div>
                    </div>

                    <div>
                        <label class="font-semibold">Pasien:</label>
                        <div class="text-gray-900">
                            {{ $selectedLog->nama_pasien ?? 'N/A' }}
                        </div>
                    </div>

                    <div>
                        <label class="font-semibold">Nomor Tujuan:</label>
                        <div class="text-gray-900">{{ $selectedLog->nomor_wa }}</div>
                    </div>

                    <div>
                        <label class="font-semibold">Pengirim:</label>
                        <div class="text-gray-900">N/A</div>
                    </div>

                    <!-- Pesan Utama -->
                    <div class="md:col-span-2">
                        <label class="font-semibold">Pesan:</label>
                        <div class="bg-white p-3 rounded border border-blue-100 whitespace-pre-wrap text-sm">
                            {{ $selectedLog->pesan }}
                        </div>
                    </div>

                    <!-- Pesan Error (hanya tampil jika status gagal) -->
                    @if ($selectedLog->status === 'gagal' && $selectedLog->error_message)
                        <div class="md:col-span-2">
                            <label class="font-semibold text-red-600">Pesan Error:</label>
                            <div
                                class="bg-red-50 p-3 rounded border border-red-200 whitespace-pre-wrap text-sm text-red-700">
                                {{ $selectedLog->error_message }}
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="font-semibold">Status:</label>
                        <div>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                            {{ $selectedLog->status === 'sukses' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i
                                    class="fas fa-circle mr-1 text-[10px] 
                                {{ $selectedLog->status === 'sukses' ? 'text-green-500' : 'text-red-500' }}"></i>
                                {{ ucfirst($selectedLog->status) }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="font-semibold">Waktu Kirim:</label>
                        <div class="text-gray-900">
                            {{ $selectedLog->waktu_kirim ? \Carbon\Carbon::parse($selectedLog->waktu_kirim)->format('d M Y H:i') : '-' }}
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-6 flex justify-end space-x-3">
                @if ($selectedLog && $selectedLog->status === 'gagal')
                    <button wire:click="kirimUlang({{ $selectedLog->id }})" type="button"
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm">
                        Kirim Ulang
                    </button>
                @endif

                <button wire:click="$set('isModalOpen', false)" type="button"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded text-sm">
                    Tutup
                </button>
            </div>
        </form>
    </x-modal>


    <x-modal wire:model="isDeleteModalOpen" title="Hapus Log WhatsApp">
        <div class="sm:flex sm:items-start">
            <div
                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin menghapus log WhatsApp untuk nomor
                    <strong>{{ $selectedNomor }}</strong>?<br>
                    Data yang sudah dihapus tidak dapat dikembalikan.
                </p>
            </div>
        </div>

        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
            <button type="button" wire:click="deleteLog"
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
