<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EatTableItem extends Model
{
    use HasFactory;
    protected $fillable = [
        "eat_table_id",
        "ingredient",
        "quantity",
    ];
    public function eat_table(): BelongsTo
    {
        return $this->belongsTo(EatTable::class, 'eat_table_id');
    }
}
