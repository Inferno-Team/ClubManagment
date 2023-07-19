<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EatTable extends Model
{
    use HasFactory;
    protected $appends = ['gym'];
    protected $fillable = [
        'name',
        'trainer_id',
    ];
    public function items(): HasMany
    {
        return $this->hasMany(EatTableItem::class, 'eat_table_id');
    }
    public function customers_relation(): HasMany
    {
        return $this->hasMany(UserEatTable::class, 'table_id');
    }
    public function trainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }
    public function gym(): Attribute
    {
        return new Attribute(
            get: function () {
                $trainer = $this->trainer()->with('trainer_club.club')->first();
                return $trainer->trainer_club->club;
            }
        );
    }
    public function format()
    {
        return (object)[
            "id" => $this->id,
            "name" => $this->name,
            "items_count" => count($this->items),
            'customer_count' => count($this->customers_relation)
        ];
    }
    public function formatForCustomers()
    {
        $gym = $this->gym;
        return (object)[
            "id" => $this->id,
            "name" => $this->name,
            "items_count" => count($this->items),
            'customer_count' => count($this->customers_relation),
            'gym' =>  $gym->name
        ];
    }
}
