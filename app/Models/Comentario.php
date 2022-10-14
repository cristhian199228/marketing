<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    use HasFactory;

    protected $table = "comentarios";
    protected $guarded = [];

    public function solicitud() {
        return $this->belongsTo(Solicitud::class, 'id_solicitud', 'id_solicitud');
    }

}
