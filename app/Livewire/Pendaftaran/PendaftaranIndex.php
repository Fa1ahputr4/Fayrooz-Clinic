<?php

namespace App\Livewire\Pendaftaran;

use Carbon\Carbon;
use App\Models\Pasien;
use App\Models\Layanan;
use Livewire\Component;
use App\Models\Pendaftaran;
use Livewire\WithPagination;
use App\Models\LayananDetail;
use App\Exports\PendaftaranExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class PendaftaranIndex extends Component
{
    use WithPagination;

    public $title = 'Fayrooz | Pendaftaran';
    public $perPage = 10;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;

    public $pendaftaranId, $kodePendaftaran, $pasienId, $layananId, $layananDetailId;
    public $noAntri, $tglKunjungan, $status, $catatan, $namaPj, $kontakPj;
    public $daftarDetailLayanan = [];
    public $tab = 'today'; // atau 'all'
    public $dateRange = '';
    public $startDate;
    public $endDate;
    public $pendaftaranDetail;

    protected $listeners = ['dateRangeSelected', 'refreshSelect2Pasien'];

    public function exportExcel()
    {
        $query = Pendaftaran::with(['pasien', 'layanan', 'layananDetail'])
            ->whereNull('deleted_at')
            ->when($this->tab === 'today', function ($q) {
                $q->whereDate('created_at', now()->toDateString());
            })
            ->when($this->tab === 'all' && $this->startDate && $this->endDate, function ($q) {
                $q->whereBetween('created_at', [$this->startDate, $this->endDate]);
            })
            ->when($this->search, function ($q) {
                $q->where(function ($q2) {
                    $q2->where('kode_pendaftaran', 'like', "%{$this->search}%")
                        ->orWhereHas('pasien', function ($q3) {
                            $q3->where('nomor_rm', 'like', "%{$this->search}%");
                        });
                });
            })
            ->orderByRaw("FIELD(status, 'menunggu', 'diperiksa', 'selesai')")
            ->orderBy($this->sortField, $this->sortDirection);

        // Tentukan nama file dinamis
        if ($this->tab === 'all' && $this->startDate && $this->endDate) {
            \Carbon\Carbon::setLocale('id');
            $start = \Carbon\Carbon::parse($this->startDate)->translatedFormat('d F Y');
            $end = \Carbon\Carbon::parse($this->endDate)->translatedFormat('d F Y');
            $filename = "data-pendaftaran {$start} - {$end}.xlsx";
        } else {
            $filename = 'data-pendaftaran semua.xlsx';
        }

        return Excel::download(new PendaftaranExport($query), $filename);
    }

    public function dateRangeSelected($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->resetPage();
    }

    public function updatedTab($value)
    {
        if ($value === 'today') {
            $this->reset(['startDate', 'endDate', 'dateRange']);
        }
        $this->resetPage();
    }

    public function resetDateFilter()
    {
        $this->reset(['startDate', 'endDate', 'dateRange']);
        $this->resetPage();
    }

    public function updatingTab()
    {
        $this->resetPage(); // reset pagination saat tab berubah
    }

    protected function rules()
    {
        return [
            'tglKunjungan'       => 'required|date',
            'pasienId'           => 'required|exists:pasien,id',
            'layananId'          => 'required|exists:layanan,id',
            'layananDetailId'   => 'required|exists:layanan_details,id',
            'catatan'             => 'nullable|string|max:255',
            'noAntri'       => 'nullable|string|max:10',
            'kodePendaftaran'    => 'nullable|string|max:20',
        ];
    }

    protected function messages()
    {
        return [
            'tglKunjungan.required' => 'Tanggal kunjungan wajib diisi',
            'pasienId.required'    => 'Silakan pilih pasien',
            'layananId.required'   => 'Jenis layanan wajib dipilih',
            'layananDetailId.required' => 'Detail layanan wajib dipilih',
        ];
    }

    public function updated($propertyName)
    {
        $this->resetErrorBag($propertyName);
    }

    public function savePendaftaran()
    {
        $this->validate();

        // Simpan atau update data pendaftaran
        $data = [
            'tanggal_kunjungan'       => $this->tglKunjungan,
            'pasien_id'           => $this->pasienId,
            'layanan_id'          => $this->layananId,
            'detail_layanan_id'  => $this->layananDetailId,
            'catatan'             => $this->catatan,
            'nomor_antrian'       => $this->noAntri,
            'kode_pendaftaran'    => $this->kodePendaftaran,
            'nama_penanggung_jawab' => $this->namaPj,
            'kontak_penanggung_jawab' => $this->kontakPj,
        ];

        if ($this->pendaftaranId) {
            $data['updated_by'] = Auth::id();
            Pendaftaran::where('id', $this->pendaftaranId)->update($data);
            $this->dispatch('flash-message', type: 'success', message: 'Pendaftaran berhasil diubah.');
        } else {
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();
            Pendaftaran::create($data);
            $this->dispatch('flash-message', type: 'success', message: 'Pendaftaran berhasil ditambahkan.');
        }

        $this->closeModal();
        $this->resetForm();
    }

    public function render()
    {
        $pendaftaran = Pendaftaran::query()
            ->with(['pasien', 'layanan', 'layananDetail', 'createdBy', 'updatedBy'])
            ->when($this->tab === 'today', function ($query) {
                $query->whereDate('created_at', now()->toDateString());
            })
            ->when($this->tab === 'all' && $this->startDate && $this->endDate, function ($query) {
                $query->whereBetween('created_at', [
                    Carbon::parse($this->startDate)->startOfDay(),
                    Carbon::parse($this->endDate)->endOfDay()
                ]);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('kode_pendaftaran', 'like', '%' . $this->search . '%')
                        ->orWhere('nomor_antrian', 'like', '%' . $this->search . '%')
                        ->orWhereHas('pasien', function ($q2) {
                            $q2->where('nama_lengkap', 'like', '%' . $this->search . '%')
                                ->orWhere('nomor_rm', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->orderByRaw("FIELD(status, 'menunggu', 'diperiksa', 'selesai')")
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $daftarLayanan = Layanan::all();
        $patients = Pasien::all();

        return view('livewire.pendaftaran.pendaftaran-index', [
            'patients' => $patients,
            'pendaftaran' => $pendaftaran,
            'daftarLayanan' => $daftarLayanan,
        ])->extends('layouts.app', [
            'title' => $this->title // Kirim title ke layout
        ]);
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

        $this->kodePendaftaran = $prefix . $number;
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

    public function updatedLayananId($value)
    {
        $this->noAntri = $this->generateNomorAntrian($this->layananId);
        $this->layananDetailId = null; // Reset pilihan detail
        $this->daftarDetailLayanan = LayananDetail::where('layanan_id', $value)->get();
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
        $this->pendaftaranId = null;
        $this->resetForm();
        $this->generateKodePendaftaran();
        $this->tglKunjungan = now()->toDateString();

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
            $this->dispatch('flash-message', type: 'success', message: 'Pasien telah dipanggil dan status diperbarui.');
        }
    }

    public function resetForm()
    {
        $this->reset([
            'kodePendaftaran',
            'noAntri',
            'pasienId',
            'layananId',
            'layananDetailId',
            'tglKunjungan',
            'catatan',
            'namaPj',
            'kontakPj'
        ]);
    }

    public function editPendaftaran($id)
    {
        $p = Pendaftaran::findOrFail($id);
        $this->pendaftaranDetail = Pendaftaran::with(['createdBy', 'updatedBy'])->find($id);

        $this->pendaftaranId = $id;
        $this->tglKunjungan = Carbon::parse($p->tanggal_kunjungan)->format('Y-m-d');
        $this->kodePendaftaran = $p->kode_pendaftaran;
        $this->noAntri = $p->nomor_antrian;
        $this->pasienId = $p->pasien_id;
        $this->layananId = $p->layanan_id;
        $this->daftarDetailLayanan = LayananDetail::where('layanan_id', $this->layananId)->get();
        $this->layananDetailId = $p->detail_layanan_id;
        $this->catatan = $p->catatan;
        $this->namaPj = $p->nama_penanggung_jawab;
        $this->kontakPj = $p->kontak_penanggung_jawab;

        $this->isModalOpen = true;
        $this->dispatch('editPendaftaran');
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

    public function openDeleteModal($id)
    {
        $pendaftaran = Pendaftaran::find($id);
        $this->noAntri = $pendaftaran->nomor_antrian;
        $this->kodePendaftaran = $pendaftaran->kode_pendaftaran;
        $this->pendaftaranId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function closeDeleteModal()
    {
        $this->isDeleteModalOpen = false;
        $this->pendaftaranId = null;
    }

    public function deletePendaftaran()
    {

        $pendaftaran = Pendaftaran::find($this->pendaftaranId);

        if ($pendaftaran) {
            $pendaftaran->delete();
            $this->dispatch('flash-message', type: 'success', message: 'Data pendaftaran berhasil dihapus.');
        } else {
            $this->dispatch('flash-message', type: 'error', message: 'Data pendaftaran gagal dihapus.');
        }

        $this->closeDeleteModal();
    }
}
