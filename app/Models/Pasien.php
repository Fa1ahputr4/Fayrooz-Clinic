<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pasien extends Model
{
    use SoftDeletes;

    protected $table = 'pasien';

    protected $fillable = [
        'nomor_rm',
        'nik',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'usia',
        'golongan_darah',
        'alamat',
        'no_telepon',
        'status_pernikahan',
        'catatan',
        'created_by',
        'updated_by'
    ];

    //Relasi satu pasien bisa banyak pendaftaran
    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class);
    }

    //Relasi user saat tambah data
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
