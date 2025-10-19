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
use App\Filament\Resources\ResidentResource\RelationManagers\DiagnosesRelationManager;
use App\Filament\Resources\ResidentResource\RelationManagers\AppointmentsRelationManager;

class ResidentResource extends Resource
{
    protected static ?string $model = Resident::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Residente';

    protected static ?string $pluralModelLabel = 'Residentes';

    protected static ?string $navigationLabel = 'Residentes';

    protected static ?string $navigationGroup = 'Gestión Hogar';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('identity_card') //cedula
                    ->label('Cédula')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('first_name') //primer_nombre
                    ->label('Primer Nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('second_name') //segundo_nombre
                    ->label('Segundo Nombre')
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name') //primer_apellido
                    ->label('Primer Apellido')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('second_last_name') //segundo_apellido
                    ->label('Segundo Apellido')
                    ->maxLength(255),
                Forms\Components\Select::make('sex') //sexo
                    ->label('Sexo')
                    ->options([
                    'Masculino' => 'Masculino',
                    'Femenino' => 'Femenino',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('birth_date') //fecha_nacimiento
                    ->label('Fecha de Nacimiento')
                    ->required()
                    ->native(false),
                Forms\Components\DatePicker::make('admission_date') //fecha_ingreso
                    ->label('Fecha de Ingreso')
                    ->required()
                    ->native(false),
                Forms\Components\Select::make('status') //estado
                    ->label('Estado')
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
                Tables\Columns\TextColumn::make('identity_card')
                    ->label('Cedula')
                    ->searchable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->label('Primer Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('second_name')
                    ->label('Segundo Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Primer Apellido')
                    ->searchable(),
                Tables\Columns\TextColumn::make('second_last_name')
                    ->label('Segundo Apellido')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sex')
                    ->label('Sexo'),
                Tables\Columns\TextColumn::make('admission_date')
                    ->label('Fecha de Ingreso')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('age') // <-- columna virtual
                    ->label('Edad') // Etiqueta personalizada para la columna
                    ->sortable(query: fn (Builder $query, string $direction): Builder => $query->orderBy('fecha_nacimiento', $direction === 'asc' ? 'desc' : 'asc')),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->searchable(),
                
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Activo' => 'Activo',
                        'Inactivo' => 'Inactivo',
                        'Trasladado' => 'Trasladado',
                        'Fallecido' => 'Fallecido',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $query, $value) => $query->where('status', $value)
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
            DiagnosesRelationManager::class,
            AppointmentsRelationManager::class,
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
