<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Horario;
use App\Models\Turno;

class UpdateHorariosWithDias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'horarios:update-dias';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza los horarios con días de la semana basado en el turno y hora';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
        
        // Por cada turno, obtener sus horarios ordenados por hora
        $turnos = Turno::all();
        
        foreach ($turnos as $turno) {
            $horarios = Horario::where('turno_id', $turno->id)
                ->orderBy('hora_inicio')
                ->get();
            
            // Distribuir los horarios entre los días de la semana
            $diaIndex = 0;
            foreach ($horarios as $index => $horario) {
                $dia = $dias[$diaIndex % 5];
                $horario->update(['dia_semana' => $dia]);
                
                // Alternar días si hay múltiples horarios
                if (($index + 1) % 1 === 0) {
                    $diaIndex++;
                }
            }
        }
        
        $this->info('✅ Horarios actualizados correctamente con días de la semana');
    }
}
