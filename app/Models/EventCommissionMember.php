<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventCommissionMember extends Model
{
    protected $fillable = ['event_id', 'user_id', 'granted_by', 'email', 'token'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function claimFor(User $user): void
    {
        $email = mb_strtolower(trim((string) $user->email));
        if ($email === '') {
            return;
        }
        static::query()
            ->whereNull('user_id')
            ->where('email', $email)
            ->update(['user_id' => $user->id, 'updated_at' => now()]);
    }
}
