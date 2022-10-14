<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudEstado extends Model
{
    use HasFactory;

    protected $table = "solicitud_estados";
    protected $primaryKey= "id_estado";
}
