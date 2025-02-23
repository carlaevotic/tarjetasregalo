<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    // protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 04;
    protected static ?string $navigationGroup = 'Configuración';
    protected static ?string $modelLabel = "Usuario"; 
    protected static ?string $pluralModelLabel = "Usuarios"; 

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('Nombre') ->required(),
                Forms\Components\TextInput::make('email')->label('Correo') ->email() ->required(),
                Forms\Components\TextInput::make('password')    
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),
                Forms\Components\Select::make('roles')->reactive()->multiple() ->relationship('roles', 'name')->preload(),
                Forms\Components\Select::make('store_id')->relationship('Store','name')->label('Tienda')->searchable()->preload()
                ->visible(fn($get) => Role::where('name','tienda')->whereIn('id',$get('roles'))->exists())
                ->required(fn($get) => Role::where('name','tienda')->whereIn('id',$get('roles'))->exists()),
                Forms\Components\Select::make('client_id')->relationship('Client','name')->label('Client')->searchable()->preload()
                ->visible(fn($get) => Role::where('name','cliente')->whereIn('id',$get('roles'))->exists())
                ->required(fn($get) => Role::where('name','cliente')->whereIn('id',$get('roles'))->exists()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nombre')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->label('Correo')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('roles.name')->label('Rol'),  
                Tables\Columns\TextColumn::make('store.name')->label('Tienda'),  
                Tables\Columns\TextColumn::make('client.name')->label('Rol'),  
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')->relationship(name: 'roles',titleAttribute: 'name', )->label('Roles'),
                Tables\Filters\SelectFilter::make('store')->relationship(name: 'store',titleAttribute: 'name', )->label('Tienda'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
