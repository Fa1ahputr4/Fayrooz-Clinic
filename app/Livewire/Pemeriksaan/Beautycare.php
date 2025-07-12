<?php

namespace App\Livewire\Pemeriksaan;

use App\Models\Barang;
use App\Models\Diagnosa;
use App\Models\Keluhan;
use App\Models\LogWhatsapp;
use App\Models\Pendaftaran;
use App\Models\RekmedBeautycare;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; // ⬅️ Tambahkan ini
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Beautycare extends Component
{

    use WithFileUploads;

    public $title = 'Fayrooz | Pemeriksaan';
    public $pendaftaran;
    public $pendaftaranId;
    public $pasienId;
    public $rekmedId;
    //pemeriksaan
    public $daftarKeluhan = [], $keluhanUtama = [], $alergi, $keteranganKeluhan, $warnaKulit, $jenisKulit, $teksturKulit, $kelembapanKulit, $jenisJerawat = [], $tingkatJerawat = null, $flek, $riwayatPenyakit, $produkTerakhir;
    //diagnosa
    public $daftarDiagnosisUmum = [], $diagnosis = [], $keteranganDiagnosis, $tingkatKeparahan;
    //tindakan
    public $barangList = [], $tindakan = [], $keteranganTindakan, $saranTreatment, $barang, $satuanBarang, $jumlahBarang, $aturanPakai, $jadwalKontrolUlang, $catatanJadwal, $kontrolUlang = false, $noPasien, $kirimWa = false, $adaWaApiAktif = false;
    //foto
    public $foto_before = [], $foto_after = [];
    public $foto_before_upload = [], $foto_after_upload = [];
    public $existingBeforePhotos = [];
    public $existingAfterPhotos = [];
    public array $deletedPhotoIds = [];

    public  $stokBarang = 0,  $jumlahBarangError = null;
    public $ringkasanPemeriksaan = '';
    public $ringkasanDiagnosa = '';
    public $ringkasanTindakan = '';
    public $ringkasanProduk = '';

    public function generateRingkasanProduk()
    {
        $hasil = [];

        if (!empty($this->barangList)) {
            $produk = collect($this->barangList)->map(function ($item) {
                return "{$item['barang']} ({$item['jumlah']} {$item['satuan']}) - {$item['aturan_pakai']}";
            })->implode('; ');
            $hasil[] = '- Resep produk: ' . $produk;
        }

        $this->ringkasanProduk = implode("\n\n", $hasil);
    }
    public function generateRingkasanTindakan()
    {
        $hasil = [];

        if (!empty($this->tindakan)) {
            $hasil[] = '- Tindakan: ' . implode(', ', $this->tindakan);
        }

        if ($this->keteranganTindakan) {
            $hasil[] = '- Keterangan tindakan: ' . $this->keteranganTindakan;
        }

        if ($this->saranTreatment) {
            $hasil[] = '- Saran treatment: ' . $this->saranTreatment;
        }

        if ($this->kontrolUlang) {
            $tanggal = $this->jadwalKontrolUlang ? \Carbon\Carbon::parse($this->jadwalKontrolUlang)->translatedFormat('l, d F Y H:i') : '-';
            $hasil[] = '- Kontrol ulang: ' . $tanggal;

            if ($this->catatanJadwal) {
                $hasil[] = '- Catatan kontrol: ' . $this->catatanJadwal;
            }

            if ($this->noPasien) {
                $hasil[] = '- Nomor WA kontrol: ' . $this->noPasien;
            }
        }

        $this->ringkasanTindakan = implode("\n\n", $hasil);
    }


    public function generateRingkasanDiagnosa()
    {
        $hasil = [];
        if (!empty($this->diagnosis)) {
            $labelDiagnosis = [];

            foreach ($this->diagnosis as $id) {
                if (Str::startsWith($id, 'temp-')) {
                    $labelDiagnosis[] = Str::replaceFirst('temp-', '', $id);
                } elseif (isset($this->daftarDiagnosisUmum[$id])) {
                    $labelDiagnosis[] = $this->daftarDiagnosisUmum[$id];
                }
            }

            $hasil[] = '- Diagnosis : ' . implode(', ', $labelDiagnosis);
        }

        if ($this->tingkatKeparahan) {
            $hasil[] = "- Tingkat keparahan : $this->tingkatKeparahan";
        }

        if ($this->keteranganDiagnosis) {
            $hasil[] = "- Keterangan diagnosis : $this->keteranganDiagnosis";
        }
        $this->ringkasanDiagnosa = implode("\n\n", $hasil);
    }

    public function generateRingkasanPemeriksaan()
    {
        $hasil = [];

        if (!empty($this->keluhanUtama)) {
            $labelKeluhan = [];

            foreach ($this->keluhanUtama as $id) {
                if (Str::startsWith($id, 'temp-')) {
                    $labelKeluhan[] = Str::replaceFirst('temp-', '', $id);
                } elseif (isset($this->daftarKeluhan[$id])) {
                    $labelKeluhan[] = $this->daftarKeluhan[$id];
                }
            }

            $hasil[] = '- Keluhan : ' . implode(', ', $labelKeluhan);
        }


        if ($this->keteranganKeluhan) {
            $hasil[] = "- Keterangan Keluhan : $this->keteranganKeluhan";
        }

        if ($this->warnaKulit) {
            $hasil[] = "- Warna kulit : $this->warnaKulit";
        }

        if ($this->jenisKulit) {
            $hasil[] = "- Jenis kulit : $this->jenisKulit";
        }

        if ($this->teksturKulit) {
            $hasil[] = "- Tekstur kulit : $this->teksturKulit";
        }

        if ($this->kelembapanKulit) {
            $hasil[] = "- Kelembapan kulit : $this->kelembapanKulit";
        }

        if ($this->jenisJerawat && is_array($this->jenisJerawat) && count($this->jenisJerawat)) {
            $hasil[] = "- Jenis jerawat : " . implode(', ', $this->jenisJerawat);
        }

        if ($this->tingkatJerawat) {
            $hasil[] = "- Tingkat jerawat : $this->tingkatJerawat";
        }

        if ($this->flek) {
            $hasil[] = "- Flek : $this->flek";
        }

        if ($this->alergi) {
            $hasil[] = "- Alergi : $this->alergi";
        }

        if ($this->riwayatPenyakit) {
            $hasil[] = "- Riwayat penyakit : $this->riwayatPenyakit";
        }

        if ($this->produkTerakhir) {
            $hasil[] = "- Produk terakhir : $this->produkTerakhir";
        }

        $this->ringkasanPemeriksaan = implode("\n\n", $hasil);
    }


    public function save()
    {
        Log::info('[REKMED] Memulai proses penyimpanan...');

        $this->validate([
            'pasienId' => 'required',
            'pendaftaranId' => 'required',
        ]);

        DB::beginTransaction();

        try {
            \Log::info('[REKMED] Validasi berhasil. pasienId=' . $this->pasienId . ', pendaftaranId=' . $this->pendaftaranId);

            $rekmed = RekmedBeautycare::with('pendaftaran.layanan', 'pendaftaran.pasien')
                ->where('id_pendaftaran', $this->pendaftaranId)
                ->first();

            if ($rekmed) {
                \Log::info('[REKMED] Update data rekam medis ID: ' . $rekmed->id);

                $rekmed->update([
                    'id_pasien' => $this->pasienId,
                    'keterangan_keluhan' => $this->keteranganKeluhan,
                    'warna_kulit' => $this->warnaKulit,
                    'jenis_kulit' => $this->jenisKulit,
                    'tekstur_kulit' => $this->teksturKulit,
                    'kelembapan_kulit' => $this->kelembapanKulit,
                    'jenis_jerawat' => json_encode($this->jenisJerawat ?? []),
                    'tingkat_jerawat' => $this->tingkatJerawat,
                    'flek' => $this->flek,
                    'riwayat_alergi' => $this->alergi,
                    'riwayat_penyakit' => $this->riwayatPenyakit,
                    'produk_kosmetik_terakhir' => $this->produkTerakhir,
                    'keterangan_diagnosis' => $this->keteranganDiagnosis,
                    'tingkat_keparahan' => $this->tingkatKeparahan,
                    'tindakan_dilakukan' => is_array($this->tindakan) ? json_encode($this->tindakan) : json_encode([]),
                    'keterangan_tindakan' => $this->keteranganTindakan,
                    'saran_treatment' => $this->saranTreatment,
                    'kontrol_ulang' => $this->kontrolUlang,
                    'jadwal_kontrol_ulang' => $this->jadwalKontrolUlang,
                    'nomor_pasien' => $this->noPasien,
                    'catatan_kontrol' => $this->catatanJadwal,
                ]);
                $rekmed->keluhanPasienBc()->delete();
                $rekmed->diagnosaPasienBc()->delete();
                $rekmed->resepProdukBc()->delete();
            } else {
                \Log::info('[REKMED] Rekam medis baru akan dibuat');

                $rekmed = RekmedBeautycare::create([
                    'id_pasien' => $this->pasienId,
                    'id_pendaftaran' => $this->pendaftaranId,
                    'keterangan_keluhan' => $this->keteranganKeluhan,
                    'warna_kulit' => $this->warnaKulit,
                    'jenis_kulit' => $this->jenisKulit,
                    'tekstur_kulit' => $this->teksturKulit,
                    'kelembapan_kulit' => $this->kelembapanKulit,
                    'jenis_jerawat' => json_encode($this->jenisJerawat ?? []),
                    'tingkat_jerawat' => $this->tingkatJerawat,
                    'flek' => $this->flek,
                    'riwayat_alergi' => $this->alergi,
                    'riwayat_penyakit' => $this->riwayatPenyakit,
                    'produk_kosmetik_terakhir' => $this->produkTerakhir,
                    'keterangan_diagnosis' => $this->keteranganDiagnosis,
                    'tingkat_keparahan' => $this->tingkatKeparahan,
                    'tindakan_dilakukan' => is_array($this->tindakan) ? json_encode($this->tindakan) : json_encode([]),
                    'keterangan_tindakan' => $this->keteranganTindakan,
                    'saran_treatment' => $this->saranTreatment,
                    'kontrol_ulang' => $this->kontrolUlang,
                    'jadwal_kontrol_ulang' => $this->jadwalKontrolUlang,
                    'nomor_pasien' => $this->noPasien,
                    'catatan_kontrol' => $this->catatanJadwal,
                ]);
            }

            if (!empty($this->deletedPhotoIds)) {
                foreach ($this->deletedPhotoIds as $photoId) {
                    $photo = \App\Models\RekmedBeautycarePhoto::find($photoId);
                    if ($photo) {
                        Storage::disk('public')->delete($photo->file_path);
                        $photo->delete();
                    }
                }
            }

            if ($this->foto_before_upload) {
                foreach ($this->foto_before_upload as $file) {
                    $path = $file->store("rekmed/beautycare/{$rekmed->id}/before", 'public');

                    \App\Models\RekmedBeautycarePhoto::create([
                        'rekmed_bc_id' => $rekmed->id,
                        'file_path' => $path,
                        'tipe' => 'before',
                    ]);
                }
            }

            if ($this->foto_after_upload) {
                foreach ($this->foto_after_upload as $file) {
                    $path = $file->store("rekmed/beautycare/{$rekmed->id}/after", 'public');

                    \App\Models\RekmedBeautycarePhoto::create([
                        'rekmed_bc_id' => $rekmed->id,
                        'file_path' => $path,
                        'tipe' => 'after',
                    ]);
                }
            }


            if (is_array($this->keluhanUtama)) {
                foreach ($this->keluhanUtama as $keluhan_id) {
                    // Jika ID numeric, langsung simpan ke pivot
                    if (is_numeric($keluhan_id)) {
                        $rekmed->keluhanPasienBc()->create([
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

                        $rekmed->keluhanPasienBc()->create([
                            'keluhan_id' => $keluhan->id,
                        ]);
                    }
                }
            }

            \Log::info('[REKMED] Simpan diagnosa tambahan...');
            if (is_array($this->diagnosis)) {
                foreach ($this->diagnosis as $diagnosa_id) {
                    if (is_numeric($diagnosa_id)) {
                        $rekmed->diagnosaPasienBc()->create([
                            'diagnosa_id' => $diagnosa_id,
                        ]);
                    } elseif (Str::startsWith($diagnosa_id, 'temp-')) {
                        $namaBaru = Str::replaceFirst('temp-', '', $diagnosa_id);
                        $diagnosa = Diagnosa::firstOrCreate([
                            'nama' => $namaBaru,
                            'layanan_id' => $rekmed->pendaftaran->layanan_id,
                        ]);
                        $rekmed->diagnosaPasienBc()->create([
                            'diagnosa_id' => $diagnosa->id,
                        ]);
                    }
                }
            }

            \Log::info('[REKMED] Simpan resep pasien...');
            if (is_array($this->barangList)) {
                foreach ($this->barangList as $resep) {
                    $rekmed->resepProdukBc()->create([
                        'barang_id' => $resep['barang_id'],
                        'jumlah' => $resep['jumlah'],
                        'status' => 'permintaan',
                        'aturan_pakai' => $resep['aturan_pakai'],
                    ]);
                }
            }

            \Log::info('[REKMED] Update status pendaftaran...');
            Pendaftaran::where('id', $this->pendaftaranId)->update([
                'status' => 'selesai'
            ]);

            $logExists = LogWhatsapp::where('rekmed_bc_id', $rekmed->id)
                ->where('status', 'sukses')
                ->exists();

            if ($rekmed->nomor_pasien && $rekmed->jadwal_kontrol_ulang && !$logExists) {
                $pasien = $rekmed->pendaftaran->pasien ?? null;
                $noWaInput = $this->noPasien ?: ($pasien->no_wa ?? null);
                $noWa = $this->formatNomorWa($noWaInput);

                if ($noWa && $pasien) {
                    $pesan = "Halo *{$pasien->nama_lengkap}*,\n\nIni pengingat kontrol ulang Anda di klinik kami pada (*" .
                        Carbon::parse($rekmed->jadwal_kontrol_ulang)->translatedFormat('l, d F Y') . "*).\n\n📌 Catatan: {$rekmed->catatan_kontrol}\n\nMohon hadir tepat waktu, terima kasih 🙏.";

                    $waApi = \App\Models\WaApi::where('active', true)->first();

                    if (!$waApi) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'wa_api' => 'Tidak ada API WhatsApp yang aktif. Silakan atur terlebih dahulu.',
                        ]);
                    }

                    $jadwalWIB = Carbon::create(2025, 6, 18, 8, 0, 0, 'Asia/Jakarta');
                    $timestampUTC = $jadwalWIB->copy()->setTimezone('UTC')->timestamp;

                    try {
                        \Log::info('[REKMED] Kirim WA ke: ' . $noWa);

                        Http::withHeaders([
                            'Authorization' => $waApi->token,
                        ])->post($waApi->base_url, [
                            'target' => $noWa,
                            'message' => $pesan,
                            'countryCode' => '62',
                        ]);

                        $rekmed->update(['notifikasi_terkirim' => true]);
                        LogWhatsapp::create([
                            'rekmed_bc_id' => $rekmed->id,
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

                        // ❌ Simpan log gagal
                        LogWhatsapp::create([
                            'rekmed_bc_id' => $rekmed->id,
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

            \Log::info('[REKMED] Commit & redirect');
            DB::commit();
            return redirect()->route('antrian');
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('[REKMED] ERROR: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            session()->flash('error', 'Terjadi kesalahan saat menyimpan.');
        }
    }



    protected function formatNomorWa($nomor)
    {
        if (!$nomor) return null;

        $nomor = preg_replace('/[^0-9]/', '', $nomor);

        // Jika sudah diawali 62 atau 0
        if (Str::startsWith($nomor, '62')) {
            return $nomor;
        } elseif (Str::startsWith($nomor, '0')) {
            return '62' . substr($nomor, 1);
        }

        return '62' . $nomor;
    }

    public function ambilNomorPasien()
    {
        // Cek apakah data pendaftaran sudah dimuat
        $pasien = optional(optional($this->pendaftaran)->pasien);

        if ($pasien && $pasien->no_telepon) {
            $this->noPasien = $pasien->no_telepon;
        } else {
            $this->addError('noPasien', 'Pasien tidak memiliki nomor telepon.');
        }
    }

    public function getBarangListProperty()
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

    public function updatedBarang($value)
    {
        $barang = Barang::with(['rak.stokrak' => function ($query) {
            $query->where('jumlah_sisa', '>', 0);
        }])->find($value);

        $this->stokBarang = $barang ? $barang->rak->flatMap->stokRak->sum('jumlah_sisa') : 0;
    }

    public function updatedJumlahBarang($value)
    {
        if ($value > $this->stokBarang) {
            $this->jumlahBarangError = "Jumlah melebihi stok tersedia ($this->stokBarang)";
        } else {
            $this->jumlahBarangError = null;
        }
    }

    public function updated($propertyName)
    {
        $this->generateRingkasanPemeriksaan();
        $this->generateRingkasanDiagnosa();
        $this->generateRingkasanTindakan();
        $this->generateRingkasanProduk();
        $this->resetErrorBag($propertyName);
        $this->jumlahBarangError = '';
    }


    public function tambahBarang()
    {
        $this->resetErrorBag();
        $this->jumlahBarangError = ''; // Reset error custom

        // Validasi bawaan Livewire
        $this->validate([
            'barang' => 'required',
            'jumlahBarang' => 'required|numeric|min:1',
            'aturanPakai' => 'required',
        ], [
            'barang.required' => 'Barang tidak boleh kosong.',
            'jumlahBarang.required' => 'Jumlah tidak boleh kosong.',
            'jumlahBarang.numeric' => 'Jumlah harus berupa angka.',
            'jumlahBarang.min' => 'Jumlah minimal 1.',
            'aturanPakai.required' => 'Aturan pakai harus diisi.',
        ]);

        // Validasi jumlah vs stok
        $barang = Barang::with('stokRaks')->findOrFail($this->barang);
        $stok = $barang->stokRaks->sum('jumlah_sisa') ?? 0;

        if ($this->jumlahBarang > $stok) {
            $this->jumlahBarangError = 'Jumlah melebihi stok tersedia (' . $stok . ').';
            return; // Penting: hentikan proses jika stok tidak cukup
        }

        // Tambahkan ke list resep
        $this->barangList[] = [
            'barang_id' => $this->barang,
            'barang' => $barang->nama_barang,
            'jumlah' => $this->jumlahBarang,
            'satuan' => $this->satuanBarang,
            'aturan_pakai' => $this->aturanPakai,
        ];

        // Reset input
        $this->reset(['barang', 'jumlahBarang', 'satuanBarang', 'aturanPakai']);
        $this->jumlahBarangError = '';
    }


    public function hapusBarang($index)
    {
        unset($this->barangList[$index]);
        $this->barangList = array_values($this->barangList);
    }

    public function mount($id)
    {
        $this->pendaftaran = Pendaftaran::with(['pasien', 'layanan'])->findOrFail($id);
        $this->pendaftaranId = $id;
        $this->pasienId = $this->pendaftaran->pasien_id;
        $layananId = $this->pendaftaran->layanan_id;
        $this->adaWaApiAktif = \App\Models\WaApi::where('active', true)->exists();

        $this->daftarKeluhan = Keluhan::where('layanan_id', $layananId)
            ->pluck('nama', 'id')
            ->toArray();

        $this->daftarDiagnosisUmum = Diagnosa::where('layanan_id', $layananId)
            ->pluck('nama', 'id')
            ->toArray();

        $rekmed = \App\Models\RekmedBeautycare::where('id_pendaftaran', $id)->first();

        if ($rekmed) {
            $this->rekmedId = $rekmed->id; // jika ingin digunakan untuk update nanti

            $this->keteranganKeluhan = $rekmed->keterangan_keluhan;
            $this->warnaKulit = $rekmed->warna_kulit;
            $this->jenisKulit = $rekmed->jenis_kulit;
            $this->teksturKulit = $rekmed->tekstur_kulit;
            $this->kelembapanKulit = $rekmed->kelembapan_kulit;

            $this->jenisJerawat = json_decode($rekmed->jenis_jerawat, true) ?? [];
            $this->tingkatJerawat = $rekmed->tingkat_jerawat;
            $this->flek = $rekmed->flek;

            $this->alergi = $rekmed->riwayat_alergi;
            $this->riwayatPenyakit = $rekmed->riwayat_penyakit;
            $this->produkTerakhir = $rekmed->produk_kosmetik_terakhir;
            $this->keteranganDiagnosis = $rekmed->keterangan_diagnosis;
            $this->tingkatKeparahan = $rekmed->tingkat_keparahan;

            $this->tindakan = json_decode($rekmed->tindakan_dilakukan, true) ?? [];
            $this->keteranganTindakan = $rekmed->keterangan_tindakan;
            $this->saranTreatment = $rekmed->saran_treatment;


            $this->jadwalKontrolUlang = $rekmed->jadwal_kontrol_ulang;
            $this->catatanJadwal = $rekmed->catatan_kontrol;

            $this->noPasien = $rekmed->nomor_pasien;
            $this->kontrolUlang = !empty($this->jadwalKontrolUlang) || !empty($this->catatanJadwal) || !empty($this->noPasien);
            $this->kirimWa = !empty($this->noPasien);

            // Jika kamu juga punya foto before/after
            $this->foto_before = $rekmed->photos()
                ->where('tipe', 'before')
                ->get()
                ->map(function ($photo) {
                    return [
                        'id' => $photo->id,
                        'path' => $photo->file_path,
                        'url' => asset('storage/' . $photo->file_path) // atau Storage::url() jika menggunakan Laravel Filesystem
                    ];
                })
                ->toArray();

            $this->foto_after = $rekmed->photos()
                ->where('tipe', 'after')
                ->get()
                ->map(function ($photo) {
                    return [
                        'id' => $photo->id,
                        'path' => $photo->file_path,
                        'url' => asset('storage/' . $photo->file_path)
                    ];
                })
                ->toArray();
            // Diagnosa tambahan dan keluhan (jika menggunakan relasi pivot lain)
            $this->diagnosis = $rekmed->diagnosaPasienBc()->pluck('diagnosa_id')->toArray();
            $this->keluhanUtama = $rekmed->keluhanPasienBc()->pluck('keluhan_id')->toArray();

            $this->barangList = $rekmed->resepProdukBc->map(function ($item) {
                return [
                    'barang_id' => $item->barang_id,
                    'barang' => optional($item->barang)->nama_barang, // ambil relasi barang jika ada
                    'jumlah' => $item->jumlah,
                    'satuan' => optional($item->barang)->satuan,
                    'aturan_pakai' => $item->aturan_pakai,
                ];
            })->toArray();
        }
    }
    public function render()
    {
        return view('livewire.pemeriksaan.beautycare')->extends('layouts.app' , [
            'title' => $this->title // Kirim title ke layout
        ]);
    }
}
