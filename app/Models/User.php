<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'store_id',
        'client_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function Store(): BelongsTo
    {   
        return $this->belongsTo(Store::class);
    }   
    public function Client(): BelongsTo
    {   
        return $this->belongsTo(Client::class);
    }  

        // PERMISSION
        public function canAccessPanel(Panel $panel): bool
        {
            if($panel->getId() == 'admin') {
                return auth()->user()->hasRole(['admin']);
            }
            if($panel->getId() == 'store') {
                return auth()->user()->hasRole(['tienda']);
            }
            if($panel->getId() == 'client') {
                return auth()->user()->hasRole(['cliente']);
            }
            return true;
        }  


}
