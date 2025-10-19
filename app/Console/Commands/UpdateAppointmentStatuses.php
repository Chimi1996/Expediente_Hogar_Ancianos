<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Carbon\Carbon;

class UpdateAppointmentStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-appointment-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now(); // Obtiene la fecha y hora actual

        $this->info('Buscando citas pendientes vencidas...');

        // Busca citas 'Pendiente' cuya fecha/hora ya pasó
        $overdueAppointments = Appointment::where('status', 'Pendiente')
                                        ->where('appointment_datetime', '<', $now)
                                        ->get();

        if ($overdueAppointments->isEmpty()) {
            $this->info('No se encontraron citas vencidas para actualizar.');
            return 0; // Termina el comando si no hay nada que hacer
        }

        $count = 0;
        foreach ($overdueAppointments as $appointment) {
            $appointment->status = 'Cancelada'; // Cambia el estado
            $appointment->save(); // Guarda el cambio en la base de datos
            $count++;
        }

        $this->info("Se actualizaron {$count} citas a 'Cancelada'.");
        return 0; // Indica que el comando terminó exitosamente
    }
}
