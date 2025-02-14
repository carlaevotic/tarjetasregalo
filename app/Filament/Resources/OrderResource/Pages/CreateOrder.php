<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function afterCreate(): void
    {
        //CREAR Nº Pedido
        $this->record->update([
            'n_order' => str_pad($this->record->id, 2, '0', STR_PAD_LEFT),
            // 'amount_order' => $this->record?->lines->sum('total'),
        ]);

        
    }


}
