<?php

namespace App\Console\Commands;

use App\Contracts\Services\IntegrationMinCult\IntegrationMinCult;
use App\Jobs\ProcessIntegrationMinCult;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;


# 21.35
#
class minCultIntegration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'integration:min-cult {type?} {offset?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private array $error_types = [];

    private int $limit = 10;

    private int $offset = 1;

    private int $numberOfProcess = 100;

    private ProgressBar $bar;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
//        dd(env('ELASTICSEARCH_ENABLED', false) ? 'y' : 'n');
        if ($this->argument('type') == 'all') {
            $this->startInt();
        } else {
            print('argument not found');
        }
        return Command::SUCCESS;
    }

    private function startInt(): void
    {
        $total = (new IntegrationMinCult())->getTotal();
        $this->bar = $this->output->createProgressBar($total);
        while ($total >= 1) {
            ProcessIntegrationMinCult::dispatch($this->offset, $this->limit);
            $this->offset = $this->offset + $this->limit;
            $this->bar->advance($this->limit);
            $total= $total + $this->limit;
        }
        $this->bar->finish();
    }
}
