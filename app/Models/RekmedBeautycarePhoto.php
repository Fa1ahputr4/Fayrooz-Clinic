<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekmedBeautycarePhoto extends Model
{
    protected $fillable = [
        'rekmed_bc_id',
        'file_path',
        'tipe',
        'keterangan'
    ];

    public function rekmedBc()
    {
        return $this->belongsTo(RekmedBeautycare::class, 'rekmed_bc_id');
    }
}
