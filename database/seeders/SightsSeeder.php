<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Sight;

class SightsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $type1 = new Sight();
        $type1->name = 'Камень';
        $type1->sponsor = 'Природа';
        $type1->city = 'Ревда';
        $type1->address = 'Ленина 32';
        $type1->latitude = '54.7522';
        $type1->longitude = '67.7522';
        $type1->description = 'Описание камня.';
        $type1->price = '1500';
        $type1->materials = 'Материалы';
        $type1->vk_group_id = '2023-09-12';
        $type1->vk_post_id = '2023-09-13';
        $type1->user_id = '16';
        $type1->save();

        $type2 = new Sight();
        $type2->name = 'Ветка';
        $type2->sponsor = 'Шишка';
        $type2->city = 'Рефтинский';
        $type2->address = 'Комсомольская 19';
        $type2->latitude = '55.7522';
        $type2->longitude = '58.7522';
        $type2->description = 'Описание ветки.';
        $type2->price = '3000';
        $type2->materials = 'Материалы';
        $type2->vk_group_id = '2022-09-12';
        $type2->vk_post_id = '2022-10-13';
        $type2->user_id = '9';
        $type2->save();

        $type3 = new Sight();
        $type3->name = 'Дерево';
        $type3->sponsor = 'Белка';
        $type3->city = 'Заречный';
        $type3->address = 'Курчатова 1';
        $type3->latitude = '57.7522';
        $type3->longitude = '55.7522';
        $type3->description = 'Описание дерева.';
        $type3->price = '1000';
        $type3->materials = 'Материалы';
        $type3->vk_group_id = '2023-05-12';
        $type3->vk_post_id = '2023-10-13';
        $type3->user_id = '8';
        $type3->save();
    }
}
