<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApprovalReportStaffNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $receiverName, $reportData;

    
    public function __construct($receiverName, $reportData)
    {
        $this->receiverName = $receiverName;
        $this->reportData = $reportData;
    }

    public function build()
    {
        return $this->view('emails.staff.reportApprovalNotification')
                    ->subject('Approval Proses Laporan "' . $this->reportData['title'] . '" Berhasil!')
                    ->with([
                        'receiverName' => $this->receiverName,
                        'mailData' => $this->reportData]
                    );
    }
}