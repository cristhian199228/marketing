<?php

namespace App\Services;

use App\Models\Solicitud;

class SolicitudService
{
    /**
     * @var Solicitud
     */
    private $solicitud;

    public function __construct(Solicitud $solicitud)
    {
        $this->solicitud = $solicitud;
    }


}