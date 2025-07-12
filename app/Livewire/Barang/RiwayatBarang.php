<?php

namespace App\Livewire\Barang;

use Livewire\Component;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;

class RiwayatBarang extends Component
{
    public $title = 'Fayrooz | Log Barang';
    public $id;
    public $barangDetail;
    public $perPage1 = 5;
    public $search = '';

    public function mount($id)
    {
        $this->id = $id;
        $this->barangDetail = Barang::findOrFail($id);
    }

    public function render()
    {
        $barangMasuk = BarangMasuk::with('barang')
            ->where('id_barang', $this->id)
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage1);

            return view('livewire.barang.riwayat_barang', [
                'barangMasuk' => $barangMasuk,
                'barangDetails' => $this->barangDetail
            ])->extends('layouts.app', [
            'title' => $this->title // Kirim title ke layout
        ]);
            
    }
}
