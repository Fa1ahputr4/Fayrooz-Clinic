<?php

namespace App\Livewire\Resep;

use App\Models\RekmedUmum;
use Livewire\Component;
use Livewire\WithPagination;

class PermintaanResep extends Component
{

    use WithPagination;

    public $perPage = 10;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'asc';
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public function render()
    {
        $histories = RekmedUmum::with([
            'pasien',
            'pendaftaran.layanan',
            'resepPasien.barang',
        ])
            ->whereHas('resepPasien') // hanya history yang punya resep
            ->when($this->search, function ($query) {
                $query->whereHas('pasien', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.resep.permintaan-resep', [
            'histories' => $histories,
        ])->extends('layouts.app');
    }
}
