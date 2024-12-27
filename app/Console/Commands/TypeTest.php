<?php

namespace App\Console\Commands;

use App\Events\Notify\StartHoursEventNotify;
use App\Events\TestEvent;
use App\Models\Event;
use App\Models\Notify;
use App\Models\User;
use Illuminate\Console\Command;

class TypeTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'type';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Notify::create([
            'message' => 'Тестирование системы оповещений',
            'data' => json_encode(['message' => 'test'])
        ]);
//        event(new StartHoursEventNotify($event, $user));
//        $currentType = new \App\Contracts\Services\CurrentType\CurrentType('Встречи');
//        print_r($currentType->getType());
        return Command::SUCCESS;
    }
}
