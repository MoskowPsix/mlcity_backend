<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\EventType;

class EventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $type1 = new EventType();
        $type1->name = 'Детские';
        $type1->ico = '/storage/icons/children.svg';
        $type1->save();

        $type2 = new EventType();
        $type2->name = 'Культурно-развлекательные';
        $type2->ico = '/storage/icons/mask.svg';
        $type2->save();

        $type3 = new EventType();
        $type3->name = 'Торгово-выставочные';
        $type3->ico = '/storage/icons/exposition.svg';
        $type3->save();

        $type4 = new EventType();
        $type4->name = 'Образовательные';
        $type4->ico = '/storage/icons/student.svg';
        $type4->save();

        $type5 = new EventType();
        $type5->name = 'Спортивные';
        $type5->ico = '/storage/icons/ball.svg';
        $type5->save();

        $type6 = new EventType();
        $type6->name = 'Благотворительные';
        $type6->ico = '/storage/icons/hearts.svg';
        $type6->save();

        $type7 = new EventType();
        $type7->name = 'Общественные';
        $type7->ico = '/storage/icons/initiative.svg';
        $type7->save();

        $type8 = new EventType();
        $type8->name = 'Деловые';
        $type8->ico = '/storage/icons/business.svg';
        $type8->save();

        $type9 = new EventType();
        $type9->name = 'Киноафиша';
        $type9->ico = '/storage/icons/film.svg';
        $type9->save();
    }
}
