<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Event;
use App\Models\Sight;
use App\Models\User;
//use App\Models\Comment;

class Comment extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'text',
        'user_id',
        'event_id',
        'sight_id',
        'comment_id'
        
    ];

    protected $dates = ['deleted_at'];

    public function event(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class, 'comment_id')->with('user', 'comments');
    }

    public function sight(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Sight::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
