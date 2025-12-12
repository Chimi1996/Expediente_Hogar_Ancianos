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
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $modelLabel = 'Usuario';
    protected static ?string $pluralModelLabel = 'Usuarios';
    protected static ?string $navigationLabel = 'Usuarios';
    protected static ?string $navigationGroup = 'Administración'; 
    protected static ?int $navigationSort = 70;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                ->label('Nombre')
                ->required()
                ->maxLength(255),
                TextInput::make('email')
                    ->label('Correo Electrónico')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true) // Asegura email único
                    ->maxLength(255),
                TextInput::make('password')
                    ->label('Contraseña')
                    ->password()
                    // Requerido solo al crear, no al editar
                    ->required(fn (string $context): bool => $context === 'create')
                    // Oculta el campo al editar si no se quiere cambiar la contraseña
                    ->visible(fn (string $context): bool => $context === 'create')
                    // Deja que el cast del modelo (`password => 'hashed'`) haga el hash
                    // No rellenes el campo al editar
                    ->dehydrated(fn ($state) => filled($state))
                    ->maxLength(255),

                // --- Selector de Roles (Spatie) ---
                Select::make('roles')
                    ->label('Roles')
                    ->relationship('roles', 'name') // Usa la relación 'roles' y muestra el 'name'
                    ->multiple() // Permite asignar múltiples roles
                    ->preload() // Carga las opciones al inicio
                    ->searchable(),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->label('Nombre')
                ->searchable()
                ->sortable(),
            TextColumn::make('email')
                ->label('Correo Electrónico')
                ->searchable()
                ->sortable(),
            // Muestra los roles asignados
            TextColumn::make('roles.name')
                ->label('Roles')
                ->badge() // Muestra los roles como etiquetas
                ->searchable(),
            TextColumn::make('created_at')
                ->label('Fecha Creación')
                ->dateTime('d/m/Y')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true), // Oculta por defecto
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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

    public static function canViewAny(): bool
    {
        // Solo permite ver si el usuario tiene el rol 'Administrador'
        return auth()->user()->hasRole('Administrador');
    }
}
