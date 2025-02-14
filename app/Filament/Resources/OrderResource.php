<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 02;
    protected static ?string $modelLabel = "Pedido"; 
    protected static ?string $pluralModelLabel = "Pedidos"; 

    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('n_order')->label('Nº Pedido')->readonly(),
                Forms\Components\Select::make('store_id')->label('Client')->required()->searchable()->preload()->relationship('Store','name'),
                Forms\Components\DatePicker::make('order_date')->label('Data')->default(now()),
                Forms\Components\TextInput::make('amount_order')->label('Importe total')->suffix('€'),
                Forms\Components\Section::make()
                ->schema([
                    Forms\Components\Repeater::make('lines')->label('Linia')->relationship()->extraAttributes(['class' => 'thin-repeater'])
                    ->schema([
                    Forms\Components\Grid::make(4)->schema([
                    Forms\Components\TextInput::make('concept')->label('Descripción')->columnSpan(1),
                    Forms\Components\TextInput::make('qty')->numeric()->reactive()->default(1)->label('Cantidad')->columnSpan(1)->numeric()->live(onBlur: true)
                    ->afterStateUpdated(function(Get $get, Set $set, $state) {
                        $price = $get('unit_price') ?? 0; 
                        $total = $state * $price; 
                        $set('total', round($total, 2)); 
                    })->suffix('Uds.'),
                    Forms\Components\TextInput::make('unit_price')->label('Precio Unit')->numeric()->suffix('Ud.€')->reactive()->live(onBlur: true) 
                    ->afterStateUpdated(function(Get $get, Set $set, $state) {
                        $qty = $get('qty') ?? 1;
                        $total = $state * $qty;
                        $set('total', round($total, 2));
                    }),
                    Forms\Components\TextInput::make('total')->label('Total')->suffix('€')->numeric()->readOnly(fn(Get $get) => true)->suffix('€')->readonly(),
                        ]),
                    ])
                    ->defaultItems(0)
                    ->columns(4)
            ])
                ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('n_order')->label('Nº Pedido') ->searchable(),
                Tables\Columns\TextColumn::make('store.name')->label('Tienda')->searchable(),
                Tables\Columns\TextColumn::make('lines.qty')->label('Nº Tarjetas')->alignCenter(),
                Tables\Columns\TextColumn::make('amount_order')->label('Total'),
                // Tables\Columns\TextColumn::make('status')->label('Estat')->badge(),
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
