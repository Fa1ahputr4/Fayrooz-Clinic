<?php

namespace App\Livewire\Rak;

use Livewire\Component;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\Rak;
use App\Models\StokRak;

class StokRakDetail extends Component
{

    public $id;
    public $rak;
    public $stokRakDetail;
    public $perPage1 = 5;
    public $search = '';

    public function mount($id)
    {
        $this->id = $id;
        $this->rak = Rak::findOrFail($id);
    }

    public function render()
    {
        $stokRakDetails = StokRak::with(['rak', 'barang_masuk']) // pastikan nama relasi sama
            ->where('rak_id', $this->id)
            ->whereHas('barang_masuk', function ($query) {
                $query->where('kode_masuk', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage1);

        return view('livewire.rak.stok-rak-detail', [
            'stokRakDetails' => $stokRakDetails,
        ])->extends('layouts.app');
    }
}
