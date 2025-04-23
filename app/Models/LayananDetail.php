<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LayananDetail extends Model
{
    use HasFactory;

    protected $table = 'layanan_details';

    protected $fillable = [
        'layanan_id',
        'kode_layanan',
        'nama_layanan',
        'deskripsi_layanan',
        'harga_layanan',
        'is_active'
    ];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }
}
