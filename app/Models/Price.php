<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $table = 'price';
    protected $fillable = [
        'event_id',
        'sight_id',
        'cost_rub',
        'descriptions',
    ];
}