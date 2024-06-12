<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CloseReportStaffNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;
    protected $receiverName, $reportName;

    
    public function __construct($receiverName, $reportName)
    {
        $this->receiverName = $receiverName;
        $this->reportName = $reportName;
    }

    public function build()
    {
        return $this->view('emails.staff.closeReportStaffNotification')
                    ->subject('Laporan "' . $this->reportName . '" Ditutup')
                    ->with([
                        'receiverName' => $this->receiverName,
                        'reportName' => $this->reportName
                    ]);
    }
}
