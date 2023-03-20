<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClubSubScription extends Model
{
    use HasFactory;
    protected $table = 'club_subscriptions';
    protected $fillable = [
        'club_id',
        'subscription_id',
        'price'
    ];

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'club_id');
    }
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(SubscriptionType::class, 'subscription_id');
    }
    public function format()
    {
        return [
            'id' => $this->id,
            'club' => $this->club,
            'subscription' => $this->subscription,
            'price' => $this->price,
        ];
    }
}
