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

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dir_name = Carbon::now()->format('Y_m_d_h_m_s');
        $filename_tar = "backup-" . Carbon::now()->format('Y_m_d_h_m_s') . ".tar";
       
        $command = "
        mkdir ./database/backup/".$dir_name.";
        PGPASSFILE='~/.pgpass'; 
        sudo pg_dump --username=" . env('DB_USERNAME') ." --host=" . env('DB_HOST') . " --dbname=" . env('DB_DATABASE') . " -Ft --file=./database/backup/".$dir_name."/".$filename_tar." -v";
  
        exec($command, $output, $returnVar);

        $returnVar === 0 ? exec("find ./database/backup/* -type d -ctime +30 -exec rm -rf {} \;", $output, $returnVar) : $this->handle();
    }
}
