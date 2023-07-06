<?php

namespace App\Models;

use Carbon\Carbon;
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
    public  function user_subscriptions()
    {
        return $this->hasMany(UserSubscription::class, 'subscription_id');
    }
    public function getCustomers()
    {
        return $this->user_subscriptions->map(function ($item) {
            return (object)[
                "id" => $item->id,
                "customer" => $item->customer->formatUser(),
                "start_at" => $item->start_at,
                "end_at" => $item->end_at,
                "price" => $item->price,
                "is_valid" => $item->is_valid,
            ];
        });
    }
    public function getThisYearCustomersSubscriptions()
    {
        $filtered = $this->user_subscriptions->filter(function ($val) {
            $valYear = Carbon::parse($val->start_at)->format('Y');
            $extaptedYear = Carbon::now()->format('Y');
            return $valYear == $extaptedYear;
            // return true;
        })->values();

        $records = $filtered->groupBy(function ($val) {
            return Carbon::parse($val->start_at)->format('M');
        });
        $revenueData = [
            "Jan" => 0,
            "Feb" => 0,
            "Mar" => 0,
            "Apr" => 0,
            "May" => 0,
            "Jun" => 0,
            "Jul" => 0,
            "Aug" => 0,
            "Sep" => 0,
            "Oct" => 0,
            "Nov" => 0,
            "Dec" => 0
        ];
        foreach ($records as $month => $subscriptions) {
            $rev = 0;
            foreach ($subscriptions as $subscription) {
                $rev = $rev + 1;
            }
            $revenueData[$month] = $rev;
        }
        return [
            'keys' => array_keys($revenueData),
            'values' => array_values($revenueData)
        ];
    }
    public function getThisMonthSubscriptionCount()
    {
        $filtered = $this->user_subscriptions->filter(function ($val) {
            $valYear = Carbon::parse($val->start_at)->format('Y');
            $valMonth = Carbon::parse($val->start_at)->format('m');
            $extaptedYear = Carbon::now()->format('Y');
            $extaptedMonth = Carbon::now()->format('m');
            return $valYear == $extaptedYear && $valMonth == $extaptedMonth;
            // return true;
        })->values();
        return count($filtered);
    }

    public function formatForAdmin()
    {
        return (object)[
            'id' => $this->id,
            'price' => $this->price,
            'user_subscriptions_count' => count($this->user_subscriptions),
            "club" => $this->club->format(),
        ];
    }
    public function format()
    {
        return (object)[
            'id' => $this->id,
            'subscription' => $this->subscription->format(),
            'price' => $this->price,
            'user_subscriptions' => $this->getThisYearCustomersSubscriptions(),
            'customers' => $this->getCustomers(),
            'user_subscriptions_count' => count($this->user_subscriptions),
            'this_month_subs' => $this->getThisMonthSubscriptionCount()
        ];
    }
}
