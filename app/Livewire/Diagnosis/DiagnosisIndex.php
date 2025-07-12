<?php

namespace App\Livewire\Diagnosis;

use App\Models\Diagnosa;
use App\Models\Layanan;
use App\Models\LayananDetail;
use Livewire\Component;
use Livewire\WithPagination;

class DiagnosisIndex extends Component
{

    use WithPagination;

    public $title = 'Fayrooz | Data Diagnosis';
    public $perPage = 10;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'asc';
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;

    public $diagnosisId;
    public $layananId;
    public $namaDiagnosis;
    public $deskripsi;

    public function render()
    {
        // Ambil data detail layanan dengan relasi kategori
        $diagnosis = Diagnosa::with('layanan', 'createdBy', 'updatedBy')
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

        // Ambil semua kategori untuk select
        $categories = Layanan::all();

        return view('livewire.diagnosis.diagnosis-index', [
            'diagnosis' => $diagnosis,
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
        $diagnosis = Diagnosa::find($id);
        $this->diagnosisId = $diagnosis->id;
        $this->namaDiagnosis = $diagnosis->nama;
        $this->isDeleteModalOpen = true;
    }

    public function closeDeleteModal()
    {
        $this->isDeleteModalOpen = false;
        $this->diagnosisId = null;
    }

    public function resetForm()
    {
        $this->reset([
            'diagnosisId',
            'layananId',
            'namaDiagnosis',
            'deskripsi',
        ]);
    }

    public function updated($propertyName)
    {
        $this->resetValidation($propertyName);

        $this->resetErrorBag($propertyName);
    }

    public function editDiagnosis($id)
    {
        $diagnosis = Diagnosa::findOrFail($id);

        $this->diagnosisId = $diagnosis->id;
        $this->layananId = $diagnosis->layanan_id;
        $this->namaDiagnosis = $diagnosis->nama;
        $this->deskripsi = $diagnosis->deskripsi;
        $this->isModalOpen = true;
    }

    public function saveDiagnosis()
    {

        $this->validate([
            'layananId' => 'required|exists:layanan,id',
            'namaDiagnosis' => 'required|string|max:255|unique:diagnosis,nama,' . $this->diagnosisId,
            'deskripsi' => 'nullable|string',
        ], [
            'layanan_id.required' => 'Jenis layanan wajib dipilih.',
            'layanan_id.exists' => 'Jenis layanan tidak ditemukan di sistem.',
            'namaDiagnosis.required' => 'Nama diagnosis wajib diisi.',
            'namaDiagnosis.unique' => 'Nama diagnosis sudah terdaftar, silakan gunakan nama lain.',
        ]);


        $data = [
            'layanan_id' => $this->layananId,
            'nama' => $this->namaDiagnosis,
            'deskripsi' => $this->deskripsi,
        ];

        if ($this->diagnosisId) {
            $data['updated_by'] = auth()->id();
            Diagnosa::find($this->diagnosisId)->update($data);
            $message = 'Data diagnosis berhasil diperbarui.';
        } else {
            $data['created_by'] = auth()->id();
            $data['updated_by'] = auth()->id();
            Diagnosa::create($data);
            $message = 'Data diagnosis berhasil ditambahkan.';
        }

        $this->dispatch('flash-message', type: 'success', message: $message);
        $this->closeModal();
    }

    public function deleteDiagnosis()
    {
        $diagnosis = Diagnosa::find($this->diagnosisId);
        $diagnosis->delete();

        $this->dispatch('flash-message', type: 'success', message: 'Data diagnosis berhasil dihapus.');
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
