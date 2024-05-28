<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ConsultationEvent;
use Carbon\Carbon;

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
            $record->save();
        }

        $this->info('Status sesi konsultasi telah diperbarui');

        return 0;
    }
}
