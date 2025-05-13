<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangMasuk extends Model
{
    use HasFactory;

    // Tulis nama tabel dengan tanda kutip agar dikenali sebagai string
    protected $table = 'barang_masuk';

    // Kolom yang bisa diisi
    protected $fillable = [
        'id_barang',
        'kode_masuk',
        'jumlah',
        'satuan',
        'tanggal_masuk',
        'batch_no',
        'exp_date',
        'total_harga',
        'keterangan',
        'is_delete',
    ];

    // Relasi dengan model Barang (BarangMasuk memiliki Barang)
    public function barang()
    {
        // Secara default Laravel akan mencari kolom barang_id sebagai foreign key
        return $this->belongsTo(Barang::class, 'id_barang');
    }
    public function stokRak()
    {
        return $this->hasMany(StokRak::class, 'barang_masuk_id');
    }

        public function stokUmum()
    {
        return $this->hasOne(StokUmum::class, 'barang_masuk_id');
    }

}
