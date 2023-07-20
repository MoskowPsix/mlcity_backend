<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class LogApi extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'method',
        'request_arg',
        'request_header',
        'user_id',
        'ip',
        'status_code',
        'response'
    ];

    public function logUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');

    }
}
