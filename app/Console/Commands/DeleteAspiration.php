<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Aspiration;
use Carbon\Carbon;

class DeleteAspiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:aspiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ini buat hapus aspirasi yang sudah lama (2 tahun)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $twoYearsAgo = Carbon::now()->subYears(2);

        // Delete aspirations that are older than 2 years
        $deletedCount = Aspiration::where('created_at', '<', $twoYearsAgo)->delete();

        // Provide feedback to the user
        $this->info("Deleted {$deletedCount} old aspirations.");

        return Command::SUCCESS;
    }
}
