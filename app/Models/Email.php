<?php

namespace App\Models;

use App\Models\EmailCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Email extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'emails';
    protected $fillable = [
        'user_id',
        'email',
        'verification',
    ];

    public function ecode(): \Illuminate\Database\Eloquent\Relations\HasMany 
    {
        return $this->hasMany(EmailCode::class);
    }
}
