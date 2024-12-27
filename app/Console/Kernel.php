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
        $schedule->command('telescope:prune --hours=48')->everySixHours()->description('Очистка записей telescope');
        $schedule->command('notify:start-events')->daily()->skip(function () { return true; })->description('Оповещение о начале мероприятия в избранном');;

        // Запуск по вызову из админки
        $schedule->command('type')->daily()->skip(function () { return true; })->description('Прверка оповещения всех пользователей');
        $schedule->command('inоtegration:min-cult all')->daily()->skip(function () { return true; })->description('Интеграция с сервисом мин-культ');;
        $schedule->command('integration:vld all')->daily()->skip(function () { return true; })->description('Интеграция с сервисом Влада');
//        $schedule->command('integration:del')->daily()->skip(function () { return true; })->description('Удалить все записи полученные через интеграцию'); // Слишком опасная команда, запуск её должен происходить из консоли
        $schedule->command('display:upd')->daily(); //->description('Обновить список городов для пользователей');
        $schedule->command('search:index')->daily()->skip(function () { return true; })->description('Проиндекчировать записи для elasticsearch');
        $schedule->command('double-type:del')->daily()->skip(function () { return true; })->description('Убрать дублирующиеся типы у сообществ и удалить сообщества без мероприятий');
        $schedule->command('backup_db')->daily()->skip(function () { return true; })->description('Создать резервную копию бд');
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
