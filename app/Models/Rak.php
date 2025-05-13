<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rak extends Model
{
    protected $fillable = ['kode_rak', 'id_barang', 'nama_rak', 'kapasitas', 'keterangan'];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function stokrak()
    {
        return $this->hasMany(StokRak::class);
    }
}
