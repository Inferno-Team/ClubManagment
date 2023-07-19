<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClubTrainer extends Model
{
    use HasFactory;
    protected $fillable = [
        'club_id',
        'trainer_id',
    ];
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'club_id');
    }
    public function trainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }
}
