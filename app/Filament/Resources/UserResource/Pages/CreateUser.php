<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

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
