<?php

namespace App\Console\Commands;

use App\Contracts\Services\CurrentType\CurrentType;
use App\Contracts\Services\IntegrationMinCult\IntegrationMinCult;
use App\Jobs\ProcessIntegrationMinCult;
use App\Models\Event;
use App\Models\EventType;
use App\Models\FileType;
use App\Models\Location;
use App\Models\Place;
use App\Models\Sight;
use App\Models\Status;
use App\Models\Timezone;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Process\Process;


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

//    private function startCommands(): void
//    {
//        $processes = [];
//        for ($i = 0; $i < $this->numberOfProcess; $i++) { // Запускаем команды по загрузке sight ['php', 'artisan', 'institutes_save', $page, $limit]
//            ProcessIntegrationMinCult::dispatch($this->offset, $this->limit);
////            $process = new Process(['php', 'artisan', 'integration:min-cult', $this->argument('type'), $this->offset]);
////            $process->setTimeout(0);
////            $process->disableOutput();
////            $process->start();
////            $processes[] = $process;
//            $this->offset = $this->offset + $this->limit;
//        }
////        while (count($processes)) {
////            foreach ($processes as $i => $runningProcess) {
////                // этот процесс завершен, поэтому удаляем его
////                if (!$runningProcess->isRunning()) {
////                    unset($processes[$i]);
////                }
////            }
////        }
//    }
}
