<?php

namespace App\Filament\Resources\ResidentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AppointmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'appointments';

    protected static ?string $title = 'Citas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
            DateTimePicker::make('appointment_datetime')
                ->label('Fecha y Hora de la Cita')
                ->required()
                ->native(false), 

            TextInput::make('doctor_name')
                ->label('Nombre del Médico')
                ->required()
                ->maxLength(255),

            TextInput::make('specialty')
                ->label('Especialidad (Opcional)')
                ->maxLength(255),

            TextInput::make('location')
                ->label('Lugar')
                ->required()
                ->maxLength(255),

            Select::make('status')
                ->label('Estado')
                ->options([
                    'Pendiente' => 'Pendiente',
                    'Completada' => 'Completada',
                    'Cancelada' => 'Cancelada',
                    'Reprogramada' => 'Reprogramada',
                ])
                ->required()
                ->default('Pendiente'), 

            Textarea::make('notes')
                ->label('Notas (Motivo, Preparación, etc.)')
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('appointment_datetime')
            ->columns([
                TextColumn::make('appointment_datetime')
                    ->label('Fecha y Hora')
                    ->dateTime('d/m/Y H:i') // Formato día/mes/año hora:minuto
                    ->sortable(),
                TextColumn::make('doctor_name')
                    ->label('Médico')
                    ->searchable(),
                TextColumn::make('location')
                    ->label('Lugar'),
                TextColumn::make('status')
                    ->label('Estado'),
            ])
            ->defaultSort('appointment_datetime', 'desc')
            ->emptyStateHeading('No se encontraron registros')
            ->emptyStateDescription('Cree una Cita para empezar.')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->label('Crear Cita'),
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
