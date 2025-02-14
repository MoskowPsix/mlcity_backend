<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailCode extends Model
{
    use HasFactory;

    protected $table = 'ecodes';
    protected $fillable = [
        'email_id',
        'code',
        'last',
    ];
}
