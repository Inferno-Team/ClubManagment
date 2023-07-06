<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserEatTable extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'table_id',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function table(): BelongsTo
    {
        return $this->belongsTo(EatTable::class, 'table_id');
    }
}
