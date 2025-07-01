<?php

namespace App\Livewire\Pasien;

use Livewire\Component;
use App\Models\Pasien;

class PasienTambah extends Component
{
    //Properti di halaman form pasien
    public $noRm, $nik, $nama, $jk = '', $tempatLahir, $tglLahir, $usia, $golDarah = '-', $alamat, $noTelp, $statusNikah = '', $catatan;
    public $pasienId = null;
    //Cek login dokter
    public $isDokter;
    //Cek form tambah atau edit
    public $mode = 'create';

    // Fungsi mount untuk membawa dan Mmnginisialisasi data
    public function mount($id = null)
    {
        if ($id) {
            $this->pasienId = $id;
            $pasien = Pasien::findOrFail($id);

            $this->noRm = $pasien->nomor_rm;
            $this->nama = $pasien->nama_lengkap;
            $this->jk = $pasien->jenis_kelamin;
            $this->nik = $pasien->nik;
            $this->tempatLahir = $pasien->tempat_lahir;
            $this->tglLahir = $pasien->tanggal_lahir;
            $this->usia = $pasien->usia;
            $this->golDarah = $pasien->golongan_darah;
            $this->alamat = $pasien->alamat;
            $this->noTelp = $pasien->no_telepon;
            $this->statusNikah = $pasien->status_pernikahan;
            $this->catatan = $pasien->catatan;
        } else {
            $this->noRm = $this->generateNomorRM();
        }

        $this->isDokter = auth()->user()->role === 'dokter';
    }

    //Fungsi render untuk mengarahkan dan merender di view
    public function render()
    {
        $pasien = $this->pasienId ? Pasien::find($this->pasienId) : null;

        return view('livewire.pasien.pasien-tambah', [
            'pasien' => $pasien
        ])->extends('layouts.app');;
    }

    //Aturan dan validasi pada input di form pasien
    public function rules()
    {
        $rules = [
            'nik' => 'nullable|numeric',
            'nama' => 'required|string',
            'jk' => 'required|string',
            'tempatLahir' => 'nullable|string',
            'tglLahir' => 'nullable|date',
            'usia' => 'nullable|numeric',
            'golDarah' => 'nullable|string',
            'alamat' => 'required|string',
            'noTelp' => 'required|numeric',
            'statusNikah' => 'nullable|string',
            'catatan' => 'nullable|string',
        ];

        if (!$this->pasienId) {
            // Jika pasienId tidak ada (tambah data), nomor_rm wajib unik
            $rules['noRm'] = 'required|unique:pasiens,nomor_rm';
        }

        return $rules;
    }

    //Pesan validasi untuk aturan input form pasien
    public function messages()
    {
        return [
            'nama.required' => 'Nama pasien wajib diisi.',
            'jk.required' => 'Jenis kelamin harus dipilih.',
            'alamat.required' => 'Alamat tidak boleh kosong.',
            'noTelp.required' => 'Nomor telepon wajib diisi.',
            'noTelp.numeric' => 'Nomor telepon harus berupa angka.',
            'noRm.required' => 'Nomor rekam medis tidak boleh kosong.',
            'noRm.unique' => 'Nomor rekam medis sudah digunakan.',
        ];
    }

    //Fungsi untuk menghapus validasi ketika ada perubahan di form input
    public function updated($propertyName)
    {
        $this->resetErrorBag($propertyName);
    }


    //Fungsi untuk membuat rekam medis baru
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

    //Fungsi untuk menyimpan dan update data
    public function save()
    {
        $this->validate($this->rules());

        $data = [
            'nik' => $this->nik,
            'nama_lengkap' => $this->nama,
            'jenis_kelamin' => $this->jk,
            'tempat_lahir' => $this->tempatLahir,
            'tanggal_lahir' => $this->tglLahir,
            'usia' => $this->usia,
            'golongan_darah' => $this->golDarah,
            'alamat' => $this->alamat,
            'no_telepon' => $this->noTelp,
            'status_pernikahan' => $this->statusNikah ?: null,
            'catatan' => $this->catatan,
        ];

        if ($this->pasienId) {
            // Untuk update data
            $data['updated_by'] = auth()->id(); // ID user yang sedang login
            $pasien = Pasien::findOrFail($this->pasienId);
            $pasien->update($data);
            session()->flash('success', 'Data pasien berhasil diperbarui.');
        } else {
            // Untuk create data baru
            $data['created_by'] = auth()->id(); // ID user yang sedang login
            $data['updated_by'] = auth()->id(); // Juga diisi saat pertama dibuat
            $data['nomor_rm'] = $this->noRm;
            Pasien::create($data);
            session()->flash('success', 'Data pasien berhasil ditambah.');
        }

        return redirect()->route('pasien');
    }
}
