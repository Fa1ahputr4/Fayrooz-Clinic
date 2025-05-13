<?php

namespace App\Livewire\Barang;

use Livewire\Component;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\Barang;
use App\Models\Rak;
use App\Models\StokRak;
use App\Models\StokUmum;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;

use function Laravel\Prompts\alert;

class BarangKeluarIndex extends Component
{

    use WithPagination;

    public $perPage = 10;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'asc';
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;

    public $selectedBarang; // Menyimpan id barang yang dipilih
    public $selectedRak;
    public $rak_id;
    public $tanggal_keluar;
    public $keterangan;
    public $stok_umum_id;
    public $status;
    public $barang_masuk_id;
    public $stok_rak_id;
    public $jumlah_barang;
    public $rakOptions = [];
    public $barangMasukOptions = [];
    public $barangMasukDariRakOptions = [];
    public $stok_rak_tersisa = 0;
    public $stok_barang_masuk_tersisa = 0;
    public bool $gunakanBarangMasuk = false;

    public function updatedGunakanBarangMasuk($value)
    {
        if ($value) {
            $this->selectedRak = null; // kosongkan rak jika pindah ke barang masuk
        } else {
            $this->barang_masuk_id = null; // kosongkan barang masuk jika pindah ke rak
        }

        $this->resetValidation(); // reset error validation juga
    }


    public function updatedJumlahBarang()
    {
        $this->hitungStokRakTersisa();
        $this->hitungStokBarangMasukTersisa();

        $this->validateJumlahBarang();

        // Paksa render ulang untuk menampilkan error
        $this->dispatch('jumlah-updated');
    }

    public function updatedSelectedRak($value)
    {
        $stokRakData = StokRak::with('barang_masuk')
            ->where('rak_id', $value)
            ->where('jumlah_sisa', '>', 0)
            ->get()
            ->sortBy(function ($item) {
                return optional($item->barang_masuk)->exp_date;
            });

        $this->barangMasukDariRakOptions = $stokRakData->map(function ($item) {
            $barangMasuk = $item->barang_masuk;
            return [
                'id' => $item->id, // gunakan ID stok_rak
                'barang_masuk_id' => $item->barang_masuk_id,
                'kode_masuk' => optional($barangMasuk)->kode_masuk,
                'exp_date' => optional($barangMasuk)->exp_date,
                'stok_sisa' => $item->jumlah_sisa,
            ];
        })->values()->toArray();
    }


    public function updatedBarangMasukId()
    {
        $this->hitungStokRakTersisa();
        $this->hitungStokBarangMasukTersisa();
        $this->validateJumlahBarang();

        // Debug setelah update
    }

    public function updatedStokRakId()
    {
        $this->hitungStokRakTersisa();
        $this->hitungStokBarangMasukTersisa();
        $this->validateJumlahBarang();

        // Debug setelah update
    }

    public function hitungStokRakTersisa()
    {
        if ($this->selectedRak) {
            if ($this->stok_rak_id) {
                // Ambil jumlah_sisa dari stok_rak tertentu
                $this->stok_rak_tersisa = StokRak::where('rak_id', $this->selectedRak)
                    ->where('id', $this->stok_rak_id)
                    ->value('jumlah_sisa') ?? 0;
            } else {
                // Ambil semua stok (bisa jadi untuk opsi lain)
                $this->stok_rak_tersisa = 0;
            }
        } else {
            $this->stok_rak_tersisa = 0;
        }
    }


    public function hitungStokBarangMasukTersisa()
    {
        if ($this->barang_masuk_id) {
            // Ambil barang masuk dari stok umum
            $barangMasuk = BarangMasuk::find($this->barang_masuk_id);

            // Jika barang masuk ada, ambil stok tersisa dari tabel stok umum
            $this->stok_barang_masuk_tersisa = $barangMasuk->stokUmum->jumlah_tersedia ?? 0;
        } else {
            $this->stok_barang_masuk_tersisa = 0;
        }
    }


    // Method validasi
    public function validateJumlahBarang()
    {
        $this->resetErrorBag();

        $rules = [
            'jumlah_barang' => ['required', 'numeric', 'min:1']
        ];

        if ($this->selectedRak && $this->barang_masuk_id) {
            $rules['jumlah_barang'][] = 'max:' . $this->stok_rak_tersisa;
        } elseif ($this->selectedRak) {
            $rules['jumlah_barang'][] = 'max:' . $this->stok_rak_tersisa;
        } elseif ($this->barang_masuk_id) {
            $rules['jumlah_barang'][] = 'max:' . $this->stok_barang_masuk_tersisa;
        }

        $this->validate($rules, [
            'jumlah_barang.max' => 'Jumlah melebihi stok tersedia (:max pcs)'
        ]);
    }



