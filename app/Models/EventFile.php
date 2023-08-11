<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FileType;

use App\Models\Event;

class EventFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'link',
        'local'
    ];

    public function event(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function file_types(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(FileType::class, 'event_file_types','file_id','type_id');

    }
}
