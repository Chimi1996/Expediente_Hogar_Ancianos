<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResidentResource\Pages;
use App\Filament\Resources\ResidentResource\RelationManagers;
use App\Models\Resident;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class ResidentResource extends Resource
{
    protected static ?string $model = Resident::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('cedula')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('primer_nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('segundo_nombre')
                    ->maxLength(255),
                Forms\Components\TextInput::make('primer_apellido')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('segundo_apellido')
                    ->maxLength(255),
                Forms\Components\Select::make('sexo')
                    ->options([
                    'Masculino' => 'Masculino',
                    'Femenino' => 'Femenino',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('fecha_nacimiento')
                    ->required()
                    ->native(false),
                Forms\Components\DatePicker::make('fecha_ingreso')
                    ->required()
                    ->native(false),
                Forms\Components\Select::make('estado')
                     ->options([
                    'Activo' => 'Activo',
                    'Inactivo' => 'Inactivo',
                    'Trasladado' => 'Trasladado',
                    'Fallecido' => 'Fallecido',
                    ])
                    ->required()
                    ->default('Activo'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cedula')
                    ->searchable(),
                Tables\Columns\TextColumn::make('primer_nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('segundo_nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('primer_apellido')
                    ->searchable(),
                Tables\Columns\TextColumn::make('segundo_apellido')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sexo'),
                Tables\Columns\TextColumn::make('fecha_ingreso')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('age') // <-- Agrega esta columna virtual
                    ->label('Edad') // Etiqueta personalizada para la columna
                    ->sortable(query: fn (Builder $query, string $direction): Builder => $query->orderBy('fecha_nacimiento', $direction === 'asc' ? 'desc' : 'asc')),
                Tables\Columns\TextColumn::make('estado')
                    ->searchable(),
                
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'Activo' => 'Activo',
                        'Inactivo' => 'Inactivo',
                        'Trasladado' => 'Trasladado',
                        'Fallecido' => 'Fallecido',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $query, $value) => $query->where('estado', $value)
                        );
        })
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
            'index' => Pages\ListResidents::route('/'),
            'create' => Pages\CreateResident::route('/create'),
            'edit' => Pages\EditResident::route('/{record}/edit'),
        ];
    }
}
