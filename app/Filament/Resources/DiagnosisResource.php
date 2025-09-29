<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiagnosisResource\Pages;
use App\Filament\Resources\DiagnosisResource\RelationManagers;
use App\Models\Diagnosis;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class DiagnosisResource extends Resource
{
    protected static ?string $model = Diagnosis::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Diagnóstico';

    protected static ?string $pluralModelLabel = 'Diagnósticos';

    protected static ?string $navigationLabel = 'Diagnósticos';

    protected static ?string $navigationGroup = 'Gestión Hogar';

    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('resident_id')
                    ->label('Residente')
                    ->relationship('resident') // Busca en la relación 'resident'
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->first_name} {$record->last_name} ({$record->identity_card})")
                    ->searchable(['first_name', 'last_name', 'identity_card'])
                    ->preload()
                    ->required(),

                TextInput::make('description')
                    ->label('Descripción del Diagnóstico')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                DatePicker::make('diagnosis_date')
                    ->label('Fecha del Diagnóstico')
                    ->required()
                    ->native(false),

                Select::make('status')
                    ->label('Estado')
                    ->options([
                        'Activo' => 'Activo',
                        'Resuelto' => 'Resuelto',
                        'En seguimiento' => 'En seguimiento',
                    ])
                    ->required()
                    ->default('Activo'),

                Textarea::make('notes')
                    ->label('Notas Adicionales')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Columna clave que muestra el nombre del residente
                TextColumn::make('resident.first_name')
                    ->label('Residente')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Descripción')
                    ->searchable(),
                
                TextColumn::make('diagnosis_date')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->searchable(),
            ])
            ->filters([
                 \Filament\Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Activo' => 'Activo',
                        'Resuelto' => 'Resuelto',
                        'En seguimiento' => 'En seguimiento',
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDiagnoses::route('/'),
            'create' => Pages\CreateDiagnosis::route('/create'),
            'edit' => Pages\EditDiagnosis::route('/{record}/edit'),
        ];
    }
}
