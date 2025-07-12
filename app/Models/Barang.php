<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $fillable = ['kode_barang', 'nama_barang', 'satuan', 'jumlah_stok', 'jenis', 'created_by', 'updated_by'];

    public function riwayatMasuk()
    {
        return $this->hasMany(BarangMasuk::class, 'id_barang');
    }

    public function rak()
    {
        return $this->hasMany(Rak::class, 'id_barang');
    }

    public function stokRak()
    {
        return $this->hasOneThrough(
            StokRak::class, // target
            Rak::class,     // perantara
            'id_barang',    // foreign key di Rak ke Barang
            'rak_id',       // foreign key di StokRak ke Rak
            'id',           // local key di Barang
            'id'            // local key di Rak
        );
    }

    public function stokRaks()
    {
        return $this->hasManyThrough(
            StokRak::class, // Model tujuan
            Rak::class,     // Model perantara
            'id_barang',    // foreign key di Rak yang menunjuk ke Barang
            'rak_id',       // foreign key di StokRak yang menunjuk ke Rak
            'id',           // primary key di Barang
            'id'            // primary key di Rak
        );
    }


    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id'); // asumsi kolom ini ada
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
