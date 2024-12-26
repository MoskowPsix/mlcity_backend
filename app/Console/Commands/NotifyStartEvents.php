<?php

namespace App\Console\Commands;

use App\Jobs\ProccessCheckAndSendNotifyUsersStartEventfavorite;
use App\Models\Event;
use App\Models\Sight;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifyStartEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:start-events';

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
        $from = Carbon::now()->addDay(1)->format('Y-m-d H:i:s');
        $to = Carbon::now()->addDay(5)->addMinute(1)->format('Y-m-d H:i:s');
        $events_q = Event::whereBetween('date_start', [$from, $to]);
        $bar = $this->output->createProgressBar($events_q->count());
        $events_q->orderBy('id')->chunk(1000, function ($events) use($bar) {
            $events->each(function($event) use($bar) {
                ProccessCheckAndSendNotifyUsersStartEventfavorite::dispatch($event);
                $bar->advance();
            });
        });
    }
}
