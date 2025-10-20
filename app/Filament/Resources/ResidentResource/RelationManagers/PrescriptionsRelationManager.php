<?php

namespace App\Filament\Resources\ResidentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class PrescriptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'prescriptions';

    protected static ?string $title = 'Prescripciones de Medicamentos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('medication_id')
                ->label('Medicamento')
                ->relationship('medication', 'name') // Selecciona de Medicamentos
                ->searchable()
                ->preload()
                ->required(),

                TextInput::make('dose')
                    ->label('Dosis (Ej: 1 tableta, 5ml)')
                    ->required()
                    ->maxLength(255),

                TextInput::make('frequency')
                    ->label('Frecuencia (Ej: Cada 8h, Con desayuno)')
                    ->required()
                    ->maxLength(255),

                DatePicker::make('start_date')
                    ->label('Fecha de Inicio')
                    ->required()
                    ->native(false),

                DatePicker::make('end_date')
                    ->label('Fecha de Fin (Opcional)')
                    ->native(false),

                Select::make('status')
                    ->label('Estado')
                    ->options([
                        'Activa' => 'Activa',
                        'Completada' => 'Completada',
                        'Suspendida' => 'Suspendida',
                    ])
                    ->required()
                    ->default('Activa'),

                Textarea::make('notes')
                    ->label('Indicaciones Especiales')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('medication.name')
            ->columns([
                TextColumn::make('medication.name') // Muestra el nombre del catálogo
                    ->label('Medicamento')
                    ->searchable(),
                TextColumn::make('dose')->label('Dosis'),
                TextColumn::make('frequency')->label('Frecuencia'),
                TextColumn::make('start_date')->label('Inicio')->date()->sortable(),
                TextColumn::make('end_date')->label('Fin')->date(),
                TextColumn::make('status')->label('Estado'),
            ])
            ->emptyStateHeading('No se encontraron registros')
            ->emptyStateDescription('Cree una Prescripción para empezar.')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->label('Añadir Prescripción'),
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
}
