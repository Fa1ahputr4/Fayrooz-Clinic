<?php

namespace App\Livewire\Pasien;

use App\Models\Pasien;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Facades\Excel;

class PasienIndex extends Component
{
    use WithPagination;

    public $title = 'Fayrooz | Data Pasien';
    public $perPage = 10;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'asc';
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    //Properti Pasien
    public $pasienId, $noRm, $nama;
    //Cek login dokter
    public $isDokter;

    // Fungsi mount untuk membawa dan Mmnginisialisasi data
    public function mount()
    {
        $this->isDokter = auth()->user()->role === 'dokter'; // Cek sekali saja
    }

    //Fungsi render untuk mengarahkan dan merender di view
    public function render()
    {
        $patients = Pasien::query()
            ->whereNull('deleted_at')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                        ->orWhere('nomor_rm', 'like', '%' . $this->search . '%')
                        ->orWhere('no_telepon', 'like', '%' . $this->search . '%')
                        ->orWhere('alamat', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.pasien.pasien-index', [
            'patients' => $patients,
        ])->extends('layouts.app', [
            'title' => $this->title // Kirim title ke layout
        ]);
    }

    //Fungsi untuk melakukan Sort berdasaran kolom tertentu
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    //Fungsi untuk membuka modal konfirmasi hapus
    public function openDeleteModal($id)
    {
        $item = Pasien::find($id);
        $this->pasienId = $id;
        $this->nama = $item->nama_lengkap;
        $this->noRm = $item->nomor_rm;
        $this->isDeleteModalOpen = true;
    }

    //Fungsi untuk menutup modal konfirmasi hapus
    public function closeDeleteModal()
    {
        $this->isDeleteModalOpen = false;
        $this->pasienId = null;
    }

    //Fungsi untuk menghapus data pasien
    public function deletePatient()
    {
        // Cek apakah user adalah admin
        if (auth()->user()->role !== 'admin') {
            $this->dispatch('flash-message', type: 'error', message: 'Anda tidak memiliki izin untuk menghapus data.');
            return;
        }

        $pasien = Pasien::find($this->pasienId);

        if ($pasien) {
            $pasien->delete();
            $this->dispatch('flash-message', type: 'success', message: 'Data pasien berhasil dihapus.');
        } else {
            $this->dispatch('flash-message', type: 'error', message: 'Data pasien gagal dihapus.');
        }

        $this->closeDeleteModal();
    }

    //Fungsi untuk export data pasien ke file excel
    public function exportExcel()
    {
        $export = new class($this->search, $this->sortField, $this->sortDirection)
        implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize {

            protected $search;
            protected $sortField;
            protected $sortDirection;

            public function __construct($search, $sortField, $sortDirection)
            {
                $this->search = $search;
                $this->sortField = $sortField;
                $this->sortDirection = $sortDirection;
            }

            public function collection()
            {
                return Pasien::query()
                    ->select([
                        'id',
                        'nomor_rm',
                        'nama_lengkap',
                        'jenis_kelamin',
                        'tempat_lahir',
                        'tanggal_lahir',
                        'golongan_darah',
                        'status_pernikahan',
                        'no_telepon',
                        'alamat',
                        'catatan',
                        'created_at'
                    ])
                    ->whereNull('deleted_at')
                    ->when($this->search, function ($query) {
                        $query->where(function ($q) {
                            $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                                ->orWhere('nomor_rm', 'like', '%' . $this->search . '%')
                                ->orWhere('no_telepon', 'like', '%' . $this->search . '%');
                        });
                    })
                    ->orderBy($this->sortField, $this->sortDirection)
                    ->get();
            }

            public function headings(): array
            {
                return [
                    'ID',
                    'No. RM',
                    'Nama Lengkap',
                    'Jenis Kelamin',
                    'Tempat Lahir',
                    'Tanggal Lahir',
                    'Gol. Darah',
                    'Status Nikah',
                    'No. Telepon',
                    'Alamat',
                    'Catatan',
                    'Tgl. Daftar'
                ];
            }

            public function map($pasien): array
            {
                return [
                    $pasien->id,
                    $pasien->nomor_rm,
                    $pasien->nama_lengkap,
                    $pasien->jenis_kelamin,
                    $pasien->tempat_lahir,
                    Carbon::parse($pasien->tanggal_lahir)->format('d/m/Y'),
                    $pasien->golongan_darah,
                    $this->formatStatus($pasien->status_pernikahan),
                    $pasien->no_telepon,
                    $pasien->alamat,
                    $pasien->catatan,
                    Carbon::parse($pasien->created_at)->format('d/m/Y H:i'), // Tanggal Daftar
                ];
            }

            protected function formatStatus($status)
            {
                $statuses = [
                    'belum_menikah' => 'Belum Menikah',
                    'menikah' => 'Menikah',
                    'cerai_hidup' => 'Cerai Hidup',
                    'cerai_mati' => 'Cerai Mati'
                ];

                return $statuses[$status] ?? $status;
            }
        };

        return Excel::download($export, 'data-pasien_' . now()->format('Ymd_His') . '.xlsx');
    }
}
