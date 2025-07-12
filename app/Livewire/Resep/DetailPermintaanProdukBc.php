<?php

namespace App\Livewire\Resep;

use Carbon\Carbon;
use App\Models\StokRak;
use Livewire\Component;
use App\Models\BarangKeluar;
use App\Models\ResepProdukBc;
use App\Models\RekmedBeautycare;
use Illuminate\Support\Facades\DB;

class DetailPermintaanProdukBc extends Component
{

    public $title = 'Fayrooz | Detail Produk';
    public $rekmedBcId;
    public $history;
    public $selectedHistory;
    public $isModalOpen = false;
    public $rakTerpilih = []; // [resep_id => rak_id]
    public $stokRakOptions = []; // [resep_id => [stok_rak_id => nama]]
    public $stokRakTerpilih = []; // [resep_id => stok_rak_id]
    public function mount($id)
    {
        $this->history = RekmedBeautycare::with([
            'pasien',
            'diagnosa',
            'keluhanPasienBc',
            'diagnosaPasienBc',
            'pendaftaran.layanan',
            'resepProdukBc.barang'
        ])->findOrFail($id);
        $this->selectedHistory = RekmedBeautycare::with('resepProdukBc.barang')->findOrFail($id);
    }
    public function render()
    {
        return view('livewire.resep.detail-permintaan-produk-bc')->extends('layouts.app', [
            'title' => $this->title // Kirim title ke layout
        ]);
    }

    public function updatedRakTerpilih($value, $key)
    {
        $resepId = $key;
        $rakId = $this->rakTerpilih[$resepId] ?? null;

        if ($rakId) {
            $stokRaks = StokRak::with(['barang_masuk.barang'])
                ->where('rak_id', $rakId)
                ->get()
                ->sortBy(function ($item) {
                    return $item->barang_masuk->exp_date ?? now()->addYears(10); // fallback kalau null
                });


            $this->stokRakOptions[$resepId] = $stokRaks->mapWithKeys(function ($item) {
                $kode = $item->barang_masuk->kode_masuk ?? 'KODE?';
                $nama = $item->barang_masuk->barang->nama_barang ?? 'NAMA?';
                $sisa = $item->jumlah_sisa ?? 0;
                $exp = $item->barang_masuk->exp_date
                    ? Carbon::parse($item->barang_masuk->exp_date)->format('d-m-Y')
                    : 'N/A';
                return [
                    $item->id => "$kode - $nama (Sisa: $sisa) | Exp: $exp"
                ];
            })->toArray();
        } else {
            $this->stokRakOptions[$resepId] = [];
        }

        $this->stokRakTerpilih[$resepId] = null;
    }

    public function updated($propertyName)
    {
        $this->resetErrorBag($propertyName);
    }

    public function openConfirmModal($id)
    {
        $this->rekmedBcId = $id;

        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->rekmedBcId = null;
    }
    public function konfirmasiResep()
    {
        $rules = [];
        $messages = [];

        // Susun aturan validasi untuk setiap resep
        foreach ($this->selectedHistory->resepProdukBc as $resep) {
            $rules["rakTerpilih.{$resep->id}"] = 'required';
            $rules["stokRakTerpilih.{$resep->id}"] = 'required';

            $messages["rakTerpilih.{$resep->id}.required"] = "Rak untuk {$resep->barang->nama_barang} harus dipilih.";
            $messages["stokRakTerpilih.{$resep->id}.required"] = "Stok rak untuk {$resep->barang->nama_barang} harus dipilih.";
        }

        // Lakukan validasi
        $this->validate($rules, $messages);

        // Lanjutkan proses konfirmasi
        DB::beginTransaction();

        try {
            foreach ($this->selectedHistory->resepProdukBc as $resep) {
                $rakId = $this->rakTerpilih[$resep->id];
                $stokRakId = $this->stokRakTerpilih[$resep->id];

                BarangKeluar::create([
                    'barang_id'     => $resep->barang->id,
                    'rekmed_bc_id'  => $this->rekmedBcId,
                    'jumlah'        => $resep->jumlah,
                    'status_keluar' => 'resep',
                    'keterangan'    => 'Pengeluaran resep dari history ID: ' . $this->rekmedBcId,
                    'tgl_keluar'    => now(),
                    'rak_id'        => $rakId,
                    'stok_rak_id'   => $stokRakId,
                ]);

                $stokRak = StokRak::find($stokRakId);
                if ($stokRak) {
                    $stokRak->jumlah_sisa = max(0, $stokRak->jumlah_sisa - $resep->jumlah);
                    $stokRak->save();
                }
            }

            ResepProdukBc::where('rekmed_bc_id', $this->rekmedBcId)
                ->update(['status' => 'dikonfirmasi']);

            DB::commit();

            $this->closeModal();
            $this->resetPage();

            session()->flash('success', 'Resep berhasil dikonfirmasi dan barang dikeluarkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal konfirmasi resep: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat mengkonfirmasi resep.');
        }
    }
}
