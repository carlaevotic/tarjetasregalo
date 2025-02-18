<?php

namespace App\Filament\Resources;

use App\Enums\CardStatus;
use App\Filament\Resources\CardResource\Pages;
use App\Filament\Resources\CardResource\RelationManagers;
use App\Filament\Store\Resources\CardResource\RelationManagers\MovementsRelationManager;
use App\Models\Card;
use App\Models\Store;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CardResource extends Resource
{
    protected static ?string $model = Card::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift-top';

    protected static ?int $navigationSort = 04;
    protected static ?string $modelLabel = "Tarjeta Regalo"; 
    protected static ?string $pluralModelLabel = "Tarjetas Regalo"; 

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('Tarjeta')->required(),
                Forms\Components\Select::make('client_id')->relationship('client','name')->label('Cliente')->required(),
                Forms\Components\Select::make('store_id')->relationship('store','name')->label('Tienda')->required(),
                Forms\Components\ToggleButtons::make('status')->inline()->options(CardStatus::class)->label('Estado')->default(0)->required(),
                Forms\Components\TextInput::make('import')->label('Importe')->numeric()->suffix('€'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Tarjeta')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('client.name')->label('Cliente')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('store.name')->label('Tienda')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('import')->label('Import')->sortable()->formatStateUsing(fn ($state) => $state . ' €'),
                Tables\Columns\TextColumn::make('status')->label('Estado')->searchable()->sortable(),

            ])
            ->filters([
                SelectFilter::make('store_id') ->options(Store::get()->pluck('name','id'))->label('Tienda'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            MovementsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCards::route('/'),
            'create' => Pages\CreateCard::route('/create'),
            'edit' => Pages\EditCard::route('/{record}/edit'),
        ];
    }
}
