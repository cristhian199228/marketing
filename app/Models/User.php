<?php

namespace App\Models;

use AjCastro\Searchable\Searchable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Searchable;

    protected $searchable = [
        'columns' => [
            'users.nombres',
            'users.apellidos',
            'nombre_completo' => 'CONCAT(users.nombres, " ", users.apellidos)'
        ],
    ];

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['full_name'];

    public function solicitudes() {
        return $this->hasMany(Solicitud::class, 'id_solicitante');
    }

    public function getFullNameAttribute() {
        return "{$this->nombres} {$this->apellidos}";
    }

    public function comentarios()
    {
        return $this->hasManyThrough(Comentario::class, Solicitud::class,
            'id_solicitante',
            'id_solicitud',
            'id',
            'id_solicitud'
        );
    }

    public function proyecto() {
        return $this->hasOne(Proyecto::class, 'id_encargado', 'id');
    }

}
