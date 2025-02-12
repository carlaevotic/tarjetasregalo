<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $guarded = [];
    public function Cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('active', function ($query) {
            if(auth()->user()->hasRole(['tienda'])){
                $query->whereHas('cards', function ($cardQuery){
                    $cardQuery->where('store_id', auth()->user()->store_id);
                });
            }
            return $query;
            
    });
    }

}
