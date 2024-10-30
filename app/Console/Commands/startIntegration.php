<?php

namespace App\Console\Commands;

use App\Models\Sight;
use DateTime;
use Illuminate\Console\Command;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Illuminate\Support\Carbon;

class startIntegration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'integration {type?} {page?}';

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
        $limit = 1; // Задаём лимит записей на странице
        $url = 'https://www.culture.ru/api/';
//        $this->getMessage('Setting the settings start');
        if (($this->argument('type') == 'event') && $this->argument('page')) {
            // event задаём page и total если пришли аргументы
            $page_event = $this->argument('page') ?$this->argument('page') : 1 ;
//            $total_event = json_decode(file_get_contents($url . 'events?page='.$page_event.'&limit='.$limit, true))->pagination->total;
        } else if (($this->argument('type') == 'sight') && $this->argument('page')) {
            // sight задаём page и total если пришли аргументы
            $page_sight = $this->argument('page') ? $this->argument('page') : 1;
            $total_sight = json_decode(file_get_contents($url.'institutes?page='.$page_sight.'&limit='.$limit, true))->pagination->total;
        } else {
            // Если не пришли аргументы то устанавливаем стартовые значения для всех
            $page_event = 1;
            $page_sight = 1;
//            $total_event = json_decode(file_get_contents($url.'events?page='.$page_event.'&limit='.$limit, true))->pagination->total;
            $total_sight = json_decode(file_get_contents($url.'institutes?page='.$page_sight.'&limit='.$limit, true))->pagination->total;
        }
        $this->getMessage('Setting the settings end');

        if ($this->argument('type') == 'event') {
            // event
            $this->saveIntegration($page_event, $limit, 1, 'event');
        } else if($this->argument('type') == 'sight') {
            // sight
            $this->saveIntegration($page_sight, $limit, $total_sight, 'sight');
        } else {
            // all
            $this->saveIntegration($page_sight, $limit, $total_sight, 'sight');
            $this->saveIntegration($page_event, $limit, 1, 'event');
        }

        return 0;
    }

    public function saveIntegration($page, $limit, $total, $type): void
    {
        $this->getMessage('Download '.$type.' start');
        try
        {
            $type == 'sight' ? $bar = $this->output->createProgressBar($total) : null;
            $total_progress = $total / 100;
            while (!($type == 'sight') || $total >= 0) {
                $start_timer = microtime(true); // Время начала процесса
                $numberOfProcess = 100;
                for ($i = 0; $i < $numberOfProcess; $i++) { // Запускаем 10 команд по загрузке sight ['php', 'artisan', 'institutes_save', $page, $limit]
                    $process = new Process(['php', 'artisan', $type.'s_save', $page, $limit]);
                    $process->setTimeout(0);
//                    $process->getExitCode();
                    $process->start();
                    $processes[] = $process;
                    $type == 'sight' ? $bar->advance() : null;
                    $total = $total - 1;
                    $page = $page + 1;
                }
                while (count($processes)) {
                    foreach ($processes as $i => $runningProcess) {
                        $processes[$i]->getExitCode() ? throw new Exception("Events end") : null;
                        // этот процесс завершен, поэтому удаляем его
                        if (!$runningProcess->isRunning()) {
                            unset($processes[$i]);
                        }
                        // sleep(1); // Тормозит процесс в среднем на 10 секунд каждую итерацию
                    }
                }
                $end_time = (microtime(true) - $start_timer)  * ($total / 10); // Время до завершения процесса
                $progress = ($total_progress * 100 - $total) / $total_progress;
                $this->info("\n page: $page \n progress: $progress% \n approximate end time: " . $this->formatTime((int)$end_time));
                info("\n page: $page \n progress: $progress% \n approximate end time: " . $this->formatTime((int)$end_time));
//                $this->getMessage('page: '.$page .', progress: '. (int)$progress . '%, approximate end time: ' . $this->formatTime((int)$end_time) ."\n");
            }
            $type == 'sight' ? $bar->finish() : null;
//            $this->getMessage('Download '.$type.' end');
            return;
        } catch (Exception $e) {
            if ($e->getMessage() == 'Events end') {
                return;
            } else {
                Log::error('Ошибка при выполнении функции saveSightIntegration: страница=' . $page . ' лимит=' . $limit . ' тотал=' . $total . json_decode($e));
                $this->warn('Ошибка при выполнении функции saveSightIntegration: страница=' . $page . ' лимит=' . $limit . ' тотал=' . $total . $e->getMessage());
                sleep(3);
                $this->saveSightIntegration($page, $limit, $total);
            }
        }
    }

    public function getMessage($text) {
        try
        {
            file_get_contents('https://api.telegram.org/bot'.env('TELEGRAM_BOT_API').'/sendMessage?chat_id='.env('LOG_CHATS_DOWNLOAD_TELEGRAM').'&text='. $text);
            print($text . "\n");
        }  catch (Exception $e) {
            Log::error('Ошибка при отправке сообщения в телеграм: '.json_decode($e));
            sleep(5);
            $this->getMessage($text);
        }
    }
    public function formatTime($microseconds) {
        $seconds = floor($microseconds);
        return gmdate("H:i:s", floor($seconds));
    }
}
