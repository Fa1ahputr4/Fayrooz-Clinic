<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosaPasienBc extends Model
{
    protected $table = 'diagnosa_pasien_bc'; // ✅ Tambahkan baris ini

    protected $fillable = [
        'rekmed_bc_id',
        'diagnosa_id',
    ];

    public function diagnosa()
    {
        return $this->belongsTo(Diagnosa::class, 'diagnosa_id');
    }
}
