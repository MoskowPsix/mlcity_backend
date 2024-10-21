<?php

namespace App\MoonShine\Pages\HistorySeance;

use MoonShine\Fields\Field;
use MoonShine\Fields\ID;

trait HistorySeanceHelperTrait
{
    public function showOriginId()
    {
        return ID::make('ID оригинала', 'seance_id')
            ->changePreview(function ($value, Field $field) {
                if (is_null($value)) {
                    return '<p style="color: greenyellow">Новое<p>';
                } else if($field->getData()->on_delete) {
                    return '<p style="color: red">Удаление | ' . $value . ' <p>';
                }
                return '<p style="color: dodgerblue">Изменено | ' . $value . ' <p>';
            });
    }
}
