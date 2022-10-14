<?php

namespace App\Policies;

use App\Models\Proyecto;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SolicitudPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->is_admin;
    }

    public function verSolicitudesPorProyecto(User $user) {
        return $user->proyecto;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Solicitud  $solicitud
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Solicitud $solicitud)
    {
        return $user->id === $solicitud->id_solicitante;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        dd($user);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Solicitud  $solicitud
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Solicitud $solicitud)
    {
        return ($user->is_admin && $solicitud->id_estado !== 5 )
            || ($user->id === $solicitud->id_solicitante && $solicitud->id_estado !== 5);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Solicitud  $solicitud
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Solicitud $solicitud)
    {
        return $user->is_admin || ($solicitud->id_estado === 1 && $solicitud->id_solicitante === $user->id);
    } 

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Solicitud  $solicitud
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Solicitud $solicitud)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Solicitud  $solicitud
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Solicitud $solicitud)
    {
        //
    }
}
