<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosaTambahanPasien extends Model
{
    protected $fillable = [
        'history_id',
        'diagnosa_id',
    ];

    public function diagnosa()
    {
        return $this->belongsTo(Diagnosa::class, 'diagnosa_id');
    }
}
