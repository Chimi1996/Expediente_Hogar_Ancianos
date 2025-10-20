<?php

namespace App\Filament\Resources\PrescriptionResource\Pages;

use App\Filament\Resources\PrescriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePrescription extends CreateRecord
{
    protected static string $resource = PrescriptionResource::class;

    protected function getFormActions(): array
    {
        return [
            // Obtiene el botón "Crear" por defecto
            $this->getCreateFormAction(),
            
            // Obtiene el botón "Crear y crear otro" por defecto
            $this->getCreateAnotherFormAction(),
            
            // Obtiene el botón "Cancelar" por defecto y le cambia la etiqueta
            $this->getCancelFormAction()->label('Regresar'),
        ];
    }
    
}
