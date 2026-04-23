<?php

namespace App\Filament\Resources\CotizacionResource\Pages;

use App\Filament\Resources\CotizacionResource;
use App\Models\Cotizacion;
use App\Models\HistorialEstado;
use App\Models\Solicitud;
use App\Services\CotizacionCalculator;
use Filament\Resources\Pages\CreateRecord;

class CreateCotizacion extends CreateRecord
{
    protected static string $resource = CotizacionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $solicitudId = $data['solicitud_id'];
        $version     = $data['version'] ?? 1;

        $data['folio_coti'] = CotizacionCalculator::generarFolioCoti($solicitudId, $version);
        $data['creado_por'] = auth()->id();

        return $data;
    }

    protected function afterCreate(): void
    {
        // Cambiar estado de la solicitud a cotizada
        $solicitud = Solicitud::find($this->record->solicitud_id);

        if ($solicitud && $solicitud->puedeTransicionarA('cotizada')) {
            $estadoAnterior = $solicitud->estado;
            $solicitud->update(['estado' => 'cotizada']);

            HistorialEstado::create([
                'solicitud_id'    => $solicitud->id,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo'    => 'cotizada',
                'user_id'         => auth()->id(),
                'motivo'          => 'Cotización creada: ' . $this->record->folio_coti,
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}