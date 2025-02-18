<?php

namespace App\Filament\Resources\CardResource\Pages;

use App\Filament\Resources\CardResource;
use App\Filament\Resources\ClientResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCard extends EditRecord
{
    protected static string $resource = CardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')->label('Ir al Cliente')
            ->url(fn () => $this->record->client?->first()->id ? ClientResource::getUrl('edit', ['record' => $this->record->client->first()->id]) : null)
            ->icon('heroicon-o-arrow-uturn-left')->color('gray'),
            Actions\DeleteAction::make(),
        ];
    }
}
