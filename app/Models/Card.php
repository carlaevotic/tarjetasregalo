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
    
}
