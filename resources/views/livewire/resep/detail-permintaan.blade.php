<div>
    <!-- Breadcrumbs -->
    <div class="text-sm breadcrumbs">
        <div class="text-sm px-4 py-2 rounded-t-lg w-max bg-[#578FCA] text-white">
            <a href="{{ route('dashboard') }}" class="hover:underline">Fayrooz</a>
            <span class="mx-1">></span>
            <a href="{{ route('permintaan-resep') }}" class="hover:underline">Permintaan Resep</a>
            <span class="mx-1">></span>
            <a href="{{ route('permintaan-resep-detail', ['id' => $history->id]) }}" class="hover:underline">Detail Resep</a>
        </div>
    </div>

    <!-- Konten Utama -->
    <div class="bg-white p-6 rounded-lg rounded-tl-none shadow">
        <h2 class="text-2xl font-semibold text-[#5e4a7e] mb-6">Detail Permintaan Resep</h2>

        <!-- Data Pasien -->
        <div class="bg-gray-50 p-4 rounded-lg border mb-6 shadow-sm">
            <h3 class="font-semibold text-[#578FCA] text-lg mb-2">Informasi Pasien</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                <div><strong>Nama :</strong> {{ $history->pasien->nama_lengkap ?? '-' }}</div>
                <div><strong>Jenis Kelamin :</strong>
                    {{ $history->pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                </div>
                <div><strong>Usia :</strong> {{ $history->pasien->usia ? $history->pasien->usia . ' tahun' : '-' }}
                </div>
                <div><strong>Tanggal Kunjungan :</strong>
                    {{ \Carbon\Carbon::parse($history->created_at)->translatedFormat('d F Y') }}</div>
                <div><strong>Dokter :</strong> {{ $history->pendaftaran->dokter->nama ?? '-' }}</div>
                <div><strong>Layanan :</strong> {{ $history->pendaftaran->layanan->nama ?? '-' }}</div>
            </div>
        </div>

        <!-- SOAP Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 text-sm">
            <!-- Subjektif -->
            <div class="bg-gray-50 p-4 rounded-lg border shadow-sm">
                <h3 class="font-semibold text-[#578FCA] mb-2">Subjektif</h3>

                {{-- Keluhan Utama (keluhan_umum adalah relasi hasMany misalnya) --}}
                @if ($history->keluhanUtamaPasien->isEmpty())
                    <p class="mb-1"><strong>Keluhan Utama :</strong> -</p>
                @else
                    <p class="mb-1"><strong>Keluhan Utama :</strong></p>
                    <ul class="list-disc list-inside mb-2 text-sm">
                        @foreach ($history->keluhanUtamaPasien as $k)
                            <li>{{ $k->keluhan->nama ?? '-' }}</li>
                        @endforeach
                    </ul>
                @endif

                <p class="mb-1"><strong>Keterangan Keluhan :</strong> {{ $history->keterangan_keluhan ?? '-' }}</p>
                <p class="mb-1"><strong>Riwayat Penyakit Dahulu :</strong> {{ $history->riwayat_penyakit ?? '-' }}</p>
                <p class="mb-1"><strong>Alergi :</strong> {{ $history->alergi ?? '-' }}</p>
                <p class="mb-1"><strong>Riwayat Keluarga :</strong> {{ $history->riwayat_keluarga ?? '-' }}</p>
                <p class="mb-1"><strong>Riwayat Sosial :</strong> {{ $history->riwayat_sosial ?? '-' }}</p>
            </div>


            <!-- Objektif -->
            <div class="bg-gray-50 p-4 rounded-lg border shadow-sm">
                <h3 class="font-semibold text-[#578FCA] mb-2">Objektif</h3>
                <p class="mb-1"><strong>Tekanan Darah :</strong>
                    {{ $history->sistolik && $history->diastolik ? "$history->sistolik/$history->diastolik mmHg" : '-' }}
                </p>

                <p class="mb-1"><strong>Suhu :</strong> {{ $history->suhu ? "$history->suhu °C" : '-' }} </p>
                <p class="mb-1"><strong>Berat Badan :</strong> {{ $history->bb ? "$history->bb kg" : '-' }} </p>
                <p class="mb-1"><strong>Berat Badan :</strong> {{ $history->tb ? "$history->tb cm" : '-' }} </p>
                <p class="mb-1"><strong>Laju Nadi :</strong>
                    {{ $history->laju_nadi ? "$history->laju_nadi bpm" : '-' }} </p>
                <p class="mb-1"><strong>Laju Nafas :</strong>
                    {{ $history->laju_nafas ? "$history->laju_nafas x/menit" : '-' }} </p>
                <p class="mb-1"><strong>Pemeriksaan Fisik Umum :</strong> {{ $history->pemeriksaan_umum ?? '-' }}</p>
                <p class="mb-1"><strong>Pemeriksaan Khusus :</strong> {{ $history->pemeriksaan_khusus ?? '-' }}</p>
            </div>

            <!-- Asesmen -->
            <div class="bg-gray-50 p-4 rounded-lg border shadow-sm">
                <h3 class="font-semibold text-[#578FCA] mb-2">Asesmen</h3>
                <p class="mb-1"><strong>Diagnosa Utama :</strong> {{ $history->diagnosa->nama ?? '-' }}</p>
                <p class="mb-1"><strong>Catatan Diagnosa Utama :</strong>
                    {{ $history->keterangan_diagnosa_utama ?? '-' }}</p>
                @if ($history->diagnosaTambahanPasien->isEmpty())
                    <p class="mb-1"><strong>Diagnosa Tambahan :</strong> -</p>
                @else
                    <p class="mb-1"><strong>Diagnosa Tambahan :</strong></p>
                    <ul class="list-disc list-inside mb-2 text-sm">
                        @foreach ($history->diagnosaTambahanPasien as $d)
                            <li>{{ $d->diagnosa->nama ?? '-' }}</li>
                        @endforeach
                    </ul>
                @endif
                <p class="mb-1"><strong>Catatan Diagnosa Tambahan :</strong>
                    {{ $history->keterangan_diagnosa_tambahan ?? '-' }}</p>
                <p class="mb-1"><strong>Keparahan :</strong> {{ $history->keparahan ?? '-' }}</p>
            </div>

            <!-- Plan -->
            <div class="bg-gray-50 p-4 rounded-lg border shadow-sm">
                <h3 class="font-semibold text-[#578FCA] mb-2">Plan</h3>
                <p class="mb-1"><strong>Tindakan :</strong> {{ $history->tindakan ?? '-' }}</p>
                <p class="mb-1">
                    <strong>Kontrol Ulang :</strong>
                    {{ $history->kontrol_ulang == 0 ? '-' : $history->kontrol_ulang }}
                </p>

                @if ($history->kontrol_ulang != 0)
                    <p class="mb-1">
                        <strong>Jadwal Kontrol:</strong>
                        {{ $history->jadwal_kontrol ?? '-' }}
                    </p>
                @endif
                <p class="mb-1"><strong>Catatan :</strong> {{ $history->catatan_tambahan ?? '-' }}</p>
            </div>
        </div>

        <!-- Resep Obat -->
        <div class="mt-8">
            <h3 class="font-semibold text-lg mb-2 text-[#578FCA]">Resep Obat</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm table-auto border border-[#ccc] shadow-sm rounded">
                    <thead class="bg-[#578FCA] text-white">
                        <tr>
                            <th class="py-2 px-3 border">Nama Obat</th>
                            <th class="py-2 px-3 border">Jumlah</th>
                            <th class="py-2 px-3 border">Satuan</th>
                            <th class="py-2 px-3 border">Aturan Pakai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($history->resepPasien as $resep)
                            <tr>
                                <td class="border px-3 py-2">
                                    {{ $resep->barang->nama_barang ?? '-' }}
                                    @php
                                        $stok = $resep->barang?->stokRaks?->sum('jumlah_sisa') ?? 0;
                                    @endphp
                                    (Sisa Stok: {{ $stok }})
                                </td>
                                <td class="border px-3 py-2">{{ $resep->jumlah ?? '-' }}</td>
                                <td class="border px-3 py-2">{{ $resep->barang->satuan ?? '-' }}</td>
                                <td class="border px-3 py-2">{{ $resep->aturan_pakai ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-3 text-gray-500">Tidak ada data resep.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <!-- Tombol Konfirmasi / Tolak -->
                <div class="mt-6 flex gap-4 justify-end">
                    @if ($history->resepPasien->first()?->status === 'dikonfirmasi')
                        <button disabled
                            class="border border-green-500 text-green-600 bg-white px-4 py-2 rounded shadow text-sm">
                            ✅ Dikonfirmasi
                        </button>
                    @else
                        <button wire:click="openConfirmModal({{ $history->id }})"
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded shadow text-sm">
                            ✅ Konfirmasi Resep
                        </button>
                    @endif

                </div>

            </div>
        </div>
        <x-modal wire:model="isModalOpen" title="Konfirmasi Permintaan Resep" max-width="md">
            <div class="space-y-4">
                <!-- Header with icon -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 bg-green-100 p-2 rounded-full">
                        <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2l4 -4M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10z" />
                        </svg>
                    </div>

                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-gray-900">Konfirmasi Permintaan Resep</h3>
                        <p class="text-sm text-gray-500">Pilih rak dan stok untuk pengambilan obat:</p>
                    </div>
                </div>

                <!-- Prescription items -->
                <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-2">
                    @foreach ($selectedHistory->resepPasien as $resep)
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <h4 class="font-medium text-gray-800 mb-2">{{ $resep->barang->nama_barang }}
                                ({{ $resep->jumlah }} {{ $resep->barang->satuan ?? 'pcs' }})</h4>

                            <div class="space-y-3">
                                <!-- Rak Selection -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Rak</label>
                                    <select wire:model.live="rakTerpilih.{{ $resep->id }}"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                                        <option value="">-- Pilih Rak --</option>
                                        @foreach ($resep->barang->rak as $rak)
                                            <option value="{{ $rak->id }}">
                                                {{ $rak->nama_rak }}
                                                @if ($rak->stokRaks)
                                                    (Stok: {{ $rak->stokRaks->sum('jumlah_sisa') }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error("rakTerpilih.{$resep->id}")
                                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                    @enderror

                                </div>

                                <!-- Stok Rak Selection -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Stok Rak</label>
                                    <select wire:model.live="stokRakTerpilih.{{ $resep->id }}"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-sm disabled:opacity-50"
                                        @if (empty($rakTerpilih[$resep->id])) disabled @endif>
                                        <option value="">-- Pilih Stok Rak --</option>
                                        @if (!empty($stokRakOptions[$resep->id]))
                                            @foreach ($stokRakOptions[$resep->id] as $stokId => $label)
                                                <option value="{{ $stokId }}">{{ $label }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                     @error("stokRakTerpilih.{$resep->id}")
                                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Action buttons -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button wire:click="closeModal" type="button"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Batal
                    </button>
                    <button wire:click="konfirmasiResep" wire:loading.attr="disabled" type="button"
                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50">
                        <span wire:loading.remove wire:target="konfirmasiResep">Konfirmasi</span>
                        <span wire:loading wire:target="konfirmasiResep">Memproses...</span>
                    </button>
                </div>

                <!-- Loading indicator -->
                <div wire:loading wire:target="konfirmasiResep" class="text-center py-2 text-sm text-gray-500">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-green-600 inline"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    Memproses konfirmasi resep...
                </div>
            </div>
        </x-modal>


    </div>
</div>
