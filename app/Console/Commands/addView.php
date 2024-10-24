<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\Sight;
use App\Models\User;
use App\Models\View;

class addView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addView';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'add count view';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $events = Event::all();
        $sights = Sight::all();
        $users = User::all();

        foreach ($events as $event) {
            foreach($users as $user) {
                View::create([
                    'user_id' =>   $user->id,
                    'event_id' =>  $event->id,
                    'time_view' => 4,
                ]);
            }
        }
        foreach ($sights as $sight) {
            foreach($users as $user) {
                View::create([
                    'user_id' =>   $user->id,
                    'sight_id' =>  $sight->id,
                    'time_view' => 4,
                ]);
            }
        }
        return print_r('SUCCESS');
    }
}
