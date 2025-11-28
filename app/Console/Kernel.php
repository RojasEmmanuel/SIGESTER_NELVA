<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\VencerApartadosCommand::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // EN DESARROLLO: cada minuto para pruebas rápidas
        if (app()->environment('local', 'testing')) {
            $schedule->command('apartados:vencer')->everyMinute();
        }

        // EN PRODUCCIÓN: cada 5 minutos (recomendado y óptimo)
        if (app()->environment('production')) {
            $schedule->command('apartados:vencer')->everyFiveMinutes();
        }

        // O si prefieres una sola línea para todos los entornos:
        // $schedule->command('apartados:vencer')->everyFiveMinutes();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}