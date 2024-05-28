<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ConsultationEvent;
use Carbon\Carbon;

class UpdateStatusEndConsultation extends Command
{
    protected $signature = 'status:end';
    protected $description = 'Update status if end date has passed';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = Carbon::now();

        $records = ConsultationEvent::where('end', '<', $now)
                            ->whereNotIn('status', ['Selesai', 'Dibatalkan'])
                            ->get();

        foreach ($records as $record) {
            $record->status = 'Selesai';
            $record->save();
        }

        $this->info('Status sesi konsultasi telah diperbarui');

        return 0;
    }
}
