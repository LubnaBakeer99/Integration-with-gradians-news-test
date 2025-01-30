<?php

namespace App\Console\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class ScheduleCommand extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\SyncGuardianNewsArticlesCommand::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('sync:guardian-news-articles')->hourly() ;
    }
     
    protected function commands()
    {
        return $this->load(__DIR__.'/Commands');
    }
}
