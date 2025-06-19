<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnosa extends Model
{
    protected $fillable = ['nama', 'deskripsi', 'layanan_id'];
}
