<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TreatmentResource\Pages;
use App\Filament\Resources\TreatmentResource\RelationManagers;
use App\Models\Treatment;
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
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class TreatmentResource extends Resource
{
    protected static ?string $model = Treatment::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $modelLabel = 'Tratamiento';

    protected static ?string $pluralModelLabel = 'Tratamientos';

    protected static ?string $navigationLabel = 'Tratamientos';

    protected static ?string $navigationGroup = 'Gestión Hogar';

    protected static ?int $navigationSort = 30;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('diagnosis_id')
                    ->label('Diagnóstico del Residente')
                    ->relationship('diagnosis')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->description} ({$record->resident->first_name} {$record->resident->last_name})")
                    ->searchable()
                    ->preload()
                    ->required(),
                
                Textarea::make('description')
                    ->label('Descripción del Tratamiento')
                    ->required()
                    ->columnSpanFull(),

                DatePicker::make('start_date')
                    ->label('Fecha de Inicio')
                    ->required()
                    ->native(false),

                DatePicker::make('end_date')
                    ->label('Fecha de Fin')
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('diagnosis.resident.full_name')
                    ->label('Residente')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->orWhereHas('diagnosis.resident', function (Builder $query) use ($search) {
                            $query
                                ->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->select('treatments.*') 
                            ->join('diagnoses', 'treatments.diagnosis_id', '=', 'diagnoses.id')
                            ->join('residents', 'diagnoses.resident_id', '=', 'residents.id')
                            ->orderBy('residents.first_name', $direction)
                            ->orderBy('residents.last_name', $direction);
                    }),
                
                TextColumn::make('diagnosis.description')
                    ->label('Diagnóstico')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')->label('Tratamiento'),
                TextColumn::make('start_date')->label('Inicio')->date()->sortable(),
                TextColumn::make('status')->label('Estado'),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Prescrito' => 'Prescrito',
                        'Activo' => 'Activo',
                        'Completado' => 'Completado',
                        'Cancelado' => 'Cancelado',
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
            'index' => Pages\ListTreatments::route('/'),
            'create' => Pages\CreateTreatment::route('/create'),
            'edit' => Pages\EditTreatment::route('/{record}/edit'),
        ];
    }
}
