<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSubscription extends Model
{
    use HasFactory;
    protected $appends = ['is_valid'];
    protected $fillable = [
        'customer_id',
        'subscription_id',
        'start_at',
        'price',
        'end_at',
        "approved"
    ];

    public function isValid(): Attribute
    {
        return new Attribute(
            get: fn () => Carbon::now()->between($this->start_at, $this->end_at) && $this->approved == 'yes',
        );
    }
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    public function sub(): BelongsTo
    {
        return $this->belongsTo(ClubSubScription::class, 'subscription_id');
    }
    public function approve()
    {
        $this->approved = "yes";
        $this->update();
    }
    public function format()
    {
        return [
            "id" => $this->id,
            "id" => $this->id,
            'customer' => $this->customer->formatUser(),
            'sub' => $this->sub->format(),
            'is_valid' => $this->is_valid,
            "price" => $this->price,
            'end_at' => $this->end_at,
            'start_at' => $this->start_at,
            'approved' => $this->approved
        ];
    }
    /* public function formatOnly()
    {
        return [
            "id" => $this->id,
            'customer' => $this->customer->formatUser(),
            'is_valid' => $this->is_valid,
            "price" => $this->price,
            'end_at' => $this->end_at,
            'start_at' => $this->start_at,
            'approved' => $this->approved
        ];
    } */
    public function formatOnly()
    {
        return [
            "id" => $this->id,
            'customer' => $this->customer->formatUser(),
            'is_valid' => $this->is_valid,
            "price" => $this->price,
            'end_at' => $this->end_at,
            'start_at' => $this->start_at,
            "price" => $this->price,
            'approved' => $this->approved
        ];
    }
    /*  public function formatOnly()
    {
        return [
            "id" => $this->id,
            'customer' => $this->customer->formatUser(),
            'is_valid' => $this->is_valid,
            'end_at' => $this->end_at,
            'start_at' => $this->start_at,
            "price" => $this->price,
        ];
    } */
    public function formatWithoutSubscriotionRelation()
    {
        return (object)[
            "id" => $this->id,
            "customer"  => $this->customer,
            "start_at"  => $this->start_at,
            "end_at"  => $this->end_at,
            "price"  => $this->price,
            "is_valid"  => $this->is_valid,
            'approved' => $this->approved,
            "sub"  => $this->sub->format(),
        ];
    }
}
