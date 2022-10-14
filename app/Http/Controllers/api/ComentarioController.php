<?php

namespace App\Http\Controllers\api;

use App\Events\NewComment;
use App\Models\Comentario;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ComentarioController extends Controller
{
    public function index(Solicitud $solicitud) {

        $this->authorize('viewAny', [Comentario::class, $solicitud]);

        return $solicitud->comentarios()
            ->join('solicitudes', 'comentarios.id_solicitud', '=', 'solicitudes.id_solicitud')
            ->join('users', 'comentarios.id_usuario', '=', 'users.id')
            ->select('comentarios.*')
            ->addSelect(DB::raw("CONCAT(users.nombres, ' ', apellidos) as usuario"))
            ->latest()->get();
    }

    public function store(Request $request, Solicitud $solicitud) {
        $data = $request->validate([
            'id_solicitud' => 'required',
            'mensaje' => 'required'
        ]);

        $this->authorize('create', [Comentario::class, $solicitud]);

        $data['id_usuario'] = $request->user()->id;
        $comentario = Comentario::create($data);
        broadcast(new NewComment($comentario))->toOthers();

        return response([
            'message' => 'Comentario enviado correctamente'
        ]);
    }

    public function update(Request $request, Comentario $comentario) {
        $comentario->update($request->only(['mensaje','id_usuario']));
    }

    public function delete(Comentario $comentario) {
        $comentario->delete();
        return response([
            'message' => 'Comentario eliminado correctamente'
        ]);
    }

    public function comentariosNoLeidosPorUsuario(Request $request){
        return Comentario::query()
            ->join('solicitudes', 'comentarios.id_solicitud', '=', 'solicitudes.id_solicitud')
            ->join('users', 'comentarios.id_usuario', '=', 'users.id')
            ->where('comentarios.id_usuario', '<>', $request->user()->id)
            ->select('comentarios.*')
            ->addSelect(DB::raw("CONCAT(users.nombres, ' ', apellidos) as usuario"))
            ->latest()->limit(5)->get();
    }

    public function nroComentariosPorUsuario(Request $request){
        return $request->user()->comentarios()->count();
    }
}
