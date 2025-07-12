<?php

namespace App\Livewire\Rak;

use Carbon\Carbon;
use App\Models\Rak;
use App\Models\Barang;
use App\Models\StokRak;
use Livewire\Component;
use App\Models\StokUmum;
use App\Models\BarangMasuk;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class StokRakIndex extends Component
{
    use WithPagination;

    public $title = 'Fayrooz | Stok Rak';
    public $perPage = 10;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'asc';
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;

    // Form fields
    public $stok_rak_id;
    public $rak_id;
    public $barang_masuk_id;
    public $tgl_masuk;
    public $barang_keluar_id;
    public $kode_rak;
    public $nama_rak;
    public $kode_masuk;
    public $kode_keluar;
    public $kode_barang;
    public $nama_barang;
    public $jumlah_barang;
    public $keterangan;
    public $barangMasuks = [];
    public $stokTersisa = [];
    public $maxJumlah;
    public $warningJumlah = null;


    public function render()
    {
        $items = Rak::with(['barang', 'stokrak.barang_masuk', 'stokrak'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('kode_rak', 'like', '%' . $this->search . '%')
                        ->orWhere('nama_rak', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        // Tambahkan perhitungan di tiap item rak
        $items->getCollection()->transform(function ($item) {
            $jumlahStok = $item->stokrak->sum('jumlah_sisa') ?? 0;
            $kapasitas = $item->kapasitas ?? 1; // sesuaikan kalau kapasitas disimpan di rak
            $persentase = ($kapasitas > 0) ? ($jumlahStok / $kapasitas) * 100 : 0;

            if ($persentase >= 60) {
                $warna = 'bg-green-500';
            } elseif ($persentase >= 20) {
                $warna = 'bg-yellow-500';
            } else {
                $warna = 'bg-red-500';
            }

            // Default status rak
            $statusBarang = 'Baik';
            $now = Carbon::now();

            if ($jumlahStok == 0) {
                $statusBarang = 'Tidak Ada Barang';
            } else {
                foreach ($item->stokrak as $stok) {
                    $expDate = optional($stok->barang_masuk)->exp_date;

                    if ($expDate) {
                        $expDate = Carbon::parse($expDate);
                        $selisihHari = $now->diffInDays($expDate, false);

                        if ($selisihHari < 0) {
                            $statusBarang = 'Ada Barang Expired';
                            break;
                        } elseif ($selisihHari <= 30 && $statusBarang != 'Ada Barang Expired') {
                            $statusBarang = 'Hampir Expired';
                        }
                    }
                }
            }

            $item->jumlah_stok = $jumlahStok;
            $item->persentase = $persentase;
            $item->warna_bar = $warna;
            $item->status_barang = $statusBarang;

            return $item;
        });

        return view('livewire.rak.stok-rak', [
            'items' => $items,
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
        $item = Rak::find($id);
        $this->rak_id = $id;
        $this->nama_rak = $item->nama_rak;
        $this->kode_rak = $item->kode_rak;
        $this->isDeleteModalOpen = true;
    }

    public function closeDeleteModal()
    {
        $this->isDeleteModalOpen = false;
        $this->rak_id = null;
    }

    public function resetForm()
    {
        $this->reset([
            'rak_id',
            'nama_rak',
            'kode_rak',
            'keterangan',
        ]);
    }

    public function tambahStok($id)
    {
        $this->rak_id = $id;

        // Cari rak yang sesuai
        $rak = Rak::findOrFail($id);

        // Ambil data barang masuk yang berelasi dengan rak
        $this->barangMasuks = BarangMasuk::with('barang', 'stokUmum') // Gunakan relasi stokUmum untuk mendapatkan stok umum
            ->where('id_barang', $rak->id_barang)
            ->orderBy('exp_date', 'asc') // Urutkan berdasarkan tanggal expired
            ->get();

        // Hitung stok tersisa untuk setiap barang yang masuk
        foreach ($this->barangMasuks as $masuk) {
            // Ambil stok yang ada di stok_umum berdasarkan barang_masuk_id
            $stokUmum = $masuk->stokUmum; // Menggunakan relasi yang sudah dimuat

            // Jika stok umum ditemukan, gunakan jumlah_tersedia sebagai stok tersisa
            $stokTersisa = $stokUmum ? $stokUmum->jumlah_tersedia : 0;

            // Simpan stok tersisa yang dihitung ke dalam array
            $this->stokTersisa[$masuk->id] = $stokTersisa;
        }

        // Buka modal untuk menampilkan data
        $this->isModalOpen = true;
    }

    public function updateMaxJumlah()
    {
        if ($this->barang_masuk_id) {
            $this->maxJumlah = $this->stokTersisa[$this->barang_masuk_id] ?? 0;
        } else {
            $this->maxJumlah = 0;
        }
    }

    public function updated($propertyName)
    {
        $this->resetErrorBag($propertyName);
    }

    public function saveStok($rak_id)
    {
        $this->validate([
            'barang_masuk_id' => 'required|exists:barang_masuk,id',
            'jumlah_barang' => [
                'required',
                'numeric',
                'min:1',
                function ($attribute, $value, $fail) {
                    if ($value > $this->maxJumlah) {
                        $fail('Jumlah barang melebihi stok tersedia (Max: ' . $this->maxJumlah . ')');
                    }
                }
            ],
            'tgl_masuk' => 'required|date',
            'keterangan' => 'nullable|string|max:255'
        ]);

        // Ambil stok umum berdasarkan barang_masuk_id
        $stokUmum = StokUmum::where('barang_masuk_id', $this->barang_masuk_id)->first();

        if (!$stokUmum) {
            $this->dispatch('flash-message', type: 'error', message: 'Stok umum tidak ditemukan.');
            return;
        }

        // Cek apakah stok mencukupi
        if ($stokUmum->jumlah_tersedia < $this->jumlah_barang) {
            $this->dispatch('flash-message', type: 'error', message: 'Stok umum tidak mencukupi.');
            return;
        }

        // Simpan ke stok rak
        StokRak::create([
            'rak_id' => $rak_id,
            'barang_masuk_id' => $this->barang_masuk_id,
            'jumlah_barang' => $this->jumlah_barang,
            'jumlah_sisa' => $this->jumlah_barang,
            'tgl_masuk' => $this->tgl_masuk,
            'keterangan' => $this->keterangan,
            'created_by' => auth()->id()
        ]);

        // Kurangi stok di stok umum
        $stokUmum->decrement('jumlah_tersedia', $this->jumlah_barang);

        // Reset input
        $this->reset(['barang_masuk_id', 'jumlah_barang']);

        // Kirim notifikasi
        $this->dispatch('flash-message', type: 'success', message: 'Stok berhasil ditambahkan.');

        $this->closeModal(); // Jika menggunakan modal
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
