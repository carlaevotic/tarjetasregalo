<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum CardStatus: int implements HasColor, HasIcon, HasLabel
{
    case DES = 0;
    case ACT = 1;
    case CANC = 2;
    case BLOQ = 3;

    public function getLabel(): ?string
    {          
        return match ($this) {
            self::DES => 'Desactivada',
            self::ACT => 'Activada',
            self::CANC => 'Cancelada',
            self::BLOQ => 'Bloqueada',

        };
    }
    public function getColor(): string | array | null
    {
        return match ($this) {
            self::DES => 'info',
            self::ACT => 'success',
            self::CANC, self ::BLOQ=> 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::ACT => 'heroicon-o-check',
            self::DES=> 'heroicon-o-clock',
            self::CANC, self:: BLOQ => 'heroicon-m-x-circle',
        };
    }
}
