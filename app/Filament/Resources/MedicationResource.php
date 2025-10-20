<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MedicationResource\Pages;
use App\Filament\Resources\MedicationResource\RelationManagers;
use App\Models\Medication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MedicationResource extends Resource
{
    protected static ?string $model = Medication::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $modelLabel = 'Medicamento';
    protected static ?string $pluralModelLabel = 'Medicamentos';
    protected static ?string $navigationLabel = 'Gestionar Medicamentos';
    protected static ?string $navigationGroup = 'Gestión Clínica';
    protected static ?int $navigationSort = 50;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nombre del Medicamento')
                    ->required()
                    ->unique(ignoreRecord: true) // Evita duplicados
                    ->maxLength(255),
                TextInput::make('presentation')
                    ->label('Presentación (Ej: Tableta, Jarabe)')
                    ->maxLength(255),
                TextInput::make('standard_dose')
                    ->label('Dosis Estándar (Ej: 500mg, 10ml)')
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Descripción / Notas Generales')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Medicamento')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('presentation')
                    ->label('Presentación')
                    ->searchable(),
                TextColumn::make('standard_dose')
                    ->label('Dosis Estándar'),
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
            'index' => Pages\ListMedications::route('/'),
            'create' => Pages\CreateMedication::route('/create'),
            'edit' => Pages\EditMedication::route('/{record}/edit'),
        ];
    }
}
