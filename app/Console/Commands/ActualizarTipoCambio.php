<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ActualizarTipoCambio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tc:actualizar';

    protected $description = 'Actualiza el tipo de cambio USD/MXN desde Banxico';

    public function handle(\App\Services\BanxicoService $banxico): int
    {
        try {
            $valor = $banxico->actualizar();
            $this->info("✓ Tipo de cambio actualizado: $" . number_format($valor, 4) . " MXN/USD");
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error("✗ Error al actualizar TC: " . $e->getMessage());
            return self::FAILURE;
        }
    }
}
