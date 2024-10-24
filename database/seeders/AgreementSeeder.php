<?php

namespace Database\Seeders;

use App\Models\UserAgreement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgreementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $agreement1 =  new UserAgreement();
        $agreement1->title = "Согласие на создание мероприятия";
        $agreement1->save();

        $agreement2 = new UserAgreement();
        $agreement2->title = "Согласие на создание достопримечательности";
        $agreement2->save();
    }
}
