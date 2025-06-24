<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekmedBeautycare extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_pasien',
        'id_pendaftaran',
        'id_layanan',

        // Pemeriksaan section
        'keterangan_keluhan',
        'warna_kulit',
        'jenis_kulit',
        'tekstur_kulit',
        'kelembapan_kulit',

        // Jerawat section
        'memiliki_jerawat',
        'jenis_jerawat',
        'tingkat_jerawat',

        // Additional skin info
        'flek',
        'riwayat_alergi',
        'riwayat_penyakit',
        'produk_kosmetik_terakhir',

        'keterangan_diagnosis',
        'tingkat_keparahan',

        // Tindakan & Saran section
        'tindakan_dilakukan',
        'keterangan_tindakan',
        'saran_treatment',

        // Kontrol ulang
        'kontrol_ulang',
        'jadwal_kontrol_ulang',
        'nomor_pasien',
        'catatan_kontrol',
    ];

    protected $casts = [
        'tindakan_dilakukan' => 'array',
    ];


    public function keluhanPasienBc()
    {
        return $this->hasMany(KeluhanPasienBc::class, 'rekmed_bc_id');
    }

    public function diagnosaPasienBc()
    {
        return $this->hasMany(DiagnosaPasienBc::class, 'rekmed_bc_id');
    }

    public function resepProdukBc()
    {
        return $this->hasMany(ResepProdukBc::class, 'rekmed_bc_id');
    }

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'id_pendaftaran');
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien');
    }

    public function diagnosa()
    {
        return $this->belongsTo(Diagnosa::class, 'id_diagnosa');
    }

    public function photos()
    {
        return $this->hasMany(RekmedBeautycarePhoto::class, 'rekmed_bc_id');
    }
}
