<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class backupPSQL extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup_db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Weekly PostgresSQL Backup';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filename = "backup-" . Carbon::now()->format('Y-m-d') . ".gz";
  
        // $command = "pg_dump --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . "  | gzip > " . storage_path() . "/app/backup/" . $filename;
  
        $command = "pg_dump -U " . env('DB_USERNAME') ." -W" . env('DB_PASSWORD') . " -h " . env('DB_HOST') . " -d " . env('DB_DATABASE') . " -F directory -j 4 | gzip > /app/backup/" . $filename;
        $returnVar = NULL;
        $output  = NULL;
  
        exec($command, $output, $returnVar);
    }
}
