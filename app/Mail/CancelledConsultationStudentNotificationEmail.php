<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CancelledConsultationStudentNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;
    protected $receiverName, $consultationData;

    
    public function __construct($receiverName, $consultationData)
    {
        $this->receiverName = $receiverName;
        $this->consultationData = $consultationData;
    }

    public function build()
    {
        $pathToImage = public_path('SkolahKitaLogo.png');

        return $this->view('emails.student.consultationCancelledNotification')
                    ->subject('Konsultasi "' . $this->consultationData['title'] . '" Dibatalkan')
                    ->with([
                        'receiverName' => $this->receiverName,
                        'consultationData' => $this->consultationData,
                        'pathToImage' => $pathToImage
                    ]);
    }
}
