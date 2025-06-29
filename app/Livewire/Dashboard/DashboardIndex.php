<?php

namespace App\Livewire\Dashboard;

use App\Models\ResepPasien;
use App\Models\ResepProdukBc;
use Livewire\Component;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\RekmedUmum;
use App\Models\RekmedBeautycare;
use Carbon\Carbon;

class DashboardIndex extends Component
{

    public $chartRange = 'today'; // Default set ke hari ini

    public $filterOptions = [
        'today' => 'Hari Ini',
        '7' => '7 Hari',
        '30' => '30 Hari',
        '365' => '1 Tahun'
    ];

    public function render()
    {
        return view('livewire.dashboard.dashboard-index', [
            'stats' => $this->getDashboardStats(),
            'recentData' => $this->getRecentData(),
            'chartData' => $this->prepareChartData(),
            'filterOptions' => $this->filterOptions,
            'totalRekmedUmum' => RekmedUmum::count(),
            'totalRekmedBeautycare' => RekmedBeautycare::count()
        ])->extends('layouts.app');
    }

    protected function prepareChartData()
    {
        $days = $this->chartRange === 'today' ? 1 : (int)$this->chartRange;
        $labels = [];
        $umumData = [];
        $beautycareData = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format($this->chartRange === 'today' ? 'd M Y' : 'd M');

            $umumData[] = Pendaftaran::whereDate('created_at', $date)
                ->where('layanan_id', 1)
                ->count();

            $beautycareData[] = Pendaftaran::whereDate('created_at', $date)
                ->where('layanan_id', 2)
                ->count();
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Umum',
                    'data' => $umumData,
                    'backgroundColor' => '#3b82f6',
                    'borderColor' => '#3b82f6'
                ],
                [
                    'label' => 'Beautycare',
                    'data' => $beautycareData,
                    'backgroundColor' => '#ec4899',
                    'borderColor' => '#ec4899'
                ]
            ]
        ];
    }


    protected function getDashboardStats()
    {
        $today = Carbon::now('Asia/Jakarta')->startOfDay();

        return [
            'totalPasien' => Pasien::count(),
            'pasienChange' => $this->calculatePasienChange(),
            'pendaftaranHariIni' => Pendaftaran::whereDate('created_at', $today)->count(),
            'pendaftaranSelesai' => Pendaftaran::whereDate('created_at', $today)
                ->where('status', 'selesai')->count(),
            'pendaftaranPending' => Pendaftaran::whereDate('created_at', $today)
                ->where('status', 'menunggu')->count(),
            'obatKeluarHariIni' => ResepPasien::whereDate('created_at', $today)->where('status', 'dikonfirmasi')
                ->sum('jumlah'),
            'produkBcKeluarHariIni' => ResepProdukBc::whereDate('created_at', $today)->where('status', 'dikonfirmasi')
                ->sum('jumlah'),

        ];
    }

    protected function getRecentData()
    {
        return [
            'pendaftaranTerbaru' => Pendaftaran::with(['pasien'])
                ->orderByDesc('created_at')
                ->take(5)
                ->get(),
            'pasienTerbaru' => Pasien::orderByDesc('created_at')
                ->take(5)
                ->get()
        ];
    }

    protected function calculatePasienChange()
    {
        $currentMonth = Pasien::whereMonth('created_at', now()->month)->count();
        $lastMonth = Pasien::whereMonth('created_at', now()->subMonth()->month)->count();

        if ($lastMonth == 0) return 0;

        return round(($currentMonth - $lastMonth) / $lastMonth * 100);
    }

    public function updatedChartRange($value)
    {
        // Validasi input
        $validRanges = ['today', '7', '30', '365'];
        $this->chartRange = in_array($value, $validRanges) ? $value : 'today';

        // Kirim event dengan data baru
        $this->dispatch('chartUpdated', data: $this->prepareChartData());
    }
}
