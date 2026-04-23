<?php

namespace App\Filament\Resources\SolicitudResource\Pages;

use App\Filament\Resources\SolicitudResource;
use App\Models\Solicitud;
use App\Models\HistorialEstado;
use Filament\Resources\Pages\CreateRecord;

class CreateSolicitud extends CreateRecord
{
    protected static string $resource = SolicitudResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generar folio automático
        $año = date('Y');
        $ultimo = Solicitud::orderBy('id', 'desc')->first();
        $num = $ultimo ? ($ultimo->id + 1) : 1;
        $data['folio'] = sprintf("VRM-%04d%s", $num, $año);

        // Registrar quién creó la solicitud
        $data['creado_por'] = auth()->id();

        return $data;
    }

    protected function afterCreate(): void
    {
        // Registrar en historial
        HistorialEstado::create([
            'solicitud_id'   => $this->record->id,
            'estado_anterior' => null,
            'estado_nuevo'   => 'nueva',
            'user_id'        => auth()->id(),
            'motivo'         => 'Solicitud creada',
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}