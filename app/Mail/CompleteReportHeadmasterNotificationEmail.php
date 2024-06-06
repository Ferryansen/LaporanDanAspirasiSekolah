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
    protected $receiverName, $reportData;

    
    public function __construct($receiverName, $reportData)
    {
        $this->receiverName = $receiverName;
        $this->reportData = $reportData;
    }

    public function build()
    {
        $pathToImage = public_path('SkolahKitaLogo.png');

        return $this->view('emails.headmaster.reportCompletionNotification')
                    ->subject('Tindak Lanjut Laporan "' . $this->reportData['title'] . '" Sudah Selesai')
                    ->with([
                        'receiverName' => $this->receiverName,
                        'reportData' => $this->reportData,
                        'pathToImage' => $pathToImage
                    ]);
    }
}
