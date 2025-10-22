<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_name',
        'price',
        'billing_cycle',
        'starts_at',
        'ends_at',
        'auto_renew',
        'cancelled_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'auto_renew' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // Plan constants
    const PLAN_FREE = 'free';
    const PLAN_PROFESSIONAL = 'professional';
    const PLAN_ENTERPRISE = 'enterprise';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function features()
    {
        return $this->hasMany(SubscriptionFeature::class, 'plan_name', 'plan_name');
    }

    // Helper methods
    public function isActive()
    {
        return $this->ends_at === null || $this->ends_at->isFuture();
    }

    public function isCancelled()
    {
        return $this->cancelled_at !== null;
    }

    public function cancel()
    {
        $this->update([
            'cancelled_at' => now(),
            'auto_renew' => false
        ]);
    }

    public function renew()
    {
        // Extend subscription based on billing cycle
        $extension = $this->billing_cycle === 'yearly' ? now()->addYear() : now()->addMonth();
        
        $this->update([
            'ends_at' => $extension
        ]);
    }

    // Plan limits
    public function getTicketLimit()
    {
        return match($this->plan_name) {
            self::PLAN_FREE => 10,
            self::PLAN_PROFESSIONAL => -1, // unlimited
            self::PLAN_ENTERPRISE => -1,
            default => 0
        };
    }

    /**
     * Return default ticket limit for a plan name (static helper).
     * Useful when checking limits without an instance.
     */
    public static function defaultTicketLimitForPlan(string $planName): int
    {
        return match($planName) {
            self::PLAN_FREE => 10,
            self::PLAN_PROFESSIONAL => -1,
            self::PLAN_ENTERPRISE => -1,
            default => 0
        };
    }

    public function getAgentLimit()
    {
        return match($this->plan_name) {
            self::PLAN_FREE => 1,
            self::PLAN_PROFESSIONAL => 5,
            self::PLAN_ENTERPRISE => -1, // unlimited
            default => 0
        };
    }

    public function hasFeature(string $feature): bool
    {
        return $this->features()->where('feature_name', $feature)->exists();
    }

    public function getFeatureValue(string $feature)
    {
        $featureModel = $this->features()->where('feature_name', $feature)->first();
        return $featureModel ? $featureModel->feature_value : null;
    }
}