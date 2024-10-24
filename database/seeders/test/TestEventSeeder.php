<?php

namespace Database\Seeders\test;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Event;
use App\models\User;

class TestEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user_id = User::first()->id;
        $type1 = new Event();
        $type1->name = 'Встреча';
        $type1->sponsor = 'Спонсор 1';
        $type1->materials = 'Материалы';
        $type1->date_start = '2023-09-12';
        $type1->date_end = '2023-09-13';
        $type1->user_id = $user_id;
        $type1->save();

        $type2 = new Event();
        $type2->name = 'Прогулка';
        $type2->sponsor = 'Какой-то Спонсор';
        $type2->description = 'Описание какое-то';
        $type2->materials = 'Материалы';
        $type2->date_start = '2022-09-12';
        $type2->date_end = '2022-10-13';
        $type2->user_id = $user_id;
        $type2->save();

        $type3 = new Event();
        $type3->name = 'Мероприятие';
        $type3->sponsor = 'Большой Спонсор';
        $type3->description = 'Описание мероприятия';
        $type3->materials = 'Материалы';
        $type3->date_start = '2023-05-12';
        $type3->date_end = '2023-10-13';
        $type3->user_id = $user_id;
        $type3->save();
    }
}
