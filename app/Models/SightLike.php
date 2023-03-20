<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Sight;

class SightLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'vk_count',
        'local_count',
        'sight_id'
    ];

    public function sight(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Sight::class);
    }

}
