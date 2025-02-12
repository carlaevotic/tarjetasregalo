<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Card extends Model
{
    protected $guarded = [];

    public function Client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function Store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
    
    //Query de tablas, mostrar solo sus cards de tienda
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('active', function ($query) {
            if(auth()->user()->hasRole(['tienda'])){
                return $query->where('store_id', auth()->user()->store_id);
            }
            if(auth()->user()->hasRole(['cliente'])){
                return $query->where('client_id', auth()->user()->client_id);
            }

            return $query;
            
    });
    }
}
