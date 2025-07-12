<?php

namespace App\Livewire\Whatsapp;

use App\Models\LogWhatsapp;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\WithPagination;

class LogWa extends Component
{
    use WithPagination;

    public $title = 'Fayrooz | Log Pesan';
    public $search = '';
    public $perPage = 10;
    public $sortField = 'waktu_kirim';
    public $sortDirection = 'desc';
    public $activeTab = 'umum';
    public $isModalOpen = false;
    public $selectedLog;
    public $idLog;

    public $isDeleteModalOpen = false;
    public $selectedNomor;

    public function openDeleteModal($id)
    {
        $log = \App\Models\LogWhatsapp::find($id);

        if (!$log) {
            session()->flash('error', 'Data tidak ditemukan.');
            return;
        }

        $this->idLog = $id;
        $this->selectedNomor = $log->nomor_wa;
        $this->isDeleteModalOpen = true;
    }

    public function closeDeleteModal()
    {
        $this->reset(['isDeleteModalOpen', 'idLog', 'selectedNomor']);
    }

    public function deleteLog()
    {
        if (!$this->idLog) return;

        \App\Models\LogWhatsapp::find($this->idLog)?->delete();

        session()->flash('success', 'Log berhasil dihapus.');

        $this->closeDeleteModal();
    }


    public function infoDetail($id)
    {
        $this->selectedLog = LogWhatsapp::with(['rekmedUmum.pasien', 'rekmedBc.pasien'])->find($id);
        $this->idLog = $id;
        $this->isModalOpen = true;
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function render()
    {
        $logs = LogWhatsapp::query()
            ->when($this->activeTab === 'umum', function ($query) {
                $query->whereNotNull('rekmed_umum_id')
                    ->with(['rekmedUmum.pasien']);
            })
            ->when($this->activeTab === 'bc', function ($query) {
                $query->whereNotNull('rekmed_bc_id')
                    ->with(['rekmedBc.pasien']);
            })
            ->when($this->search, function ($query) {
                $query->where('nomor_wa', 'like', '%' . $this->search . '%')
                    ->orWhere('pesan', 'like', '%' . $this->search . '%')
                    ->orWhereHas('rekmedUmum.pasien', function ($q) {
                        $q->where('nama', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('rekmedBc.pasien', function ($q) {
                        $q->where('nama', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy('waktu_kirim', 'desc')
            ->paginate($this->perPage);

        return view('livewire.whatsapp.log-wa', [
            'logs' => $logs
        ])->extends('layouts.app', [
            'title' => $this->title // Kirim title ke layout
        ]);
    }

    public function kirimUlang($id)
    {
        $log = LogWhatsapp::find($id);

        if (!$log || $log->status === 'sukses') {
            session()->flash('error', 'Data tidak ditemukan atau status sudah sukses.');
            return;
        }

        $noWa = $this->formatNomorWa($log->nomor_wa);
        $pesan = $log->pesan;

        $waApi = \App\Models\WaApi::where('active', true)->first();

        if (!$waApi) {
            session()->flash('error', 'Tidak ada API WhatsApp yang aktif.');
            return;
        }

        try {
            \Log::info('[KIRIM ULANG] Kirim WA ke: ' . $noWa);

            $response = Http::withHeaders([
                'Authorization' => $waApi->token,
            ])->post($waApi->base_url, [
                'target' => $noWa,
                'message' => $pesan,
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                $log->update([
                    'status' => 'sukses',
                    'waktu_kirim' => now(),
                    'error_message' => null,
                ]);

                session()->flash('success', 'Pesan berhasil dikirim ulang.');
            } else {
                $log->update([
                    'status' => 'gagal',
                    'waktu_kirim' => now(),
                    'error_message' => $response->body(),
                ]);

                session()->flash('error', 'Gagal kirim ulang: ' . $response->body());
            }
        } catch (\Exception $e) {
            \Log::error("Gagal kirim ulang WA: " . $e->getMessage());

            $log->update([
                'status' => 'gagal',
                'waktu_kirim' => now(),
                'error_message' => $e->getMessage(),
            ]);

            session()->flash('error', 'Terjadi error: ' . $e->getMessage());
        }

        $this->isModalOpen = false;
    }

    private function formatNomorWa($nomor)
    {
        $nomor = preg_replace('/[^0-9]/', '', $nomor);
        if (str_starts_with($nomor, '0')) {
            return '62' . substr($nomor, 1);
        }
        return $nomor;
    }
}
