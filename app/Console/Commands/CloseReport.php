<?php

namespace App\Console\Commands;

use App\Models\Report;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CloseReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'close:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For closing report after reaching end of process estimation date';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get today's date
        $today = Carbon::today();

        // Define statuses to exclude
        $excludedStatuses = ['Closed', 'Completed', 'Rejected', 'Cancelled'];

        // Find reports where processEstimationDate is equal to today and status is not in the excluded statuses
        $reportsToClose = Report::where('processEstimationDate', $today)
                                ->whereNotIn('status', $excludedStatuses)
                                ->get();

        foreach ($reportsToClose as $report) {
            $report->update(['status' => 'Closed']);
        }

        $this->info('Reports have been successfully closed.');

        return Command::SUCCESS;
    }
}
