<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FileType;

use App\Models\Sight;

class SightFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'link',
        'local'
    ];

    public function sight(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Sight::class);
    }
    
    public function file_types(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(FileType::class, 'sight_file_types','file_id','type_id');

    }
}
