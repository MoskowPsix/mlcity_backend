<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Status;

class StatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status1 = new Status();
        $status1->name = 'Опубликовано';
        $status1->save();

        $status2 = new Status();
        $status2->name = 'Отказ';
        $status2->save();

        $status = new Status();
        $status->name = "Новое";
        $status->save();

        $status = new Status();
        $status->name = "На редактировании";
        $status->save();

        $status3 = new Status();
        $status3->name = 'Черновик';
        $status3->save();

        $status5 = new Status();
        $status5->name = 'В архиве';
        $status5->save();
    }
}
