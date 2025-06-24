<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResepProdukBc extends Model
{
    protected $table = 'resep_produk_bc'; // ✅ Tambahkan baris ini

    protected $fillable = [
        'rekmed_bc_id',
        'barang_id',
        'jumlah',
        'aturan_pakai',
        'status'
    ];

    // App\Models\ResepPasien.php
    public function rekmedBc()
    {
        return $this->belongsTo(RekmedBeautycare::class, 'rekmed_bc_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
