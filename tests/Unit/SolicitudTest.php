<?php

namespace Tests\Unit;

use App\Models\Solicitud;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
//use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class SolicitudTest extends TestCase
{
    private function getUser() {
        return User::factory()->make();
    }

    public function test_solicitud_completada_cannot_be_deleted() {
        $solicitud = Solicitud::factory()->make();
        dd($this->getUser()->id);
    }
}
