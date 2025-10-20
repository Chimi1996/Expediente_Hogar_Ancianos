<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

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
