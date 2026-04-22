<?php

namespace App\Console;

use App\Console\Commands\CheckVipExpiry;
use App\Console\Commands\RecalculateVipLevels;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Scheduling\Schedule;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(RecalculateVipLevels::class)->monthlyOn(1, '01:00');
        $schedule->command(CheckVipExpiry::class)->daily();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
    }
}
