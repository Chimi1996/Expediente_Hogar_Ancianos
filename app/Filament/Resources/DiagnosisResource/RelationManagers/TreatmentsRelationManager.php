<?php

namespace App\Filament\Resources\DiagnosisResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class TreatmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'treatments';

    protected static ?string $title = 'Tratamientos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('description')
                ->label('Descripción del Tratamiento')
                ->required()
                ->columnSpanFull(),

            DatePicker::make('start_date')
                ->label('Fecha de Inicio')
                ->required()
                ->native(false),

            DatePicker::make('end_date')
                ->label('Fecha de Finalizado')
                ->native(false),

            Select::make('status')
                ->label('Estado')
                ->options([
                    'Prescrito' => 'Prescrito',
                    'Activo' => 'Activo',
                    'Completado' => 'Completado',
                    'Cancelado' => 'Cancelado',
                ])
                ->required()
                ->default('Prescrito'),

            Textarea::make('notes')
                ->label('Notas Adicionales')
                ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                TextColumn::make('description')->label('Tratamiento'),
                TextColumn::make('start_date')->label('Inicio')->date()->sortable(),
                TextColumn::make('end_date')->label('Fin')->date()->sortable(),
                TextColumn::make('status')->label('Estado'),
            ])
            ->emptyStateHeading('No se encontraron registros')
            ->emptyStateDescription('Cree un Tratamiento para empezar.')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->label('Crear Tratamiento'),
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
