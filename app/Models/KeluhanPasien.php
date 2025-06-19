<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeluhanPasien extends Model
{
    protected $fillable = [
        'history_id',
        'keluhan_id',
    ];

    public function keluhan()
    {
        return $this->belongsTo(Keluhan::class, 'keluhan_id');
    }
}
