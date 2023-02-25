<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SightType extends Model
{
    use HasFactory;

    protected $table = 'sight_type';

    protected $fillable = [
        'name',
        'ico',
    ];
}
