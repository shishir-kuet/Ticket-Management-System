<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_name',
        'feature_name',
        'feature_value',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'plan_name', 'plan_name');
    }
}