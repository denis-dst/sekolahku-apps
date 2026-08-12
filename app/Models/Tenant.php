<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'subscription_plan_id',
        'subscription_tier',
        'status',
        'subscription_status',
        'subscription_expires_at',
        'subscribed_at',
        'notes',
    ];

    protected $casts = [
        'subscription_expires_at' => 'date',
        'subscribed_at' => 'datetime',
    ];

    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function schools()
    {
        return $this->hasMany(School::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function isSubscriptionActive(): bool
    {
        if ($this->subscription_status === 'suspended') {
            return false;
        }

        if ($this->subscription_expires_at && $this->subscription_expires_at->isPast()) {
            return false;
        }

        return true;
    }

    public function hasFeature(string $featureKey): bool
    {
        if (!$this->isSubscriptionActive()) {
            return false;
        }

        if ($this->subscriptionPlan) {
            return $this->subscriptionPlan->hasFeature($featureKey);
        }

        // Default fallback if no plan attached
        return true;
    }
}
