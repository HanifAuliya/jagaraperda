<?php

namespace App\Console\Commands;

use App\Models\Aspirasi;
use Illuminate\Console\Command;

class AspirasiAutoExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:aspirasi-auto-expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Aspirasi::query()
            ->whereNotIn('status', ['selesai', 'ditolak', 'kadaluwarsa'])
            ->whereNotNull('final_deadline_at')
            ->where('final_deadline_at', '<', now())
            ->update([
                'status'    => 'kadaluwarsa',
                'closed_at' => now(),
            ]);

        $this->info('Aspirasi kadaluwarsa diproses.');
    }
}
