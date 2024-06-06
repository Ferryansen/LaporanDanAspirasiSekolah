<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CompleteAspirationHeadmasterNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;
    protected $receiverName, $aspirationData;

    
    public function __construct($receiverName, $aspirationData)
    {
        $this->receiverName = $receiverName;
        $this->aspirationData = $aspirationData;
    }

    public function build()
    {
        $pathToImage = public_path('SkolahKitaLogo.png');

        return $this->view('emails.headmaster.aspirationRealizationNotification')
                    ->subject('Realisasi Aspirasi "' . $this->aspirationData['title'] . '" Sudah Selesai')
                    ->with([
                        'receiverName' => $this->receiverName,
                        'mailData' => $this->aspirationData,
                        'pathToImage' => $pathToImage
                    ]);
    }
}
