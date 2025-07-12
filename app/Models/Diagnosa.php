<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Diagnosa extends Model
{
    use SoftDeletes;
    protected $table = 'diagnosis';
    protected $fillable = ['nama', 'deskripsi', 'layanan_id', 'created_by', 'updated_by'];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
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