    public function updatedSelectedBarang($barangId)
    {
        // Ambil rak yang sesuai dengan barang yang dipilih
        $this->rakOptions = Rak::with('stokrak')
            ->where('id_barang', $barangId)
            ->get()
            ->mapWithKeys(function ($rak) {
                $jumlahSisa = $rak->stokrak->sum('jumlah_sisa');
                return [
                    $rak->id => $rak->nama_rak . ' (sisa: ' . $jumlahSisa . ')'
                ];
            })
            ->toArray();


        $this->barangMasukOptions = BarangMasuk::with('stokUmum') // eager load
            ->where('id_barang', $barangId)
            ->orderBy('exp_date', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'stok_umum_id' => $item->stokUmum->id,
                    'kode_masuk' => $item->kode_masuk,
                    'exp_date' => Carbon::parse($item->exp_date)->translatedFormat('d F Y'),
                    'sisa' => $item->stokUmum->jumlah_tersedia ?? 0,
                ];
            })
            ->toArray();


        $this->barang_masuk_id = null;
        $this->selectedRak = null;
    }

    public function render()
    {
        $items = BarangKeluar::with(['barang', 'rak', 'stok_rak'])
            ->when($this->search, function ($query) {
                $query->whereHas('barang', function ($q) {
                    $q->where('kode_barang', 'like', '%' . $this->search . '%')
                        ->orWhere('nama_barang', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $itemOptions = Barang::select('id', 'nama_barang')
            ->distinct()
            ->whereNotNull('nama_barang')
            ->get();

        $rakOptions = Rak::select('id', 'nama_rak')
            ->distinct()
            ->whereNotNull('nama_rak')
            ->get();

        return view('livewire.barang.barang-keluar-index', [
            'items' => $items,
            'itemOptions' => $itemOptions,
            'rakOptions' => $rakOptions,
        ])->extends('layouts.app');
    }


    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetErrorBag();
    }

    public function saveBarangKeluar()
    {
        $rules = [
            'tanggal_keluar' => 'required|date',
            'status' => 'required|in:rusak,expired,terpakai,lainya',
            'selectedBarang' => 'required|exists:barangs,id',
            'jumlah_barang' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string|max:255',
        ];

        $barang_masuk_id = null;
        $selectedOption = collect($this->barangMasukOptions)
            ->firstWhere('id', $this->barang_masuk_id);
        
        $stokUmumId = $selectedOption['stok_umum_id'] ?? null;

        // Tambahkan validasi bersyarat
        if ($this->gunakanBarangMasuk) {
            $rules['barang_masuk_id'] = 'required|exists:barang_masuk,id';
            $barang_masuk_id = $this->barang_masuk_id;
        } else {
            $rules['selectedRak'] = 'required|exists:raks,id';
            $rules['stok_rak_id'] = 'required|exists:stok_raks,id';
            $stok_rak_id = $this->stok_rak_id;

            $stokRak = StokRak::find($stok_rak_id);
            if ($stokRak) {
                $barang_masuk_id = $stokRak->barang_masuk_id;
            }
        }

        // Jalankan validasi sekaligus
        $this->validate($rules);


        BarangKeluar::create([
            'tgl_keluar' => $this->tanggal_keluar,
            'status_keluar' => $this->status,
            'barang_id' => $this->selectedBarang,
            'rak_id' => $this->selectedRak,
            'stok_umum_id' => $stokUmumId,
            'stok_rak_id' => $this->stok_rak_id,
            'barang_masuk_id' => $barang_masuk_id,
            'jumlah' => $this->jumlah_barang,
            'keterangan' => $this->keterangan,
        ]);

        $this->resetForm();
        $this->isModalOpen = false;

        $this->dispatch('flash-message', type: 'success', message: 'Stok berhasil ditambahkan.');
    }

    protected function resetForm()
    {
        $this->reset([
            'tanggal_keluar',
            'status',
            'selectedBarang',
            'jumlah_barang',
            'keterangan',
            'selectedRak',
            'barang_masuk_id',
            'gunakanBarangMasuk',
            'stok_rak_tersisa',
            'stok_barang_masuk_tersisa'
        ]);
    }
}
