<?php

namespace Database\Seeders\test;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Sight;
use App\Models\User;

class TestSightsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sight1 = new Sight();
        $sight1->name = 'Денисовский сельский клуб';
        $sight1->sponsor = 'МБУ Гороховецкого района «Районный центр культуры»';
        $sight1->address = 'Владимирская обл., Гороховецкий р-н., п. Пролетарский, ул. Кооперативная, д. 1а';
        $sight1->latitude = '56.10356300000000';
        $sight1->longitude = '42.33936900000000';
        $sight1->description = 'Денисовский сельский клуб является преемником клуба Денисовского завода № 9, который прекратил существование в конце 90-х годов прошлого века. Клуб был демонтирован, а на его месте сейчас построен продуктовый магазин. С того времени клуб сменил несколько адресов. В 2003 году переведен в помещение бывшего детского сада Денисовского завода № 9.  Сегодня на первом этаже расположен танцевальный зал, на втором этаже находится досуговая комната ветеранов и комната для игры в настольный теннис. Работа клуба основана на организации досуга и массового отдыха населения поселка Пролетарского  и близлежащих 14 деревень. Дом культуры оснащен техническим и музыкальным оборудованием. В учреждении работают 7 клубных формирований на любой вкус, возраст участников – от 5 до 75 лет. Проводятся концертные, тематические, просветительные программы. Большое внимание уделяется занятиям спортом. Здесь каждый может найти занятие по душе, в удобное время и в дружной компании сверстников.';
        $sight1->user_id = User::all()[0]->id;
        $sight1->work_time = 'пн: выходной, вт: выходной, ср: выходной, чт: 11:00 - 18:30, пт: 19:30 - 22:00, cб: 19:30 - 22:00, вс: 12:00 - 18:30 (в четверг и воскресенье обед с 15:00 до 15:30)';
        $sight1->location_id = Location::all()[0]->id;
        $sight1->save();

        $sight2 = new Sight();
        $sight2->name = 'Денисовский клуб';
        $sight2->sponsor = 'МБУ Гороховецкого района «Районный центр культуры»';
        $sight2->address = 'Владимирская обл., Гороховецкий р-н., п. Пролетарский, ул. Кооперативная, д. 1а';
        $sight2->latitude = '56.10356300000000';
        $sight2->longitude = '42.33936900000000';
        $sight2->description = 'Денисовский сельский клуб является преемником клуба Денисовского завода № 9, который прекратил существование в конце 90-х годов прошлого века. Клуб был демонтирован, а на его месте сейчас построен продуктовый магазин. С того времени клуб сменил несколько адресов. В 2003 году переведен в помещение бывшего детского сада Денисовского завода № 9.  Сегодня на первом этаже расположен танцевальный зал, на втором этаже находится досуговая комната ветеранов и комната для игры в настольный теннис. Работа клуба основана на организации досуга и массового отдыха населения поселка Пролетарского  и близлежащих 14 деревень. Дом культуры оснащен техническим и музыкальным оборудованием. В учреждении работают 7 клубных формирований на любой вкус, возраст участников – от 5 до 75 лет. Проводятся концертные, тематические, просветительные программы. Большое внимание уделяется занятиям спортом. Здесь каждый может найти занятие по душе, в удобное время и в дружной компании сверстников.';
        $sight2->user_id = User::all()[0]->id;
        $sight2->work_time = 'пн: выходной, вт: выходной, ср: выходной, чт: 11:00 - 18:30, пт: 19:30 - 22:00, cб: 19:30 - 22:00, вс: 12:00 - 18:30 (в четверг и воскресенье обед с 15:00 до 15:30)';
        $sight2->location_id = Location::all()[0]->id;
        $sight2->save();

        $sight3 = new Sight();
        $sight3->name = 'Верхоисетский клуб';
        $sight3->sponsor = 'МБУ Гороховецкого района «Районный центр культуры»';
        $sight3->address = 'Владимирская обл., Гороховецкий р-н., п. Пролетарский, ул. Кооперативная, д. 1а';
        $sight3->latitude = '56.10356300000000';
        $sight3->longitude = '42.33936900000000';
        $sight3->description = 'Денисовский сельский клуб является преемником клуба Денисовского завода № 9, который прекратил существование в конце 90-х годов прошлого века. Клуб был демонтирован, а на его месте сейчас построен продуктовый магазин. С того времени клуб сменил несколько адресов. В 2003 году переведен в помещение бывшего детского сада Денисовского завода № 9.  Сегодня на первом этаже расположен танцевальный зал, на втором этаже находится досуговая комната ветеранов и комната для игры в настольный теннис. Работа клуба основана на организации досуга и массового отдыха населения поселка Пролетарского  и близлежащих 14 деревень. Дом культуры оснащен техническим и музыкальным оборудованием. В учреждении работают 7 клубных формирований на любой вкус, возраст участников – от 5 до 75 лет. Проводятся концертные, тематические, просветительные программы. Большое внимание уделяется занятиям спортом. Здесь каждый может найти занятие по душе, в удобное время и в дружной компании сверстников.';
        $sight3->user_id = User::all()[0]->id;
        $sight3->work_time = 'пн: выходной, вт: выходной, ср: выходной, чт: 11:00 - 18:30, пт: 19:30 - 22:00, cб: 19:30 - 22:00, вс: 12:00 - 18:30 (в четверг и воскресенье обед с 15:00 до 15:30)';
        $sight3->location_id = Location::all()[0]->id;
        $sight3->save();

        $sight4 = new Sight();
        $sight4->name = 'Клуб Лайм';
        $sight4->sponsor = 'МБУ Гороховецкого района «Районный центр культуры»';
        $sight4->address = 'Владимирская обл., Гороховецкий р-н., п. Пролетарский, ул. Кооперативная, д. 1а';
        $sight4->latitude = '56.10356300000000';
        $sight4->longitude = '42.33936900000000';
        $sight4->description = 'Денисовский сельский клуб является преемником клуба Денисовского завода № 9, который прекратил существование в конце 90-х годов прошлого века. Клуб был демонтирован, а на его месте сейчас построен продуктовый магазин. С того времени клуб сменил несколько адресов. В 2003 году переведен в помещение бывшего детского сада Денисовского завода № 9.  Сегодня на первом этаже расположен танцевальный зал, на втором этаже находится досуговая комната ветеранов и комната для игры в настольный теннис. Работа клуба основана на организации досуга и массового отдыха населения поселка Пролетарского  и близлежащих 14 деревень. Дом культуры оснащен техническим и музыкальным оборудованием. В учреждении работают 7 клубных формирований на любой вкус, возраст участников – от 5 до 75 лет. Проводятся концертные, тематические, просветительные программы. Большое внимание уделяется занятиям спортом. Здесь каждый может найти занятие по душе, в удобное время и в дружной компании сверстников.';
        $sight4->user_id = User::all()[0]->id;
        $sight4->work_time = 'пн: выходной, вт: выходной, ср: выходной, чт: 11:00 - 18:30, пт: 19:30 - 22:00, cб: 19:30 - 22:00, вс: 12:00 - 18:30 (в четверг и воскресенье обед с 15:00 до 15:30)';
        $sight4->location_id = Location::all()[0]->id;
        $sight4->save();
    }
}
