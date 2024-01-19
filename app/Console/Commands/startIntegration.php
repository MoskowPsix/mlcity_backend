<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

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
        $limit = 10; // Задаём лимит записей на странице

        if (($this->argument('type') == 'event') && $this->argument('page')) {
            // event задаём page и total если пришли аргументы
            $page_event = $this->argument('page');
            $total_event = json_decode(file_get_contents('https://www.culture.ru/api/events?page='.$page_event.'&limit='.$limit.'&statuses=published', true))->pagination->total;
        } else if (($this->argument('type') == 'sight') && $this->argument('page')) {
            // sight задаём page и total если пришли аргументы
            $page_sight = $this->argument('page');
            $total_sight = json_decode(file_get_contents('https://www.culture.ru/api/institutes?page='.$page_sight.'&limit='.$limit . '&statuses=published', true))->pagination->total;
        } else {
            // Если не пришли аргументы то устанавливаем стартовые значения для всех
            $page_event = 1;
            $page_sight = 1;
            $total_event = json_decode(file_get_contents('https://www.culture.ru/api/events?page='.$page_event.'&limit='.$limit.'&statuses=published', true))->pagination->total;
            $total_sight = json_decode(file_get_contents('https://www.culture.ru/api/institutes?page='.$page_sight.'&limit='.$limit . '&statuses=published', true))->pagination->total;
        }
        $limit = 10;

        if ($this->argument('type') == 'event') {
            // event
            $this->saveEventIntegration($page_event, $limit, $total_event);
        } else if($this->argument('type') == 'sight') {
            // sight
            $this->saveSightIntegration($page_sight, $limit, $total_sight);
        } else {
            // all
            $this->saveSightIntegration($page_sight, $limit, $total_sight);
            $this->saveEventIntegration($page_event, $limit, $total_event);
        }

        return 0;
    }

    public function saveEventIntegration($page, $limit, $total) {
        try
        {
            while ($total >= 0) {

            }
            return 0;
        } catch (Exception $e) {
            Log::error('Ошибка при выполнении функции saveEventIntegration: '.json_decode($e));
            sleep(3);
            $this->saveEventIntegration($page, $limit, $total);
        }   
    }
    public function saveSightIntegration($page, $limit, $total) {
        try
        {
            print('start');
            while ($total >= 0) {
                $i = 10;
                while($i >= 0) { // Запускаем 10 команд по загрузке sight
                    $process = new Process(['php', 'artisan', 'institutes_save', $page, $limit]);
                    $process->start();
                    $i = $i-1;
                    print($i);
                }
                $total = $total - 1;
                $page = $page + 1;
            }
            return 0;
        } catch (Exception $e) {
            Log::error('Ошибка при выполнении функции saveSightIntegration: страница='. $page .' лимит='. $limit .' тотал='. $total .json_decode($e));
            sleep(3);
            $this->saveSightIntegration($page, $limit, $total);
        }   
    }
}
