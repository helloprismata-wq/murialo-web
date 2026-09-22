<?php

namespace App\Console\Commands;

use App\Services\TesKandidatService;
use Illuminate\Console\Command;

class FinalizeExpiredTestsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tes:finalize-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finalisasi otomatis tes kandidat yang telah melewati batas waktu pengerjaan';

    /**
     * Execute the console command.
     */
    public function handle(TesKandidatService $tesService): int
    {
        $this->info('Memeriksa pengerjaan tes yang telah melewati batas waktu...');

        $finalized = $tesService->finalizeAllExpired();

        $this->info("Berhasil memfinalisasi {$finalized} pengerjaan tes yang kedaluwarsa.");

        return Command::SUCCESS;
    }
}
