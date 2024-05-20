<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class FavoriteCity extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function city(){
        return $this->belongsTo(Location::class, "location_id", "id");
    }
}
