<?php

namespace App\Livewire\Pemeriksaan;

use App\Models\Barang;
use App\Models\Diagnosa;
use App\Models\DiagnosaTambahanPasien;
use App\Models\Keluhan;
use App\Models\KeluhanPasien;
use App\Models\LogWhatsapp;
use App\Models\Pendaftaran;
use App\Models\RekmedUmum;
use App\Models\ResepPasien;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Component;

class KesehatanUmum extends Component
{

    public $title = 'Fayrooz | Pemeriksaan';
    public $pendaftaran;
    public $pendaftaranId;
    public $pasienId;
    public $rekmedId;
    //Anamesa
    public $daftarKeluhan = [], $keluhanUtama = [], $keteranganKeluhan, $riwayatDahulu, $riwayatAlergi, $riwayatKeluarga, $riwayatSosial;
    //Pemeriksaan
    public $sistolik, $diastolik, $tb, $bb, $lajuNadi, $lajuNafas, $suhu, $pemeriksaanUmum, $pemeriksaanKhusus;
    //Diagnosis
    public $daftarDiagnosisUmum = [], $daftarDiagnosisTambahan = [], $diagnosisUtama, $keteranganUtama, $diagnosisTambahan = [], $keteranganTambahan, $keparahan;
    //Tindakan & Plan
    public $resepList = [], $tindakan, $catatan, $jadwalKontrolUlang, $catatanJadwal, $kontrolUlang = false, $nomorKontrol, $kirimWa, $resep, $obat, $jumlahObat, $satuanObat, $aturanObat;
    protected $listeners = ['input-updated' => '$refresh'];

    public  $stokObat = 0,  $jumlahObatError = null;
    public $adaWaApiAktif, $totalKunjungan, $riwayatRekmed;
    public $showDetailModal = false;
    public $selectedRekmed;

    public function openDetail($rekmedId)
    {
        $this->selectedRekmed = RekmedUmum::with([
            'pendaftaran.layananDetail',
            'diagnosa',
            'createdBy'
        ])->findOrFail($rekmedId);

        $this->showDetailModal = true;
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
    }

    public function updatedNomorKontrol($value)
    {
        if (!empty($value)) {
            $this->resetErrorBag('nomorKontrol');
        }
    }


    public function ambilNomorPasien()
    {
        // Cek apakah data pendaftaran sudah dimuat
        $pasien = optional(optional($this->pendaftaran)->pasien);

        if ($pasien && $pasien->no_telepon) {
            $this->nomorKontrol = $pasien->no_telepon;
        } else {
            $this->addError('nomorKontrol', 'Pasien tidak memiliki nomor telepon.');
        }
    }

    private function formatNomorWa($nomor)
    {
        $nomor = preg_replace('/[^0-9]/', '', $nomor); // Hanya angka

        if (strpos($nomor, '62') === 0) {
            return $nomor; // Sudah format internasional
        }

        if (strpos($nomor, '0') === 0) {
            return '62' . substr($nomor, 1); // Ganti 0 di awal jadi 62
        }

        return $nomor; // fallback, asumsi sudah valid
    }


