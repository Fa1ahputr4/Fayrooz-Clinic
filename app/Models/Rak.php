<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rak extends Model
{
    use SoftDeletes;
    protected $table = 'rak';
    protected $fillable = ['kode_rak', 'id_barang', 'nama_rak', 'kapasitas', 'keterangan', 'created_by', 'updated_by'];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function stokrak()
    {
        return $this->hasMany(StokRak::class);
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
