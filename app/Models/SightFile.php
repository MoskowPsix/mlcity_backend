<?php

namespace App\Models;

use Elastic\Elasticsearch\Client;
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
//    Если нужны файлы в elastic
//    public static function boot(): void
//    {
//        parent::boot();
//        self::saved(function (Model $model) {
//            if(config('elasticsearch.enabled')) {
//
//                $event = Sight::with('files')->find($model->sight_id)->toArray();
//                resolve(Client::class)->index([
//                    'index' => 'sights',
//                    'type' => '_doc',
//                    'id' => $model->sight_id,
//                    'body' => $event,
//                ]);
//            }
//        });
//    }

    public function sight(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Sight::class);
    }

    public function file_types(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(FileType::class, 'sight_file_types','file_id','type_id');

    }
}
