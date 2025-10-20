<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrescriptionResource\Pages;
use App\Filament\Resources\PrescriptionResource\RelationManagers;
use App\Models\Prescription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Resident;

class PrescriptionResource extends Resource
{
    protected static ?string $model = Prescription::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $modelLabel = 'Prescripción';
    protected static ?string $pluralModelLabel = 'Prescripciones';
    protected static ?string $navigationLabel = 'Asignar Medicamentos';
    protected static ?string $navigationGroup = 'Gestión Clínica';
    protected static ?int $navigationSort = 60;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Selector de Residente 
                Select::make('resident_id')
                    ->label('Residente')
                    ->relationship('resident')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->first_name} {$record->last_name} ({$record->identity_card})")
                    ->searchable(['first_name', 'last_name', 'identity_card'])
                    ->preload()
                    ->required(),

                // Selector de Medicamento (del Catálogo) 
                Select::make('medication_id')
                    ->label('Medicamento')
                    ->relationship('medication', 'name')
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                 // --- Columna de Residente (con búsqueda y ordenamiento) ---
                TextColumn::make('resident.full_name') // Usa el accesor
                    ->label('Residente')
                    ->searchable(query: function (Builder $query, string $search): Builder { // Búsqueda personalizada
                        return $query->orWhereHas('resident', function (Builder $query) use ($search) {
                            $query
                                ->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder { // Ordenamiento personalizado
                        return $query
                            ->orderBy(
                                Resident::select('first_name')->whereColumn('residents.id', 'prescriptions.resident_id'),
                                $direction
                            )
                            ->orderBy(
                                Resident::select('last_name')->whereColumn('residents.id', 'prescriptions.resident_id'),
                                $direction
                            );
                    }),
                
                TextColumn::make('medication.name')
                    ->label('Medicamento')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('dose')
                    ->label('Dosis'),
                TextColumn::make('frequency')
                    ->label('Frecuencia'),
                TextColumn::make('start_date')
                    ->label('Fecha de Inicio')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Estado'),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Activa' => 'Activa',
                        'Completada' => 'Completada',
                        'Suspendida' => 'Suspendida',
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
            'index' => Pages\ListPrescriptions::route('/'),
            'create' => Pages\CreatePrescription::route('/create'),
            'edit' => Pages\EditPrescription::route('/{record}/edit'),
        ];
    }
}
