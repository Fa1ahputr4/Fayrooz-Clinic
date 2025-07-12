<div wire:poll.5s>
    <div class="bg-white p-6 rounded shadow mb-6 overflow-hidden relative">
        <div class="w-full whitespace-nowrap animate-marquee text-[#5e4a7e] font-semibold text-xl">
            <span>
                👋 Selamat Datang, {{ Auth::user()->name ?? Auth::user()->username }}! &nbsp;&nbsp;
            </span>
        </div>
        <p class="text-gray-700 mt-4">Ini adalah halaman dashboard utama kamu.</p>
    </div>

    <style>
        @keyframes marquee {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .animate-marquee {
            display: inline-block;
            animation: marquee 20s linear infinite;
        }
    </style>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Pasien -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-[#5e4a7e]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Pasien</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $stats['totalPasien'] }}</h3>
                </div>
                <div class="bg-[#5e4a7e] bg-opacity-10 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#5e4a7e]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <p class="mt-2 text-xs text-gray-500">
                <span class="{{ $stats['pasienChange'] >= 0 ? 'text-green-500' : 'text-red-500' }} font-medium">
                    {{ $stats['pasienChange'] >= 0 ? '↑' : '↓' }} {{ abs($stats['pasienChange']) }}%
                </span> dari bulan lalu
            </p>
        </div>

        <!-- Pendaftaran Hari Ini -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pendaftaran Hari Ini</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $stats['pendaftaranHariIni'] }}</h3>
                </div>
                <div class="bg-blue-500 bg-opacity-10 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <p class="mt-2 text-xs text-gray-500">{{ $stats['pendaftaranSelesai'] }} selesai,
                {{ $stats['pendaftaranPending'] }} pending</p>
        </div>

        <!-- Produk Terjual -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Produk Terjual Hari Ini</p>
                    <h3 class="text-2xl font-bold text-gray-800">
                        {{ $stats['obatKeluarHariIni'] + $stats['produkBcKeluarHariIni'] }}
                    </h3>
                </div>
                <div class="bg-green-500 bg-opacity-10 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            <p class="mt-2 text-xs text-gray-500">Obat: {{ $stats['obatKeluarHariIni'] }}, Produk Beautycare:
                {{ $stats['produkBcKeluarHariIni'] }}</p>
        </div>

        <!-- Rekam Medis -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Rekam Medis</p>
                    <h3 class="text-2xl font-bold text-gray-800">
                        {{ $totalRekmedUmum + $totalRekmedBeautycare }}
                    </h3>
                </div>
                <div class="bg-purple-500 bg-opacity-10 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <p class="mt-2 text-xs text-gray-500">
                {{ $totalRekmedUmum }} Umum, {{ $totalRekmedBeautycare }} Beautycare
            </p>
        </div>
    </div>

    <!-- Charts and Recent Data -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Grafik Kunjungan -->
        <div class="bg-white p-6 rounded-lg shadow lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">
                    Kunjungan Pasien -
                    <span class="text-[#5e4a7e]">{{ $filterOptions[$chartRange] }}</span>
                </h3>
                <select wire:model.live="chartRange"
                    class="text-sm border-gray-300 rounded focus:border-[#5e4a7e] focus:ring-[#5e4a7e]">
                    @foreach ($filterOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tambahkan keterangan jumlah pasien di sini -->
            <div class="flex gap-4 mb-4">
                <div class="flex items-center">
                    <span class="inline-block w-3 h-3 rounded-full bg-[#3b82f6] mr-2"></span>
                    <span class="text-sm font-medium">
                        Umum: <span id="umumCount">{{ array_sum($chartData['datasets'][0]['data']) }}</span>
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="inline-block w-3 h-3 rounded-full bg-[#ec4899] mr-2"></span>
                    <span class="text-sm font-medium">
                        Beautycare: <span
                            id="beautycareCount">{{ array_sum($chartData['datasets'][1]['data']) }}</span>
                    </span>
                </div>
                <div class="flex items-center ml-auto">
                    <span class="text-sm font-medium">
                        Total: <span
                            id="totalCount">{{ array_sum($chartData['datasets'][0]['data']) + array_sum($chartData['datasets'][1]['data']) }}</span>
                    </span>
                </div>
            </div>

            <div class="h-64">
                @if (array_sum($chartData['datasets'][0]['data']) + array_sum($chartData['datasets'][1]['data']) > 0)
                    <canvas id="visitsChart" wire:ignore></canvas>
                @else
                    <div class="flex items-center justify-center h-full text-gray-500">
                        Tidak ada data untuk ditampilkan
                    </div>
                @endif
            </div>
        </div>

        <!-- Pendaftaran Terbaru -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Pendaftaran Terbaru</h3>
            <div class="space-y-4">
                @forelse($recentData['pendaftaranTerbaru'] as $pendaftaran)
                    <div class="border-l-4 border-[#5e4a7e] pl-4 py-2">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium text-gray-800">{{ $pendaftaran->pasien->nama_lengkap }}</p>
                            </div>
                            <span class="text-xs bg-[#5e4a7e] bg-opacity-10 text-[#5e4a7e] px-2 py-1 rounded">
                                {{ $pendaftaran->created_at->format('H:i') }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            Status:
                            <span
                                class="{{ $pendaftaran->status == 'selesai' ? 'text-green-500' : 'text-yellow-500' }}">
                                {{ $pendaftaran->status }}
                            </span>
                        </p>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">Tidak ada pendaftaran terbaru</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Patients -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Pasien Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.
                            RM</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Terdaftar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jenis Kelamin</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($recentData['pasienTerbaru'] as $pasien)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 rounded-full bg-[#5e4a7e] bg-opacity-10 flex items-center justify-center">
                                        <span
                                            class="text-[#5e4a7e] font-medium">{{ substr($pasien->nama_lengkap, 0, 1) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $pasien->nama_lengkap }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $pasien->no_telp }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pasien->nomor_rm }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $pasien->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pasien->jenis_kelamin }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            let chartInstance;

            document.addEventListener('DOMContentLoaded', function() {
                loadDashboardChart();
            });

            document.addEventListener('livewire:navigated', function() {
                loadDashboardChart();
            });

            function loadDashboardChart() {
                const onDashboard = window.location.pathname.includes('dashboard');
                if (!onDashboard) return;

                const chartContainer = document.querySelector('.h-64');
                if (!chartContainer) return;

                const totalInitial =
                    @json(array_sum($chartData['datasets'][0]['data']) + array_sum($chartData['datasets'][1]['data']));

                if (totalInitial > 0) {
                    // Pastikan canvas ada
                    if (!chartContainer.querySelector('canvas')) {
                        chartContainer.innerHTML = '<canvas id="visitsChart" wire:ignore></canvas>';
                    }

                    initChart(@js($chartData));
                    updateCounters(@js($chartData));
                }

                Livewire.on('chartUpdated', ({
                    data
                }) => {
                    const totalData = data.datasets[0].data.reduce((a, b) => a + b, 0) +
                        data.datasets[1].data.reduce((a, b) => a + b, 0);

                    if (totalData > 0) {
                        if (!chartContainer.querySelector('canvas')) {
                            chartContainer.innerHTML = '<canvas id="visitsChart" wire:ignore></canvas>';
                        }

                        if (!chartInstance) {
                            initChart(data);
                        } else {
                            updateChart(data);
                        }

                        updateCounters(data);
                    } else {
                        chartContainer.innerHTML =
                            '<div class="flex items-center justify-center h-full text-gray-500">Tidak ada data untuk ditampilkan</div>';

                        if (chartInstance) {
                            chartInstance.destroy();
                            chartInstance = null;
                        }
                    }
                });
            }

            function initChart(data) {
                const ctx = document.getElementById('visitsChart')?.getContext('2d');
                if (!ctx) return;

                if (chartInstance) chartInstance.destroy(); // Hancurkan jika sudah ada

                chartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    precision: 0
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `${context.dataset.label}: ${context.raw}`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            function updateChart(data) {
                if (!chartInstance) {
                    initChart(data);
                    return;
                }

                chartInstance.data.labels = data.labels;
                chartInstance.data.datasets = data.datasets;
                chartInstance.update();
            }

            function updateCounters(data) {
                const umumTotal = data.datasets[0].data.reduce((a, b) => a + b, 0);
                const beautycareTotal = data.datasets[1].data.reduce((a, b) => a + b, 0);

                document.getElementById('umumCount').textContent = umumTotal;
                document.getElementById('beautycareCount').textContent = beautycareTotal;
                document.getElementById('totalCount').textContent = umumTotal + beautycareTotal;
            }
        </script>
    @endpush
</div>
{{-- <script>
    let chartInstance;

    function initChart(data) {
        const ctx = document.getElementById('visitsChart')?.getContext('2d');
        if (!ctx) return;

        if (chartInstance) chartInstance.destroy(); // Reset kalau sudah ada chart lama

        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw}`;
                            }
                        }
                    }
                }
            }
        });
    }

    function updateChart(data) {
        if (!chartInstance) {
            initChart(data);
            return;
        }

        chartInstance.data.labels = data.labels;
        chartInstance.data.datasets = data.datasets;
        chartInstance.update();
    }

    function updateCounters(data) {
        const umumTotal = data.datasets[0].data.reduce((a, b) => a + b, 0);
        const beautycareTotal = data.datasets[1].data.reduce((a, b) => a + b, 0);

        document.getElementById('umumCount').textContent = umumTotal;
        document.getElementById('beautycareCount').textContent = beautycareTotal;
        document.getElementById('totalCount').textContent = umumTotal + beautycareTotal;
    }

    function loadDashboardChart() {
        const onDashboard = window.location.pathname.includes('dashboard');
        if (!onDashboard) return;

        const chartContainer = document.querySelector('.h-64');
        if (!chartContainer) return;

        const chartData = @js($chartData);

        const totalInitial = chartData.datasets[0].data.reduce((a, b) => a + b, 0) +
            chartData.datasets[1].data.reduce((a, b) => a + b, 0);

        // Selalu buat canvas meskipun totalInitial == 0
        if (!chartContainer.querySelector('canvas')) {
            chartContainer.innerHTML = '<canvas id="visitsChart" wire:ignore></canvas>';
        }

        initChart(chartData);
        updateCounters(chartData);

        if (totalInitial === 0) {
            // Tambahkan pesan jika tidak ada data
            const notice = document.createElement('div');
            notice.className = "text-center text-gray-500 text-sm mt-2";
            notice.textContent = "Tidak ada data untuk ditampilkan";
            chartContainer.appendChild(notice);
        }
    }

    // DOM Ready
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(loadDashboardChart, 100);
    });

    // Jika pakai SPA/Livewire navigate
    document.addEventListener('livewire:navigated', function() {
        loadDashboardChart();
    });

    // Jika data diupdate secara dinamis
    Livewire.on('chartUpdated', ({
        data
    }) => {
        const chartContainer = document.querySelector('.h-64');
        if (!chartContainer) return;

        const totalData = data.datasets[0].data.reduce((a, b) => a + b, 0) +
            data.datasets[1].data.reduce((a, b) => a + b, 0);

        if (!chartContainer.querySelector('canvas')) {
            chartContainer.innerHTML = '<canvas id="visitsChart" wire:ignore></canvas>';
        }

        if (totalData > 0) {
            if (!chartInstance) {
                initChart(data);
            } else {
                updateChart(data);
            }

            updateCounters(data);
        } else {
            chartContainer.innerHTML =
                '<canvas id="visitsChart" wire:ignore></canvas><div class="text-center text-gray-500 text-sm mt-2">Tidak ada data untuk ditampilkan</div>';

            if (chartInstance) {
                chartInstance.destroy();
                chartInstance = null;
            }
        }
    });
    console.log("ChartData on load:", @js($chartData));
</script> --}}
