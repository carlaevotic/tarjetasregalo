<?php

namespace App\Filament\Store\Resources\CardResource\RelationManagers;

use App\Enums\ActionCard;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MovementsRelationManager extends RelationManager
{
    protected static string $relationship = 'Movements';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\ToggleButtons::make('action')->inline()->options(ActionCard::class)->required(),
                Forms\Components\TextInput::make('import')->numeric()->required()->suffix('€'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('movements')
            ->columns([
                Tables\Columns\TextColumn::make('action')->label('Acción'),
                Tables\Columns\TextColumn::make('import')->label('Importe')->formatStateUsing(fn ($state) => $state . ' €'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Crear Movimiento')
                    ->using(function (array $data, $livewire) {
                        $card = $livewire->getOwnerRecord(); 
                        if ($data['action'] === '0' ) {
                            $card->import += $data['import'];
                        } elseif ($data['action'] === '1') {
                            $card->import -= $data['import'];
                        }
                        $card->save();
                        $livewire->redirect(request()->header('Referer'));
                        return $livewire->getRelationship()->create($data);
                        }),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function ($record, $livewire) {
                        $card = $livewire->getOwnerRecord();
                        if ($record->action->value === 0) {
                            $card->import -= $record->import;
                        } elseif ($record->action->value === 1) {
                            $card->import += $record->import;
                        }
                        $card->save();
                        $livewire->redirect(request()->header('Referer'));
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
