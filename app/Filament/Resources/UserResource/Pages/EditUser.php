<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\TextInput; 
use Illuminate\Support\Facades\Hash; 
use App\Models\User;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

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
            Actions\Action::make('setPassword')
            ->label('Establecer Contraseña')
            ->icon('heroicon-o-key') 
            ->color('secondary')
            ->form([
                TextInput::make('new_password')
                    ->label('Nueva Contraseña')
                    ->password()
                    ->required()
                    ->minLength(8), // Añade validación de longitud
                TextInput::make('new_password_confirmation')
                    ->label('Confirmar Nueva Contraseña')
                    ->password()
                    ->required()
                    ->same('new_password') // Asegura que las contraseñas coincidan
                    ->minLength(8),
            ])
            ->action(function (array $data, User $record): void {
                // La lógica que se ejecuta al enviar el formulario
                $record->update([
                    'password' => Hash::make($data['new_password']),
                ]);
                // Muestra una notificación de éxito
                \Filament\Notifications\Notification::make()
                    ->title('Contraseña actualizada')
                    ->success()
                    ->send();
            })
            // Solo visible para Administradores
            ->visible(fn (): bool => auth()->user()->hasRole('Administrador')),
            Actions\DeleteAction::make() 
                ->visible(fn (): bool => auth()->user()->hasRole('Administrador')),
        ];
    }
}
