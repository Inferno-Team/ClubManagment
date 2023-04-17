<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionType extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    protected $appends = ['number_of_clubs', 'number_of_subs'];

    public function clubs()
    {
        return $this->belongsToMany(Club::class, 'club_subscriptions', 'subscription_id');
    }
    public function customer_subscriptions()
    {
        return $this->belongsToMany(UserSubscription::class, 'club_subscriptions', 'subscription_id', 'id', null, 'subscription_id');
    }
    public function numberOfClubs(): Attribute
    {
        return new Attribute(
            get: fn ($value) => count($this->clubs),
        );
    }
    public function numberOfSubs(): Attribute
    {
        return new Attribute(
            get: fn ($value) => count($this->customer_subscriptions),
        );
    }
}
