<?php

namespace App\Console\Commands;

use App\Jobs\DeleteReservationJob;
use App\Models\Reservation;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Symfony\Component\Console\Command\Command as CommandAlias;

class DeleteOldReservationsCommand extends Command
{
    protected $signature = 'delete:old-reservations';
    protected $description = 'commands.old_reservations.description';

    public function handle(): int
    {
        $this->info(__('commands.old_reservations.starting'));

        $oneYearAgo = Carbon::now()->subYear();

        $reservations = Reservation::whereDate('check_out', '<=', $oneYearAgo)->get();

        $this->info(__('commands.old_reservations.found', ['count' => $reservations->count()]));

        foreach ($reservations as $reservation) {
            DeleteReservationJob::dispatch($reservation);
        }

        $this->info(__('commands.old_reservations.completed'));

        return CommandAlias::SUCCESS;
    }
}
