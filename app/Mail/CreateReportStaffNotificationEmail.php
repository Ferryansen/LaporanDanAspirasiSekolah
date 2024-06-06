<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CreateReportStaffNotificationEmail extends Mailable
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

        return $this->view('emails.staff.createReportStaffNotification')
                    ->subject('Laporan Baru "' . $this->reportData['title'] . '" Diterima')
                    ->with([
                        'receiverName' => $this->receiverName,
                        'reportData' => $this->reportData,
                        'pathToImage' => $pathToImage
                    ]);
    }
}
