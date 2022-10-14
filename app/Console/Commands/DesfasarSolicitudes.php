<?php

namespace App\Console\Commands;

use App\Models\Solicitud;
use Illuminate\Console\Command;

class DesfasarSolicitudes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'desfasar:solicitudes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cambiar el estado de las solicituades a "DESFASADO" cuando la fecha de entrega haya sido superada por la fecha actual';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Solicitud::where("id_estado", 3)
            ->where('fecha_entrega', '<', now()->format('Y-m-d'))
            ->get()
            ->each(function ($solicitud) {
                $solicitud->update(["id_estado" => 4]);
            });

        return 0;
    }
}
