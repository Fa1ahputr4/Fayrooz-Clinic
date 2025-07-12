<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class LayananDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'layanan_details';

    protected $fillable = [
        'layanan_id',
        'kode_layanan',
        'nama_layanan',
        'deskripsi_layanan',
        'created_by',
        'updated_by'
    ];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
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
