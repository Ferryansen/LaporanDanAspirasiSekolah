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

        // Generate 200 reports
        for ($i = 1; $i <= 200; $i++) {
            $reports[] = [
                'reportNo' => 'RPT-' . $i,
                'user_id' => 3, // Fixed user_id as 3
                'category_id' => 1, // Assuming category IDs exist from 1 to 10
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
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Chunk the reports array into smaller arrays to insert in batches
        $chunks = array_chunk($reports, 50); // Insert in batches of 50 reports

        foreach ($chunks as $chunk) {
            DB::table('reports')->insert($chunk);
        }

        $this->info('Reports inserted successfully!');
    }
}
