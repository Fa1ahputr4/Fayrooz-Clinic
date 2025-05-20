<?php

namespace App\Livewire\Pendaftaran;

use Livewire\Component;
use App\Models\Pendaftaran;
use App\Models\Layanan;
use App\Models\LayananDetail;
use App\Models\Pasien;
use Livewire\WithPagination;
use Carbon\Carbon;

class AntrianIndex extends Component
{

    use WithPagination;

    public $perPage = 10;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'asc';
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;

    public $id_pendaftaran;
    public $kode_pendaftaran;
    public $jenis_pasien;
    public $id_pasien;
    public $patients = [];
    public $id_layanan;
    public $layanan_detail_id;
    public $daftarDetailLayanan = [];
    public $nomor_antrian;
    public $tgl_kunjungan;
    public $status;
    public $catatan;

    public function render()
{
    $pendaftaran = Pendaftaran::query()
        ->with(['pasien', 'layanan'])
        ->whereDate('tanggal_kunjungan', Carbon::today()) // 🔍 hanya data hari ini
        ->when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('kode_pendaftaran', 'like', '%' . $this->search . '%')
                    ->orWhereHas('pasien', function ($q2) {
                        $q2->where('nomor_rm', 'like', '%' . $this->search . '%');
                    });
            });
        })
        ->orderBy($this->sortField, $this->sortDirection)
        ->paginate($this->perPage);

    $daftarLayanan = Layanan::all();

    return view('livewire.pendaftaran.antrian-index', [
        'pendaftaran' => $pendaftaran,
        'daftarLayanan' => $daftarLayanan,
    ])->extends('layouts.app');
}

    public function updatedIdLayanan($value)
    {
        $this->nomor_antrian = $this->generateNomorAntrian($this->id_layanan);
        $this->layanan_detail_id = null; // Reset pilihan detail
        $this->daftarDetailLayanan = LayananDetail::where('layanan_id', $value)->get();
    }

    public function updatedJenisPasien($value)
    {
        // Simpan ID pasien yang sedang dipilih
        $currentPasienId = $this->id_pasien;

        $this->id_pasien = null; // reset pilihan

        if ($value === 'baru') {
            $query = Pasien::whereDoesntHave('pendaftaran');
        } elseif ($value === 'lama') {
            $query = Pasien::whereHas('pendaftaran');
        } else {
            $this->patients = [];
            return;
        }

        // Tambahkan pasien yang sedang dipilih jika ada
        if ($currentPasienId) {
            $query->orWhere('id', $currentPasienId);
        }

        $this->patients = $query->get();
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
        $this->id_pendaftaran = null;
        $this->resetForm();
        $this->generateKodePendaftaran();
        $this->tgl_kunjungan = now()->toDateString();

        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
        $this->resetErrorBag();
    }

    public function editPendaftaran($id)
    {
        $p = Pendaftaran::findOrFail($id);

        $this->id_pendaftaran = $id;
        $this->tgl_kunjungan = Carbon::parse($p->tanggal_kunjungan)->format('Y-m-d');
        $this->kode_pendaftaran = $p->kode_pendaftaran;
        $this->nomor_antrian = $p->nomor_antrian;
        $jumlahPendaftaran = Pendaftaran::where('pasien_id', $p->pasien_id)->count();
        $this->jenis_pasien = $jumlahPendaftaran > 1 ? 'lama' : 'baru';
        $this->patients = Pasien::where('id', $p->pasien_id)
            ->orWhere(function ($query) use ($p) {
                if ($this->jenis_pasien == 'baru') {
                    $query->whereDoesntHave('pendaftaran');
                } else {
                    $query->whereHas('pendaftaran');
                }
            })->get();
        $this->id_pasien = $p->pasien_id;
        $this->id_layanan = $p->layanan_id;
        $this->daftarDetailLayanan = LayananDetail::where('layanan_id', $this->id_layanan)->get();
        $this->layanan_detail_id = $p->detail_layanan_id;

        $this->isModalOpen = true;
    }

    public function resetForm()
    {
        $this->reset([
            'kode_pendaftaran',
            'nomor_antrian',
            'id_pasien',
            'id_layanan',
            'layanan_detail_id',
            'jenis_pasien',
            'tgl_kunjungan',
            'catatan',
        ]);
    }
}
