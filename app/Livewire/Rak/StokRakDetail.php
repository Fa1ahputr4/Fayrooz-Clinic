<?php

namespace App\Livewire\Rak;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Rak;
use App\Models\StokRak;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class StokRakDetail extends Component
{
    use WithPagination;

    public $title = 'Fayrooz | Stok Rak Detail';
    public $id;
    public $rak;
    public $stokRakDetail;
    public $perPage = 10;
    public $search = '';
    public $tab = 'barangMasuk';


    public function mount($id)
    {
        $this->id = $id;
        $this->rak = Rak::findOrFail($id);
    }

    public function render()
    {
        if ($this->tab === 'barangKeluar') {
            $barangKeluarDetails = BarangKeluar::with(['stok_rak.barang_masuk', 'createdBy'])
                ->whereHas('stok_rak', function ($query) {
                    $query->where('rak_id', $this->id);
                })
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('status_keluar', 'like', '%' . $this->search . '%');
                    });
                })
                ->orderBy('created_at', 'desc')
                ->paginate($this->perPage);

            return view('livewire.rak.stok-rak-detail', [
                'barangKeluarDetails' => $barangKeluarDetails,
                'stokRakDetails' => null, // Explicitly set to null
            ])->extends('layouts.app');
        }

        // Default: Barang Masuk
        $stokRakDetails = StokRak::with(['rak', 'barang_masuk', 'createdBy', 'updatedBy'])
            ->where('rak_id', $this->id)
            ->when($this->search, function ($query) {
                $query->whereHas('barang_masuk', function ($q) {
                    $q->where('kode_masuk', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        // Add status information
        $stokRakDetails->getCollection()->transform(function ($item) {
            $now = now();
            $expDate = $item->barang_masuk->exp_date ? Carbon::parse($item->barang_masuk->exp_date) : null;

            if (!$expDate) {
                $item->status = 'Tidak Ada Info Expired';
                $item->status_class = 'bg-gray-200 text-gray-800';
            } elseif ($now->gt($expDate)) {
                $item->status = 'Expired';
                $item->status_class = 'bg-red-100 text-red-800';
            } elseif ($now->diffInDays($expDate) <= 30) {
                $item->status = 'Hampir Expired';
                $item->status_class = 'bg-yellow-400 text-white';
            } else {
                $item->status = 'Baik';
                $item->status_class = 'bg-green-100 text-green-800';
            }

            return $item;
        });

        return view('livewire.rak.stok-rak-detail', [
            'stokRakDetails' => $stokRakDetails,
            'barangKeluarDetails' => null, // Explicitly set to null
        ])->extends('layouts.app', [
            'title' => $this->title // Kirim title ke layout
        ]);
    }
}
