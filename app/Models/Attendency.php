<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendency extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "club_sub"
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function club_sub(): BelongsTo
    {
        return $this->belongsTo(ClubSubScription::class, 'club_sub');
    }
}
