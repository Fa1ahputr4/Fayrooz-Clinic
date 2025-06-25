<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    protected $fillable = [
        'kode_pendaftaran',
        'nomor_antrian',
        'pasien_id',
        'layanan_id',
        'detail_layanan_id',
        'status',
        'tanggal_kunjungan',
        'catatan'
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }
    public function layanandetail()
    {
        return $this->belongsTo(LayananDetail::class, 'detail_layanan_id'); // harus sesuai dengan kolom di tabel pendaftarans
    }
}
