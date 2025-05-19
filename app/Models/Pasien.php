<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pasien extends Model
{
    use SoftDeletes; 

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
        'email',
        'nama_pj',
        'hubungan_pj',
        'kontak_pj',
        'foto',
        'status_pernikahan',
        'catatan'
    ];
}
