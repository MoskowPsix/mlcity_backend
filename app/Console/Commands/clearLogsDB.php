<?php

namespace App\Console\Commands;

use App\Models\LogApi;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class clearLogsDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean-log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command clearing table logs api.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        LogApi::where('created_at', '<', Carbon::now()->subMonth())->delete();
        return print('success');
    }
}
