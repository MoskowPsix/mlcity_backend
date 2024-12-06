<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // По расписанию
        $schedule->command('integration:min-cult all')->everySixHours();
        $schedule->command('telescope:prune --hours=48')->daily();

        // Запуск по вызову из админки
        $schedule->command('integration:vld all')->daily()->skip(function () { return true; });
        $schedule->command('integration:del')->daily()->skip(function () { return true; });
        $schedule->command('display:upd')->daily()->skip(function () { return true; });
        $schedule->command('search:index')->daily()->skip(function () { return true; });
        $schedule->command('double-type:del')->daily()->skip(function () { return true; });
        $schedule->command('backup_db')->daily()->skip(function () { return true; });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
