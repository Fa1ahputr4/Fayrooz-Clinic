<div>
    <!-- Breadcrumbs -->
    <div class="text-sm breadcrumbs">
        <div class="text-sm px-4 py-2 rounded-t-lg w-max bg-[#578FCA] text-white">
            <a href="{{ route('dashboard') }}" class="hover:underline">Fayrooz</a>
            <span class="mx-1">></span>
            <a href="{{ route('antrian') }}" class="hover:underline">Antrian Pasien</a>
            <span class="mx-1">></span>
            <a href="" class="hover:underline">Pemeriksan Pasien</a>
        </div>
    </div>

    <!-- Konten -->
    <div class="bg-white p-6 rounded-lg rounded-tl-none shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#5e4a7e]">Pemeriksaan</h2>
            {{-- <button wire:click.prevent="openModal"
                class="bg-blue-500 hover:bg-blue-900 text-white px-3 py-2 rounded text-sm flex items-center">Tambah
                Data</button> --}}
        </div>

        <div>
            <div class="mb-6 p-4 bg-gray-50 border border-[#578FCA] rounded-lg shadow-sm">
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

            <div x-data="{ tab: 'riwayat' }" class="mt-8 w-full">
                <!-- Tabs -->
                <div class="flex bg-gray-100 rounded-t-xl overflow-hidden border border-b-0 border-gray-200 w-full">
                    <template
                        x-for="item in ['riwayat', 'anamnesis', 'pemeriksaan', 'diagnosa', 'tindakan & resep', 'selesai']"
                        :key="item">
                        <button @click="tab = item" class="px-5 py-3 text-sm font-medium transition duration-200"
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
                <div class="bg-white rounded-b-xl shadow p-6 border border-t-0 border-gray-200 w-full">
                    <div x-show="tab === 'riwayat'" x-cloak class="space-y-6">
                        <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Riwayat Kunjungan Pasien
                        </h3>

                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 mb-2">Kunjungan Terakhir</h4>
                                    @if ($pendaftaran)
                                        <p class="text-gray-800 font-medium">
                                            {{ \Carbon\Carbon::parse($pendaftaran->first()->tgl_kunjungan)->translatedFormat('d F Y') }}
                                        </p>
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ $pendaftaran->first()->layananDetail->nama_layanan ?? '-' }}
                                        </p>
                                    @else
                                        <p class="text-gray-600">Belum ada kunjungan</p>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 mb-2">Total Kunjungan</h4>
                                    <p class="text-gray-800 font-medium">{{ $totalKunjungan }}</p>
                                </div>
                            </div>

                            <div class="mt-6">
                                <h4 class="text-sm font-medium text-gray-500 mb-3">Riwayat Lengkap</h4>
                                <div class="space-y-4">
                                    @forelse ($riwayatRekmed as $rekmed)
                                        <div class="flex items-start border-l-4 border-indigo-200 pl-4 py-2">
                                            <div
                                                class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-50 flex items-center justify-center mr-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <div class="flex-grow">
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ \Carbon\Carbon::parse($rekmed->created_at)->translatedFormat('d F Y') }}
                                                </p>
                                                <p class="text-sm text-gray-600">
                                                    {{ $rekmed->pendaftaran->layananDetail->nama_layanan ?? '-' }}
                                                    - Diagnosa: {{ $rekmed->diagnosa->nama ?? '-' }}
                                                </p>
                                            </div>
                                            <div class="flex-shrink-0 ml-4">
                                                {{-- <button wire:click="openDetail('{{ $rekmed->id }}')"
                                                    class="text-indigo-600 hover:text-indigo-900 text-sm font-medium flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Detail
                                                </button> --}}
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-600">Belum ada riwayat rekam medis.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                    </div>

                    <div x-show="tab === 'anamnesis'" x-cloak x-data="{ adaAlergi: false }">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Keluhan Utama -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Keluhan Utama</label>
                                <div wire:ignore>
                                    <select id="keluhanUtama" multiple
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 h-11">
                                        @foreach ($daftarKeluhan as $id => $nama)
                                            <option value="{{ $id }}">{{ $nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <textarea wire:model.live="keteranganKeluhan"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 h-24 resize-none"
                                    placeholder="Keterangan keluhan pasien..."></textarea>
                            </div>

                            <!-- Riwayat Penyakit Sekarang -->
                            <div class="flex flex-col" x-data="{
                                adaAlergi: false,
                                init() {
                                    this.adaAlergi = $wire.riwayatAlergi !== null && $wire.riwayatAlergi !== '';
                                }
                            }" x-init="init()">
                                <label class="text-gray-700 font-semibold mb-2">Alergi</label>

                                <div class="flex items-center space-x-3 mb-3">
                                    <button type="button"
                                        @click=" adaAlergi = !adaAlergi;
                                        if (!adaAlergi) $wire.set('riwayatAlergi', '');"
                                        :class="adaAlergi ? 'bg-blue-600' : 'bg-gray-300'"
                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-300 focus:outline-none">
                                        <span :class="adaAlergi ? 'translate-x-6' : 'translate-x-1'"
                                            class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-300"></span>
                                    </button>

                                    <span class="text-sm text-gray-600">Ya</span>
                                </div>

                                <textarea wire:model.live="riwayatAlergi" x-show="adaAlergi" x-transition
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 h-24 resize-none" placeholder="Keterangan Alergi"></textarea>
                            </div>


                            <!-- Alergi dengan Switch Checkbox -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Riwayat Penyakit</label>
                                <textarea wire:model.live="riwayatDahulu" class="w-full border border-gray-300 rounded-md px-3 py-2 h-24 resize-none"
                                    placeholder="Riwayat penyakit terdahulu"></textarea>
                            </div>

                            <!-- Riwayat Penyakit Keluarga -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Riwayat Penyakit Keluarga</label>
                                <textarea wire:model.live="riwayatKeluarga"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 h-24 resize-none"
                                    placeholder="Riwayat penyakit keluarga"></textarea>
                            </div>

                            <!-- Riwayat Pribadi & Sosial -->
                            <div class="flex flex-col md:col-span-2">
                                <label class="text-gray-700 font-semibold mb-1">Riwayat Pribadi & Sosial</label>
                                <textarea wire:model.live="riwayatSosial"class="w-full border border-gray-300 rounded-md px-3 py-2 h-24 resize-none"
                                    placeholder="Kebiasaan merokok, konsumsi alkohol, aktivitas fisik, dll..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div x-show="tab === 'pemeriksaan'" x-cloak>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <!-- Tekanan Darah -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Tekanan Darah (mmHg)</label>
                                <div class="flex items-center space-x-0">
                                    <input wire:model.live="sistolik" type="number"
                                        class="w-1/2 border border-gray-300 rounded-l-md px-3 py-2"
                                        placeholder="Sistolik" maxlength="3"
                                        oninput="this.value = this.value.slice(0, 3);">

                                    <div class="px-3 py-2 border-t border-b border-gray-300 bg-gray-100 text-gray-600">
                                        /
                                    </div>

                                    <input wire:model.live="diastolik" type="number"
                                        class="w-1/2 border border-gray-300 rounded-r-md px-3 py-2"
                                        placeholder="Diastolik" maxlength="3"
                                        oninput="this.value = this.value.slice(0, 3);">
                                </div>
                            </div>


                            <!-- Nadi -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Nadi</label>
                                <div class="flex items-center space-x-0">
                                    <input wire:model.live="lajuNadi" type="number"
                                        class="w-full border border-gray-300 rounded-l-md px-3 py-2"
                                        placeholder="Contoh: 78" oninput="this.value = this.value.slice(0, 3);">
                                    <div class="px-3 py-2 border border-gray-300 bg-gray-100 text-gray-600">
                                        x/menit
                                    </div>
                                </div>
                            </div>

                            <!-- Suhu Tubuh -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Suhu Tubuh</label>
                                <div class="flex items-center space-x-0">
                                    <input wire:model.live="suhu" type="text"
                                        class="w-full border border-gray-300 rounded-l-md px-3 py-2"
                                        placeholder="Contoh: 36.8"
                                        oninput="this.value = this.value.replace(/[^0-9.,]/g, '').replace(/(,.*?),/g, '$1').replace(/(\..*?)\./g, '$1');">
                                    <div class="px-3 py-2 border border-gray-300 bg-gray-100 text-gray-600">
                                        °C
                                    </div>
                                </div>
                            </div>

                            <!-- Pernapasan -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Laju Pernapasan</label>
                                <div class="flex items-center space-x-0">
                                    <input wire:model.live="lajuNafas" type="number"
                                        class="w-full border border-gray-300 rounded-l-md px-3 py-2"
                                        placeholder="Contoh: 18" oninput="this.value = this.value.slice(0, 3);">
                                    <div class="px-3 py-2 border border-gray-300 bg-gray-100 text-gray-600">
                                        x/menit
                                    </div>
                                </div>
                            </div>

                            <!-- Berat Badan -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Berat Badan</label>
                                <div class="flex items-center space-x-0">
                                    <input wire:model.live="bb" type="text"
                                        class="w-full border border-gray-300 rounded-l-md px-3 py-2"
                                        placeholder="Contoh: 65"
                                        oninput="this.value = this.value.replace(/[^0-9.,]/g, '').replace(/(,.*?),/g, '$1').replace(/(\..*?)\./g, '$1');">
                                    <div class="px-3 py-2 border border-gray-300 bg-gray-100 text-gray-600">
                                        kg
                                    </div>
                                </div>
                            </div>

                            <!-- Tinggi Badan -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Tinggi Badan</label>
                                <div class="flex items-center space-x-0">
                                    <input wire:model.live="tb" type="text"
                                        class="w-full border border-gray-300 rounded-l-md px-3 py-2"
                                        placeholder="Contoh: 170"
                                        oninput="this.value = this.value.replace(/[^0-9.,]/g, '').replace(/(,.*?),/g, '$1').replace(/(\..*?)\./g, '$1');">
                                    <div class="px-3 py-2 border border-gray-300 bg-gray-100 text-gray-600">
                                        cm
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pemeriksaan Fisik Umum -->
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-1">Pemeriksaan Fisik Umum</label>
                            <textarea wire:model.live="pemeriksaanUmum" class="w-full border border-gray-300 rounded-md px-3 py-2 resize-none"
                                rows="4" placeholder="Pemeriksaan umum"></textarea>
                        </div>

                        <!-- Pemeriksaan Khusus -->
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-1">Pemeriksaan Khusus (Jika
                                Ada)</label>
                            <textarea wire:model.live="pemeriksaanKhusus" class="w-full border border-gray-300 rounded-md px-3 py-2 resize-none"
                                rows="3" placeholder="Pemeriksaan khusus"></textarea>
                        </div>
                    </div>

                    <div x-show="tab === 'diagnosa'" x-cloak>

                        <div class="mb-4 grid md:grid-cols-2 gap-4">
                            <!-- Diagnosa Utama -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Diagnosa Utama</label>
                                <div wire:ignore>
                                    <select id="diagnosa_utama"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2">
                                        <option value="">Pilih Diagnosa</option>
                                        @foreach ($daftarDiagnosisUmum as $id => $nama)
                                            <option value="{{ $id }}">{{ $nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <textarea wire:model.live="keteranganUtama" name="keterangan_diagnosa_utama" id="keterangan_diagnosa_utama"
                                    class="w-full border border-gray-300 px-3 py-2" placeholder="Keterangan diagnosa utama (opsional)"></textarea>
                            </div>

                            <!-- Tingkat Keparahan -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Tingkat Keparahan</label>
                                <select wire:model.live="keparahan" id="tingkat_keparahan"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2">
                                    <option value="">Pilih Tingkat Keparahan</option>
                                    <option value="Ringan">Ringan</option>
                                    <option value="Sedang">Sedang</option>
                                    <option value="Berat">Berat</option>
                                    <option value="Kritis">Kritis</option>
                                </select>
                            </div>
                        </div>

                        <!-- Diagnosa Tambahan / Komorbid -->
                        <div class="flex flex-col">
                            <label class="text-gray-700 font-semibold mb-1">Diagnosa Tambahan (Jika Ada)</label>
                            <div wire:ignore>
                                <select id="diagnosa_tambahan" multiple
                                    class="w-full border border-gray-300 rounded-md px-3 py-2">
                                    @foreach ($daftarDiagnosisUmum as $id => $nama)
                                        <option value="{{ $id }}">{{ $nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <textarea wire:model.live="keteranganTambahan" name="keterangan_diagnosa_tambahan" id="keterangan_diagnosa_tambahan"
                                class="w-full border border-gray-300 px-3 py-2" placeholder="Keterangan diagnosa tambahan (opsional)"></textarea>
                        </div>
                    </div>

                    <div x-show="tab === 'tindakan & resep'" x-cloak>
                        <div x-data="{
                            kontrolUlang: @entangle('kontrolUlang'),
                            kirimWa: @entangle('kirimWa'),
                            init() {
                                // Jika toggle Kontrol Ulang dimatikan, bersihkan semua
                                this.$watch('kontrolUlang', (value) => {
                                    if (!value) {
                                        $wire.set('jadwalKontrolUlang', null);
                                        $wire.set('catatanJadwal', '');
                                        $wire.set('nomorKontrol', '');
                                        this.kirimWa = false;
                                        $wire.set('kirimWa', false);
                                    }
                                });
                        
                                // Jika toggle Kirim WA dimatikan, bersihkan nomor dan catatan
                                this.$watch('kirimWa', (value) => {
                                    if (!value) {
                                        $wire.set('nomorKontrol', '');
                                        $wire.set('catatanJadwal', '');
                                    }
                                });
                            }
                        }" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">

                            <!-- Tindakan Utama -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Tindakan Utama</label>
                                <input wire:model.live="tindakan" type="text"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2"
                                    placeholder="Tindakan utama">
                            </div>

                            <!-- Switch Jadwalkan Kontrol -->
                            <div class="flex flex-col">
                                <label class="text-gray-700 font-semibold mb-1">Jadwalkan Kontrol Ulang</label>
                                <div class="flex items-center mt-2">
                                    <button type="button"
                                        @click="
                    kontrolUlang = !kontrolUlang;
                    $wire.set('kontrolUlang', kontrolUlang);
                "
                                        :class="kontrolUlang ? 'bg-green-500' : 'bg-gray-300'"
                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none">
                                        <span :class="kontrolUlang ? 'translate-x-6' : 'translate-x-1'"
                                            class="inline-block h-4 w-4 transform rounded-full bg-white transition"></span>
                                    </button>
                                    <span class="ml-3 text-sm text-gray-700"
                                        x-text="kontrolUlang ? 'Ya' : 'Tidak'"></span>
                                </div>
                            </div>

                            <!-- Input Tanggal, Nomor WA, dan Catatan -->
                            <div class="md:col-span-2" x-show="kontrolUlang" x-transition x-cloak>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Tanggal & Waktu Kontrol -->
                                    <div class="flex flex-col">
                                        <label class="text-gray-700 font-semibold mb-1">Tanggal & Waktu Kontrol
                                            Ulang</label>
                                        <input wire:model.live="jadwalKontrolUlang" type="datetime-local"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2">
                                    </div>

                                    <!-- Toggle Kirim WhatsApp -->
                                    <div class="flex flex-col">
                                        <label class="text-gray-700 font-semibold mb-1">Kirim WhatsApp
                                            @if (!$adaWaApiAktif)
                                                <span class="text-red-500 text-xs italic">(API tidak
                                                    aktif)</span>
                                            @endif
                                        </label>

                                        <div class="flex items-center mt-2">
                                            <button type="button"
                                                @click="
                                                    kirimWa = !kirimWa;
                                                    $wire.set('kirimWa', kirimWa);
                                                "
                                                :disabled="{{ $adaWaApiAktif ? 'false' : 'true' }}"
                                                :class="kirimWa ? 'bg-green-500' : 'bg-gray-300'"
                                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none">
                                                <span :class="kirimWa ? 'translate-x-6' : 'translate-x-1'"
                                                    class="inline-block h-4 w-4 transform rounded-full bg-white transition"></span>
                                            </button>
                                            <span class="ml-3 text-sm text-gray-700"
                                                x-text="kirimWa ? 'Ya' : 'Tidak'"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Input Nomor dan Catatan (hanya muncul jika kirimWa aktif) -->
                                <template x-if="kirimWa">
                                    <div class="space-y-6 mt-6">
                                        <!-- Nomor Telepon -->
                                        <div class="flex flex-col">
                                            <label class="text-gray-700 font-semibold mb-1">Nomor WhatsApp</label>
                                            <div class="flex space-x-2">
                                                <input wire:model.live="nomorKontrol" type="number"
                                                    class="flex-1 border border-gray-300 rounded-md px-3 py-2"
                                                    placeholder="Contoh: 6281234567890">

                                                <button type="button" wire:click="ambilNomorPasien"
                                                    class="px-3 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
                                                    Ambil Nomor Pasien
                                                </button>
                                            </div>
                                            @error('nomorKontrol')
                                                <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Catatan Kontrol -->
                                        <div class="flex flex-col">
                                            <label class="text-gray-700 font-semibold mb-1">Catatan Kontrol</label>
                                            <input wire:model.live="catatanJadwal" type="text"
                                                class="w-full border border-gray-300 rounded-md px-3 py-2"
                                                placeholder="Contoh: Evaluasi pengobatan, hasil lab, dll.">
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Catatan Tambahan -->
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-1">Catatan Tambahan</label>
                            <textarea wire:model.live="catatan" class="w-full border border-gray-300 rounded-md px-3 py-2 resize-none"
                                rows="4" placeholder="Tambahkan catatan bila ada..."></textarea>
                        </div>
                        <div class="mt-3">
                            <label class="block text-gray-700 font-semibold mb-1">Resep</label>
                            <table class="w-full border text-sm mb-4">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="p-2 border">Nama Obat</th>
                                        <th class="p-2 border">Jumlah</th>
                                        <th class="p-2 border">Satuan</th>
                                        <th class="p-2 border">Aturan Pakai</th>
                                        <th class="p-2 border"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Baris Input --}}
                                    <tr>
                                        <td class="p-2 border">
                                            <div wire:ignore>
                                                <select id="resep_obat" wire:model="obat"
                                                    class="w-full p-1 border border-gray-400 rounded">
                                                    <option value="">Pilih Obat</option>
                                                    @foreach ($this->obatList as $item)
                                                        <option value="{{ $item->id }}"
                                                            data-satuan="{{ $item->satuan }}"
                                                            data-stok="{{ $item->total_stok }}">
                                                            {{ $item->nama_barang }} (sisa stok:
                                                            {{ $item->total_stok }} {{ $item->satuan }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('obat')
                                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </td>
                                        <td class="p-2 border">
                                            <input wire:model.live="jumlahObat" type="number"
                                                class="w-full p-1 border border-gray-400 rounded">
                                            @error('jumlahObat')
                                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                            @enderror

                                            @if ($jumlahObatError)
                                                <p class="text-red-600 text-xs mt-1">{{ $jumlahObatError }}</p>
                                            @endif
                                        </td>

                                        <td class="p-2 border">
                                            <input wire:model.live="satuanObat" type="text"
                                                class="w-full p-1 border border-gray-400 rounded" disabled>
                                        </td>
                                        <td class="p-2 border">
                                            <input wire:model.live="aturanObat" type="text"
                                                class="w-full p-1 border border-gray-400 rounded">
                                            @error('aturanObat')
                                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                            @enderror

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
                                            <td class="p-2 border">{{ $item['satuan'] }}</td>
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

                    <div x-show="tab === 'selesai'" x-cloak>
                        <h3 class="text-lg font-semibold mb-4">Review Rekam Medis</h3>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            <!-- Subjektif -->
                            <div class="flex flex-col">
                                <label class="block font-semibold mb-1">Subjektif</label>
                                <textarea class="w-full border border-gray-300 rounded p-2 flex-grow" rows="5" readonly>{{ $this->subjektif }}</textarea>
                            </div>

                            <!-- Objektif -->
                            <div class="flex flex-col">
                                <label class="block font-semibold mb-1">Objektif</label>
                                <textarea class="w-full border border-gray-300 rounded p-2 flex-grow" rows="5" readonly>{{ $this->objektif }}</textarea>
                            </div>

                            <!-- Asesmen -->
                            <div class="flex flex-col">
                                <label class="block font-semibold mb-1">Asesmen</label>
                                <textarea class="w-full border border-gray-300 rounded p-2 flex-grow" rows="5" readonly>{{ $this->assesment }}</textarea>
                            </div>

                            <!-- Plan -->
                            <div class="flex flex-col">
                                <label class="block font-semibold mb-1">Plan</label>
                                <textarea class="w-full border border-gray-300 rounded p-2 flex-grow" rows="5" readonly>{{ $this->plan }}</textarea>
                            </div>
                        </div>

                        <div class="text-right">
                            <button wire:click="save"
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


        function initSelect2DiagnosaUtama() {
            const $select = $('#diagnosa_utama');
            const livewireValue = @this.get('diagnosisUtama');

            if ($.fn.select2 && $select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }

            $select.select2({
                width: '100%',
                placeholder: 'Pilih Diagnosa',
                allowClear: true,
                language: {
                    noResults: () => `
                <div class="px-4 py-2 text-sm text-gray-700">
                    <div class="mb-2">Tidak ada data ditemukan.</div>
                    <button id="tambah-diagnosa-btn" type="button"
                        class="w-full px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition">
                        + Tambah Diagnosa
                    </button>
                </div>`
                },
                escapeMarkup: markup => markup
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


            if (livewireValue) {
                if (!$select.find(`option[value="${livewireValue}"]`).length) {
                    $select.append(new Option(livewireValue, livewireValue, true, true));
                }
                $select.val(livewireValue).trigger('change');
            }

            $select.on('change', () => @this.set('diagnosisUtama', $select.val()));

            $(document).off('click', '#tambah-diagnosa-btn').on('click', '#tambah-diagnosa-btn', function() {
                const $dropdown = $('#diagnosa_utama').data('select2').dropdown.$search || $(
                    '.select2-search__field'); // fallback
                const keyword = $dropdown.val();

                if (keyword) {
                    const $select = $('#diagnosa_utama');

                    // Tambah opsi baru
                    const newOption = new Option(keyword, keyword, true, true);
                    $select.append(newOption).val(keyword).trigger('change');

                    // Update ke Livewire
                    @this.set('diagnosisUtama', keyword);

                    // Tutup dropdown
                    $select.select2('close');
                }
            });
        }

        function initSelect2DiagnosaTambahan() {
            const $select = $('#diagnosa_tambahan');
            const livewireValue = @this.get('diagnosisTambahan'); // ini array of values

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
                @this.set('diagnosisTambahan', $select.val());
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
                            @this.set('diagnosisTambahan', currentVal);
                        }

                        $select.select2('close');
                    }
                });
            });

        }


        function initSelect2ResepObat() {
            const $select = $('#resep_obat');
            const livewireValue = @this.get('obat');

            // Hancurkan instance Select2 sebelumnya jika sudah terinisialisasi
            if ($.fn.select2 && $select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }

            // Inisialisasi Select2 normal tanpa tombol tambah
            $select.select2({
                width: '100%',
                placeholder: 'Pilih Obat',
                allowClear: true,
                language: {
                    noResults: () => "Tidak ada data ditemukan."
                }
            });

            // Styling Select2 secara manual
            $('#resep_obat + .select2 .select2-selection').css({
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

                @this.set('obat', selectedId);
                @this.set('satuanObat', satuan);
            });
        }


        document.addEventListener("DOMContentLoaded", function() {
            initSelect2DiagnosaUtama();
            initSelect2KeluhanUtama();
            initSelect2DiagnosaTambahan();
            initSelect2ResepObat();
        });

        document.addEventListener("livewire:navigated", function() {
            initSelect2DiagnosaUtama();
            initSelect2KeluhanUtama();
            initSelect2DiagnosaTambahan();
            initSelect2ResepObat();
        });

        Livewire.hook('message.processed', (message, component) => {
            initSelect2DiagnosaUtama();
            initSelect2KeluhanUtama();
            initSelect2DiagnosaTambahan();
            initSelect2ResepObat();
        });
    </script>
@endpush
