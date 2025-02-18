<?php

namespace App\Models;

use App\Enums\ActionCard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movement extends Model
{
    protected $guarded = [];
    protected function casts(): array
    {  return [
            'action' => ActionCard::class,
        ];
    }
    public function Card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
