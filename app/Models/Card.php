<?php

namespace App\Models;

use App\Enums\CardStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Card extends Model
{
    protected $guarded = [];
    protected function casts(): array
    {  return [
            'status' => CardStatus::class,
        ];
    }

    public function Client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function Store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function Movements(): HasMany
    {
        return $this->hasMany(Movement::class);
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
