<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\returnSelf;

class Club extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'location',
        'lat',
        'lng',
        'image',
        'manager_id'
    ];
    // protected $appends = ['number_of_subs', 'number_of_last_month_subs', 'number_of_customer_sub', 'revenue'];



    public function image(): Attribute
    {
        return new Attribute(
            get: function () {
                $orgin = '4.jpg';
                if (!empty($this->getRawOriginal('image')))
                    $orgin = $this->getRawOriginal('image');
                $http = request()->getSchemeAndHttpHost();
                $path = '/images/' . $orgin;
                return $http . $path;
            }
        );
    }



    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
    public function subs(): HasMany
    {
        return $this->hasMany(ClubSubScription::class, 'club_id');
    }
    public function customer_sub()
    {
        return $this->belongsToMany(UserSubscription::class, 'club_subscriptions', 'club_id', 'id', null, 'subscription_id')
            ->with('customer', 'sub.subscription');
    }
    public function numberOfCustomerSub(): Attribute
    {
        return new Attribute(
            get: fn () => count($this->customer_sub),
        );
    }
    public function last_month_subs(): BelongsToMany
    {
        return $this->belongsToMany(
            UserSubscription::class,
            'club_subscriptions',
            'club_id',
            'id',
            null,
            'subscription_id'
        )
            ->whereMonth(
                'start_at',
                '=',
                Carbon::now()->month
            )->whereYear("start_at", "=", Carbon::now()->year);
    }
    public function revenue(): Attribute
    {
        $filtered = $this->customer_sub->sortBy('start_at')->filter(function ($val) {
            $valYear = Carbon::parse($val->start_at)->format('Y');
            $extaptedYear = Carbon::now()->format('Y');
            return $valYear == $extaptedYear;
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
                $rev = $rev + $subscription->price;
            }
            $revenueData[$month] = $rev;
        }
        return new Attribute(
            get: fn () => [
                'keys' => array_keys($revenueData),
                'values' => array_values($revenueData)
            ]
        );
    }

    public function numberOfSubs(): Attribute
    {
        return new Attribute(
            get: fn () => count($this->subs),
        );
    }
    public function numberOfLastMonthSubs(): Attribute
    {
        return new Attribute(
            get: fn () => count($this->last_month_subs),
        );
    }

    public function format()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'location' => $this->location,
            'pos' => [$this->lat, $this->lng],
            'image' => $this->image,
            'manager' => $this->manager->formatUser(),
        ];
    }

    public function myClubFormat()
    {
        return (object)[
            "id" => $this->id,
            "name" => $this->name,
            "location" => $this->location,
            "lat" => $this->lat,
            "lng" => $this->lng,
            "number_of_subs" => $this->number_of_subs,
            "number_of_last_month_subs" => $this->number_of_last_month_subs,
            "number_of_customer_sub" => $this->number_of_customer_sub,
            "revenue" => $this->revenue,
            "customer_sub" => $this->customer_sub->map->formatWithoutSubscriotionRelation(),
        ];
    }
    public function formatForAdmin()
    {
        return (object)[
            "id" => $this->id,
            "number_of_subs" => $this->number_of_subs,
            "number_of_last_month_subs" => $this->number_of_last_month_subs,
            "number_of_customer_sub" => $this->number_of_customer_sub,
            "revenue" => $this->revenue,
            "customer_sub" => $this->customer_sub->map->formatWithoutSubscriotionRelation(),
        ];
    }

}