    public function save()
    {
        $this->validate([
            'pasienId' => 'required',
            'pendaftaranId' => 'required',
            'diagnosisUtama' => 'nullable|string',
            'tindakan' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Cek apakah rekam medis sudah ada
            $rekmed = RekmedUmum::with('pendaftaran.layanan', 'pendaftaran.pasien')
                ->where('id_pendaftaran', $this->pendaftaranId)
                ->first();


            if ($rekmed) {
                if (!is_numeric($this->diagnosisUtama)) {
                    $this->diagnosisUtama = trim($this->diagnosisUtama);

                    if (!empty($this->diagnosisUtama)) {
                        $existing = Diagnosa::where('nama', $this->diagnosisUtama)->first();
                        if ($existing) {
                            $this->diagnosisUtama = $existing->id;
                        } else {
                            $diagnosaBaru = Diagnosa::create([
                                'nama' => $this->diagnosisUtama,
                                'layanan_id' => $rekmed?->pendaftaran?->layanan_id,
                            ]);
                            $this->diagnosisUtama = $diagnosaBaru->id;
                        }
                    } else {
                        // tidak usah buat diagnosa baru, biarkan id_diagnosa null
                        $this->diagnosisUtama = null;
                    }
                }

                // Update jika sudah ada
                $rekmed->update([
                    'id_pasien' => $this->pasienId,
                    'keterangan_keluhan' => $this->keteranganKeluhan,
                    'alergi' => $this->riwayatAlergi,
                    'riwayat_penyakit' => $this->riwayatDahulu,
                    'riwayat_sosial' => $this->riwayatSosial,
                    'riwayat_keluarga' => $this->riwayatKeluarga,
                    'sistolik' => $this->sistolik,
                    'diastolik' => $this->diastolik,
                    'suhu' => $this->suhu,
                    'bb' => $this->bb,
                    'tb' => $this->tb,
                    'laju_nadi' => $this->lajuNadi,
                    'laju_nafas' => $this->lajuNafas,
                    'pemeriksaan_umum' => $this->pemeriksaanUmum,
                    'pemeriksaan_khusus' => $this->pemeriksaanKhusus,
                    'id_diagnosa' => $this->diagnosisUtama ?: null,
                    'keterangan_diagnosa_utama' => $this->keteranganUtama,
                    'keterangan_diagnosa_tambahan' => $this->keteranganTambahan,
                    'keparahan' => $this->keparahan,
                    'tindakan' => $this->tindakan,
                    'kontrol_ulang' => $this->kontrolUlang,
                    'nomor_pasien' => $this->nomorKontrol,
                    'jadwal_kontrol' => $this->jadwalKontrolUlang,
                    'catatan_tambahan' => $this->catatan,
                    'catatan_jadwal' => $this->catatanJadwal,
                    'updated_by' => auth()->id()
                ]);

                // Bersihkan relasi lama
                $rekmed->keluhanUtamaPasien()->delete();
                $rekmed->diagnosaTambahanPasien()->delete();
                $rekmed->resepPasien()->delete();
            } else {
                if (!is_numeric($this->diagnosisUtama)) {
                    $this->diagnosisUtama = trim($this->diagnosisUtama);

                    if (!empty($this->diagnosisUtama)) {
                        $existing = Diagnosa::where('nama', $this->diagnosisUtama)->first();
                        if ($existing) {
                            $this->diagnosisUtama = $existing->id;
                        } else {
                            $diagnosaBaru = Diagnosa::create([
                                'nama' => $this->diagnosisUtama,
                                'layanan_id' => $rekmed?->pendaftaran?->layanan_id,
                            ]);
                            $this->diagnosisUtama = $diagnosaBaru->id;
                        }
                    } else {
                        // tidak usah buat diagnosa baru, biarkan id_diagnosa null
                        $this->diagnosisUtama = null;
                    }
                }

                // Create jika belum ada
                $rekmed = RekmedUmum::create([
                    'id_pasien' => $this->pasienId,
                    'id_pendaftaran' => $this->pendaftaranId,
                    'keterangan_keluhan' => $this->keteranganKeluhan,
                    'alergi' => $this->riwayatAlergi,
                    'riwayat_penyakit' => $this->riwayatDahulu,
                    'riwayat_sosial' => $this->riwayatSosial,
                    'riwayat_keluarga' => $this->riwayatKeluarga,
                    'sistolik' => $this->sistolik,
                    'diastolik' => $this->diastolik,
                    'suhu' => $this->suhu,
                    'bb' => $this->bb,
                    'tb' => $this->tb,
                    'laju_nadi' => $this->lajuNadi,
                    'laju_nafas' => $this->lajuNafas,
                    'pemeriksaan_umum' => $this->pemeriksaanUmum,
                    'pemeriksaan_khusus' => $this->pemeriksaanKhusus,
                    'id_diagnosa' => $this->diagnosisUtama,
                    'keterangan_diagnosa_utama' => $this->keteranganUtama,
                    'keterangan_diagnosa_tambahan' => $this->keteranganTambahan,
                    'keparahan' => $this->keparahan,
                    'tindakan' => $this->tindakan,
                    'kontrol_ulang' => $this->kontrolUlang,
                    'nomor_pasien' => $this->nomorKontrol,
                    'jadwal_kontrol' => $this->jadwalKontrolUlang,
                    'catatan_tambahan' => $this->catatan,
                    'catatan_jadwal' => $this->catatanJadwal,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id()
                ]);
            }

            $logExists = LogWhatsapp::where('rekmed_umum_id', $rekmed->id)
                ->where('status', 'sukses')
                ->exists();

            if ($rekmed->nomor_pasien && $rekmed->jadwal_kontrol && !$logExists) {
                $pasien = $rekmed->pendaftaran->pasien ?? null;
                $noWaInput = $this->nomorKontrol ?: ($pasien->no_wa ?? null);
                $noWa = $this->formatNomorWa($noWaInput);

                if ($noWa && $pasien) {
                    $pesan = "Halo *{$pasien->nama_lengkap}*,\n\nIni pengingat kontrol ulang Anda di klinik kami pada (*" .
                        Carbon::parse($rekmed->jadwal_kontrol)->translatedFormat('l, d F Y') . "*).\n\n📌 Catatan: {$rekmed->catatan_jadwal}\n\nMohon hadir tepat waktu, terima kasih 🙏.";

                    // Ambil API WhatsApp aktif
                    $waApi = \App\Models\WaApi::where('active', true)->first();

                    if (!$waApi) {
                        session()->flash('error', 'Tidak ada API WhatsApp yang aktif. Silakan atur terlebih dahulu.');
                        return;
                    }

                    // Jadwalkan pengiriman (opsional, bisa juga langsung kirim tanpa schedule)
                    $jadwalWIB = Carbon::create(2025, 6, 18, 0, 55, 0, 'Asia/Jakarta');
                    $timestampUTC = $jadwalWIB->copy()->setTimezone('UTC')->timestamp;

                    try {
                        Http::withHeaders([
                            'Authorization' => $waApi->token,
                        ])->post($waApi->base_url, [
                            'target' => $noWa,
                            'message' => $pesan,
                            'countryCode' => '62',
                            'schedule' => $timestampUTC, // Uncomment jika ingin dijadwalkan
                        ]);

                        $rekmed->update(['notifikasi_terkirim' => true]);

                        // Simpan log sukses
                        LogWhatsapp::create([
                            'rekmed_umum_id' => $rekmed->id,
                            'nama_pasien' => $pasien->nama_lengkap,
                            'nomor_wa' => $noWa,
                            'pesan' => $pesan,
                            'waktu_kirim' => now(),
                            'status' => 'sukses',
                            'error_message' => null,
                        ]);

                        session()->flash('success', 'Rekam medis berhasil disimpan & notifikasi dijadwalkan otomatis.');
                    } catch (\Exception $e) {
                        \Log::error("Gagal jadwalkan WA Fonnte ke {$noWa}: " . $e->getMessage());

                        LogWhatsapp::create([
                            'rekmed_umum_id' => $rekmed->id,
                            'nama_pasien' => $pasien->nama_lengkap,
                            'nomor_wa' => $noWa,
                            'pesan' => $pesan,
                            'waktu_kirim' => now(),
                            'status' => 'gagal',
                            'error_message' => $e->getMessage(),
                        ]);

                        session()->flash('error', 'Rekam medis tersimpan, tapi gagal menjadwalkan notifikasi.');
                    }
                } else {
                    session()->flash('error', 'Tidak dapat mengirim notifikasi karena data pasien tidak lengkap.');
                }
            } else {
                session()->flash('success', 'Rekam medis berhasil disimpan tanpa notifikasi.');
            }



            // Simpan ulang keluhan utama
            if (is_array($this->keluhanUtama)) {
                foreach ($this->keluhanUtama as $keluhan_id) {
                    // Jika ID numeric, langsung simpan ke pivot
                    if (is_numeric($keluhan_id)) {
                        $rekmed->keluhanUtamaPasien()->create([
                            'keluhan_id' => $keluhan_id,
                        ]);
                    }
                    // Jika ID buatan seperti temp-pusing
                    elseif (Str::startsWith($keluhan_id, 'temp-')) {
                        $namaBaru = Str::replaceFirst('temp-', '', $keluhan_id);

                        // Cek apakah nama ini sudah ada
                        $keluhan = \App\Models\Keluhan::firstOrCreate([
                            'nama' => $namaBaru,
                            'layanan_id' => $rekmed->pendaftaran->layanan_id,
                        ]);

                        $rekmed->keluhanUtamaPasien()->create([
                            'keluhan_id' => $keluhan->id,
                        ]);
                    }
                }
            }


            // Simpan ulang diagnosa tambahan
            if (is_array($this->diagnosisTambahan)) {
                foreach ($this->diagnosisTambahan as $diagnosa_id) {
                    if (is_numeric($diagnosa_id)) {
                        $rekmed->diagnosaTambahanPasien()->create([
                            'diagnosa_id' => $diagnosa_id,
                        ]);
                    } elseif (Str::startsWith($diagnosa_id, 'temp-')) {
                        $namaBaru = Str::replaceFirst('temp-', '', $diagnosa_id);
                        $diagnosa = Diagnosa::firstOrCreate([
                            'nama' => $namaBaru,
                            'layanan_id' => $rekmed->pendaftaran->layanan_id,
                        ]);

                        $rekmed->diagnosaTambahanPasien()->create([
                            'diagnosa_id' => $diagnosa->id,
                        ]);
                    }
                }
            }


            // Simpan ulang resep
            if (is_array($this->resepList)) {
                foreach ($this->resepList as $resep) {
                    $rekmed->resepPasien()->create([
                        'barang_id' => $resep['obat_id'],
                        'jumlah' => $resep['jumlah'],
                        'status' => 'permintaan',
                        'aturan_pakai' => $resep['aturan_pakai'],
                    ]);
                }
            }

            // Update status pendaftaran
            Pendaftaran::where('id', $this->pendaftaranId)
                ->update(['status' => 'selesai']);

            DB::commit();

            session()->flash('success', 'Rekam medis berhasil disimpan.');
            return redirect()->route('antrian');
        } catch (\Throwable $e) {
            DB::rollBack();
            logger()->error('Gagal simpan rekam medis: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menyimpan.');
        }
    }

    public function getPlanProperty()
    {
        $plan = [];

        // Tambah tindakan utama
        if ($this->tindakan) {
            $plan[] = "Tindakan Utama : {$this->tindakan}";
        }

        // Jika ada jadwal kontrol ulang
        if ($this->jadwalKontrolUlang) {
            $tglKontrol = \Carbon\Carbon::parse($this->jadwalKontrolUlang)->translatedFormat('d F Y');
            $catatan = $this->catatanJadwal ? " - {$this->catatanJadwal}" : '';
            $plan[] = "Kontrol Ulang : {$tglKontrol}{$catatan}";
        }

        // Catatan tambahan
        if ($this->catatan) {
            $plan[] = "Catatan Tambahan : {$this->catatan}";
        }

        // Tambah resep obat
        if (!empty($this->resepList)) {
            $plan[] = "Resep Obat :";
            foreach ($this->resepList as $item) {
                $plan[] = "- {$item['obat']} ({$item['jumlah']} {$item['satuan']}) → {$item['aturan_pakai']}";
            }
        }

        return implode("\n\n", $plan);
    }
    public function getAssesmentProperty()
    {
        $tdiagnosisUtama = null;
        $diagnosisTambahanList = [];

        if (!empty($this->diagnosisTambahan)) {
            // Pisahkan ID dari database dan input manual (temp-)
            $idDatabase = collect($this->diagnosisTambahan)
                ->filter(fn($id) => is_numeric($id))
                ->values()
                ->all();

            $idTemp = collect($this->diagnosisTambahan)
                ->filter(fn($id) => Str::startsWith($id, 'temp-'))
                ->map(fn($id) => Str::replaceFirst('temp-', '', $id))
                ->values()
                ->all();

            // Ambil nama dari database
            $namaDariDB = Diagnosa::whereIn('id', $idDatabase)
                ->pluck('nama')
                ->toArray();

            // Gabungkan semuanya
            $diagnosisTambahanList = array_merge($namaDariDB, $idTemp);
        }

        $diagnosaTambahanFormatted = null;
        if (!empty($diagnosisTambahanList)) {
            $diagnosaTambahanFormatted = "- Diagnosis Tambahan :\n" . collect($diagnosisTambahanList)
                ->map(fn($item) => "  • $item")
                ->implode("\n");
        }

        if (!empty($this->diagnosisUtama)) {
            if (is_numeric($this->diagnosisUtama)) {
                $diagnosa = Diagnosa::find($this->diagnosisUtama);
                $tdiagnosisUtama = $diagnosa ? $diagnosa->nama : null;
            } else {
                $tdiagnosisUtama = Str::replaceFirst('temp-', '', $this->diagnosisUtama);
            }
        }

        return collect([
            $this->diagnosisUtama ? "- Diagnosis Utama : $tdiagnosisUtama" : null,
            $this->keteranganUtama ? "- Keterangan Diagnosis Umum : $this->keteranganUtama" : null,
            $this->keparahan ? "- Tingkat Keparahan : $this->keparahan" : null,
            $diagnosaTambahanFormatted,
            $this->keteranganTambahan ? "- Keterangan Diagnosis Tambahan : $this->keteranganTambahan" : null,
        ])->filter()->implode("\n\n");
    }

    public function getSubjektifProperty()
    {
        $keluhanUtamaList = [];

        if (!empty($this->keluhanUtama)) {
            // Pisahkan ID yang berasal dari database dan yang buatan (temp-*)
            $idDatabase = collect($this->keluhanUtama)
                ->filter(fn($id) => is_numeric($id))
                ->values()
                ->all();

            $idTemp = collect($this->keluhanUtama)
                ->filter(fn($id) => Str::startsWith($id, 'temp-'))
                ->map(fn($id) => Str::replaceFirst('temp-', '', $id))
                ->values()
                ->all();

            // Ambil nama dari database
            $namaDariDB = Keluhan::whereIn('id', $idDatabase)
                ->pluck('nama')
                ->toArray();

            // Gabungkan dengan nama dari ID temp
            $keluhanUtamaList = array_merge($namaDariDB, $idTemp);
        }

        $keluhanUtamaFormatted = null;
        if (!empty($keluhanUtamaList)) {
            $keluhanUtamaFormatted = "- Keluhan utama:\n" . collect($keluhanUtamaList)
                ->map(fn($item) => "  • $item")
                ->implode("\n");
        }

        return collect([
            $keluhanUtamaFormatted,
            $this->keteranganKeluhan ? "- Keterangan Keluhan : $this->keteranganKeluhan" : null,
            $this->riwayatDahulu ? "- Riwayat penyakit dahulu : $this->riwayatDahulu" : null,
            $this->riwayatAlergi ? "- Alergi : $this->riwayatAlergi" : null,
            $this->riwayatKeluarga ? "- Riwayat keluarga : $this->riwayatKeluarga" : null,
            $this->riwayatSosial ? "- Riwayat sosial : $this->riwayatSosial" : null,
        ])->filter()->implode("\n\n");
    }

    public function getObjektifProperty()
    {
        return collect([
            $this->sistolik && $this->diastolik ? "- Tensi : {$this->sistolik}/{$this->diastolik} mmHg" : null,
            $this->suhu ? "- Suhu Badan : $this->suhu °C" : null,
            $this->lajuNadi ? "- Laju Nadi : $this->lajuNadi x/menit" : null,
            $this->lajuNafas ? "- Laju Nafas : $this->lajuNafas x/menit" : null,
            $this->tb ? "- Tinggi Badan : $this->tb cm" : null,
            $this->bb ? "- Berat Badan : $this->bb kg" : null,
            $this->pemeriksaanUmum ? "- Pemeriksaan Fisik Umum : $this->pemeriksaanUmum" : null,
            $this->pemeriksaanKhusus ? "- Pemeriksaan Khusus : $this->pemeriksaanKhusus" : null,

        ])->filter()->implode("\n\n");
    }

    public function getObatListProperty()
    {
        $pendaftaran = Pendaftaran::find($this->pendaftaranId);
        if (!$pendaftaran) return collect();

        return Barang::where('layanan_id', $pendaftaran->layanan_id)
            ->with(['rak.stokrak']) // Eager loading
            ->whereHas('rak.stokrak', function ($query) {
                $query->where('jumlah_sisa', '>', 0); // Hanya yang punya stok
            })
            ->get()
            ->map(function ($item) {
                // Hitung total stok dari semua rak
                $item->total_stok = $item->rak->flatMap->stokRak->sum('jumlah_sisa');
                return $item;
            });
    }

    public function updatedObat($value)
    {
        $barang = Barang::with(['rak.stokrak' => function ($query) {
            $query->where('jumlah_sisa', '>', 0);
        }])->find($value);

        $this->stokObat = $barang ? $barang->rak->flatMap->stokRak->sum('jumlah_sisa') : 0;
    }

    public function updatedJumlahObat($value)
    {
        if ($value > $this->stokObat) {
            $this->jumlahObatError = "Jumlah melebihi stok tersedia ($this->stokObat)";
        } else {
            $this->jumlahObatError = null;
        }
    }

    public function updated($propertyName)
    {
        $this->resetErrorBag($propertyName);
        $this->jumlahObatError = '';
    }


    public function tambahResep()
    {
        $this->resetErrorBag();
        $this->jumlahObatError = ''; // Reset error custom

        // Validasi bawaan Livewire
        $this->validate([
            'obat' => 'required',
            'jumlahObat' => 'required|numeric|min:1',
            'aturanObat' => 'required',
        ], [
            'obat.required' => 'Obat tidak boleh kosong.',
            'jumlahObat.required' => 'Jumlah tidak boleh kosong.',
            'jumlahObat.numeric' => 'Jumlah harus berupa angka.',
            'jumlahObat.min' => 'Jumlah minimal 1.',
            'aturanObat.required' => 'Aturan pakai harus diisi.',
        ]);

        // Validasi jumlah vs stok
        $barang = Barang::with('stokRaks')->findOrFail($this->obat);
        $stok = $barang->stokRaks->sum('jumlah_sisa') ?? 0;

        if ($this->jumlahObat > $stok) {
            $this->jumlahObatError = 'Jumlah melebihi stok tersedia (' . $stok . ').';
            return; // Penting: hentikan proses jika stok tidak cukup
        }

        // Tambahkan ke list resep
        $this->resepList[] = [
            'obat_id' => $this->obat,
            'obat' => $barang->nama_barang,
            'jumlah' => $this->jumlahObat,
            'satuan' => $this->satuanObat,
            'aturan_pakai' => $this->aturanObat,
        ];

        // Reset input
        $this->reset(['obat', 'jumlahObat', 'satuanObat', 'aturanObat']);
        $this->jumlahObatError = '';
    }


    public function hapusResep($index)
    {
        unset($this->resepList[$index]);
        $this->resepList = array_values($this->resepList);
    }

    public function mount($id)
    {
        $this->pendaftaran = Pendaftaran::with(['pasien', 'layanan', 'layananDetail'])->findOrFail($id);
        $this->pendaftaranId = $id;
        $this->pasienId = $this->pendaftaran->pasien_id;
        $this->totalKunjungan = Pendaftaran::where('pasien_id', $this->pasienId)
            ->whereNull('deleted_at')
            ->whereHas('rekmedUmum') // hanya pendaftaran yang sudah ada rekam medis
            ->count();
        $this->riwayatRekmed = RekmedUmum::with(['pendaftaran.layananDetail'])
            ->whereHas('pendaftaran', fn($q) => $q->where('pasien_id', $this->pasienId))
            ->whereNull('deleted_at') // jika pakai softdelete
            ->orderByDesc('created_at')
            ->get();

        $layananId = $this->pendaftaran->layanan_id;
        $this->adaWaApiAktif = \App\Models\WaApi::where('active', true)->exists();

        $this->daftarKeluhan = Keluhan::where('layanan_id', $layananId)
            ->pluck('nama', 'id')
            ->toArray();

        $this->daftarDiagnosisUmum = Diagnosa::where('layanan_id', $layananId)
            ->pluck('nama', 'id')
            ->toArray();

        // ✅ Tambahan untuk load data rekam medis jika sudah pernah disimpan
        $rekmed = RekmedUmum::where('id_pendaftaran', $id)->with(['keluhanUtamaPasien', 'diagnosaTambahanPasien', 'resepPasien'])->first();

        if ($rekmed) {
            $this->rekmedId = $rekmed->id; // optional jika perlu

            $this->keteranganKeluhan = $rekmed->keterangan_keluhan;
            $this->riwayatAlergi = $rekmed->alergi;
            $this->riwayatDahulu = $rekmed->riwayat_penyakit;
            $this->riwayatSosial = $rekmed->riwayat_sosial;
            $this->riwayatKeluarga = $rekmed->riwayat_keluarga;
            $this->sistolik = $rekmed->sistolik;
            $this->diastolik = $rekmed->diastolik;
            $this->suhu = $rekmed->suhu;
            $this->bb = $rekmed->bb;
            $this->tb = $rekmed->tb;
            $this->lajuNadi = $rekmed->laju_nadi;
            $this->lajuNafas = $rekmed->laju_nafas;
            $this->pemeriksaanUmum = $rekmed->pemeriksaan_umum;
            $this->pemeriksaanKhusus = $rekmed->pemeriksaan_khusus;
            $this->diagnosisUtama = $rekmed->id_diagnosa;
            $this->keteranganUtama = $rekmed->keterangan_diagnosa_utama;
            $this->keteranganTambahan = $rekmed->keterangan_diagnosa_tambahan;
            $this->keparahan = $rekmed->keparahan;
            $this->tindakan = $rekmed->tindakan;
            $this->jadwalKontrolUlang = $rekmed->jadwal_kontrol;
            $this->catatan = $rekmed->catatan_tambahan;
            $this->catatanJadwal = $rekmed->catatan_jadwal;
            $this->kontrolUlang =  !empty($this->jadwalKontrolUlang) || !empty($this->catatanJadwal);

            $this->nomorKontrol = $rekmed->nomor_pasien;
            $this->kirimWa = !empty($this->nomorKontrol);

            // Keluhan utama (pivot)
            $this->keluhanUtama = $rekmed->keluhanUtamaPasien->pluck('keluhan_id')->toArray();
            $this->diagnosisTambahan = $rekmed->diagnosaTambahanPasien->pluck('diagnosa_id')->toArray();
            // Resep obat (pivot)
            $this->resepList = $rekmed->resepPasien->map(function ($item) {
                return [
                    'obat_id' => $item->barang_id,
                    'obat' => optional($item->barang)->nama_barang, // ambil relasi barang jika ada
                    'jumlah' => $item->jumlah,
                    'satuan' => optional($item->barang)->satuan,
                    'aturan_pakai' => $item->aturan_pakai,
                ];
            })->toArray();
        }
    }

    public function render()
    {
        return view('livewire.pemeriksaan.kesehatan-umum')->extends('layouts.app', [
            'title' => $this->title // Kirim title ke layout
        ]);
    }
}
