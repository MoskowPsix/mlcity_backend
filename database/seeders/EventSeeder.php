<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Event;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $type1 = new Event();
        // $type1->name = 'Встреча';
        // $type1->sponsor = 'Спонсор 1';
        // $type1->city = 'Ревда';
        // $type1->address = 'Ленина 32';
        // $type1->latitude = '54.7522';
        // $type1->longitude = '67.7522';
        // $type1->description = 'Описание';
        // $type1->price = '1500';
        // $type1->materials = 'Материалы';
        // $type1->date_start = '2023-09-12';
        // $type1->date_end = '2023-09-13';
        // $type1->user_id = '1';
        // $type1->save();

        $type2 = new Event();
        $type2->name = 'Прогулка';
        $type2->sponsor = 'Какой-то Спонсор';
        $type2->city = 'Рефтинский';
        $type2->address = 'Комсомольская 19';
        $type2->latitude = '55.7522';
        $type2->longitude = '58.7522';
        $type2->description = 'Описание какое-то';
        $type2->price = '3000';
        $type2->materials = 'Материалы';
        $type2->date_start = '2022-09-12';
        $type2->date_end = '2022-10-13';
        $type2->user_id = '1';
        $type2->save();

        // $type3 = new Event();
        // $type3->name = 'Мероприятие';
        // $type3->sponsor = 'Большой Спонсор';
        // $type3->city = 'Заречный';
        // $type3->address = 'Курчатова 1';
        // $type3->latitude = '57.7522';
        // $type3->longitude = '55.7522';
        // $type3->description = 'Описание мероприятия';
        // $type3->price = '1000';
        // $type3->materials = 'Материалы';
        // $type3->date_start = '2023-05-12';
        // $type3->date_end = '2023-10-13';
        // $type3->user_id = '1';
        // $type3->save();
    }
}
