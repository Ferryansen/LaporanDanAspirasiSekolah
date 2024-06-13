<?php

namespace App\Console\Commands;

use App\Mail\CloseReportHeadmasterNotificationEmail;
use App\Mail\CloseReportStaffNotificationEmail;
use App\Mail\CloseReportStudentNotificationEmail;
use App\Models\Report;
use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

        $headmasters = User::where('role', 'headmaster')->get();

        foreach ($reportsToClose as $report) {
            try {
                DB::beginTransaction();
                $report->update(['status' => 'Closed']);
                $student = $report->user;
                $staff = $report->processExecutor;
                
                Mail::to($student->email)->queue(new CloseReportStudentNotificationEmail($student->name, $report->name));
                Mail::to($staff->email)->queue(new CloseReportStaffNotificationEmail($staff->name, $report->name));
                
                $reportData = [
                        'title' => $report->name,
                        'reportNo' => $report->reportNo,
                        'relatedStaff' => $staff->name
                ];

                foreach ($headmasters as $headmaster) {
                    Mail::to($headmaster->email)->queue(new CloseReportHeadmasterNotificationEmail($headmaster->name, $reportData));
                }

                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
        
                // Log the error
                Log::error('Error rejecting report: ' . $e->getMessage());
        
                $this->info('Report failed to be closed');
                return Command::FAILURE;
            }   
        }

        $this->info('Reports have been successfully closed.');

        return Command::SUCCESS;
    }
}
