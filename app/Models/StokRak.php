<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokRak extends Model
{
    protected $fillable = ['rak_id', 'barang_masuk_id', 'barang_keluar_id', 'barang_id', 'jumlah_barang', 'jumlah_sisa', 'keterangan'];

    public function rak()
    {
        return $this->belongsTo(Rak::class, 'rak_id');
    }

     public function barang_masuk()
    {
        return $this->belongsTo(BarangMasuk::class, 'barang_masuk_id');
    }

    public function barang_keluar()
    {
        return $this->belongsTo(BarangKeluar::class, 'barang_keluar_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
