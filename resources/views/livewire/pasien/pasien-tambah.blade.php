<div>
    <!-- Breadcrumbs -->
    <div class="text-sm breadcrumbs">
        <ul class="bg-[#3b82f6] px-4 py-2 rounded-t-lg w-max text-white">
            <li>
                <a href="/dashboard" class="text-white">Fayrooz > {{ $pasienId ? 'Edit Data Pasien' : 'Tambah Data Pasien' }}</a>
            </li>
        </ul>
    </div>

    <!-- Konten -->
    <div class="bg-white p-6 rounded-lg rounded-tl-none shadow border border-[#3b82f6]">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#5e4a7e]">{{ $pasienId ? 'Edit Data Pasien' : 'Tambah Data Pasien' }}</h2>
        </div>

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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nomor Rekam Medis</label>
                    <input type="text" id="nomor_rm" wire:model="nomor_rm" readonly
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg">
                    @error('nomor_rm')
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
                    <input type="text" wire:model="nama_lengkap" x-ref="nama_lengkap"
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg ">
                    @error('nama_lengkap')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <select wire:model="jenis_kelamin" x-ref="jenis_kelamin"
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg ">
                        <option value="">-- Pilih --</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                    <input type="text" wire:model="tempat_lahir"
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg ">
                    @error('tempat_lahir')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                    <input type="date" wire:model="tanggal_lahir"
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg ">
                    @error('tanggal_lahir')
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
                    <select wire:model="golongan_darah"
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg ">
                        <option value="">-- Pilih --</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="AB">AB</option>
                        <option value="O">O</option>
                        <option value="-">-</option>
                    </select>
                    @error('golongan_darah')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea wire:model="alamat" rows="3" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg "></textarea>
                    @error('alamat')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
                    <input type="text" wire:model="no_telepon"
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg ">
                    @error('no_telepon')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" wire:model="email"
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg ">
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Penanggung Jawab</label>
                    <input type="text" wire:model="nama_pj"
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg ">
                    @error('nama_pj')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Hubungan Penanggung Jawab</label>
                    <input type="text" wire:model="hubungan_pj"
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg ">
                    @error('hubungan_pj')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Kontak Penanggung Jawab</label>
                    <input type="text" wire:model="kontak_pj"
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg ">
                    @error('kontak_pj')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Status Pernikahan</label>
                    <select wire:model="status_pernikahan"
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg ">
                        <option value="">-- Pilih --</option>
                        <option value="belum_menikah">Belum Menikah</option>
                        <option value="menikah">Menikah</option>
                        <option value="cerai_hidup">Cerai Hidup</option>
                        <option value="cerai_mati">Cerai Mati</option>
                    </select>
                    @error('status_pernikahan')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Catatan</label>
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
                <div>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-800 text-white px-6 py-2 rounded-lg shadow text-sm">
                        {{ $pasienId ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </div>
        </form>

    </div>

</div>
