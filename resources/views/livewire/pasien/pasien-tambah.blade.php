<div>
    {{-- Breadcrumbs --}}
    <div class="text-sm breadcrumbs">
        <ul class="bg-[#578FCA] px-4 py-2 rounded-t-lg w-max text-white">
            <a href="{{ route('dashboard') }}" class="hover:underline">Fayrooz</a>
            <span class="mx-1">></span>
            <a href="{{ route('pasien') }}" class="hover:underline">Data Pasien</a>
            <span class="mx-1">></span>
            @if ($isDokter)
                <a href="{{ route('pasien-edit', ['id' => $pasienId]) }}" class="hover:underline">Detail Pasien</a>
            @else
                @if ($pasienId)
                    <a href="{{ route('pasien-edit', ['id' => $pasienId]) }}" class="hover:underline">Edit Pasien</a>
                @else
                    <a href="{{ route('pasien-tambah') }}" class="hover:underline">Tambah Pasien</a>
                @endif
            @endif
        </ul>
    </div>

    {{-- Konten --}}
    <div class="bg-white p-6 rounded-lg rounded-tl-none shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#5e4a7e]">
                @if ($isDokter)
                    Detail Data Pasien
                @else
                    {{ $pasienId ? 'Edit Data Pasien' : 'Tambah Data Pasien' }}
                @endif
            </h2>

            {{-- Info Data Dibuat dan Diubah     --}}
            @if ($pasienId && $pasien)
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <div class="flex flex-col space-y-1 text-sm text-gray-600">
                        @if ($pasien->created_at)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>
                                    Dibuat: <span class="font-medium">{{ $pasien->createdBy->name ?? 'System' }}</span>
                                    pada {{ $pasien->created_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                        @endif

                        @if ($pasien->updated_at && $pasien->updated_at != $pasien->created_at)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span>
                                    Diubah: <span class="font-medium">{{ $pasien->updatedBy->name ?? 'System' }}</span>
                                    pada {{ $pasien->updated_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Form Data Pasien --}}
        <form wire:submit.prevent="save" x-data="{
            formValid: true,
            fields: ['nama_lengkap', 'jenis_kelamin'],
            validate() {
                this.formValid = true;
                for (const field of this.fields) {
                    const input = this.$refs[field];
                    if (!input.value) {
                        this.formValid = false;
                        input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        input.focus();
                        break;
                    }
                }
            }
        }" @submit.prevent="validate; if (formValid) $wire.save()"
            class="space-y-4">
            @csrf
            <fieldset @if ($isDokter) disabled @endif>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Rekam Medis</label>
                        <input type="text" id="nomor_rm" wire:model="noRm" readonly
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg">
                        @error('noRm')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">NIK</label>
                        <input type="text" wire:model="nik" maxlength="16"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg ">
                        @error('nik')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" wire:model.live="nama" x-ref="nama_lengkap"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg ">
                        @error('nama')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                        <select wire:model.live="jk" x-ref="jenis_kelamin"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg ">
                            <option value="">-- Pilih --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                        @error('jk')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                        <input type="text" wire:model="tempatLahir"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg ">
                        @error('tempatLahir')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                        <input type="date" wire:model="tglLahir"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg ">
                        @error('tglLahir')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Usia</label>
                        <input type="number" wire:model="usia" min="0"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg ">
                        @error('usia')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Golongan Darah</label>
                        <select wire:model="golDarah" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg ">
                            <option value="">-- Pilih --</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="AB">AB</option>
                            <option value="O">O</option>
                            <option value="-">-</option>
                        </select>
                        @error('golDarah')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea wire:model.live="alamat" rows="3" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg "></textarea>
                        @error('alamat')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
                        <input type="text" wire:model.live="noTelp"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg ">
                        @error('noTelp')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status Pernikahan</label>
                        <select wire:model="statusNikah"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg ">
                            <option value="">-- Pilih --</option>
                            <option value="belum_menikah">Belum Menikah</option>
                            <option value="menikah">Menikah</option>
                            <option value="cerai_hidup">Cerai Hidup</option>
                            <option value="cerai_mati">Cerai Mati</option>
                        </select>
                        @error('statusNikah')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Catatan Tambahan</label>
                        <textarea wire:model="catatan" rows="3" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg "></textarea>
                        @error('catatan')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-between mt-6">
                    <div>
                        <a wire:navigate.hover href="{{ route('pasien') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white px-6 py-2 rounded-lg shadow text-sm">
                            Kembali
                        </a>
                    </div>
                    @if (!$isDokter)
                        <div>
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-800 text-white px-6 py-2 rounded-lg shadow text-sm">
                                {{ $pasienId ? 'Update' : 'Simpan' }}
                            </button>
                        </div>
                    @endif
                </div>
            </fieldset>
        </form>

    </div>

</div>
