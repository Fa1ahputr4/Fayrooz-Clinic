<div>
    <!-- Breadcrumbs -->
    <div class="text-sm breadcrumbs">
        <div class="text-sm px-4 py-2 rounded-t-lg w-max bg-[#578FCA] text-white">
            <a href="{{ route('dashboard') }}" class="hover:underline">Fayrooz</a>
            <span class="mx-1">></span>
            <a href="{{ route('user') }}" class="hover:underline">Data User</a>
        </div>
    </div>

    <!-- Konten -->
    <div class="bg-white p-6 rounded-lg rounded-tl-none shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#5e4a7e]">Manajemen User</h2>
            <button wire:click.prevent="openModal"
                class="bg-blue-500 hover:bg-blue-900 text-white px-3 py-2 rounded text-sm flex items-center">Tambah
                Data</button>
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

            <div class="overflow-x-auto w-full">
                <table class="table w-full text-sm text-center border border-[#578FCA]">
                    <thead class="bg-[#578FCA] bg-opacity-90 text-white">
                        <tr>
                            <th class="py-3 px-4 border border-[#578FCA] cursor-pointer" wire:click="sortBy('id')">
                                ID
                                @if ($sortField === 'id')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#578FCA] cursor-pointer" wire:click="sortBy('name')">
                                Nama
                                @if ($sortField === 'name')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#578FCA] cursor-pointer"
                                wire:click="sortBy('username')">
                                Username
                                @if ($sortField === 'username')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#578FCA] cursor-pointer" wire:click="sortBy('email')">
                                Email
                                @if ($sortField === 'email')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#578FCA] cursor-pointer" wire:click="sortBy('role')">
                                Role
                                @if ($sortField === 'role')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#578FCA] cursor-pointer" wire:click="sortBy('role')">
                                Status
                                @if ($sortField === 'status')
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                            <th class="py-3 px-4 border border-[#578FCA]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $index => $user)
                            <tr class="hover:bg-[#f3eaff] transition-all" wire:key="user-{{ $user->id }}">
                                <td class="py-2 px-4 border border-gray-300">{{ $user->id }}</td>
                                <td class="py-2 px-4 border border-gray-300">{{ $user->name }}</td>
                                <td class="py-2 px-4 border border-gray-300">{{ $user->username }}</td>
                                <td class="py-2 px-4 border border-gray-300">{{ $user->email }}</td>
                                <td class="py-2 px-4 border border-gray-300">
                                    @php
                                        $roleColors = [
                                            'admin' => 'bg-blue-500 text-white',
                                            'apoteker' => 'bg-green-500 text-white',
                                            'staff' => 'bg-yellow-400 text-black',
                                            'dokter' => 'bg-purple-500 text-white',
                                            'resepsionis' => 'bg-pink-500 text-white',
                                        ];
                                        $colorClass = $roleColors[$user->role] ?? 'bg-gray-300 text-black';
                                    @endphp
                                    <span class="px-2 py-1 text-xs rounded {{ $colorClass }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>

                                <td class="py-2 px-4 border border-gray-300">
                                    @if ($user->status === 'active')
                                        <span class="px-2 py-1 text-xs rounded bg-green-500 text-white">Aktif</span>
                                    @elseif ($user->status === 'non_active')
                                        <span class="px-2 py-1 text-xs rounded bg-red-500 text-white">Tidak Aktif</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded bg-gray-400 text-white">Status Tidak
                                            Dikenal</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 border border-gray-300">
                                    <div class="flex justify-center gap-2">
                                        <button wire:click="editUser({{ $user->id }})"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded text-sm flex items-center gap-1"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="openDeleteModal({{ $user->id }})"
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
                {{ $users->links('vendor.livewire.tailwind') }}
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <x-modal wire:model="isModalOpen" title="{{ $userId ? 'Edit User' : 'Tambah User' }}"
        wire:key="modal-{{ $userId }}">
        <form wire:submit.prevent="saveUser">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" wire:model="name" id="name"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('name')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" wire:model="username" id="username"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('username')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" wire:model="email" id="email"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('email')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <select wire:model="role" id="role"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="admin">Admin</option>
                    <option value="dokter">Dokter</option>
                    <option value="resepsionis">Resepsionis</option>
                    <option value="apoteker">Apoteker</option>
                    <option value="staff">Staff</option>
                </select>
                @error('role')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" wire:model.defer="password" autocomplete="new-password" id="password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('password')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi
                    Password</label>
                <input type="password" wire:model="password_confirmation" id="password_confirmation"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div x-data="{ status: @entangle('status') }" class="mb-4 flex items-center">
                <label for="status" class="mr-4 text-sm font-medium text-gray-700">Status</label>

                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" x-model="status" id="status" class="sr-only peer">
                    <div
                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-500 rounded-full peer peer-checked:bg-green-500 transition duration-300">
                    </div>
                    <div
                        class="absolute left-0.5 top-0.5 w-5 h-5 bg-white border rounded-full transition-all duration-300 peer-checked:translate-x-full peer-checked:border-white">
                    </div>
                </label>

                <span class="ml-3 text-sm font-medium" :class="{ 'text-green-600': status, 'text-red-600': !status }"
                    x-text="status ? 'Aktif' : 'Tidak Aktif'">
                </span>
            </div>



            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="submit"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#5e4a7e] text-base font-medium text-white hover:bg-[#4b3a65] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Simpan
                </button>
                <button type="button" @click="isOpen = false" wire:click="closeModal"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
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
                    Apakah Anda yakin ingin menghapus user <strong>{{ $username }}</strong> dengan ID
                    <strong>{{ $userId }}</strong>? Data yang sudah dihapus tidak dapat dikembalikan.
                </p>
            </div>
        </div>

        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
            <button type="button" wire:click="deleteUser"
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
