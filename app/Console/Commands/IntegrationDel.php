<?php

namespace App\Console\Commands;

use App\Models\Event;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class IntegrationDel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'integration:del';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $events_q = Event::whereNotNull('source_id');
        $bar = $this->output->createProgressBar($events_q->count());
        $events_q->chunk(1000, function ($events) use($bar) {
            $events->each(function($event) use($bar) {
                $bar->advance();
//                DB::beginTransaction();
//                try {
                    if($event->organization()->exists()) {
                        if($event->organization()->first()->sight()->exists()) {
                            $event->organization()->first()->sight()->delete();
                        }
                        $event->organization()->delete();
                    }
                    $event->delete();
//                    DB::commit();
//                } catch(Exception $e) {
//                    dd($e);
//                    DB::rollBack();
//                }
            });
        });
        $bar->finish();
        return 0;
    }
}
