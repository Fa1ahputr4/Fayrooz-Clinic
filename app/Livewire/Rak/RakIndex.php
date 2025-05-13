<?php

namespace App\Livewire\Rak;

use Livewire\Component;
use App\Models\Rak;
use App\Models\Barang;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class RakIndex extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'asc';
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;

    // Form fields
    public $rak_id;
    public $id_barang;
    public $kode_rak;
    public $nama_rak;
    public $kapasitas;
    public $keterangan;

    public function render()
    {
        $items = Rak::with('barang')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('kode_rak', 'like', '%' . $this->search . '%')
                        ->orWhere('nama_rak', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

            $itemOptions = Barang::select('id', 'nama_barang')
            ->distinct()
            ->whereNotNull('nama_barang')
            ->get();

        return view('livewire.rak.rak-index', [
            'items' => $items,
            'itemOptions' => $itemOptions,
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
        $item = Rak::find($id);
        $this->rak_id = $id;
        $this->nama_rak = $item->nama_rak;
        $this->kode_rak = $item->kode_rak;
        $this->isDeleteModalOpen = true;
    }

    public function closeDeleteModal()
    {
        $this->isDeleteModalOpen = false;
        $this->rak_id = null;
    }

    public function resetForm()
    {
        $this->reset([
            'rak_id',
            'id_barang',
            'nama_rak',
            'kode_rak',
            'kapasitas',
            'keterangan',
        ]);
    }

    public function editRak($id)
    {
        $item = Rak::findOrFail($id);

        $this->rak_id = $item->id;
        $this->id_barang = $item->id_barang;
        $this->kode_rak = $item->kode_rak;
        $this->nama_rak = $item->nama_rak;
        $this->kapasitas = $item->kapasitas;
        $this->keterangan = $item->keterangan;
        $this->isModalOpen = true;
    }

    public function saveBarang()
    {
        $this->validate([
            'kode_rak' => [
                'required',
                Rule::unique('raks', 'kode_rak')->ignore($this->rak_id),
            ],
            'id_barang' => 'required',
            'nama_rak' => 'required|string|max:255',
            'kapasitas' => 'required|integer',
            'keterangan' => 'nullable|string',
        ], [
            'kode_rak.required' => 'Kode rak wajib diisi.',
            'kode_rak.unique' => 'Kode rak sudah terdaftar.',
            'id_barang.required' => 'Data barang wajib diisi',
            'nama_rak.required' => 'Nama rak tidak boleh osong',
            'kapasitas.required' => 'Kapasitas wajib diisi'
        ]);
        $data = [
            'kode_rak' => $this->kode_rak,
            'id_barang' => $this->id_barang,
            'nama_rak' => $this->nama_rak,
            'kapasitas' => $this->kapasitas,
            'keterangan' => $this->keterangan,
        ];

        if ($this->rak_id) {
            Rak::find($this->rak_id)->update($data);
            $message = 'Data barang berhasil diperbarui.';
        } else {
            Rak::create($data);
            $message = 'Data barang berhasil ditambahkan.';
        }

        $this->dispatch('flash-message', type: 'success', message: $message);
        $this->closeModal();
    }

    public function deleteRak()
    {
        $item = Rak::find($this->rak_id);

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
