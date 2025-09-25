@section('page_title', 'JAGARAPERDA')
@section('title', 'Dashboard - Aspirasi Masuk')
<div>
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0"><i class="bi bi-speedometer2 me-2"></i> Dashboard</h4>
        <span class="text-muted small">Terakhir diperbarui: {{ now()->format('d M Y H:i') }}</span>
    </div>

    {{-- KPI Cards --}}
    {{-- KPI Cards --}}
    <div class="row g-3 mb-4">
        {{-- Total Aspirasi --}}
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">Total Aspirasi</div>
                            <div class="fs-3 fw-bold">{{ number_format($kpi['total_aspirasi']) }}</div>
                        </div>
                        <div class="kpi-ico bg-primary-subtle text-primary">
                            <i class="bi bi-inbox"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Selesai/Closed --}}
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">Selesai/Closed</div>
                            <div class="fs-3 fw-bold">{{ number_format($kpi['total_closed']) }}</div>
                        </div>
                        <div class="kpi-ico bg-success-subtle text-success">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tingkat Kepuasan (NSI) --}}
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">Tingkat Kepuasan (NSI)</div>
                            <div class="fs-3 fw-bold">
                                {{ $kpi['nsi'] === null ? '—' : $kpi['nsi'] . '%' }}
                            </div>
                        </div>
                        <div class="kpi-ico bg-warning-subtle text-warning">
                            <i class="bi bi-emoji-smile"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Jumlah Feedback --}}
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">Jumlah Feedback</div>
                            <div class="fs-3 fw-bold">{{ number_format($kpi['total_feedback']) }}</div>
                        </div>
                        <div class="kpi-ico bg-info-subtle text-info">
                            <i class="bi bi-chat-dots"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Charts --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 fw-semibold">Trend Aspirasi 12 Bulan</div>
                <div class="card-body"><canvas id="chartMonthly" height="130"></canvas></div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 fw-semibold">Aspirasi per Status</div>
                <div class="card-body"><canvas id="chartStatus" height="130"></canvas></div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 fw-semibold">Distribusi Feedback</div>
                <div class="card-body"><canvas id="chartFeedback" height="180"></canvas></div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 fw-semibold">Top 5 Raperda (Aspirasi)</div>
                <div class="card-body">
                    @if (count($topRaperda))
                        <ol class="mb-0">
                            @foreach ($topRaperda as $t)
                                <li class="mb-2 d-flex justify-content-between">
                                    <span class="me-3">{{ $t['judul'] }}</span>
                                    <span class="badge text-bg-light">{{ $t['total'] }}</span>
                                </li>
                            @endforeach
                        </ol>
                    @else
                        <div class="text-muted">Belum ada data.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>



    {{-- Chart.js CDN --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            (function() {
                const monthlyLabels = @json($monthly['labels']);
                const monthlyData = @json($monthly['data']);

                const statusLabels = @json(array_keys($byStatus));
                const statusData = @json(array_values($byStatus));

                const fbLabels = @json($feedback['labels']);
                const fbData = @json($feedback['data']);

                // Monthly (Line)
                new Chart(document.getElementById('chartMonthly'), {
                    type: 'line',
                    data: {
                        labels: monthlyLabels,
                        datasets: [{
                            label: 'Aspirasi',
                            data: monthlyData,
                            tension: 0.35,
                            fill: true,
                            backgroundColor: 'rgba(21,61,138,0.12)',
                            borderColor: 'rgba(21,61,138,1)',
                            pointRadius: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });

                // Status (Bar)
                new Chart(document.getElementById('chartStatus'), {
                    type: 'bar',
                    data: {
                        labels: statusLabels,
                        datasets: [{
                            label: 'Jumlah',
                            data: statusData,
                            backgroundColor: 'rgba(255, 159, 64, 0.6)',
                            borderColor: 'rgba(255, 159, 64, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });

                // Feedback (Doughnut)
                new Chart(document.getElementById('chartFeedback'), {
                    type: 'doughnut',
                    data: {
                        labels: fbLabels,
                        datasets: [{
                            data: fbData,
                            backgroundColor: [
                                'rgba(34,197,94,0.7)', // Puas
                                'rgba(234,179,8,0.7)', // Cukup
                                'rgba(239,68,68,0.7)' // Tidak
                            ],
                            borderColor: [
                                'rgba(34,197,94,1)',
                                'rgba(234,179,8,1)',
                                'rgba(239,68,68,1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            })();
        </script>
    @endpush
</div>
