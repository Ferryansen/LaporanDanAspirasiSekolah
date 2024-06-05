<?php

namespace App\Console\Commands;

use App\Models\ConsultationEvent;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateDailyConsultation extends Command
{
    protected $signature = 'daily:consultation';
    protected $description = 'For scheduling daily consultation';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $consultationTimes = [
            '08:00' => 'Pertemuan Harian Pagi',
            '12:00' => 'Pertemuan Harian Siang',
        ];
        $startDate = Carbon::now()->next(Carbon::MONDAY);

        foreach ($consultationTimes as $time => $title) {
            for ($i = 0; $i < 5; $i++) {
                $startDateTime = Carbon::parse($startDate)->addDays($i)->format('Y-m-d') . ' ' . $time . ':00';
                $endDateTime = Carbon::parse($startDateTime)->addHour(3);
            
                $credentials = [
                    'title' => $title,
                    'description' => 'Ada yang mau kamu tanyakan langsung ke sekolah? Yuk, langsung daftar konsultasinya!',
                    'start' => $startDateTime,
                    'end' => $endDateTime,
                    'attendeeLimit' => 15,
                    'status' => '',
                    'attendees' => [],
                    'is_online' => true,
                    'is_private' => false,
                    'is_confirmed' => false,
                ];
                ConsultationEvent::create($credentials);
            }
        }

        $this->info('Consultations scheduled successfully.');
    }
}