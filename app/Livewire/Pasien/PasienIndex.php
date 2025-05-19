<?php

namespace App\Livewire\Pasien;

use Livewire\Component;
use App\Models\Pasien;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PasienIndex extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'asc';
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $id;
    public $nomor_rm;
    public $nama_lengkap;

    public function render()
    {
        $patients = Pasien::query()
            ->whereNull('deleted_at') // Filter soft-deleted records
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                        ->orWhere('nomor_rm', 'like', '%' . $this->search . '%')
                        ->orWhere('no_telepon', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.pasien.pasien-index', [
            'patients' => $patients,
        ])->extends('layouts.app');
    }

    public function openDeleteModal($id)
    {
        $item = Pasien::find($id);
        $this->id = $id;
        $this->nama_lengkap = $item->nama_lengkap;
        $this->nomor_rm = $item->nomor_rm;
        $this->isDeleteModalOpen = true;
    }

    public function closeDeleteModal()
    {
        $this->isDeleteModalOpen = false;
        $this->id = null;
    }

    public function deletePatient()
    {
        $pasien = Pasien::find($this->id);

        if ($pasien) {
            $pasien->delete(); // Soft delete
            session()->flash('success', 'Pasien berhasil dihapus.');
        } else {
            session()->flash('error', 'Pasien tidak ditemukan.');
        }

        $this->closeDeleteModal();
        session()->flash('success', 'Data pasien berhasil diperbarui.');
    }
}
