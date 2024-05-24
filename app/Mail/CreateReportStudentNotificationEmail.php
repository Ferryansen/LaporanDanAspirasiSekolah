<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CreateReportStudentNotificationEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    protected $receiverName, $reportData;


    public function __construct($receiverName, $reportData)
    {
        $this->receiverName = $receiverName;
        $this->reportData = $reportData;
    }

    public function build()
    {
        return $this->view('emails.student.createReportStudentNotification')
                    ->subject('Pengaduan Laporan "' . $this->reportData['title'] . '" Berhasil!')
                    ->with([
                        'receiverName' => $this->receiverName,
                        'reportData' => $this->reportData]
                    );
    }
}
