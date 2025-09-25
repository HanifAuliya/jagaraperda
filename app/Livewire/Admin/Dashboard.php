<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Aspirasi;
use App\Models\AspirasiFeedback;
use App\Models\Raperda;

class Dashboard extends Component
{
    /** @var array<string,int> */
    public array $byStatus = [];
    /** @var array{labels: string[], data: int[]} */
    public array $monthly = ['labels' => [], 'data' => []];
    /** @var array{labels: string[], data: int[]} */
    public array $feedback = ['labels' => ['Puas', 'Cukup', 'Tidak'], 'data' => []];
    /** @var array<int,array{judul:string,total:int}> */
    public array $topRaperda = [];
    public array $kpi = [
        'total_aspirasi' => 0,
        'total_closed'   => 0,
        'nsi'            => null, // Net Satisfaction Index (%)
        'total_feedback' => 0,
    ];
    public $recentFeedback;

    // Status yang dianggap selesai/closed -> sesuaikan dengan punya kamu
    private array $doneStatuses = ['ditanggapi', 'selesai', 'closed', 'done', 'finish'];

    public function render()
    {
        $this->loadData();
        return view('livewire.admin.dashboard')->layout('layouts.app');
    }

    private function loadData(): void
    {
        // 1) KPI & Status
        $this->kpi['total_aspirasi'] = (int) Aspirasi::count();

        $byStatus = Aspirasi::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        $this->byStatus = $byStatus;

        // Hitung closed berdasarkan daftar status "selesai"
        $closed = 0;
        foreach ($this->doneStatuses as $st) {
            $closed += $byStatus[$st] ?? 0;
        }
        $this->kpi['total_closed'] = $closed;

        // 2) Trend 12 bulan (berdasarkan created_at)
        $start = now()->copy()->startOfMonth()->subMonths(11);
        $raw = Aspirasi::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, COUNT(*) as total')
            ->where('created_at', '>=', $start)
            ->groupBy('ym')->orderBy('ym')->pluck('total', 'ym')->toArray();

        $labels = [];
        $data   = [];
        $cursor = $start->copy();
        for ($i = 0; $i < 12; $i++) {
            $ym = $cursor->format('Y-m');
            $labels[] = $cursor->isoFormat('MMM YY'); // ex: Sep 25
            $data[]   = (int)($raw[$ym] ?? 0);
            $cursor->addMonth();
        }
        $this->monthly = ['labels' => $labels, 'data' => $data];

        // 3) Feedback distribusi & NSI
        $dist = AspirasiFeedback::select('rating', DB::raw('COUNT(*) as total'))
            ->groupBy('rating')->pluck('total', 'rating')->toArray();

        $puas  = (int)($dist['puas']  ?? 0);
        $cukup = (int)($dist['cukup'] ?? 0);
        $tidak = (int)($dist['tidak'] ?? 0);
        $totalFb = $puas + $cukup + $tidak;
        $this->feedback['data'] = [$puas, $cukup, $tidak];
        $this->kpi['total_feedback'] = $totalFb;

        $this->kpi['nsi'] = $totalFb ? round((($puas - $tidak) / $totalFb) * 100, 1) : null;

        // 4) Top 5 Raperda by aspirasi
        $this->topRaperda = Aspirasi::select('raperda_id', DB::raw('COUNT(*) as total'))
            ->whereNotNull('raperda_id')
            ->groupBy('raperda_id')
            ->orderByDesc('total')
            ->with('raperda:id,judul') // pastikan relasi ada di model Aspirasi
            ->limit(5)
            ->get()
            ->map(fn($r) => [
                'judul' => optional($r->raperda)->judul ?? '—',
                'total' => (int) $r->total,
            ])->toArray();

        // 5) Feedback terbaru (10)
        $this->recentFeedback = AspirasiFeedback::with(['aspirasi:id,judul,created_at,status'])
            ->latest()->limit(10)->get();
    }
}
