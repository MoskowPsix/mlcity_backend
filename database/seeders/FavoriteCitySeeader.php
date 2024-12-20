<?php

namespace Database\Seeders;

use App\Models\FavoriteCity;
use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FavoriteCitySeeader extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $favoriteCities = [
        "Москва", "Санкт-Петербург", "Нижний Новгород",
        "Екатеринбург", "Казань", "Калининград", "Краснодар","Новосибирск", "Сочи", "Красноярск", "Владивосток","Севастополь"];

        foreach($favoriteCities as $city) {
            $favoriteCity = new FavoriteCity();
            $favoriteCity->location_id = Location::where("name", $city)->get()->first()->id;
            $favoriteCity->save();
        }
    }
}
