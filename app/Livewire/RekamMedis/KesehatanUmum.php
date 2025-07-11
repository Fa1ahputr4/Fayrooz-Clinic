<?php

namespace App\Livewire\RekamMedis;

use Livewire\Component;
use App\Models\Pasien;
use App\Models\RekmedBeautycare;
use App\Models\RekmedUmum;
use Livewire\WithPagination;

class KesehatanUmum extends Component
{
    use WithPagination;

    public $title = 'Fayrooz | Rekam Medis';
    public $pasienId;
    public $perPage = 10;
    public $search = '';
    public $sortField = 'tanggal_kunjungan';
    public $sortDirection = 'desc';

    public function mount($id)
    {
        $this->pasienId = $id;
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function render()
    {
        $rekamMedis = RekmedUmum::where('id_pasien', $this->pasienId)
            ->whereHas('pendaftaran') // pastikan relasi pendaftaran ada
            ->with(['pendaftaran.pasien', 'pendaftaran.layanandetail', 'diagnosa']) // eager load relasi
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('diagnosis', 'like', '%' . $this->search . '%')
                        ->orWhere('tindakan', 'like', '%' . $this->search . '%');
                });
            })
            ->join('pendaftaran', 'rekmed_umums.id_pendaftaran', '=', 'pendaftaran.id')
            ->orderBy('pendaftaran.tanggal_kunjungan', 'desc') // order berdasarkan tanggal di relasi
            ->select('rekmed_umums.*') // penting agar hanya field dari rekmed_umums yang diambil
            ->paginate($this->perPage);

        $pasien = Pasien::find($this->pasienId);

        return view('livewire.rekam-medis.kesehatan-umum', [
            'rekamMedis' => $rekamMedis,
            'pasien' => $pasien,
        ])->extends('layouts.app', [
            'title' => $this->title // Kirim title ke layout
        ]);
    }
}
