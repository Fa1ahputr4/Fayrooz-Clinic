<?php

namespace App\Livewire\Barang;

use Livewire\Component;
use App\Models\Barang;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;


class BarangIndex extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'asc';
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;

    // Form fields
    public $barangId;
    public $kode_barang;
    public $nama_barang;
    public $satuan;
    public $jenis;
    public $jumlah_stok;
    public $created_at;
    public $updated_at;

    public function render()
    {
        $items = Barang::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('kode_barang', 'like', '%' . $this->search . '%')
                        ->orWhere('nama_barang', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    
        $satuanOptions = Barang::select('satuan')
            ->distinct()
            ->whereNotNull('satuan')
            ->pluck('satuan');
    
        return view('livewire.barang.barang_index', [
            'items' => $items,
            'satuanOptions' => $satuanOptions,
        ])->extends('layouts.app');
    }
    

    public function updatingSearch()
    {
        $this->resetPage();
        $this->closeModal();
    }

    public function updatingPerPage()
    {
        $this->isModalOpen = false;
        $this->resetPage();
        $this->closeModal();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
        $this->resetErrorBag(); 
    }

    public function openDeleteModal($id)
    {
        $item = Barang::find($id);
        $this->barangId = $id;
        $this->nama_barang = $item->nama_barang;
        $this->kode_barang = $item->kode_barang;
        $this->isDeleteModalOpen = true;
    }

    public function closeDeleteModal()
    {
        $this->isDeleteModalOpen = false;
        $this->barangId = null;
    }

    public function resetForm()
    {
        $this->reset([
            'barangId',
            'nama_barang',
            'kode_barang',
            'jenis',
            'satuan',
            'jumlah_stok',
        ]);
    }

    public function editBarang($id)
    {
        $item = Barang::findOrFail($id);

        $this->barangId = $item->id;
        $this->kode_barang = $item->kode_barang;
        $this->nama_barang = $item->nama_barang;
        $this->jenis =$item->jenis;
        $this->satuan = $item->satuan;
        $this->jumlah_stok = $item->jumlah_stok;
        $this->isModalOpen = true;
    }

    public function saveBarang()
    {
        $this->validate([
            'kode_barang' => [
                'required',
                Rule::unique('raks', 'kode_rak')->ignore($this->kode_barang),
            ],
            'nama_barang' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
            'satuan' => 'nullable|string|min:0',
        ], [
            'kode_barang.required' => 'Kode barang wajib diisi.',
            'kode_barang.unique' => 'Kode barang sudah terdaftar.',
            'jenis.required' => 'Jenis barang tidak boleh kosong.',
            'satuan.required' => 'Satuan tidak boleh kosong.',
        ]);

        $data = [
            'kode_barang' => $this->kode_barang,
            'nama_barang' => $this->nama_barang,
            'jenis' => $this->jenis,
            'satuan' => $this->satuan,
        ];

        if ($this->barangId) {
            Barang::find($this->barangId)->update($data);
            $message = 'Data barang berhasil diperbarui.';
        } else {
            Barang::create($data);
            $message = 'Data barang berhasil ditambahkan.';
        }

        $this->dispatch('flash-message', type: 'success', message: $message);
        $this->dispatch('pageRefresh');
        $this->closeModal();
    }

    public function deleteBarang()
{
    $item = Barang::find($this->barangId);

    if (!$item) {
        $this->dispatch('flash-message', type: 'error', message: 'Barang tidak ditemukan.');
        return;
    }

    $item->delete();

    $this->dispatch('flash-message', type: 'success', message: 'Barang berhasil dihapus.');
    $this->closeDeleteModal();
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
}
