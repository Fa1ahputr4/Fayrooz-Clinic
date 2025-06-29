<?php

namespace App\Livewire\Pendaftaran;

use Livewire\Component;
use App\Models\Pendaftaran;
use App\Models\Layanan;
use App\Models\LayananDetail;
use App\Models\Pasien;
use Livewire\WithPagination;
use Carbon\Carbon;

class PendaftaranIndex extends Component
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

    protected function rules()
    {
        return [
            'tgl_kunjungan'       => 'required|date',
            'jenis_pasien'        => 'required|in:lama,baru',
            'id_pasien'           => 'required|exists:pasiens,id',
            'id_layanan'          => 'required|exists:layanan,id',
            'layanan_detail_id'   => 'required|exists:layanan_details,id',
            'catatan'             => 'nullable|string|max:255',
            'nomor_antrian'       => 'nullable|string|max:10',
            'kode_pendaftaran'    => 'nullable|string|max:20',
        ];
    }

    public function render()
    {
        $pendaftaran = Pendaftaran::query()
            ->with(['pasien', 'layanan'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('kode_pendaftaran', 'like', '%' . $this->search . '%')
                        ->orWhereHas('pasien', function ($q2) {
                            $q2->where('nomor_rm', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->orderByRaw("FIELD(status, 'menunggu', 'diperiksa', 'selesai')") // prioritas status
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $daftarLayanan = Layanan::all();

        return view('livewire.pendaftaran.pendaftaran-index', [
            'pendaftaran' => $pendaftaran,
            'daftarLayanan' => $daftarLayanan,
        ])->extends('layouts.app');
    }

    public function generateKodePendaftaran()
    {
        $prefix = 'REG-' . date('Ymd') . '-';

        // Ambil pendaftaran terakhir berdasarkan kode_pendaftaran yang sesuai prefix
        $latest = Pendaftaran::where('kode_pendaftaran', 'like', $prefix . '%')
            ->orderBy('kode_pendaftaran', 'desc')
            ->first();

        if (!$latest) {
            $number = '001';
        } else {
            // Ambil angka urutan dari 3 digit terakhir
            $lastNumber = (int) substr($latest->kode_pendaftaran, -3);
            $number = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        }

        $this->kode_pendaftaran = $prefix . $number;
    }

    public function generateNomorAntrian($layananId)
    {
        // Mapping kode awalan berdasarkan ID layanan (atau bisa juga nama layanan)
        $kodePrefix = [
            1 => 'K-', // Misal ID 2 adalah Kesehatan Umum
            2 => 'B-', // Misal ID 1 adalah Beautycare
        ];

        $prefix = $kodePrefix[$layananId] ?? 'X-';

        $today = date('Y-m-d');

        // Ambil antrian terakhir untuk layanan dan tanggal hari ini
        $latest = Pendaftaran::where('layanan_id', $layananId)
            ->whereDate('tanggal_kunjungan', $today)
            ->where('nomor_antrian', 'like', $prefix . '%')
            ->orderBy('nomor_antrian', 'desc')
            ->first();


        if (!$latest) {
            $number = '001';
        } else {
            $lastNumber = (int) substr($latest->nomor_antrian, -3);
            $number = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        }

        return $prefix . $number;
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

    public function panggilPasien($id)
    {
        $pendaftaran = Pendaftaran::find($id);
        if ($pendaftaran && $pendaftaran->status === 'menunggu') {
            $pendaftaran->status = 'diperiksa';
            $pendaftaran->save();

            session()->flash('message', 'Pasien telah dipanggil dan status diperbarui.');
        }
    }


    public function savePendaftaran()
    {
        $this->validate();

        // Simpan atau update data pendaftaran
        $data = [
            'tanggal_kunjungan'       => $this->tgl_kunjungan,
            'pasien_id'           => $this->id_pasien,
            'layanan_id'          => $this->id_layanan,
            'detail_layanan_id'  => $this->layanan_detail_id,
            'catatan'             => $this->catatan,
            'nomor_antrian'       => $this->nomor_antrian,
            'kode_pendaftaran'    => $this->kode_pendaftaran,
        ];

        if ($this->id_pendaftaran) {
            Pendaftaran::where('id', $this->id_pendaftaran)->update($data);
            session()->flash('success', 'Pendaftaran berhasil ditambahkan.');
        } else {
            Pendaftaran::create($data);
            session()->flash('success', 'Pendaftaran berhasil diperbarui.');
        }

        $this->closeModal();
        $this->resetForm();
        return $this->redirect(route('pendaftaran'), navigate: true);
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

    // public function openDeleteModal($id)
    // {
    //     $service = LayananDetail::find($id);
    //     $this->nama_layanan = $service->nama_layanan;
    //     $this->kode_layanan = $service->kode_layanan;
    //     $this->serviceId = $id;
    //     $this->isDeleteModalOpen = true;
    // }

    // public function closeDeleteModal()
    // {
    //     $this->isDeleteModalOpen = false;
    //     $this->serviceId = null;
    // }
}
