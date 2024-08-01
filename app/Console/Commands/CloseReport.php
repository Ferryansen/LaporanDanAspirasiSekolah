<?php

namespace App\Console\Commands;

use App\Models\Report;
use Illuminate\Console\Command;
use App\Mail\CloseReportStudentNotificationEmail;
use App\Mail\CloseReportHeadmasterNotificationEmail;
use App\Mail\CloseReportStaffNotificationEmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
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
        $today = Carbon::today();

        $excludedStatuses = ['Closed', 'Completed', 'Rejected', 'Cancelled'];

        $reportsToClose = Report::where('processEstimationDate', $today)
                                ->whereNotIn('status', $excludedStatuses)
                                ->get();

        $headmasters = User::where('role', 'headmaster')->get();
        
        foreach ($reportsToClose as $report) {
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
        }
        

        $this->info('Reports have been successfully closed.');

        return Command::SUCCESS;
    }
}