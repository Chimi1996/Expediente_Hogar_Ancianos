<?php

namespace App\Filament\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Resident;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;
    protected static ?string $modelLabel = 'Cita Médica';
    protected static ?string $pluralModelLabel = 'Citas Médicas';
    protected static ?string $navigationLabel = 'Citas Médicas';
    protected static ?string $navigationGroup = 'Gestión Clínica';
    protected static ?int $navigationSort = 40;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

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

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('appointment_datetime')
            ->columns([
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
                                Resident::select('first_name')->whereColumn('residents.id', 'appointments.resident_id'),
                                $direction
                            )
                            ->orderBy(
                                Resident::select('last_name')->whereColumn('residents.id', 'appointments.resident_id'),
                                $direction
                            );
                    }),
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
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Pendiente' => 'Pendiente',
                        'Completada' => 'Completada',
                        'Cancelada' => 'Cancelada',
                        'Reprogramada' => 'Reprogramada',
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
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view residents') || auth()->user()->can('manage residents');
    }

    // Controla quién puede acceder a la página de creación
    public static function canCreate(): bool
    {
        return auth()->user()->can('manage residents');
    }

    // Controla quién puede acceder a la página de edición
    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('manage residents');
    }
    
}
