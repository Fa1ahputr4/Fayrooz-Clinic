<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = ['kode_barang', 'nama_barang', 'satuan', 'jumlah_stok', 'jenis'];

    public function riwayatMasuk()
    {
        return $this->hasMany(BarangMasuk::class, 'id_barang');
    }

    
}
