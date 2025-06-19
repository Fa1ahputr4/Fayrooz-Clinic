<div>
    <!-- Breadcrumbs -->
    <div class="text-sm breadcrumbs">
        <ul class="bg-[#3b82f6] px-4 py-2 rounded-t-lg w-max text-white">
            <li>
                <a href="/dashboard" class="text-white">Fayrooz > Pemeriksaan</a>
            </li>
        </ul>
    </div>

    <!-- Konten -->
    <div class="bg-white p-6 rounded-lg rounded-tl-none shadow border border-[#3b82f6]">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#5e4a7e]">Pemeriksaan</h2>
            {{-- <button wire:click.prevent="openModal"
                class="bg-blue-500 hover:bg-blue-900 text-white px-3 py-2 rounded text-sm flex items-center">Tambah
                Data</button> --}}
        </div>

        <div>
            <div class="mb-6 p-4 bg-gray-50 border border-blue-300 rounded-lg shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                    <!-- Baris 1 -->
                    <div class="flex">
                        <span class="w-40 font-medium">No. Antrian</span>
                        <span class="mr-1">:</span>
                        <span>{{ $pendaftaran->nomor_antrian }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-40 font-medium">Jenis Kelamin</span>
                        <span class="mr-1">:</span>
                        <span> {{ $pendaftaran->pasien->jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan' }}
                        </span>
                    </div>

                    <!-- Baris 2 -->
                    <div class="flex">
                        <span class="w-40 font-medium">No. Rekam Medis</span>
                        <span class="mr-1">:</span>
                        <span>{{ $pendaftaran->pasien->nomor_rm }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-40 font-medium">Usia</span>
                        <span class="mr-1">:</span>
                        <span>{{ $pendaftaran->pasien->usia }}</span>
                    </div>

                    <!-- Baris 3 -->
                    <div class="flex">
                        <span class="w-40 font-medium">Jenis Layanan</span>
                        <span class="mr-1">:</span>
                        <span>{{ $pendaftaran->layanan->nama }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-40 font-medium">Alamat</span>
                        <span class="mr-1">:</span>
                        <span>{{ $pendaftaran->pasien->alamat }}</span>
                    </div>

                    <!-- Baris 4 -->
                    <div class="flex">
                        <span class="w-40 font-medium">Nama</span>
                        <span class="mr-1">:</span>
                        <span>{{ $pendaftaran->pasien->nama_lengkap }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-40 font-medium">No. Telepon</span>
                        <span class="mr-1">:</span>
                        <span>{{ $pendaftaran->pasien->no_telepon }}</span>
                    </div>
                </div>
            </div>

            <div x-data="rekamMedis" class="mt-8 w-full">
                <!-- Navigation Tabs (lebih ringkas dengan x-for) -->
                <div class="flex bg-gray-100 rounded-t-xl overflow-hidden border border-b-0 border-gray-200 w-full">
                    <template x-for="item in tabs" :key="item">
                        <button @click="activeTab = item" class="px-5 py-3 text-sm font-medium transition duration-200"
                            :class="{
                                'bg-white text-gray-900 font-semibold border border-b-0 border-gray-200 rounded-t-xl': activeTab ===
                                    item,
                                'text-gray-500 hover:text-gray-700': activeTab !== item
                            }"
                            x-text="item.charAt(0).toUpperCase() + item.slice(1)">
                        </button>
                    </template>
                </div>

                <!-- Tab Content -->
                <div class="bg-white rounded-b-xl shadow p-6 border border-t-0 border-gray-200 w-full">
                    <div x-show="activeTab === 'riwayat'" x-cloak>
                        <div class="overflow-x-auto border rounded-md">
                            <table class="min-w-full bg-white text-sm">
                                <thead class="bg-gray-100 text-gray-700">
                                    <tr>
                                        <th class="px-4 py-2 text-left">Tanggal Kunjungan</th>
                                        <th class="px-4 py-2 text-left">Jenis Layanan</th>
                                        <th class="px-4 py-2 text-left">Diagnosa</th>
                                        <th class="px-4 py-2 text-left">Tindakan</th>
                                        <th class="px-4 py-2 text-left">Dokter</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-800">
                                    {{-- @forelse ($riwayat as $item)
                                        <tr class="border-t">
                                            <td class="px-4 py-2">{{ $item->tanggal->format('d-m-Y') }}</td>
                                            <td class="px-4 py-2">{{ $item->keluhan }}</td>
                                            <td class="px-4 py-2">{{ $item->diagnosa }}</td>
                                            <td class="px-4 py-2">{{ $item->tindakan }}</td>
                                            <td class="px-4 py-2">{{ $item->dokter->nama ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-4 text-center text-gray-500">Belum ada
                                                riwayat kunjungan.</td>
                                        </tr>
                                    @endforelse --}}
                                </tbody>
                            </table>
                        </div>

                    </div>


                    <div x-show="activeTab === 'anamnesis'" x-cloak>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Form Anamnesis</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Keluhan Utama -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Keluhan Utama</label>
                                <textarea x-model="anamnesisData.keluhanUtama"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 h-24 resize-none"
                                    placeholder="Contoh: Nyeri perut sejak 2 hari lalu..."></textarea>
                            </div>

                            <!-- Riwayat Penyakit Sekarang -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Riwayat Penyakit Sekarang</label>
                                <textarea x-model="anamnesisData.riwayatSekarang"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 h-24 resize-none"
                                    placeholder="Ceritakan kronologi keluhan pasien..."></textarea>
                            </div>

                            <!-- Riwayat Penyakit Dahulu -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Riwayat Penyakit Dahulu</label>
                                <textarea x-model="anamnesisData.riwayatDahulu"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 h-24 resize-none"
                                    placeholder="Penyakit yang pernah diderita sebelumnya..."></textarea>
                            </div>

                            <!-- Riwayat Pengobatan -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Riwayat Pengobatan</label>
                                <textarea x-model="anamnesisData.riwayatPengobatan"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 h-24 resize-none"
                                    placeholder="Pengobatan yang pernah atau sedang dijalani..."></textarea>
                            </div>

                            <!-- Riwayat Alergi -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Riwayat Alergi</label>
                                <textarea x-model="anamnesisData.riwayatAlergi"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 h-24 resize-none" placeholder="Contoh: Alergi obat sulfa"></textarea>
                            </div>

                            <!-- Riwayat Penyakit Keluarga -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Riwayat Penyakit Keluarga</label>
                                <textarea x-model="anamnesisData.riwayatKeluarga"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 h-24 resize-none"
                                    placeholder="Contoh: Ayah menderita diabetes mellitus..."></textarea>
                            </div>

                            <!-- Riwayat Pribadi & Sosial (Full Width) -->
                            <div class="flex flex-col md:col-span-2">
                                <label class="text-gray-700 font-semibold mb-1">Riwayat Pribadi & Sosial</label>
                                <textarea x-model="anamnesisData.riwayatSosial"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 h-24 resize-none"
                                    placeholder="Kebiasaan merokok, konsumsi alkohol, aktivitas fisik, dll..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div x-show="activeTab === 'pemeriksaan'" x-cloak>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Pemeriksaan Dokter</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <!-- Tekanan Darah -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Tekanan Darah (mmHg)</label>
                                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2"
                                    placeholder="Contoh: 120/80" x-model="pemeriksaanData.tekananDarah">
                            </div>

                            <!-- Nadi -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Nadi (x/menit)</label>
                                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2"
                                    placeholder="Contoh: 78" x-model="pemeriksaanData.denyutNadi">
                            </div>

                            <!-- Suhu Tubuh -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Suhu Tubuh (°C)</label>
                                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2"
                                    placeholder="Contoh: 36.8" x-model="pemeriksaanData.suhuTubuh">
                            </div>

                            <!-- Pernapasan -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Laju Pernapasan (x/menit)</label>
                                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2"
                                    placeholder="Contoh: 18" x-model="pemeriksaanData.respirasi">
                            </div>

                            <!-- Berat Badan -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Berat Badan (kg)</label>
                                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2"
                                    placeholder="Contoh: 65" x-model="pemeriksaanData.bB">
                            </div>

                            <!-- Tinggi Badan -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Tinggi Badan (cm)</label>
                                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2"
                                    placeholder="Contoh: 170" x-model="pemeriksaanData.tb">
                            </div>
                        </div>

                        <!-- Pemeriksaan Fisik Umum -->
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-1">Pemeriksaan Fisik Umum</label>
                            <textarea class="w-full border border-gray-300 rounded-md px-3 py-2 resize-none" rows="4"
                                placeholder="Tuliskan hasil pemeriksaan fisik umum..." x-model="pemeriksaanData.pemeriksaanFisik"></textarea>
                        </div>

                        <!-- Pemeriksaan Khusus -->
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-1">Pemeriksaan Khusus (Jika
                                Ada)</label>
                            <textarea class="w-full border border-gray-300 rounded-md px-3 py-2 resize-none" rows="3"
                                placeholder="Contoh: Pemeriksaan neurologi, kardiologi, dll..."></textarea>
                        </div>
                    </div>

                    <div x-show="tab === 'diagnosa'" x-cloak>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Diagnosa Pasien</h3>

                        <div class="mb-4">
                            <!-- Diagnosa Utama -->
                            <div class="flex flex-col mb-4">
                                <label class="text-gray-700 font-semibold mb-1">Diagnosa Utama</label>
                                <select id="diagnosa_utama" class="w-full border border-gray-300 rounded-md px-3 py-2"
                                    style="height: 40px">
                                    <option value="">Pilih Diagnosa</option>
                                    <option value="DBD">Demam Berdarah Dengue</option>
                                    <option value="ISPA">Infeksi Saluran Pernapasan Akut</option>
                                    <option value="TBC">Tuberkulosis</option>
                                    <option value="Hipertensi">Hipertensi</option>
                                    <option value="Diabetes">Diabetes Mellitus</option>
                                    <!-- Tambahkan lebih banyak sesuai kebutuhan -->
                                </select>
                                <textarea name="keterangan_diagnosa" id="keterangan_diagnosa" class="w-full border border-gray-300 px-3 py-2"></textarea>
                            </div>

                            <!-- Diagnosa Tambahan / Komorbid -->
                            <div class="flex flex-col md:col-span-2">
                                <label class="text-gray-700 font-semibold mb-1">Diagnosa Tambahan (Jika
                                    Ada)</label>
                                <select id="diagnosa_tambahan"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2" style="height: 40px">
                                    <option value="">Pilih Diagnosa</option>
                                    <option value="DBD">Demam Berdarah Dengue</option>
                                    <option value="ISPA">Infeksi Saluran Pernapasan Akut</option>
                                    <option value="TBC">Tuberkulosis</option>
                                    <option value="Hipertensi">Hipertensi</option>
                                    <option value="Diabetes">Diabetes Mellitus</option>
                                    <!-- Tambahkan lebih banyak sesuai kebutuhan -->
                                </select>
                                <textarea name="keterangan_diagnosa" id="keterangan_diagnosa" class="w-full border border-gray-300 px-3 py-2"></textarea>
                            </div>
                        </div>
                    </div>

                    <div x-show="tab === 'tindakan & resep'" x-cloak>
                        <label class="block text-gray-700 font-semibold mb-1">Tindakan</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <!-- Tindakan Utama -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Tindakan Utama</label>
                                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2"
                                    placeholder="Contoh: Nebulizer, Injeksi">
                            </div>

                            <!-- Alat yang Digunakan -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Alat yang Digunakan</label>
                                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2"
                                    placeholder="Contoh: Spuit, Masker Oksigen">
                            </div>

                            <!-- Lokasi Tindakan -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Lokasi Tindakan</label>
                                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2"
                                    placeholder="Contoh: Lengan kiri, dada, punggung">
                            </div>

                            <!-- Reaksi Pasien -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Respon/Reaksi Pasien</label>
                                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2"
                                    placeholder="Contoh: Tidak ada keluhan, nyeri ringan">
                            </div>
                        </div>

                        <!-- Catatan Tambahan -->
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-1">Catatan Tambahan</label>
                            <textarea class="w-full border border-gray-300 rounded-md px-3 py-2 resize-none" rows="4"
                                placeholder="Tambahkan catatan bila ada..."></textarea>
                        </div>
                        <div class="mt-3">
                            <label class="block text-gray-700 font-semibold mb-1">Resep</label>
                            <table class="w-full border text-sm mb-4">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="p-2 border">Nama Obat</th>
                                        <th class="p-2 border">Jumlah</th>
                                        <th class="p-2 border">Aturan Pakai</th>
                                        <th class="p-2 border"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Baris Input --}}
                                    <tr>
                                        <td class="p-2 border">
                                            <select wire:model="obat"
                                                class="w-full p-1 border border-gray-400 rounded">
                                                <option value="">Pilih Obat</option>
                                                @foreach ($this->obatList as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama_barang }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="p-2 border">
                                            <input type="number" wire:model="jumlah"
                                                class="w-full p-1 border border-gray-400 rounded">
                                        </td>
                                        <td class="p-2 border">
                                            <input type="text" wire:model="aturan_pakai"
                                                class="w-full p-1 border border-gray-400 rounded">
                                        </td>
                                        <td class="p-2 border text-center">
                                            <button wire:click="tambahResep"
                                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                                Tambah
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- Data yang baru ditambahkan (belum dari database) --}}
                                    @foreach ($resepList as $index => $item)
                                        <tr>
                                            <td class="p-2 border">{{ $item['obat'] }}</td>
                                            <td class="p-2 border">{{ $item['jumlah'] }}</td>
                                            <td class="p-2 border">{{ $item['aturan_pakai'] }}</td>
                                            <td class="p-2 border text-center">
                                                <button wire:click="hapusResep({{ $index }})"
                                                    class="text-red-600 hover:underline">Hapus</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div x-show="activeTab === 'selesai'" x-cloak>
                        <h3 class="text-lg font-semibold mb-4">Review Rekam Medis (SOAP)</h3>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            <!-- Subjektif -->
                            <div class="flex flex-col">
                                <label class="block font-semibold mb-1">S - Subjektif</label>
                                <textarea x-text="generateSubjektif()" class="w-full border border-gray-300 rounded p-2 flex-grow" rows="5"
                                    readonly></textarea>
                            </div>

                            <!-- Objektif -->
                            <div class="flex flex-col">
                                <label class="block font-semibold mb-1">O - Objektif</label>
                                <textarea x-text="generateObjektif()" class="w-full border border-gray-300 rounded p-2 flex-grow" rows="5" readonly></textarea>
                            </div>

                            <!-- Asesmen -->
                            <div class="flex flex-col">
                                <label class="block font-semibold mb-1">A - Asesmen</label>
                                <textarea x-text="generateAsesmen()" class="w-full border border-gray-300 rounded p-2 flex-grow" rows="5" readonly></textarea>
                            </div>

                            <!-- Plan -->
                            <div class="flex flex-col">
                                <label class="block font-semibold mb-1">P - Plan / Tindakan</label>
                                <textarea x-text="generatePlan()" class="w-full border border-gray-300 rounded p-2 flex-grow" rows="5" readonly></textarea>
                            </div>
                        </div>

                        <div class="text-right">
                            <button wire:click="simpanRekamMedis"
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                Simpan Rekam Medis
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#diagnosa_utama , #diagnosa_tambahan').select2({
                tags: true,
                placeholder: 'Pilih atau tambahkan diagnosa',
                width: '100%',
                allowClear: true,
            });

            $('.select2-selection').css({
                'width': '100%',
                'height': '2.5rem',
                'padding': '0.5rem 0.75rem',
                'border-radius': '0.375rem',
                'font-size': '0.875rem',
                'display': 'flex',
                'align-items': 'center'
            });
        });
    </script>

    <script src="{{ asset('assets/js/rekmed-umum.js') }}"></script>
@endpush
