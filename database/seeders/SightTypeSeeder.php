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
        // $type1 = new SightType();
        // $type1->name = 'Архитектурные';
        // $type1->ico = '/storage/icons/architecture.svg';
        // $type1->save();

        // $type2 = new SightType();
        // $type2->name = 'Исторические';
        // $type2->ico = '/storage/icons/historic.svg';
        // $type2->save();

        // $type3 = new SightType();
        // $type3->name = 'Музеи';
        // $type3->ico = '/storage/icons/museum.svg';
        // $type3->save();

        // $type4 = new SightType();
        // $type4->name = 'Театры';
        // $type4->ico = '/storage/icons/theaters.svg';
        // $type4->save();

        // $type5 = new SightType();
        // $type5->name = 'Природные парки';
        // $type5->ico = '/storage/icons/natures.svg';
        // $type5->save();

        // $type6 = new SightType();
        // $type6->name = 'Святыни';
        // $type6->ico = '/storage/icons/religion.svg';
        // $type6->save();

        // $type7 = new SightType();
        // $type7->name = 'Смотровая площадка';
        // $type7->ico = '/storage/icons/lookout.svg';
        // $type7->save();

        // $type8 = new SightType();
        // $type8->name = 'Спортивные';
        // $type8->ico = '/storage/icons/ball.svg';
        // $type8->save();

        // $type9 = new SightType();
        // $type9->name = 'Зоопитомники';
        // $type9->ico = '/storage/icons/zoo.svg';
        // $type9->save();

        // $type10 = new SightType();
        // $type10->name = 'Индустриальные';
        // $type10->ico = '/storage/icons/factory.svg';
        // $type10->save();

        // $type11 = new SightType();
        // $type11->name = 'Развлекательный парк';
        // $type11->ico = '/storage/icons/entertainment.svg';
        // $type11->save();

        // $type12 = new SightType();
        // $type12->name = 'Гостевой маршрут';
        // $type12->ico = '/storage/icons/geo.svg';
        // $type12->save();

        //Типы с культуры

        $type1 = new SightType();
        $type1->name = 'Образы Росии';
        $type1->ico = '';
        $type1->cult_id = 28;
    
        $type2 = new SightType();
        $type2->name = 'Кино';
        $type2->ico = '';
        $type2->cult_id = 2;
    
        $type3 = new SightType();
        $type3->name = 'Архитектурные';
        $type3->ico = '';
        $type3->cult_id = 419;
    
        $type4 = new SightType();
        $type4->name = 'Литература';
        $type4->ico = ''; 
        $type4->cult_id = 12;
    
        $type5 = new SightType();
        $type5->name = 'Посетить';
        $type5->ico = '';
        $type5->cult_id = 546;
    
        $type6 = new SightType();
        $type6->name = 'Музеи';
        $type6->ico = '';
        $type6->cult_id = 1;
    
        $type7 = new SightType();
        $type7->name = 'Атлас';
        $type7->ico = ''; 
        $type7->cult_id = 22;
    
        $type8 = new SightType();
        $type8->name = 'Культурный стриминг';
        $type8->ico = ''; 
        $type8->cult_id = 568;
    
        $type9 = new SightType();
        $type9->name = 'Традиции';
        $type9->ico = '';
        $type9->cult_id = 561;

        $type10 = new SightType();
        $type10->name = 'Театры';
        $type10->ico = '';
        $type10->cult_id = 3;

        $type11 = new SightType();
        $type11->name = 'Образование';
        $type11->ico = '';
        $type11->cult_id = 4;

        $type12 = new SightType();
        $type12->name = 'Музыка';
        $type12->ico = '';
        $type12->cult_id = 6;
    }
}
