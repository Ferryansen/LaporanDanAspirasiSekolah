<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CompleteReportHeadmasterNotificationEmail extends Mailable
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
        return $this->view('emails.headmaster.reportCompletionNotification')
                    ->subject('Proses Laporan "' . $this->reportData['title'] . '" Sudah Selesai')
                    ->with([
                        'receiverName' => $this->receiverName,
                        'mailData' => $this->reportData]
                    );
    }
}
