<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Club extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'location',
        'lat',
        'lng',
        'manager_id'
    ];
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
    public function subs(): HasMany
    {
        return $this->hasMany(ClubSubScription::class, 'club_id');
    }
    public function format()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'location' => $this->location,
            'pos' => [$this->lat, $this->lng],
            'manager' => $this->manager->formatUser(),
            'subs' => $this->subs->map->format(),
        ];
    }
}
