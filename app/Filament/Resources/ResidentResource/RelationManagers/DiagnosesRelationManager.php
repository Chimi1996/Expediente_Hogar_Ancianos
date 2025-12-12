<?php

namespace App\Filament\Resources\ResidentResource\RelationManagers;

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
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;


class DiagnosesRelationManager extends RelationManager
{
    protected static string $relationship = 'diagnoses';

    protected static ?string $title = 'Diagnósticos';

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            TextInput::make('description')
                ->label('Descripción del Diagnóstico')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(), // Ocupa todo el ancho

            DatePicker::make('diagnosis_date')
                ->label('Fecha del Diagnóstico')
                ->required()
                ->native(false),

            Select::make('status')
                ->label('Estado')
                ->options([
                    'Activo'      => 'Activo',
                    'Resuelto'    => 'Resuelto',
                    'En seguimiento' => 'En seguimiento',
                ])
                ->required()
                ->default('Activo'),
            
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
                TextColumn::make('description')
                    ->label('Descripción'),
                TextColumn::make('diagnosis_date')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Estado'),
            ])
            ->emptyStateHeading('No se encontraron registros')
            ->emptyStateDescription('Cree un Diagnóstico para empezar.')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Crear Diagnóstico')
                    ->modalHeading('Crear Diagnóstico'),
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
