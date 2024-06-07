<?php

namespace App\Console\Commands;

use App\Mail\StartedConsultationStudentNotificationEmail;
use Illuminate\Console\Command;
use App\Models\ConsultationEvent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class UpdateStatusStartConsultation extends Command
{
    protected $signature = 'status:start';
    protected $description = 'Update status if start date has passed';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = Carbon::now();

        $records = ConsultationEvent::where('start', '<', $now)
                            ->whereNotIn('status', ['Selesai', 'Dibatalkan', 'Sedang dimulai'])
                            ->get();

        foreach ($records as $record) {
            $record->status = 'Sedang dimulai';
            $attendees = $record->attendees;
            $consultationData = [
                'title' => $record->title,
                'date' => $record->start,
                'consultant' => $record->consultBy->name,
                'is_online' => $record->is_online,
                'location' => $record->location
            ];

            foreach ($attendees as $attendee) {
                $currAttendee = User::findOrFail($attendee);

                Mail::to($currAttendee->email)->queue(new StartedConsultationStudentNotificationEmail($currAttendee->name, $consultationData));
            }

            $record->save();
        }

        $this->info('Status sesi konsultasi telah diperbarui');

        return 0;
    }
}
