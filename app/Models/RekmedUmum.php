<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekmedUmum extends Model
{
    protected $fillable = [
        'id_pasien',
        'id_pendaftaran',
        'id_layanan',

        // anamnesis
        'keterangan_keluhan',
        'alergi',
        'riwayat_penyakit',
        'riwayat_sosial',
        'riwayat_keluarga',

        // pemeriksaan
        'sistolik',
        'diastolik',
        'suhu',
        'bb',
        'tb',
        'laju_nadi',
        'laju_nafas',
        'pemeriksaan_umum',
        'pemeriksaan_khusus',

        // diagnosis
        'id_diagnosa',
        'keterangan_diagnosa_utama',
        'keterangan_diagnosa_tambahan',
        'keparahan',

        // tindakan & plan
        'tindakan',
        'kontrol_ulang',
        'nomor_pasien',
        'jadwal_kontrol',
        'catatan_tambahan',
        'catatan_jadwal',
    ];
    public function keluhanUtamaPasien()
    {
        return $this->hasMany(KeluhanPasien::class, 'history_id');
    }

    public function diagnosaTambahanPasien()
    {
        return $this->hasMany(DiagnosaTambahanPasien::class, 'history_id');
    }

    public function resepPasien()
    {
        return $this->hasMany(ResepPasien::class, 'history_id');
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
}
