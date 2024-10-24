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
    protected $casts = [
      'url' => 'encrypted',
      'request_arg' => 'encrypted',
      'request_header' => 'encrypted',
      'response' => 'encrypted'
    ];
    // protected static function booted() {
    //     static::retrieved(function($logs) {
    //         $logs->url = decrypt($logs->url);
    //         $logs->request_arg = decrypt($logs->request_arg);
    //         $logs->request_header = decrypt($logs->request_header);
    //         $logs->response = decrypt($logs->response);

    //       return $logs;
    //     });
    //   }

    public function logUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');

    }
}
