<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sight;

class SightType extends Model
{
    use HasFactory;

    protected $table = 'stypes';

    protected $fillable = [
        'name',
        'ico',
    ];

    public function sights(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Sight::class);
    }
}
