<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokRak extends Model
{
    protected $table = 'stok_rak';
    protected $fillable = ['rak_id', 'barang_masuk_id', 'barang_keluar_id', 'barang_id', 'jumlah_barang', 'jumlah_sisa', 'keterangan', 'created_by', 'updated_by'];

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
