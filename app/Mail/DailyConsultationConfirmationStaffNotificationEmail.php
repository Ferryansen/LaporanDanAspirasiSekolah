<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyConsultationConfirmationStaffNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;
    protected $receiverName;

    
    public function __construct($receiverName)
    {
        $this->receiverName = $receiverName;
    }

    public function build()
    {
        $currentDate = date('Y-m-d');
        $nextWeekDate = date('Y-m-d', strtotime($currentDate . ' +7 days'));
        $currentMonth = date('m', strtotime($currentDate));
        $nextWeekMonth = date('m', strtotime($nextWeekDate));

        if ($nextWeekMonth != $currentMonth) {
            $nextWeekNumber = 1;
            $nextWeekEnding = "Bulan Depan";
        } else {
            $nextWeekNumber = ceil(date('j', strtotime($nextWeekDate)) / 7);
            $nextWeekEnding = "Bulan Ini";
        }

        return $this->view('emails.staff.consultationDailyConfirmationNotification')
                    ->subject('Segera Konfirmasi Sesi Konsultasimu di Minggu Ke-' . $nextWeekNumber . ' ' . $nextWeekEnding)
                    ->with([
                        'receiverName' => $this->receiverName]
                    );
    }
}
