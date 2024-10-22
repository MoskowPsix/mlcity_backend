<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Event;
use App\Models\EventType;
use App\Models\Sight;
use App\Models\SightType;
use App\Models\User;
use MoonShine\Components\Layout\Div;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Metrics\DonutChartMetric;
use MoonShine\Metrics\ValueMetric;
use MoonShine\Pages\Page;
use MoonShine\Components\MoonShineComponent;

class Dashboard extends Page
{
    /**
     * @return array<string, string>
     */
    public function breadcrumbs(): array
    {
        return [
            '#' => $this->title()
        ];
    }

    public function title(): string
    {
        return $this->title ?: 'Главная';
    }

    /**
     * @return list<MoonShineComponent>
     */
    public function components(): array
	{
//        dd(collect(EventType::query()->withCount('events')->get())->pluck('events_count', 'name')->all());
		return [
            Div::make([
                Grid::make([
                    Column::make([
                        ValueMetric::make('Событий')
                            ->value(Event::count()),
                    ])->columnSpan(4),
                    Column::make([
                            ValueMetric::make('Мест')
                                ->value(Sight::count()),
                    ])->columnSpan(4),
                    Column::make([
                        ValueMetric::make('Пользователей')
                            ->value(User::count()),
                    ])->columnSpan(4),
                ]),
            ]),
            Div::make([
                Grid::make([
                    Column::make([
                        DonutChartMetric::make('События')
                            ->values(collect(EventType::query()->withCount('events')->get())->pluck('events_count', 'name')->all()),
                    ])->columnSpan(6),
                    Column::make([
                        DonutChartMetric::make('Места')
                            ->values(collect(SightType::query()->withCount('sights')->get())->pluck('sights_count', 'name')->all()),
                    ])->columnSpan(6),
                ]),
            ])->customAttributes(['class' => 'mt-8']),
        ];
	}
}
