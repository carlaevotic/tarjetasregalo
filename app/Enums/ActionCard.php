<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ActionCard: int implements HasColor, HasIcon, HasLabel
{
    case REC = 0;
    case CON = 1;


    public function getLabel(): ?string
    {          
        return match ($this) {
            self::REC => 'Recarga',
            self::CON => 'Consumo',


        };
    }
    public function getColor(): string | array | null
    {
        return match ($this) {
            self::REC => 'success',
            self::CON =>'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::REC => 'heroicon-o-plus',
            self::CON=> 'heroicon-o-minus',

        };
    }
}
