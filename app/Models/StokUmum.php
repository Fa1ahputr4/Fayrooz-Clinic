<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StokUmum extends Model
{
     protected $table = 'stok_umum';

    // Tentukan kolom yang dapat diisi secara mass-assignment
    protected $fillable = [
        'barang_masuk_id', 
        'jumlah_tersedia', 
        'created_at', 
        'updated_at'
    ];

    // Tentukan relasi dengan model BarangMasuk (jika ada)
    public function barangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class, 'barang_masuk_id');
    }
}
