<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateReportTesting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:testing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Just for testing';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $reports = [];

        for ($i = 1; $i <= 200; $i++) {
            $reports[] = [
                'reportNo' => 'RPT-' . $i,
                'user_id' => 3, 
                'category_id' => 1, 
                'name' => 'Report ' . $i,
                'description' => 'Description of Report ' . $i,
                'priority' => 1,
                'isUrgent' => false,
                'isChatOpened' => false,
                'processDate' => null,
                'processEstimationDate' => null,
                'processedBy' => null,
                'approvalBy' => null,
                'lastUpdatedBy' => null,
                'status' => 'Freshly submitted',
                'rejectReason' => null,
                'closedReason' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $chunks = array_chunk($reports, 50); 

        foreach ($chunks as $chunk) {
            DB::table('reports')->insert($chunk);
        }

        $this->info('Reports inserted successfully!');
    }
}
