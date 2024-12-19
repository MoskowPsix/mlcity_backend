<?php

namespace App\Console\Commands;

use App\Jobs\ProccessCheckAndSendNotifyUsersStartEventfavorite;
use App\Models\Event;
use App\Models\Sight;
use Illuminate\Console\Command;

class NotifyStartEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notyfy:start-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notification users started events';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bar = $this->output->createProgressBar(Sight::query()->count());
        Event::query()->orderBy('id')->chunk(1000, function ($events) use($bar) {
            $events->each(function($event) use($bar) {
                ProccessCheckAndSendNotifyUsersStartEventfavorite::dispatch($event);
                $bar->advance();
            });
        });

        }
}
