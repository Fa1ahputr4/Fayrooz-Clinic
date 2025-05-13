<?php

namespace App\Livewire\Rak;

use Livewire\Component;
use App\Models\Rak;
use App\Models\StokRak;
use App\Models\StokUmum;
use App\Models\BarangMasuk;
use App\Models\Barang;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class StokRakIndex extends Component
{
    use WithPagination;

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


    public function render()
{
    $items = Rak::with(['barang', 'stokrak'])
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

        $item->jumlah_stok = $jumlahStok;
        $item->persentase = $persentase;
        $item->warna_bar = $warna;

        return $item;
    });

    return view('livewire.rak.stok-rak', [
        'items' => $items,
    ])->extends('layouts.app');
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



    protected $rules = [
        'barang_masuk_id' => 'required',
        'jumlah_barang' => 'required|numeric|min:1',
        'tgl_masuk' => 'required'
    ];

    // Tambahkan ini untuk validasi custom
    protected function rules()
    {
        return [
            'barang_masuk_id' => 'required',
            'tgl_masuk' => 'required',
            'jumlah_barang' => [
                'required',
                'numeric',
                'min:1',
                function ($attribute, $value, $fail) {
                    if ($this->barang_masuk_id && isset($this->stokTersisa[$this->barang_masuk_id])) {
                        if ($value > $this->stokTersisa[$this->barang_masuk_id]) {
                            $fail('Jumlah melebihi stok tersedia (Maks: ' . $this->stokTersisa[$this->barang_masuk_id] . ')');
                        }
                    }
                }
            ],
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        // Validasi custom real-time
        if ($propertyName === 'jumlah_barang' && $this->barang_masuk_id) {
            $this->validate([
                'jumlah_barang' => [
                    function ($attribute, $value, $fail) {
                        if ($value > $this->stokTersisa[$this->barang_masuk_id]) {
                            $fail('Jumlah melebihi stok tersedia');
                        }
                    }
                ]
            ]);
        }
    }

    public function validateJumlah()
    {
        $this->validateOnly('jumlah_barang');

        if ($this->barang_masuk_id && $this->jumlah_barang) {
            $this->validate([
                'jumlah_barang' => [
                    function ($attribute, $value, $fail) {
                        if ($value > $this->stokTersisa[$this->barang_masuk_id]) {
                            $fail('Jumlah melebihi stok tersedia');
                        }
                    }
                ]
            ]);
        }
    }

    protected $listeners = ['updateMaxJumlah' => 'updateMaxJumlah'];

    public function updateMaxJumlah()
    {
        $this->reset('jumlah_barang');
        $this->resetErrorBag('jumlah_barang');
    }

    public function saveStok($rak_id)
{
    $this->validate();

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
