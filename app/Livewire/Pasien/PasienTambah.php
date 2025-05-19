<?php

namespace App\Livewire\Pasien;

use Livewire\Component;
use App\Models\Pasien;

class PasienTambah extends Component
{

    public $nomor_rm, $nik, $nama_lengkap, $jenis_kelamin = '', $tempat_lahir, $tanggal_lahir,
        $usia, $golongan_darah = '-', $alamat, $no_telepon, $email, $nama_pj, $hubungan_pj,
        $kontak_pj, $foto, $status_pernikahan = '', $catatan;
    public $pasienId = null;
    public $mode = 'create'; // default: tambah

    public function rules()
    {
        $rules = [
            'nik' => 'required|numeric',
            'nama_lengkap' => 'required|string',
            'jenis_kelamin' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'usia' => 'required|numeric',
            'golongan_darah' => 'required|string',
            'alamat' => 'required|string',
            'no_telepon' => 'required|numeric',
            'email' => 'nullable|email',
            'nama_pj' => 'required|string',
            'hubungan_pj' => 'required|string',
            'kontak_pj' => 'required|numeric',
            'status_pernikahan' => 'required|string',
            'catatan' => 'nullable|string',
        ];

        if (!$this->pasienId) {
            // Jika pasienId tidak ada (tambah data), nomor_rm wajib unik
            $rules['nomor_rm'] = 'required|unique:pasiens';
        }

        return $rules;
    }


    public function mount($id = null)
    {
        if ($id) {
            $this->pasienId = $id;
            $this->loadPatientData($id);
        } else {
            $this->nomor_rm = $this->generateNomorRM();
        }
    }

    public function loadPatientData($id)
    {
        $pasien = Pasien::findOrFail($id);

        $this->nomor_rm = $pasien->nomor_rm;
        $this->nama_lengkap = $pasien->nama_lengkap;
        $this->jenis_kelamin = $pasien->jenis_kelamin;
        $this->nik = $pasien->nik;
        $this->nomor_rm = $pasien->nomor_rm;
        $this->tempat_lahir = $pasien->tempat_lahir;
        $this->tanggal_lahir = $pasien->tanggal_lahir;
        $this->usia = $pasien->usia;
        $this->golongan_darah = $pasien->golongan_darah;
        $this->alamat = $pasien->alamat;
        $this->no_telepon = $pasien->no_telepon;
        $this->email = $pasien->email;
        $this->nama_pj = $pasien->nama_pj;
        $this->hubungan_pj = $pasien->hubungan_pj;
        $this->kontak_pj = $pasien->kontak_pj;
        $this->foto = $pasien->foto;
        $this->status_pernikahan = $pasien->status_pernikahan;
        $this->catatan = $pasien->catatan;
    }

    protected function generateNomorRM()
    {
        $today = now()->format('Ymd');
        $last = Pasien::whereDate('created_at', now()->toDateString())
            ->orderByDesc('id')->first();

        $lastNumber = 1;
        if ($last && preg_match('/(\d+)$/', $last->nomor_rm, $matches)) {
            $lastNumber = intval($matches[1]) + 1;
        }

        return 'RM-' . $today . '-' . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);
    }
    public function save()
    {
        $this->validate($this->rules());

        $fotoPath = $this->foto ? $this->foto->store('foto-pasien', 'public') : null;

        if ($this->pasienId) {
            // ✏️ Mode Edit
            $pasien = Pasien::findOrFail($this->pasienId);

            $pasien->update([
                'nik' => $this->nik,
                'nama_lengkap' => $this->nama_lengkap,
                'jenis_kelamin' => $this->jenis_kelamin,
                'tempat_lahir' => $this->tempat_lahir,
                'tanggal_lahir' => $this->tanggal_lahir,
                'usia' => $this->usia,
                'golongan_darah' => $this->golongan_darah,
                'alamat' => $this->alamat,
                'no_telepon' => $this->no_telepon,
                'email' => $this->email,
                'nama_pj' => $this->nama_pj,
                'hubungan_pj' => $this->hubungan_pj,
                'kontak_pj' => $this->kontak_pj,
                'status_pernikahan' => $this->status_pernikahan,
                'catatan' => $this->catatan,
                // Update foto hanya jika ada file baru
                'foto' => $fotoPath ?? $pasien->foto,
            ]);

            session()->flash('success', 'Data pasien berhasil diperbarui.');
        } else {
            // ➕ Mode Tambah
            Pasien::create([
                'nomor_rm' => $this->nomor_rm,
                'nik' => $this->nik,
                'nama_lengkap' => $this->nama_lengkap,
                'jenis_kelamin' => $this->jenis_kelamin,
                'tempat_lahir' => $this->tempat_lahir,
                'tanggal_lahir' => $this->tanggal_lahir,
                'usia' => $this->usia,
                'golongan_darah' => $this->golongan_darah,
                'alamat' => $this->alamat,
                'no_telepon' => $this->no_telepon,
                'email' => $this->email,
                'nama_pj' => $this->nama_pj,
                'hubungan_pj' => $this->hubungan_pj,
                'kontak_pj' => $this->kontak_pj,
                'foto' => $fotoPath,
                'status_pernikahan' => $this->status_pernikahan,
                'catatan' => $this->catatan,
            ]);


            session()->flash('success', 'Data pasien berhasil ditambah.');
        }
        return redirect()->route('pasien');
    }

    public function render()
    {
        return view('livewire.pasien.pasien-tambah')->extends('layouts.app');;
    }
}
