<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAgreement extends Model
{
    use HasFactory;

    protected $table = "user_agreements";

    protected $fillable = [
        "title",
        "file_path"
    ];
}
