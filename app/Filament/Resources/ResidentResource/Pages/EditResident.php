<?php

namespace App\Filament\Resources\ResidentResource\Pages;

use App\Filament\Resources\ResidentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResident extends EditRecord
{
    protected static string $resource = ResidentResource::class;

    protected function getFormActions(): array
    {
        return [
            // Obtiene el botón "Guardar Cambios" por defecto
            $this->getSaveFormAction(),

            // Obtiene el botón "Cancelar" por defecto y le cambia la etiqueta
            $this->getCancelFormAction()->label('Regresar'),
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->visible(fn (): bool => auth()->user()->hasRole('Administrador')),
        ];
    }
}
