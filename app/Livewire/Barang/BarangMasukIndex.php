<?php

namespace App\Livewire\Barang;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\StokUmum;
use Illuminate\Validation\Rules;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BarangMasukExport;

class BarangMasukIndex extends Component
{
    use WithPagination;

    public $title = 'Fayrooz | Barang Masuk';
    public $perPage = 10;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'asc';
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $modalDetail = false;

    // Form fields
    public $inbarangId;
    public $barangId;
    public $nama_barang;
    public $kode_barang;
    public $kode_masuk;
    public $selected = '';
    public $satuan;
    public $jenis;
    public $jumlah_masuk;
    public $total_harga;
    public $batch_no;
    public $exp_date;
    public $tanggal_masuk;
    public $keterangan;
    public $isEdit = false;

    public $dateRange = '';
    public $startDate;
    public $endDate;

    public function exportExcel()
    {
        $query = BarangMasuk::with(['barang'])
            ->whereNull('deleted_at')

            // Filter pencarian
            ->when($this->search, function ($q) {
                $q->where(function ($q2) {
                    $q2->where('kode_barang_masuk', 'like', "%{$this->search}%")
                        ->orWhereHas('barang', function ($q3) {
                            $q3->where('nama_barang', 'like', "%{$this->search}%")
                                ->orWhere('kode_barang', 'like', "%{$this->search}%");
                        });
                });
            })

            // Filter tanggal
            ->when($this->startDate, function ($q) {
                $q->whereDate('tanggal_masuk', '>=', $this->startDate);
            })
            ->when($this->endDate, function ($q) {
                $q->whereDate('tanggal_masuk', '<=', $this->endDate);
            })

            // Pengurutan
            ->orderBy($this->sortField, $this->sortDirection);

        // Tentukan nama file dinamis
        if ($this->startDate && $this->endDate) {
            \Carbon\Carbon::setLocale('id');
            $start = \Carbon\Carbon::parse($this->startDate)->translatedFormat('d F Y');
            $end = \Carbon\Carbon::parse($this->endDate)->translatedFormat('d F Y');
            $filename = "data-barang-masuk {$start} - {$end}.xlsx";
        } else {
            $filename = 'data-barang-masuk semua.xlsx';
        }

        return Excel::download(new BarangMasukExport($query), $filename);
    }
    public function resetDateFilter()
    {
        $this->reset(['startDate', 'endDate', 'dateRange']);
        $this->resetPage();
    }
    public function dateRangeSelected($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->resetPage();
    }
    public function setBarangId($value)
    {
        $this->barangId = $value;
    }
    public function render()
    {
        // Mengambil data barang masuk dengan informasi barang terkait
        $items = BarangMasuk::with('barang', 'createdBy', 'updatedBy')
            ->where('is_delete', false) // <-- ini penting
            ->when($this->search, function ($query) {
                $query->whereHas('barang', function ($q) {
                    $q->where('kode_barang', 'like', '%' . $this->search . '%')
                        ->orWhere('nama_barang', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->startDate && $this->endDate, function ($query) {
                $query->whereBetween('tanggal_masuk', [
                    $this->startDate,
                    $this->endDate
                ]);
            })

            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        // Menampilkan satuan distinct dari tabel barang
        $itemOptions = Barang::select('id', 'nama_barang')
            ->distinct()
            ->whereNotNull('nama_barang')
            ->get();

        return view('livewire.barang.barang_masuk_index', [
            'items' => $items,
            'itemOptions' => $itemOptions,
        ])->extends('layouts.app', [
            'title' => $this->title // Kirim title ke layout
        ]);
    }

    public function generateKodeMasuk()
    {
        $prefix = 'MSK-' . date('Ymd') . '-';

        // Cari kode_masuk terbaru YANG diawali dengan prefix
        $latest = BarangMasuk::where('kode_masuk', 'like', $prefix . '%')
            ->orderBy('kode_masuk', 'desc')
            ->first();

        if (!$latest) {
            $number = '001';
        } else {
            // Ambil 3 digit angka dari kode_masuk
            $lastNumber = (int) substr($latest->kode_masuk, -3);
            $number = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        }

        $this->kode_masuk = $prefix . $number;
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

    public function resetForm()
    {
        $this->reset([
            'barangId',
            'inbarangId',
            'kode_masuk',
            'kode_barang',
            'jenis',
            'satuan',
            'jumlah_masuk',
            'total_harga',
            'batch_no',
            'exp_date',
            'tanggal_masuk',
            'keterangan'
        ]);
    }

    public function openDeleteModal($id)
    {
        $item = BarangMasuk::find($id);

        $sudahMasukRak = \App\Models\StokRak::where('barang_masuk_id', $item->id)->exists();
        $sudahKeluarViaRak = \App\Models\BarangKeluar::whereHas('stok_rak', function ($query) use ($item) {
            $query->where('barang_masuk_id', $item->id);
        })->exists();
        $sudahKeluarViaUmum = \App\Models\BarangKeluar::whereHas('stok_umum', function ($query) use ($item) {
            $query->where('barang_masuk_id', $item->id);
        })->exists();
        if ($sudahMasukRak || $sudahKeluarViaRak || $sudahKeluarViaUmum) {
            $this->dispatch('flash-message', type: 'error', message: 'Barang sudah digunakan atau keluar. Tidak bisa dihapus.');
            return;
        }

        $this->inbarangId = $id;
        $this->barangId = $item->id_barang;
        $this->kode_masuk = $item->kode_masuk;
        $this->isDeleteModalOpen = true;
    }

    public function closeDeleteModal()
    {
        $this->isDeleteModalOpen = false;
        $this->barangId = null;
    }

    public function openModal()
    {
        $this->resetForm();
        $this->generateKodeMasuk();
        $this->isModalOpen = true;
        $this->isEdit = false;
        $this->dispatch('barangMasukTambah');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
        $this->resetErrorBag();
    }

    public function detailBarangMasuk($id)
    {
        $this->resetForm();

        $item = BarangMasuk::findOrFail($id);

        $this->inbarangId = $item->id;
        $this->barangId = $item->id_barang;
        $this->kode_masuk = $item->kode_masuk;
        $this->jumlah_masuk = $item->jumlah;
        $this->batch_no = $item->batch_no;
        $this->exp_date = $item->exp_date;
        $this->tanggal_masuk = $item->tanggal_masuk;
        $this->keterangan = $item->keterangan;
        $this->modalDetail = true;
    }

    public function editBarangMasuk($id)
    {
        $this->resetForm();

        $item = BarangMasuk::findOrFail($id);
        $sudahMasukRak = \App\Models\StokRak::where('barang_masuk_id', $item->id)->exists();
        $sudahKeluarViaRak = \App\Models\BarangKeluar::whereHas('stok_rak', function ($query) use ($item) {
            $query->where('barang_masuk_id', $item->id);
        })->exists();
        $sudahKeluarViaUmum = \App\Models\BarangKeluar::whereHas('stok_umum', function ($query) use ($item) {
            $query->where('barang_masuk_id', $item->id);
        })->exists();
        if ($sudahMasukRak || $sudahKeluarViaRak || $sudahKeluarViaUmum) {
            $this->dispatch('flash-message', type: 'error', message: 'Barang sudah digunakan atau keluar. Tidak bisa diedit.');
            return;
        }

        $this->inbarangId = $item->id;
        $this->barangId = $item->id_barang;
        $this->kode_masuk = $item->kode_masuk;
        $this->jumlah_masuk = $item->jumlah;
        $this->total_harga = (int)$item->total_harga; // Pastikan sebagai integer
        $this->batch_no = $item->batch_no;
        $this->exp_date = $item->exp_date;
        $this->tanggal_masuk = $item->tanggal_masuk;
        $this->keterangan = $item->keterangan;
        $this->isModalOpen = true;
        $this->isEdit = true;
        $this->dispatch('barangMasukEdited');
    }

    public function saveBarang()
    {
        $this->validate([
            'barangId' => 'required',
            'kode_masuk' => 'required|string',
            'jumlah_masuk' => 'required|integer',
            'tanggal_masuk' => 'required|date',
            'batch_no' => 'required|string',
            'exp_date' => 'required|date',
            'keterangan' => 'nullable|string',
        ], [
            'barangId.required' => 'Barang wajib diisi.',
            'jumlah_masuk.required' => 'Jumlah barang tidak boleh kosong.',
            'tanggal_masuk.required' => 'Tanggal masuk tidak boleh kosong.',
            'batch_no.required' => 'Nomor produk tidak boleh kosong.',
            'exp_date.required' => 'Tanggal expired tidak boleh kosong.',
            'total_harga.required' => 'Total harga tidak boleh kosong.',
        ]);

        $data = [
            'id_barang' => $this->barangId,
            'kode_masuk' => $this->kode_masuk,
            'jumlah' => $this->jumlah_masuk,
            'satuan' => $this->satuan,
            'batch_no' => $this->batch_no,
            'exp_date' => $this->exp_date,
            'tanggal_masuk' => $this->tanggal_masuk,
            'keterangan' => $this->keterangan
        ];

        if ($this->inbarangId) {
            // Ambil data lama
            $barangMasuk = BarangMasuk::find($this->inbarangId);
            $jumlahLama = $barangMasuk->jumlah;

            // Update barang masuk
            $barangMasuk->update($data);

            // Update stok_umum
            $stokUmum = StokUmum::where('barang_masuk_id', $barangMasuk->id)->first();
            if ($stokUmum) {
                // Hitung selisih jumlah dan update
                $selisih = $this->jumlah_masuk - $jumlahLama;
                $stokUmum->update([
                    'jumlah_tersedia' => $stokUmum->jumlah_tersedia + $selisih
                ]);
            }

            $message = 'Data barang berhasil diperbarui.';
        } else {
            // Simpan ke tabel barang_masuk
            $barangMasuk = BarangMasuk::create($data);

            // Tambahkan data ke tabel stok_umum
            StokUmum::create([
                'barang_masuk_id' => $barangMasuk->id,
                'jumlah_tersedia' => $this->jumlah_masuk,
            ]);

            $message = 'Data barang berhasil ditambahkan.';
        }

        $this->dispatch('flash-message', type: 'success', message: $message);
        $this->closeModal();
    }


    public function deleteBarang()
    {
        $item = BarangMasuk::find($this->inbarangId);

        if (!$item) {
            $this->dispatch('flash-message', type: 'error', message: 'Barang tidak ditemukan.');
            return;
        }

        $item->update([
            'is_delete' => true
        ]);
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
