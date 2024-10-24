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
        // $schedule->command('inspire')->hourly();
//        $schedule->command('addView')->twiceDaily(1, 13);
        // $schedule->command('institutes_save')->weekly();
//        $schedule->command('clear-log')->weeklyOn(7, '3:00');
        $schedule->command('int', ['all'])->daily()->after(function ($day) {
            $this->call('duble');
            $this->call('integration:min-cult', ['all']);
        });
        $schedule->command('backup_db')->weeklyOn(6, '3:00');
        $schedule->command('telescope:prune --hours=48')->daily();
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
