<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $guarded = [];
    public function Cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    public function Stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class, 'store_client', 'client_id', 'store_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('active', function ($query) {
            if(auth()->user()->hasRole(['tienda'])){
                $storeId = auth()->user()->store_id;
                // $query->whereHas('cards', function ($cardQuery){
                //     $cardQuery->where('store_id', auth()->user()->store_id);
                // $query->whereHas('stores', function ($q) use ($storeId) {
                //     $q->where('store_client.store_id', $storeId); 
                // });
            }
            
    });
    }

}

