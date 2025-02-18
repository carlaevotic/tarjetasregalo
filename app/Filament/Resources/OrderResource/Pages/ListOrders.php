<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Http\Controllers\CardController;
use App\Http\Controllers\OrderController;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('importer_api')->label('Importar Api')->color('info')
            ->action(function(){
                $order = new OrderController();
                $newOrder = $order->importOrders();
            }),
        ];
    }
}
