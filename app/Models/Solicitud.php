<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = "solicitudes";
    protected $primaryKey = "id_solicitud";
    protected $guarded = [];

    public function solicitante() {
        return $this->belongsTo(User::class, 'id_solicitante', 'id');
    }

    public function encargado() {
        return $this->belongsTo(User::class, 'id_encargado', 'id');
    }

    public function prioridad() {
        return $this->belongsTo(SolicitudPrioridad::class, 'id_prioridad', 'id');
    }

    public function estado() {
        return $this->belongsTo(SolicitudEstado::class, 'id_estado', 'id_estado');
    }

    public function proyecto() {
        return $this->belongsTo(Proyecto::class, 'id_proyecto', 'id');
    }

    public function adjuntos() {
        return $this->hasMany(SolicitudAdjunto::class, 'id_solicitud', 'id_solicitud');
    }

    public function comentarios() {
        return $this->hasMany(Comentario::class, 'id_solicitud', 'id_solicitud');
    }
}
