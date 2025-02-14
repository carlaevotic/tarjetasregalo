<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromotionResource\Pages;
use App\Filament\Resources\PromotionResource\RelationManagers;
use App\Models\Promotion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PromotionResource extends Resource
{
    protected static ?string $model = Promotion::class;

    protected static ?string $navigationIcon = 'heroicon-o-percent-badge';
    protected static ?int $navigationSort = 05;
    protected static ?string $modelLabel = "Promoción"; 
    protected static ?string $pluralModelLabel = "Promociones"; 

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('Promoción')->required(),
                Forms\Components\TextInput::make('percent')->label('Descuento')->suffix('%'),
                Forms\Components\Toggle::make('is_adult')->label('Edad +18')->inline(false),
                Forms\Components\DatePicker::make('start_date')->label('Fecha Inicio')->default(now()),
                Forms\Components\DatePicker::make('end_date')->label('Fecha Fin'),
                Forms\Components\Textarea::make('description')->label('Descripción')->rows(3)->columnSpan(2),
                Forms\Components\FileUpload::make('image')->image()->label('Imagen')->preserveFilenames()
                ->disk('public')->directory(fn(): string => '/img-files')->imagePreviewHeight('50')->columnSpan(1),

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')->label('Imagen')->searchable(),
                Tables\Columns\TextColumn::make('name')->label('Promoción')->searchable(),
                Tables\Columns\TextColumn::make('percent')->label('Porcentaje')->formatStateUsing(fn ($state) => $state . ' %'),
                Tables\Columns\TextColumn::make('start_date')->label('Fecha Inicio')->date('d-m-Y'),
                Tables\Columns\TextColumn::make('end_date')->label('Fecha Fin')->date('d-m-Y'),
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
            'index' => Pages\ListPromotions::route('/'),
            'create' => Pages\CreatePromotion::route('/create'),
            'edit' => Pages\EditPromotion::route('/{record}/edit'),
        ];
    }
}
