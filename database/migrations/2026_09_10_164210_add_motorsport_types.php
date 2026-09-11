<?php

use App\Models\EventType;
use App\Models\SightType;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (!EventType::query()->where('name', 'Мотоспорт')->exists()) {
            EventType::create([
                'name' => 'Мотоспорт',
                'ico' => '/storage/icons/ball.svg',
                'order' => 10,
                'image_path' => '/storage/images_types/sport.jpg',
            ]);
        }

        if (!SightType::query()->where('name', 'Мотоспорт')->exists()) {
            SightType::create([
                'name' => 'Мотоспорт',
                'ico' => '/storage/icons/ball.svg',
                'order' => 10,
                'image_path' => '/storage/images_types/sport.jpg',
            ]);
        }
    }

    public function down(): void
    {
        EventType::query()->where('name', 'Мотоспорт')->delete();
        SightType::query()->where('name', 'Мотоспорт')->delete();
    }
};
