<?php

namespace App\Console;

use App\Http\Controllers\ProcessTarefasController;
use App\Jobs\UpdateMercadoLivreTokens;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // Agendar o comando para rodar a cada 5 minutos
        //  $schedule->command('processar:pedidos-sem-rastreio')->everyFiveMinutes();
        // Executar o Job a cada minuto
         $schedule->job(new \App\Jobs\UpdateBlingToken)->everyMinute();
         // Executar o Job de atualização de tokens do Mercado Livre a cada 1 minuto
         $schedule->job(new UpdateMercadoLivreTokens)->everyMinute();
        //  $schedule->job(new \App\Jobs\ProcessTarefasJob)->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
