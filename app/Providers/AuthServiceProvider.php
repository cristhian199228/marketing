<?php

namespace App\Providers;

use App\Models\Comentario;
use App\Models\Solicitud;
use App\Policies\ComentarioPolicy;
use App\Policies\SolicitudPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Solicitud::class => SolicitudPolicy::class,
        Comentario::class => ComentarioPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
