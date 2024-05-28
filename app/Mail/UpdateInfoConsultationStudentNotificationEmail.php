<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UpdateInfoConsultationStudentNotificationEmail extends Mailable
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
        return $this->view('emails.student.consultationUpdateInformationNotification')
                    ->subject('Info Baru untuk Konsultasi "' . $this->consultationData['title'] . '"')
                    ->with([
                        'receiverName' => $this->receiverName,
                        'consultationData' => $this->consultationData]
                    );
    }
}