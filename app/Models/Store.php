<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    protected $guarded = [];

    // public function Clients(): HasMany
    // {
    //     return $this->hasMany(Client::class);
    // }


    public function Cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }
}
