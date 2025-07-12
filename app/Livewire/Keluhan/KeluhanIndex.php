<?php

namespace App\Livewire\Keluhan;

use App\Models\Keluhan;
use App\Models\Layanan;
use Livewire\Component;
use Livewire\WithPagination;

class KeluhanIndex extends Component
{
    use WithPagination;

    public $title = 'Fayrooz | Data Keluhan';
    public $perPage = 10;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'asc';
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;

    public $keluhanId;
    public $layananId;
    public $namaKeluhan;
    public $deskripsi;

    public function render()
    {
        // Ambil data detail layanan dengan relasi kategori
        $keluhan = Keluhan::with('layanan', 'createdBy', 'updatedBy')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                        ->orWhereHas('layanan', function ($q) {
                            $q->where('nama', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $categories = Layanan::all();

        return view('livewire.keluhan.keluhan-index', [
            'keluhan' => $keluhan,
            'categories' => $categories
        ])->extends('layouts.app', [
            'title' => $this->title // Kirim title ke layout
        ]);
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
        $keluhan = Keluhan::find($id);
        $this->keluhanId = $keluhan->id;
        $this->namaKeluhan = $keluhan->nama;
        $this->isDeleteModalOpen = true;
    }

    public function closeDeleteModal()
    {
        $this->isDeleteModalOpen = false;
        $this->keluhanId = null;
    }

    public function resetForm()
    {
        $this->reset([
            'keluhanId',
            'layananId',
            'namaKeluhan',
            'deskripsi',
        ]);
    }

    public function updated($propertyName)
    {
        $this->resetValidation($propertyName);

        $this->resetErrorBag($propertyName);
    }

    public function editKeluhan($id)
    {
        $keluhan = Keluhan::findOrFail($id);

        $this->keluhanId = $keluhan->id;
        $this->layananId = $keluhan->layanan_id;
        $this->namaKeluhan = $keluhan->nama;
        $this->deskripsi = $keluhan->deskripsi;
        $this->isModalOpen = true;
    }

    public function saveKeluhan()
    {

        $this->validate([
            'layananId' => 'required|exists:layanan,id',
            'namaKeluhan' => 'required|string|max:255|unique:keluhan,nama,' . $this->keluhanId,
            'deskripsi' => 'nullable|string',
        ], [
            'layanan_id.required' => 'Jenis layanan wajib dipilih.',
            'layanan_id.exists' => 'Jenis layanan tidak ditemukan di sistem.',
            'namaKeluhan.required' => 'Nama Keluhan wajib diisi.',
            'namaKeluhan.unique' => 'Nama Keluhan sudah terdaftar, silakan gunakan nama lain.',
        ]);


        $data = [
            'layanan_id' => $this->layananId,
            'nama' => $this->namaKeluhan,
            'deskripsi' => $this->deskripsi,
        ];

        if ($this->keluhanId) {
            $data['updated_by'] = auth()->id();
            Keluhan::find($this->keluhanId)->update($data);
            $message = 'Data keluhan berhasil diperbarui.';
        } else {
            $data['created_by'] = auth()->id();
            $data['updated_by'] = auth()->id();
            Keluhan::create($data);
            $message = 'Data keluhan berhasil ditambahkan.';
        }

        $this->dispatch('flash-message', type: 'success', message: $message);
        $this->closeModal();
    }

    public function deleteKeluhan()
    {
        $keluhan = Keluhan::find($this->keluhanId);
        $keluhan->delete();

        $this->dispatch('flash-message', type: 'success', message: 'Data keluhan berhasil dihapus.');
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
