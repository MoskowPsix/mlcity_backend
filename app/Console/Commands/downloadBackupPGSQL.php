<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class downloadBackupPGSQL extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dowload_backup_bd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download PostgresSQL Backup';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dir = '0000_00_00_00_00_00';
    
        $command = "cd ./database/backup/; ls";
  
        exec($command, $output, $returnVar);
        foreach ($output as $date) {
            $date > $dir ? $dir = $date : null;
        }

        $command = "cd ./database/backup/".$dir." ; ls";
        $output = [];
        exec($command, $output, $returnVar);
        print_r( $returnVar);


        $command = "PGPASSFILE='~/.pgpass'; 
        pg_restore --username=" . env('DB_USERNAME') ." --host=" . env('DB_HOST') . " --dbname=test -v < ./database/backup/".$dir."/backup-".$dir.".tar";

        exec($command, $output, $returnVar);
    }
}
