<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{

    protected $fillable = [
        'tgl_keluar',
        'rak_id',
        'stok_rak_id',
        'stok_umum_id',
        'barang_id',
        'barang_masuk_id',
        'jumlah',
        'status_keluar',
        'tanggal_keluar',
        'keterangan',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function rak()
    {
        return $this->belongsTo(Rak::class);
    }

    public function stok_rak()
    {
        return $this->belongsTo(StokRak::class);
    }

    // public function pasien()
    // {
    //     return $this->belongsTo(Pasien::class);
    // }

    // public function dokter()
    // {
    //     return $this->belongsTo(User::class, 'dokter_id'); // jika dokter disimpan via user table
    // }

    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'user_id');
    // }

}
