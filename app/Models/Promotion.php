<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $guarded = [];
    protected function casts(): array
    {  return [
            'image' => 'array',
        ];
    }
}
