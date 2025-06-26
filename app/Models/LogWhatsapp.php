<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogWhatsapp extends Model
{
    protected $fillable = [
    'rekmed_umum_id',
    'rekmed_bc_id',
    'nama_pasien',
    'nomor_wa',
    'pesan',
    'waktu_kirim',
    'status',
    'error_message',
];

public function rekmedUmum()
{
    return $this->belongsTo(RekmedUmum::class, 'rekmed_umum_id');
}

public function rekmedBc()
{
    return $this->belongsTo(RekmedBeautycare::class, 'rekmed_bc_id');
}


}
