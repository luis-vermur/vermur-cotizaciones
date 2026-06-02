<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Actualizar tipo de cambio USD/MXN desde Banxico cada día a las 9am
Schedule::command('tc:actualizar')->dailyAt('09:00');
