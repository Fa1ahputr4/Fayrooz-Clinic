<?php

namespace App\Livewire\Layanan;

use App\Models\Layanan;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LayananDetail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

class LayananIndex extends Component
{
    use WithPagination;

    public $title = 'Fayrooz | Data Layanan';
    public $perPage = 10;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'asc';
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;

    public $serviceId;
    public $layanan_id;
    public $kode_layanan;
    public $nama_layanan;
    public $deskripsi_layanan;
    public $harga_layanan;
    public $is_active = true;

    public function render()
    {
        // Ambil data detail layanan dengan relasi kategori
        $services = LayananDetail::with('layanan', 'createdBy', 'updatedBy')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('kode_layanan', 'like', '%' . $this->search . '%')
                        ->orWhereHas('layanan', function ($q) {
                            $q->where('nama_layanan', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        // Ambil semua kategori untuk select
        $categories = Layanan::all();

        return view('livewire.layanan.layanan-menu', [
            'services' => $services,
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
        $service = LayananDetail::find($id);
        $this->nama_layanan = $service->nama_layanan;
        $this->kode_layanan = $service->kode_layanan;
        $this->serviceId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function closeDeleteModal()
    {
        $this->isDeleteModalOpen = false;
        $this->serviceId = null;
    }

    public function resetForm()
    {
        $this->reset([
            'serviceId',
            'layanan_id',
            'kode_layanan',
            'nama_layanan',
            'harga_layanan',
            'deskripsi_layanan',
            'is_active',
        ]);
    }

    public function editService($id)
    {
        $service = LayananDetail::findOrFail($id);

        $this->serviceId = $service->id;
        $this->kode_layanan = $service->kode_layanan;
        $this->layanan_id = $service->layanan_id;
        $this->nama_layanan = $service->nama_layanan;
        $this->harga_layanan = $service->harga_layanan;
        $this->deskripsi_layanan = $service->deskripsi_layanan;
        $this->is_active = (bool)$service->is_active;
        $this->isModalOpen = true;
    }

    public function saveService()
    {

        $this->validate([
            'layanan_id' => 'required|exists:layanan,id',
            'kode_layanan' => [
                'required',
                'string',
                'max:255',
                Rule::unique('layanan_details')->ignore($this->serviceId)
            ],
            'nama_layanan' => 'required|string|max:255',
            'deskripsi_layanan' => 'nullable|string',
        ], [
            'layanan_id.required' => 'Jenis layanan wajib dipilih.',
            'layanan_id.exists' => 'Jenis layanan tidak ditemukan di sistem.',
            'kode_layanan.unique' => 'Kode layanan sudah digunakan.',
            'kode_layanan.required' => 'Kode layanan tidak boleh kosong.',
            'nama_layanan.required' => 'Nama layanan wajib diisi.',
        ]);

        $data = [
            'layanan_id' => $this->layanan_id,
            'kode_layanan' => $this->kode_layanan,
            'nama_layanan' => $this->nama_layanan,
            'deskripsi_layanan' => $this->deskripsi_layanan,
        ];

        if ($this->serviceId) {
            $data['updated_by'] = auth()->id();
            LayananDetail::find($this->serviceId)->update($data);
            $message = 'Detail layanan berhasil diperbarui.';
        } else {
            $data['created_by'] = auth()->id();
            $data['updated_by'] = auth()->id();
            LayananDetail::create($data);
            $message = 'Detail layanan berhasil ditambahkan.';
        }

        $this->dispatch('flash-message', type: 'success', message: $message);
        $this->closeModal();
    }

    public function updated($propertyName){
        $this->resetErrorBag($propertyName);
    }

    public function deleteService()
    {
        $user = LayananDetail::find($this->serviceId);
        $user->delete();

        $this->dispatch('flash-message', type: 'success', message: 'Layanan berhasil dihapus.');
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
