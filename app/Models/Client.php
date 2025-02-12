<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $guarded = [];

    // public function Store(): BelongsTo
    // {
    //     return $this->belongsTo(Store::class);
    // }

    public function Cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }
}
