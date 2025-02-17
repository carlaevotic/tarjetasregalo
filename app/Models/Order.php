<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $guarded = [];

    public function Store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function Lines(): HasMany
    {
        return $this->hasMany(OrderLine::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('active', function ($query) {
            if(auth()->user()->hasRole(['tienda'])){
                $storeId = auth()->user()->store_id;
                $query->whereHas('store', function ($q) use ($storeId) {
                    $q->where('store_id', $storeId); 
                });
            }
            
    });
    }
}
