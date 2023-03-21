<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\SightType;

class SightTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $type1 = new SightType();
        $type1->name = 'Архитектурные';
        $type1->ico = '/storage/icons/architecture.svg';
        $type1->save();

        $type2 = new SightType();
        $type2->name = 'Исторические';
        $type2->ico = '/storage/icons/historic.svg';
        $type2->save();

        $type3 = new SightType();
        $type3->name = 'Музеи';
        $type3->ico = '/storage/icons/museum.svg';
        $type3->save();

        $type4 = new SightType();
        $type4->name = 'Театры';
        $type4->ico = '/storage/icons/theaters.svg';
        $type4->save();

        $type5 = new SightType();
        $type5->name = 'Природные парки';
        $type5->ico = '/storage/icons/natures.svg';
        $type5->save();

        $type6 = new SightType();
        $type6->name = 'Святыни';
        $type6->ico = '/storage/icons/religion.svg';
        $type6->save();

        $type7 = new SightType();
        $type7->name = 'Смотровая площадка';
        $type7->ico = '/storage/icons/lookout.svg';
        $type7->save();

        $type8 = new SightType();
        $type8->name = 'Спортивные';
        $type8->ico = '/storage/icons/ball.svg';
        $type8->save();

        $type9 = new SightType();
        $type9->name = 'Зоопитомники';
        $type9->ico = '/storage/icons/zoo.svg';
        $type9->save();

        $type10 = new SightType();
        $type10->name = 'Индустриальные';
        $type10->ico = '/storage/icons/factory.svg';
        $type10->save();

        $type11 = new SightType();
        $type11->name = 'Развлекательный парк';
        $type11->ico = '/storage/icons/entertainment.svg';
        $type11->save();

        $type12 = new SightType();
        $type12->name = 'Гостевой маршрут';
        $type12->ico = '/storage/icons/geo.svg';
        $type12->save();
    }
}