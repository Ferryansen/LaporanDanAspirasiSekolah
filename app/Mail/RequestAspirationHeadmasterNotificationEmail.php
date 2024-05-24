<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequestAspirationHeadmasterNotificationEmail extends Mailable
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
        return $this->view('emails.headmaster.aspirationRealizationNotification')
                    ->subject('Realisasi Aspirasi "' . $this->aspirationData['title'] . '" Butuh Approval')
                    ->with([
                        'receiverName' => $this->receiverName,
                        'mailData' => $this->aspirationData]
                    );
    }
}