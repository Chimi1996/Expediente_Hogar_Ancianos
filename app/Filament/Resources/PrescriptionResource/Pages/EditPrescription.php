<?php

namespace App\Filament\Resources\PrescriptionResource\Pages;

use App\Filament\Resources\PrescriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrescription extends EditRecord
{
    protected static string $resource = PrescriptionResource::class;

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
            Actions\DeleteAction::make(),
        ];
    }
}
