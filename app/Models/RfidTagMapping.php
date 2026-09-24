<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RfidTagMapping extends Model
{
    protected $fillable = [
        'moto_tag_number',
        'sensor_value',
        'normalized_sensor_value',
        'source',
        'notes',
    ];

    public function setSensorValueAttribute(mixed $value): void
    {
        $sensorValue = trim((string) $value);

        $this->attributes['sensor_value'] = $sensorValue;
        $this->attributes['normalized_sensor_value'] = self::normalizeSensorValue($sensorValue);
    }

    public function setMotoTagNumberAttribute(mixed $value): void
    {
        $this->attributes['moto_tag_number'] = trim((string) $value);
    }

    public static function normalizeSensorValue(mixed $value): string
    {
        return mb_strtoupper(preg_replace('/[\s:\-_]+/', '', trim((string) $value)) ?? '');
    }
}
