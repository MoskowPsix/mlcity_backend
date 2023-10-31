<?php

namespace App\Models;

use App\Models\PhoneCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Phone extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'phones';
    protected $fillable = [
        'user_id',
        'number',
        'verification',
    ];

    public function pcode(): \Illuminate\Database\Eloquent\Relations\HasMany 
    {
        return $this->hasMany(PhoneCode::class);
    }
}
