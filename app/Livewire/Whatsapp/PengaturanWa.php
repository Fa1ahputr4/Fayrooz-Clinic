<?php

namespace App\Livewire\Whatsapp;

use App\Models\WaApi;
use Livewire\Component;

class PengaturanWa extends Component
{
    public $title = 'Fayrooz | Pengaturan API WA';
    public $waApi; // Variabel untuk menampung data WA API
    public $waId, $nama, $urlApi, $token;
    public $isModalOpen = false;
    public $deleteModal = false, $showWarningModal = false;
    public $showCannotActivateModal = false;

    public function setInactive($id)
    {
        $wa = WaApi::find($id);

        if (!$wa) {
            $this->dispatch('flash-message', type: 'error', message: 'Data tidak ditemukan.');
            return;
        }

        if (!$wa->active) {
            $this->dispatch('flash-message', type: 'info', message: 'Data sudah nonaktif.');
            return;
        }

        $wa->update(['active' => false]);

        $this->dispatch('flash-message', type: 'success', message: 'API berhasil dinonaktifkan.');
    }


    public function setActive($id)
    {
        $activeExists = WaApi::where('active', true)->where('id', '!=', $id)->exists();

        if ($activeExists) {
            // Tampilkan modal peringatan
            $this->showCannotActivateModal = true;
            return;
        }

        // Aktifkan hanya jika belum aktif
        $wa = WaApi::findOrFail($id);

        if (!$wa->active) {
            $wa->update(['active' => true]);
            $this->dispatch('flash-message', type: 'success', message: 'API berhasil diaktifkan.');
        }
    }

    public function render()
    {
        $this->waApi = WaApi::all(); // Ambil semua data

        return view('livewire.whatsapp.pengaturan-wa', ['waApi' => $this->waApi])->extends('layouts.app', [
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
        $wa = WaApi::find($id);
        $this->waId = $wa->id;
        $this->nama = $wa->nama;
        if ($wa->active) {
            $this->showWarningModal = true; // Tampilkan modal peringatan
        } else {
            $this->deleteModal = true; // Lanjutkan ke modal hapus
        }
    }

    public function closeDeleteModal()
    {
        $this->deleteModal = false;
        $this->waId = null;
    }

    public function resetForm()
    {
        $this->reset([
            'waId',
            'nama',
            'urlApi',
            'token',
        ]);
    }

    public function updated($propertyName)
    {
        $this->resetErrorBag($propertyName);
    }

    public function edit($id)
    {
        $wa = WaApi::findOrFail($id);

        $this->waId = $wa->id;
        $this->nama = $wa->nama;
        $this->urlApi = $wa->base_url;
        $this->token = $wa->token;
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'urlApi' => 'required|url',
            'token' => 'required|string',
        ], [
            'nama.required' => 'Nama tidak boleh kosong.',
            'urlApi.required' => 'URL API wajib diisi.',
            'urlApi.url' => 'URL API harus berupa URL yang valid.',
            'token.required' => 'Token wajib diisi.',
        ]);

        // Logika untuk status aktif:
        if ($this->waId) {
            WaApi::findOrFail($this->waId)->update([
                'nama' => $this->nama,
                'base_url' => $this->urlApi,
                'token' => $this->token,
                // Jangan ubah status aktif saat edit
            ]);
            $message = 'Data API berhasil diperbarui.';
        } else {
            // Tambahkan logika aman
            $existingActive = WaApi::where('active', true)->first();

            WaApi::create([
                'nama' => $this->nama,
                'base_url' => $this->urlApi,
                'token' => $this->token,
                'active' => $existingActive ? false : true,
            ]);
            $message = 'Data API berhasil ditambahkan.';
        }


        $this->dispatch('flash-message', type: 'success', message: $message);
        $this->closeModal();

        $this->reset(['waId', 'nama', 'urlApi', 'token']);
    }

    public function delete()
    {
        $wa = WaApi::find($this->waId);

        if (!$wa) {
            $this->dispatch('flash-message', type: 'error', message: 'Data tidak ditemukan.');
            return;
        }

        if ($wa->active) {
            // Tampilkan peringatan bahwa data sedang aktif
            $this->dispatch('show-warning-delete');
            return;
        }

        $wa->delete();
        $this->dispatch('flash-message', type: 'success', message: 'Data API berhasil dihapus.');
        $this->closeDeleteModal();
    }
}
