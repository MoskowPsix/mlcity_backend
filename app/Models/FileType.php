<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EventFile;
use App\Models\SightFile;

class FileType extends Model
{
    use HasFactory;

    protected $table = 'file_types';

    protected $fillable = [
        'name'
    ];

    public function events(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(EventFile::class, 'event_file_types','type_id','file_id');
    }

    public function sights(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(SightFile::class, 'sight_file_types','type_id','file_id');
    }
}
