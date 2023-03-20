<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSubscription extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'subscription_id',
        'start_at',
        'end_at',
    ];
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    public function sub(): BelongsTo
    {
        return $this->belongsTo(ClubSubScription::class, 'subscription_id');
    }
    public function format()
    {
        return [
            'customer' => $this->customer->formatUser(),
            'sub' => $this->sub->format(),
            'is_valid' => ($this->end_at > $this->start_at) ,
            'end_at' => $this->end_at,
            'start_at' => $this->start_at
        ];
    }
}
