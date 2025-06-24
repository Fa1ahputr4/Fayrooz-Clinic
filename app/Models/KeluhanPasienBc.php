<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeluhanPasienBc extends Model
{

    protected $table = 'keluhan_pasien_bc'; // ✅ Tambahkan baris ini

    protected $fillable = [
        'rekmed_bc_id',
        'keluhan_id',
    ];

    public function keluhan()
    {
        return $this->belongsTo(Keluhan::class, 'keluhan_id');
    }
}
