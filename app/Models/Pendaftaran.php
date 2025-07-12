<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pendaftaran extends Model
{
    use SoftDeletes;

    protected $table = 'pendaftaran';

    protected $fillable = [
        'kode_pendaftaran',
        'nomor_antrian',
        'pasien_id',
        'layanan_id',
        'detail_layanan_id',
        'status',
        'tanggal_kunjungan',
        'catatan',
        'nama_penanggung_jawab',
        'kontak_penanggung_jawab',
        'created_by',
        'updated_by'
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id', 'id');
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id', 'id');
    }

    public function layananDetail()
    {
        return $this->belongsTo(LayananDetail::class, 'detail_layanan_id', 'id');
    }

    public function rekmedUmum()
    {
        return $this->hasOne(RekmedUmum::class, 'id_pendaftaran');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    //Relasi user saat ubah data
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
