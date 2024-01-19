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
    //     $type1 = new EventType();
    //     $type1->name = 'Детские';
    //     $type1->ico = '/storage/icons/children.svg';
    //     $type1->save();

    //     $type2 = new EventType();
    //     $type2->name = 'Культурно-развлекательные';
    //     $type2->ico = '/storage/icons/mask.svg';
    //     $type2->save();

    //     $type3 = new EventType();
    //     $type3->name = 'Торгово-выставочные';
    //     $type3->ico = '/storage/icons/exposition.svg';
    //     $type3->save();

    //     $type4 = new EventType();
    //     $type4->name = 'Образовательные';
    //     $type4->ico = '/storage/icons/student.svg';
    //     $type4->save();

    //     $type5 = new EventType();
    //     $type5->name = 'Спортивные';
    //     $type5->ico = '/storage/icons/ball.svg';
    //     $type5->save();

    //     $type6 = new EventType();
    //     $type6->name = 'Благотворительные';
    //     $type6->ico = '/storage/icons/hearts.svg';
    //     $type6->save();

    //     $type7 = new EventType();
    //     $type7->name = 'Общественные';
    //     $type7->ico = '/storage/icons/initiative.svg';
    //     $type7->save();

    //     $type8 = new EventType();
    //     $type8->name = 'Деловые';
    //     $type8->ico = '/storage/icons/business.svg';
    //     $type8->save();

    //     $type9 = new EventType();
    //     $type9->name = 'Киноафиша';
    //     $type9->ico = '/storage/icons/film.svg';
    //     $type9->save();

    // Типы с  культуры

    $type1 = new EventType();
    $type1->name = 'Экскурсии';
    $type1->ico = '/storage/icons/geo.svg';
    $type1->cult_id = 116;

    $type2 = new EventType();
    $type2->name = 'Кино';
    $type2->ico = '/storage/icons/film.svg';
    $type2->cult_id = 66;

    $type3 = new EventType();
    $type3->name = 'Праздники';
    $type3->ico = '/storage/icons/events.svg'; // Уже нарисовать
    $type3->cult_id = 50;

    $type4 = new EventType();
    $type4->name = 'Спорт';
    $type4->ico = '/storage/icons/ball.svg'; // Уже нарисовать(Но мероприятий нет, так как это наш тип)

    $type5 = new EventType();
    $type5->name = 'Театр';
    $type5->ico = '/storage/icons/mask.svg';
    $type5->cult_id = 37;

    $type6 = new EventType();
    $type6->name = 'Обучение';
    $type6->ico = '/storage/icons/student.svg';
    $type6->cult_id = 36;

    $type7 = new EventType();
    $type7->name = 'Выступления';
    $type7->ico = '/storage/icons/theaters.svg'; // Уже нарисовать
    $type7->cult_id = 35;

    $type8 = new EventType();
    $type8->name = 'Выставки';
    $type8->ico = '/storage/icons/exhibitions.svg'; // Уже нарисовать
    $type8->cult_id = 34;

    $type9 = new EventType();
    $type9->name = '/storage/icons/initiative.svg'; // Нарисовать
    $type9->ico = 'Встречи';
    $type9->cult_id = 33;
    }
}
