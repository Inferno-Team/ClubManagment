<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionType extends Model
{
    use HasFactory;
    protected $fillable = ['name','duration'];
    // protected $appends = ['number_of_clubs', 'number_of_subs', 'last_year_subscriptions'];


    public function clubs()
    {
        return $this->belongsToMany(Club::class, 'club_subscriptions', 'subscription_id')
            ->orderBy('club_subscriptions.created_at', 'DESC')->withTimestamps();
    }
    public function lastYearSubscriptions(): Attribute
    {
        return new Attribute(
            get: function () {
                $filtered = $this->clubs->filter(function ($val) {
                    $valYear = Carbon::parse($val->pivot->created_at)->format('Y');
                    $extaptedYear = Carbon::now()->format('Y');
                    return $valYear == $extaptedYear;
                    // return true;
                })->values();

                $records = $filtered->groupBy(function ($val) {
                    return Carbon::parse($val->pivot->created_at)->format('M');
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
        );
    }
    public function club_subscriptions(){
        return $this->hasMany(ClubSubScription::class,'subscription_id');
    }
    public function customer_subscriptions()
    {
        return $this->belongsToMany(UserSubscription::class, 'club_subscriptions', 'subscription_id', 'id', null, 'subscription_id')
            ->orderBy('club_subscriptions.created_at', 'DESC')->withTimestamps();
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
    public function getAllFormat()
    {
        return (object)[
            'id' => $this->id,
            'name' => $this->name,
            'number_of_clubs' => $this->number_of_clubs,
            'number_of_subs' => $this->number_of_subs,
        ];
    }
    public function getThisYearCustomersSubscriptions()
    {
        $filtered = $this->customer_subscriptions->filter(function ($val) {
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

    public function singleSubscriptionFormat()
    {

        return (object)[
            'id' => $this->id,
            'name' => $this->name,
            'last_year_subscriptions' => $this->getThisYearCustomersSubscriptions(),
            "customers" => $this->customer_subscriptions->map->formatOnly(),
            "number_of_customer_sub" => 150,
            "number_of_last_month_subs" => 50,

        ];
    }
    public function format()
    {
        return (object)[
            'id' => $this->id,
            'name' => $this->name,
            'duration' => $this->duration,
        ];
    }
}
