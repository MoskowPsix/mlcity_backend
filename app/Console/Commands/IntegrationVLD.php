<?php

namespace App\Console\Commands;

use App\Contracts\Services\IntegrationVld\IntegrationVldService;
use App\Jobs\ProcessIntegrationVld;
use Illuminate\Console\Command;

class IntegrationVLD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'integration:vld {type?}';

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
        $type = $this->argument('type');
//        $page = $this->argument('page');

        switch ($type) {
            case 'all':
                $this->integrationAll();
                break;
            case 'delete':
                $this->integrationDel();
                break;
        }
        return 0;
    }
    private function integrationAll()
    {
        $service = new IntegrationVldService();
        $response = $service->getFirstScroll();
        $total = $response->hits->total->value / 10;
        $scroll = $response->_scroll_id;
        $bar = $this->output->createProgressBar($total);
        ProcessIntegrationVld::dispatch();
        while ($total >= 0) {
            ProcessIntegrationVld::dispatch($scroll);
            $total = $total - 1;
            $bar->advance();
        }
        $bar->finish();
    }
}
