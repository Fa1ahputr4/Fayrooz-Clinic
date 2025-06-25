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
            <h2 class="text-2xl font-semibold text-[#5e4a7e]">Pemeriksaan Beautycare</h2>
        </div>

        <div>
            <!-- Identitas Pasien -->
            <div class="mb-6 p-4 bg-gray-50 border border-blue-300 rounded-lg shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                    <template
                        x-for="(label, index) in [
                        'No. Antrian', 'Jenis Kelamin', 'No. Rekam Medis', 'Usia', 
                        'Jenis Layanan', 'Alamat', 'Nama', 'No. Telepon']"
                        :key="index">
                        <div class="flex">
                            <span class="w-40 font-medium" x-text="label"></span>
                            <span class="mr-1">:</span>
                            <span>--data--</span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Tab Navigasi -->
            <div x-data="{ tab: 'pemeriksaan' }" class="mt-8 w-full">
                <!-- Tab Header -->
                <div class="flex bg-gray-100 rounded-t-xl overflow-x-auto border border-b-0 border-gray-200 w-full">
                    <template x-for="item in ['pemeriksaan', 'diagnosis', 'tindakan & saran', 'dokumentasi', 'selesai']"
                        :key="item">
                        <button @click="tab = item"
                            class="px-5 py-3 text-sm font-medium transition duration-200 whitespace-nowrap"
                            :class="{
                                'bg-white text-gray-900 font-semibold border border-b-0 border-gray-200 rounded-t-xl': tab ===
                                    item,
                                'text-gray-500 hover:text-gray-700': tab !== item
                            }"
                            x-text="item.charAt(0).toUpperCase() + item.slice(1)">
                        </button>
                    </template>
                </div>

                <!-- Tab Content -->
                <div class="bg-white rounded-b-xl shadow p-6 border border-t-0 border-gray-200 w-full ">

                    <!-- Pemeriksaan -->
                    <div x-show="tab === 'pemeriksaan'" x-cloak>
                        <h3 class="text-xl font-semibold text-gray-800">Pemeriksaan Awal</h3>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div class="space-y-6 bg-white p-5 rounded-lg">
                                <!-- Keluhan Utama Section -->
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-2">Keluhan
                                            Utama</label>
                                        <div wire:ignore>
                                            <select id="keluhanUtama" multiple
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                                @foreach ($daftarKeluhan as $id => $nama)
                                                    <option value="{{ $id }}">{{ $nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <textarea wire:model.live="keteranganKeluhan"
                                            class="w-full border border-gray-300 rounded-md px-3text-sm h-24 resize-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="Deskripsikan keluhan pasien secara detail..."></textarea>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-2">Warna Kulit</label>
                                    <select wire:model.live="warnaKulit" name="warna_kulit"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Pilih Warna Kulit</option>
                                        <option>Sangat Terang</option>
                                        <option>Terang</option>
                                        <option>Sedang</option>
                                        <option>Sawo Matang</option>
                                        <option>Gelap</option>
                                    </select>
                                </div>

                                <!-- Skin Assessment Section -->
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-2">Jenis Kulit</label>
                                        <select wire:model.live="jenisKulit" name="jenis_kulit"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="">Pilih Jenis Kulit</option>
                                            <option>Normal</option>
                                            <option>Berminyak</option>
                                            <option>Kering</option>
                                            <option>Kombinasi</option>
                                            <option>Sensitif</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-2">Tekstur
                                            Kulit</label>
                                        <select wire:model.live="teksturKulit" name="tekstur_kulit"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="">Pilih Tekstur Kulit</option>
                                            <option>Halus</option>
                                            <option>Sedikit Kasar</option>
                                            <option>Kasar</option>
                                            <option>Bergelombang</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-2">Kelembapan
                                            Kulit</label>
                                        <div class="flex flex-wrap gap-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" wire:model.live="kelembapanKulit"
                                                    value="sangat_kering"
                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                                <span class="ml-2 text-sm text-gray-700">Sangat Kering</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" wire:model.live="kelembapanKulit" value="kering"
                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                                <span class="ml-2 text-sm text-gray-700">Kering</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" wire:model.live="kelembapanKulit" value="normal"
                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                                <span class="ml-2 text-sm text-gray-700">Normal</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-6 bg-white p-5 rounded-lg">
                                <!-- Jerawat Section -->
                                <div class="pt-4">
                                    <div class="flex flex-col" x-data="{
                                        init() {
                                                // Update state toggle ketika data berubah
                                                this.$watch('$wire.jenisJerawat', (value) => {
                                                    this.adaJerawat = value.length > 0 || ($wire.tingkatJerawat !== null && $wire.tingkatJerawat !== '');
                                                });
                                                this.$watch('$wire.tingkatJerawat', (value) => {
                                                    this.adaJerawat = $wire.jenisJerawat.length > 0 || (value !== null && value !== '');
                                                });
                                            },
                                            adaJerawat: $wire.jenisJerawat.length > 0 || ($wire.tingkatJerawat !== null && $wire.tingkatJerawat !== '')
                                    }">
                                        <label class="block text-sm font-medium text-gray-600 mb-2">Jerawat</label>
                                        <div class="flex items-center space-x-3">
                                            <button type="button"
                                                @click="adaJerawat = !adaJerawat; 
                                                    if (!adaJerawat) { 
                                                        $wire.set('jenisJerawat', []); 
                                                        $wire.set('tingkatJerawat', ''); 
                                                    }"
                                                :class="adaJerawat ? 'bg-indigo-600' : 'bg-gray-300'"
                                                class="relative inline-flex h-5 w-10 items-center rounded-full transition-colors duration-200 focus:outline-none">
                                                <span :class="adaJerawat ? 'translate-x-5' : 'translate-x-1'"
                                                    class="inline-block h-3 w-3 transform rounded-full bg-white transition-transform duration-200"></span>
                                            </button>
                                            <span class="text-sm text-gray-600">Pasien memiliki jerawat</span>
                                        </div>

                                        <!-- Jerawat Details -->
                                        <div x-show="adaJerawat" x-transition.opacity
                                            class="space-y-4 bg-gray-50 p-3 rounded-md mt-2">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-600 mb-2">Kondisi
                                                    Jerawat</label>
                                                <div class="grid grid-cols-2 gap-3">
                                                    <label class="inline-flex items-center space-x-2">
                                                        <input type="checkbox" wire:model="jenisJerawat" value="komedo"
                                                            class="h-4 w-4 text-indigo-600 rounded focus:ring-indigo-500">
                                                        <span class="text-sm text-gray-700">Komedo</span>
                                                    </label>
                                                    <label class="inline-flex items-center space-x-2">
                                                        <input type="checkbox" wire:model="jenisJerawat" value="papula"
                                                            class="h-4 w-4 text-indigo-600 rounded focus:ring-indigo-500">
                                                        <span class="text-sm text-gray-700">Papula</span>
                                                    </label>
                                                    <label class="inline-flex items-center space-x-2">
                                                        <input type="checkbox" wire:model="jenisJerawat"
                                                            value="pustula"
                                                            class="h-4 w-4 text-indigo-600 rounded focus:ring-indigo-500">
                                                        <span class="text-sm text-gray-700">Pustula</span>
                                                    </label>
                                                    <label class="inline-flex items-center space-x-2">
                                                        <input type="checkbox" wire:model="jenisJerawat"
                                                            value="nodul"
                                                            class="h-4 w-4 text-indigo-600 rounded focus:ring-indigo-500">
                                                        <span class="text-sm text-gray-700">Nodul</span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-600 mb-2">
                                                    Tingkat Jerawat
                                                </label>
                                                <select wire:model.live="tingkatJerawat" name="tingkat_jerawat"
                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                                    <option value="">Pilih Tingkat Jerawat</option>
                                                    <option value="Ringan">Ringan (1–5 jerawat)</option>
                                                    <option value="Sedang">Sedang (6–15 jerawat)</option>
                                                    <option value="Parah">Parah (>15 jerawat)</option>
                                                    <option value="Meradang">Jerawat Meradang</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Skin Info -->
                                <div class="space-y-4">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-600 mb-2">Flek/Hiperpigmentasi</label>
                                        <div class="flex flex-wrap gap-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" wire:model="flek" value="tidak_ada"
                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                                <span class="ml-2 text-sm text-gray-700">Tidak Ada</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" wire:model="flek" value="ringan"
                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                                <span class="ml-2 text-sm text-gray-700">Ringan</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" wire:model="flek" value="sedang"
                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                                <span class="ml-2 text-sm text-gray-700">Sedang</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="flex flex-col" x-data="{ adaAlergi: false }" x-init="adaAlergi = $wire.alergi !== null && $wire.alergi !== ''">
                                        <label class="block text-sm font-medium text-gray-600 mb-2">Alergi</label>
                                        <div class="flex items-center space-x-3 mb-2">
                                            <button type="button"
                                                @click="adaAlergi = !adaAlergi; if (!adaAlergi) $wire.set('alergi', '');"
                                                :class="adaAlergi ? 'bg-indigo-600' : 'bg-gray-300'"
                                                class="relative inline-flex h-5 w-10 items-center rounded-full transition-colors duration-200 focus:outline-none">
                                                <span :class="adaAlergi ? 'translate-x-5' : 'translate-x-1'"
                                                    class="inline-block h-3 w-3 transform rounded-full bg-white transition-transform duration-200"></span>
                                            </button>
                                            <span class="text-sm text-gray-600">Pasien memiliki alergi</span>
                                        </div>
                                        <textarea wire:model.live="alergi" x-show="adaAlergi" x-transition.opacity
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm h-20 resize-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="Jelaskan alergi pasien..."></textarea>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-2">Riwayat
                                            Penyakit</label>
                                        <input wire:model.live="riwayatPenyakit" type="text" name="penyakit"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="Contoh: diabetes, hipertensi, autoimun">
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Produk Kosmetik Terakhir
                                            Dipakai</label>
                                        <textarea wire:model.live="produkTerakhir" name="riwayat_kosmetik"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm h-20"
                                            placeholder="Daftar produk kosmetik yang baru digunakan (skincare, makeup, dll)"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Diagnosis -->
                    <div x-show="tab === 'diagnosis'" x-cloak class="space-y-4">
                        <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500 mr-2"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            Diagnosis & Analisa Kulit
                        </h3>

                        <!-- Row untuk diagnosis dan keparahan -->
                        <div class="flex flex-col md:flex-row gap-4 items-start">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Hasil Diagnosis</label>
                                <div wire:ignore>
                                    <select id="diagnosa_tambahan" multiple
                                        class="w-full border border-gray-300 rounded-md px-3 py-2">
                                        @foreach ($daftarDiagnosisUmum as $id => $nama)
                                            <option value="{{ $id }}">{{ $nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="w-full md:w-1/3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tingkat Keparahan</label>
                                <select wire:model.live="tingkatKeparahan" id="tingkat_keparahan"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2">
                                    <option value="">Pilih Tingkat Keparahan</option>
                                    <option value="ringan">Ringan</option>
                                    <option value="sedang">Sedang</option>
                                    <option value="berat">Berat</option>
                                    <option value="kritis">Kritis</option>
                                </select>
                            </div>
                        </div>

                        <!-- Textarea di bawah -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan Diagnosis</label>
                            <textarea wire:model.live="keteranganDiagnosis" name="diagnosis" rows="5"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                placeholder="Tuliskan hasil diagnosis dan analisa kondisi kulit pasien..."></textarea>
                        </div>
                    </div>

                    <!-- Tindakan & Saran -->
                    <div x-show="tab === 'tindakan & saran'" x-cloak class="space-y-6">
                        <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500 mr-2"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Tindakan & Rekomendasi
                        </h3>

                        <div x-data="{
                            kontrolUlang: @entangle('kontrolUlang'),
                            init() {
                                this.$watch('kontrolUlang', (value) => {
                                    if (!value) {
                                        $wire.set('jadwalKontrolUlang', null);
                                        $wire.set('catatanJadwal', '');
                                        $wire.set('noPasien', '');
                                    }
                                });
                            }
                        }">
                            <!-- Tindakan Section -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <!-- Left Column - Tindakan -->
                                <div class="bg-white p-4 rounded-lg border border-gray-200">
                                    <h4 class="text-sm font-medium text-gray-700 mb-3">Tindakan yang Dilakukan</h4>
                                    <div class="space-y-2">
                                        @foreach (['Facial', 'Peeling', 'Microdermabrasi', 'Mesotherapy', 'Injeksi Whitening'] as $item)
                                            <label
                                                class="flex items-center p-2 rounded-md hover:bg-indigo-50 transition cursor-pointer border border-gray-200">
                                                <input type="checkbox" value="{{ $item }}"
                                                    wire:model.live="tindakan"
                                                    class="h-4 w-4 text-indigo-600 rounded">
                                                <span class="ml-3 text-gray-700">{{ $item }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Right Column - Rekomendasi -->
                                <div class="space-y-4">
                                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan
                                            Tindakan</label>
                                        <textarea wire:model.live="keteranganTindakan" name="keterangan_tindakan" rows="3"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"></textarea>
                                    </div>

                                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Saran
                                            Treatment</label>
                                        <textarea wire:model.live="saranTreatment" name="saran" rows="3"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Kontrol Ulang Section -->
                            <div class="bg-white p-4 rounded-lg border border-gray-200 mb-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-sm font-medium text-gray-700">Jadwalkan Kontrol Ulang</h4>
                                    <button type="button"
                                        @click="kontrolUlang = !kontrolUlang; $wire.set('kontrolUlang', kontrolUlang);"
                                        :class="kontrolUlang ? 'bg-green-500' : 'bg-gray-300'"
                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors">
                                        <span :class="kontrolUlang ? 'translate-x-6' : 'translate-x-1'"
                                            class="inline-block h-4 w-4 transform rounded-full bg-white transition"></span>
                                    </button>
                                </div>

                                <div x-show="kontrolUlang" x-transition class="space-y-4 mt-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Tanggal & Waktu</label>
                                            <input wire:model.live="jadwalKontrolUlang" type="datetime-local"
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Nomor WhatsApp</label>
                                            <div class="flex space-x-2">
                                                <input wire:model.live="noPasien" type="number"
                                                    class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm"
                                                    placeholder="Contoh: 6281234567890">
                                                <button type="button" wire:click="ambilNomorPasien"
                                                    class="px-3 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition text-sm">
                                                    Ambil Nomor
                                                </button>
                                            </div>
                                            @error('noPasien')
                                                <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Catatan Kontrol</label>
                                        <input wire:model.live="catatanJadwal" type="text"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                                            placeholder="Contoh: Evaluasi pengobatan, hasil lab, dll.">
                                    </div>
                                </div>
                            </div>

                            <!-- SkinCare Prescription Section -->
                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                <div class="p-4 border-b">
                                    <h4 class="text-sm font-medium text-gray-700">Resep SkinCare</h4>
                                </div>
                                <div class="p-4">
                                    <table class="w-full text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="p-2 text-left text-gray-600 font-medium">Nama Barang</th>
                                                <th class="p-2 text-left text-gray-600 font-medium">Jumlah</th>
                                                <th class="p-2 text-left text-gray-600 font-medium">Satuan</th>
                                                <th class="p-2 text-left text-gray-600 font-medium">Aturan Pakai</th>
                                                <th class="p-2"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <!-- Input Row -->
                                            <tr>
                                                <td class="p-2">
                                                    <select wire:model.live="barang" id="resep_barang"
                                                        name="resep_barang"
                                                        class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
                                                        <option value="">Pilih Produk</option>
                                                        @foreach ($this->BarangList as $item)
                                                            <option value="{{ $item->id }}"
                                                                data-satuan="{{ $item->satuan }}">
                                                                {{ $item->nama_barang }} (Stok:
                                                                {{ $item->total_stok }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('barang')
                                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                                    @enderror
                                                </td>
                                                <td class="p-2">
                                                    <input wire:model.live="jumlahBarang" type="number"
                                                        class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
                                                    @error('jumlahBarang')
                                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                                    @enderror
                                                    @if ($jumlahBarangError)
                                                        <p class="text-xs text-red-600 mt-1">{{ $jumlahBarangError }}
                                                        </p>
                                                    @endif
                                                </td>
                                                <td class="p-2">
                                                    <input wire:model.live="satuanBarang" type="text"
                                                        class="w-full border border-gray-300 rounded px-2 py-1 text-sm"
                                                        disabled>
                                                </td>
                                                <td class="p-2">
                                                    <input wire:model.live="aturanPakai" type="text"
                                                        class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
                                                    @error('aturanPakai')
                                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                                    @enderror
                                                </td>
                                                <td class="p-2 text-right">
                                                    <button wire:click="tambahBarang"
                                                        class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                                                        Tambah
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Added Items -->
                                            @foreach ($barangList as $index => $item)
                                                <tr>
                                                    <td class="p-2">{{ $item['barang'] }}</td>
                                                    <td class="p-2">{{ $item['jumlah'] }}</td>
                                                    <td class="p-2">{{ $item['satuan'] }}</td>
                                                    <td class="p-2">{{ $item['aturan_pakai'] }}</td>
                                                    <td class="p-2 text-right">
                                                        <button wire:click="hapusBarang({{ $index }})"
                                                            class="text-red-500 hover:text-red-700 text-sm">
                                                            Hapus
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dokumentasi -->
                    <div x-show="tab === 'dokumentasi'" x-cloak class="space-y-4">
                        <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500 mr-2"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Dokumentasi Perawatan
                        </h3>

                        <!-- Modern Toast Notification -->
                        <div x-data="{ showToast: false, toastMsg: '' }" x-show="showToast" x-transition.opacity.duration.300ms
                            @toast.window="toastMsg = $event.detail.message; showToast = true; setTimeout(() => showToast = false, 5000)"
                            class="fixed top-5 right-5 z-50 w-80 max-w-full">
                            <div
                                class="bg-red-50 border border-red-200 text-red-800 rounded-lg shadow-lg p-4 flex items-start gap-3">
                                <svg class="h-6 w-6 text-red-500 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm font-medium flex-grow" x-text="toastMsg"></p>
                                <button @click="showToast = false" class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Preview Modal -->
                        <div x-data="{ previewModal: false, currentImage: '' }" x-cloak>
                            <div x-show="previewModal" @click="previewModal = false"
                                class="fixed inset-0 bg-black bg-opacity-75 z-50 transition-opacity duration-300">
                            </div>

                            <div x-show="previewModal" class="fixed inset-0 flex items-center justify-center z-50 p-4"
                                @keydown.escape.window="previewModal = false">
                                <div
                                    class="relative bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-auto shadow-xl">
                                    <button @click="previewModal = false"
                                        class="absolute top-4 right-4 bg-gray-800 text-white rounded-full p-2 z-10 hover:bg-gray-700 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                    <img :src="currentImage" class="w-full h-auto object-contain max-h-[85vh]"
                                        alt="Preview Foto">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8" x-data="{
                                beforeFiles: [],
                                afterFiles: [],
                                existingBeforePhotos: @js($foto_before ?? []),
                                existingAfterPhotos: @js($foto_after ?? []),
                                isDragging: false,
                                MAX_SIZE: 3 * 1024 * 1024,
                                ALLOWED_TYPES: ['image/jpeg', 'image/png'],
                                filterValidFiles(files) {
                                    return Array.from(files).filter(file => {
                                        const isValidSize = file.size <= this.MAX_SIZE;
                                        const isValidType = this.ALLOWED_TYPES.includes(file.type);
                            
                                        if (!isValidSize) {
                                            window.dispatchEvent(new CustomEvent('toast', {
                                                detail: { message: `File '${file.name}' melebihi batas 3MB` }
                                            }));
                                        } else if (!isValidType) {
                                            window.dispatchEvent(new CustomEvent('toast', {
                                                detail: { message: `Format file '${file.name}' tidak didukung (hanya JPG/PNG)` }
                                            }));
                                        }
                            
                                        return isValidSize && isValidType;
                                    });
                                }
                            }">

                                <!-- Before Upload Section -->
                                <div class="space-y-4"
                                    @drop.prevent="isDragging = false; beforeFiles = [...beforeFiles, ...filterValidFiles($event.dataTransfer.files)]"
                                    @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false">

                                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Before
                                        Treatment</label>

                                    <div class="relative">
                                        <div @click="$refs.beforeInput.click()"
                                            :class="isDragging ? 'border-indigo-500 bg-indigo-50' : 'border-gray-300'"
                                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed rounded-xl cursor-pointer transition-colors">
                                            <div class="space-y-1 text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="mx-auto h-12 w-12 text-gray-400" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <div class="flex text-sm text-gray-600 justify-center">
                                                    <span
                                                        class="relative font-medium text-indigo-600 hover:text-indigo-500">Upload
                                                        file</span>
                                                    <p class="pl-1">or drag and drop</p>
                                                </div>
                                                <p class="text-xs text-gray-500">PNG, JPG up to 3MB</p>
                                            </div>
                                        </div>
                                        <input wire:model.live="foto_before_upload" x-ref="beforeInput"
                                            type="file" multiple accept="image/jpeg, image/png" class="sr-only"
                                            @change="beforeFiles = [...beforeFiles, ...filterValidFiles($event.target.files)]; $event.target.value = ''">
                                    </div>


                                    <!-- Preview Before Files -->
                                    <template x-if="beforeFiles.length">
                                        <div class="grid grid-cols-3 gap-3 mt-4">
                                            <template x-for="(file, index) in beforeFiles" :key="index">
                                                <div class="relative group">
                                                    <div
                                                        class="border border-gray-200 rounded-md overflow-hidden relative">
                                                        <img :src="URL.createObjectURL(file)"
                                                            class="h-24 w-full object-cover">
                                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 flex items-center justify-center transition-all duration-300 cursor-pointer"
                                                            @click="currentImage = URL.createObjectURL(file); previewModal = true">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-8 w-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <button @click="beforeFiles.splice(index, 1)"
                                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition shadow-sm hover:bg-red-600">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                    <span x-text="file.name"
                                                        class="block text-xs truncate mt-1 px-1"></span>
                                                    <span class="block text-xs text-gray-500 mt-1 px-1"
                                                        x-text="(file.size / 1024 / 1024).toFixed(2) + ' MB'"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </template>

                                    <!-- Preview Existing Before Files -->
                                    <template x-if="existingBeforePhotos.length">
                                        <div class="grid grid-cols-3 gap-3 mt-4">
                                            <template x-for="(photo, index) in existingBeforePhotos"
                                                :key="index">
                                                <div class="relative group">
                                                    <div
                                                        class="border border-gray-200 rounded-md overflow-hidden relative">
                                                        <img :src="photo.url" class="h-24 w-full object-cover">
                                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 flex items-center justify-center transition-all duration-300 cursor-pointer"
                                                            @click="currentImage = photo.url; previewModal = true">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-8 w-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <button
                                                        @click="
                                                            $wire.deletedPhotoIds.push(photo.id);
                                                            existingBeforePhotos.splice(index, 1);
                                                        "
                                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition shadow-sm hover:bg-red-600">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>

                                <!-- After Upload Section -->
                                <div class="space-y-4"
                                    @drop.prevent="isDragging = false; afterFiles = [...afterFiles, ...filterValidFiles($event.dataTransfer.files)]"
                                    @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false">

                                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto After
                                        Treatment</label>

                                    <div class="relative">
                                        <div @click="$refs.afterInput.click()"
                                            :class="isDragging ? 'border-indigo-500 bg-indigo-50' : 'border-gray-300'"
                                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed rounded-xl cursor-pointer transition-colors">
                                            <div class="space-y-1 text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="mx-auto h-12 w-12 text-gray-400" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <div class="flex text-sm text-gray-600 justify-center">
                                                    <span
                                                        class="relative font-medium text-indigo-600 hover:text-indigo-500">Upload
                                                        file</span>
                                                    <p class="pl-1">or drag and drop</p>
                                                </div>
                                                <p class="text-xs text-gray-500">PNG, JPG up to 3MB</p>
                                            </div>
                                        </div>
                                        <input wire:model.live="foto_after_upload" x-ref="afterInput" type="file"
                                            multiple accept="image/jpeg, image/png" class="sr-only"
                                            @change="afterFiles = [...afterFiles, ...filterValidFiles($event.target.files)]; $event.target.value = ''">
                                    </div>

                                    <!-- Preview After Files -->
                                    <template x-if="afterFiles.length">
                                        <div class="grid grid-cols-3 gap-3 mt-4">
                                            <template x-for="(file, index) in afterFiles" :key="index">
                                                <div class="relative group">
                                                    <div
                                                        class="border border-gray-200 rounded-md overflow-hidden relative">
                                                        <img :src="URL.createObjectURL(file)"
                                                            class="h-24 w-full object-cover">
                                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 flex items-center justify-center transition-all duration-300 cursor-pointer"
                                                            @click="currentImage = URL.createObjectURL(file); previewModal = true">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-8 w-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <button @click="afterFiles.splice(index, 1)"
                                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition shadow-sm hover:bg-red-600">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                    <span x-text="file.name"
                                                        class="block text-xs truncate mt-1 px-1"></span>
                                                    <span class="block text-xs text-gray-500 mt-1 px-1"
                                                        x-text="(file.size / 1024 / 1024).toFixed(2) + ' MB'"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="existingAfterPhotos.length">
                                        <div class="grid grid-cols-3 gap-3 mt-4">
                                            <template x-for="(photo, index) in existingAfterPhotos"
                                                :key="index">
                                                <div class="relative group">
                                                    <div
                                                        class="border border-gray-200 rounded-md overflow-hidden relative">
                                                        <img :src="photo.url" class="h-24 w-full object-cover">
                                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 flex items-center justify-center transition-all duration-300 cursor-pointer"
                                                            @click="currentImage = photo.url; previewModal = true">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-8 w-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <button
                                                        @click="
                                                            $wire.deletedPhotoIds.push(photo.id);
                                                            existingAfterPhotos.splice(index, 1);
                                                        "
                                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition shadow-sm hover:bg-red-600">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Pastikan foto diambil dengan pencahayaan yang sama untuk memudahkan perbandingan
                                        hasil sebelum dan sesudah treatment.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Selesai -->
                    <div x-show="tab === 'selesai'" x-cloak>
                        <h3 class="text-xl font-semibold text-gray-800 flex items-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500 mr-2"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Review Data Pemeriksaan
                        </h3>

                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                <!-- Subjektif -->
                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pemeriksaan</label>
                                    <textarea wire:model.live="ringkasanPemeriksaan" class="w-full border border-gray-300 rounded p-2 flex-grow"
                                        rows="5" readonly></textarea>
                                </div>

                                <!-- Objektif -->
                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Diagnosis</label>
                                    <textarea wire:model.live="ringkasanDiagnosa" class="w-full border border-gray-300 rounded p-2 flex-grow"
                                        rows="5" readonly></textarea>
                                </div>

                                <!-- Asesmen -->
                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tindakan</label>
                                    <textarea wire:model.live="ringkasanTindakan" class="w-full border border-gray-300 rounded p-2 flex-grow"
                                        rows="5" readonly></textarea>
                                </div>

                                <!-- Plan -->
                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Produk</label>
                                    <textarea wire:model.live="ringkasanProduk" class="w-full border border-gray-300 rounded p-2 flex-grow"
                                        rows="5" readonly></textarea>
                                </div>
                            </div>

                            <div class="flex justify-between pt-6 border-t border-gray-200">
                                <button @click="tab = 'dokumentasi'"
                                    class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                                    Kembali
                                </button>
                                <button wire:click="save"
                                    class="px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
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
</div>

@push('scripts')
    <script>
        function initSelect2KeluhanUtama() {
            const $select = $('#keluhanUtama');
            const livewireValue = @this.get('keluhanUtama'); // ini array of values

            if ($.fn.select2 && $select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }

            $select.select2({
                width: '100%',
                placeholder: 'Pilih Keluhan',
                allowClear: true,
                language: {
                    noResults: () => `
                <div class="px-4 py-2 text-sm text-gray-700">
                    <div class="mb-2">Tidak ada data ditemukan.</div>
                    <button id="tambah-keluhan-btn" type="button"
                        class="w-full px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition">
                        + Tambah Keluhan
                    </button>
                </div>
            `,
                },
                escapeMarkup: markup => markup
            });

            $('.select2-selection').css({
                width: '100%',
                height: '2.5rem',
                fontSize: '0.875rem',
                display: 'flex',
                alignItems: 'center'
            });

            // Set value awal jika ada
            if (Array.isArray(livewireValue)) {
                $select.val(livewireValue).trigger('change');
            }

            $select.on('change', () => {
                @this.set('keluhanUtama', $select.val());
            });

            // Tangani tombol tambah keluhan
            $(document).off('click', '#tambah-keluhan-btn').on('click', '#tambah-keluhan-btn', function() {
                const $searchInput = $('#keluhanUtama').data('select2').dropdown.$search || $(
                    '.select2-search__field');
                const keyword = $searchInput.val()?.trim();

                if (keyword) {
                    // Buat ID sementara (bisa pakai keyword atau prefix supaya unik)
                    const tempId = `temp-${keyword}`;

                    // Jika belum ada di select
                    if (!$select.find(`option[value="${tempId}"]`).length) {
                        // Tambahkan ke select
                        const newOption = new Option(keyword, tempId, true, true);
                        $select.append(newOption).trigger('change');

                        // Update ke Livewire
                        const currentVal = $select.val();
                        @this.set('keluhanUtama', currentVal);
                    }

                    // Tutup dropdown
                    $select.select2('close');
                }
            });
        }

        function initSelect2DiagnosaTambahan() {
            const $select = $('#diagnosa_tambahan');
            const livewireValue = @this.get('diagnosis'); // ini array of values

            if ($.fn.select2 && $select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }

            $select.select2({
                width: '100%',
                placeholder: 'Pilih Diagnosa Tambahan',
                allowClear: true,
                language: {
                    noResults: () => `
                <div class="px-4 py-2 text-sm text-gray-700">
                    <div class="mb-2">Tidak ada data ditemukan.</div>
                    <button id="tambah-diagnosis-btn" type="button"
                        class="w-full px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition">
                        + Tambah Diagnosa
                    </button>
                </div>
            `,
                },
                escapeMarkup: markup => markup
            });

            $('.select2-selection').css({
                width: '100%',
                height: '2.5rem',
                fontSize: '0.875rem',
                display: 'flex',
                alignItems: 'center'
            });

            // Set value awal jika ada
            if (Array.isArray(livewireValue)) {
                $select.val(livewireValue).trigger('change');
            }

            $select.on('change', () => {
                @this.set('diagnosis', $select.val());
            });

            // Tangani tombol tambah diagnosa
            $select.on('select2:open', function() {
                $(document).off('click', '#tambah-diagnosis-btn').on('click', '#tambah-diagnosis-btn', function() {
                    const $searchInput = $('.select2-container--open .select2-search__field');
                    const keyword = $searchInput.val()?.trim();

                    if (keyword) {
                        const tempId = `temp-${keyword}`;

                        if (!$select.find(`option[value="${tempId}"]`).length) {
                            const newOption = new Option(keyword, tempId, true, true);
                            $select.append(newOption).trigger('change');

                            // Update ke Livewire
                            const currentVal = $select.val();
                            @this.set('diagnosis', currentVal);
                        }

                        $select.select2('close');
                    }
                });
            });

        }

        function initSelect2ResepObat() {
            const $select = $('#resep_barang');
            const livewireValue = @this.get('barang');

            // Hancurkan instance Select2 sebelumnya jika sudah terinisialisasi
            if ($.fn.select2 && $select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }

            // Inisialisasi Select2 normal tanpa tombol tambah
            $select.select2({
                width: '100%',
                placeholder: 'Pilih Barang',
                allowClear: true,
                language: {
                    noResults: () => "Tidak ada data ditemukan."
                }
            });

            // Styling Select2 secara manual
            $('#resep_barang + .select2 .select2-selection').css({
                width: '100%',
                height: '2.5rem',
                padding: '0.5rem 0.75rem',
                fontSize: '0.875rem',
                display: 'flex',
                alignItems: 'center'
            });

            // Set nilai jika ada dari Livewire
            if (livewireValue) {
                if (!$select.find(`option[value="${livewireValue}"]`).length) {
                    $select.append(new Option(livewireValue, livewireValue, true, true));
                }
                $select.val(livewireValue).trigger('change');
            }

            // Bind perubahan ke Livewire
            $select.on('change', function() {
                const selectedId = $(this).val();
                const selectedOption = $(this).find(':selected');
                const satuan = selectedOption.data('satuan') || '';

                @this.set('barang', selectedId);
                @this.set('satuanBarang', satuan);
            });
        }


        document.addEventListener("DOMContentLoaded", function() {
            initSelect2KeluhanUtama();
            initSelect2ResepObat();
            initSelect2DiagnosaTambahan();
        });

        document.addEventListener("livewire:navigated", function() {
            initSelect2KeluhanUtama();
            initSelect2ResepObat();
            initSelect2DiagnosaTambahan();
        });

        Livewire.hook('message.processed', (message, component) => {
            initSelect2KeluhanUtama();
            initSelect2ResepObat();
            initSelect2DiagnosaTambahan();
        });
    </script>
@endpush
