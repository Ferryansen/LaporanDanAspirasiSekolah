<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InteractionReportStudentStaffNotificationEmail extends Mailable
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
        $pathToImage = public_path('SkolahKitaLogo.png');

        return $this->view('emails.reportInteractionNotification')
                    ->subject('Ada Respon Terkait Laporan "' . $this->reportData['title'] . '" nih!')
                    ->with([
                        'receiverName' => $this->receiverName,
                        'reportData' => $this->reportData,
                        'pathToImage' => $pathToImage
                    ]);
    }
}
