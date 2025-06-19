<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResepPasien extends Model
{
    protected $fillable = [
        'history_id',
        'barang_id',
        'jumlah',
        'aturan_pakai',
        'status'
    ];

    // App\Models\ResepPasien.php
    public function rekmedUmum  ()
    {
        return $this->belongsTo(RekmedUmum::class, 'history_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
