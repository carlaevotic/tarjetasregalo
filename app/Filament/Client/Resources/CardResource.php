<?php

namespace App\Filament\Client\Resources;

use App\Filament\Client\Resources\CardResource\Pages;
use App\Models\Card;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;


class CardResource extends Resource
{
    protected static ?string $model = Card::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift-top';
    protected static ?string $modelLabel = "Tarjeta Regalo"; 
    protected static ?string $pluralModelLabel = "Tarjetas Regalo"; 

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Tarjeta'),
                Tables\Columns\TextColumn::make('store.name')->label('Tienda'),
                Tables\Columns\TextColumn::make('import')->label('Import')->formatStateUsing(fn ($state) => $state . ' €'),
            ])
            ->filters([
                //
            ])
            ->actions([
                 Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCards::route('/'),
            // 'create' => Pages\CreateCard::route('/create'),
            // 'edit' => Pages\EditCard::route('/{record}/edit'),
        ];
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name')->label('Tarjeta'),
                TextEntry::make('store.name')->label('Tienda'),
                TextEntry::make('import')->label('Importe')->formatStateUsing(fn ($state) => $state . ' €'),
                TextEntry::make('movements')->label('Movimientos')
                ->formatStateUsing(function ($state, $record) {
                    return $record->Movements->map(function ($mov) {
                        return "<li>" . $mov->import . " € " . $mov->action->getLabel() . "</li>";
                    })->join('');})
                    ->html()
            ])->columns(3);
    }

}
