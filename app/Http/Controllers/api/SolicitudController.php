<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Proyecto;
use App\Models\Solicitud;
use App\Models\SolicitudEstado;
use App\Models\SolicitudPrioridad;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class SolicitudController extends Controller
{
    private static $imgExtensions = ['png', 'jpg', 'bmp', 'jpeg'];

    public function solicitudes(Request $request) {
        return Solicitud::with('prioridad','estado','proyecto','adjuntos')
            ->with('solicitante:id,nombres,apellidos')
            ->with('encargado:id,nombres,apellidos')
            ->select('*',DB::raw('altura * ancho as medidas'))
            ->withCount(['comentarios as comentarios_no_leidos_count' => function ($q) use ($request) {
                $q->where('comentarios.id_usuario', '<>', $request->user()->id);
            }])
            ->whereBetween(DB::raw('date(created_at)'), [
                $request->fecha_inicio,
                $request->fecha_fin
            ]);
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Solicitud::class);

        $solicitudes = $this->aplicarFiltrosYOrdenar($this->solicitudes($request), $request);

        return response()->json($solicitudes);
    }

    public function solicitudesPorProyecto(Request $request) {

        $collection = $this->solicitudes($request)
            ->whereHas('proyecto', function (Builder $q) use ($request) {
                $q->where('id_encargado', $request->user()->id);
            });

        $solicitudes = $this->aplicarFiltrosYOrdenar($collection, $request);

        return response()->json($solicitudes);
    }

    public function show(Solicitud $solicitud) {
        //$solicitud->load('adjuntos');
        $this->authorize('view', $solicitud);
        return $solicitud;
    }

    public function showImage($path) {
        $path = "marketing/" . $path;
        $file = Storage::disk('ftp')->get($path);

        return Image::make($file)->response();
    }

    public function update(Solicitud $solicitud,Request $request) {

        $data = $request->validate([
            'id_solicitante' => 'required',
            'id_encargado' => '',
            'id_prioridad' => '',
            'id_proyecto' => 'required',
            'fecha_entrega' => '',
            'objetivo' => 'required',
            'descripcion' => 'required',
            'contenido' => 'required',
            'altura' => '',
            'ancho' => '',
            'unidad_medida' => '',
            'formato' => '',
            'id_estado' => 'required',
        ]);

        $this->authorize('update', $solicitud);

        if ($solicitud->id_estado === 4 && ($data['fecha_entrega'] >= now()->format('Y-m-d'))) {
            $data['id_estado'] = 3;
        }

        if ($request->has('image')) {
            if ($solicitud->path) {
                Storage::disk('ftp')->delete("marketing/{$solicitud->path}");
            }
            $data['path'] = $this->guardarImagen($request);
        }

        $solicitud->update($data);

        return response([
            'message' => 'Solicitud actualizada correctamente'
        ]);
    }

    public function solicitudesPorUsuario(Request $request) {

        $collection = $request->user()->solicitudes()           
            ->with('prioridad','estado','proyecto','adjuntos')
            ->select('*',DB::raw('altura * ancho as medidas'))
            ->withCount(['comentarios as comentarios_no_leidos_count' => function ($q) use ($request) {
                $q->where('comentarios.id_usuario', '<>', $request->user()->id);
            }])
            ->whereBetween(DB::raw('date(created_at)'), [
                $request->fecha_inicio,
                $request->fecha_fin
            ]);

        $solicitudes = $this->aplicarFiltrosYOrdenar($collection, $request);

        return response()->json($solicitudes);
    }

    public function store(Request $request) {

        $data = $request->validate([
            'id_proyecto' => 'required',
            'objetivo' => 'required',
            //'id_prioridad' => 'required',
            //'fecha_entrega' => 'required|date_format:Y-m-d',
            'descripcion' => 'required',
            'contenido' => 'required',
            'altura' => '',
            'ancho' => '',
            'unidad_medida' => '',
            'formato' => ''
        ]);

        $data['id_solicitante'] = $request->user()->id;

        if ($request->has('image')) {
            $data['path'] = $this->guardarImagen($request);
        }

        Solicitud::create($data);

        return response([
            'message' => 'Solicitud creada correctamente'
        ]);
    }

    public function destroy(Solicitud $solicitud) {
        $this->authorize('delete', $solicitud);

        if ($solicitud->path) {
            Storage::disk('ftp')->delete("marketing/{$solicitud->path}");
        }
        $solicitud->delete();

        return response()->json([
            'message' => 'Solicitud eliminada correctamente'
        ]);
    }

    private function aplicarFiltrosYOrdenar($collection, Request $request) {

        if ($request->has('codigo')) {
            $codigo = (int) str_replace('REQ', '', $request->get('codigo'));
            $collection->where('id_solicitud', $codigo);
        }

        if ($request->has('proyecto')) {
            $collection->whereIn('id_proyecto', $request->get('proyecto'));
        }

        if ($request->has('formato')) {
            $collection->where('formato', $request->get('formato'));
        }

        if ($request->has('prioridad')) {
            $collection->whereIn('id_prioridad', $request->get('prioridad'));
        }

        if ($request->has('estado')) {
            $collection->whereIn('id_estado', $request->get('estado'));
        }

        if ($request->has('solicitante')) {
            $collection->whereHas('solicitante', function ($q) use ($request) {
                $q->search($request->get('solicitante'));
            });
        }

        if ($request->has('encargado')) {
            $collection->whereHas('encargado', function ($q) use ($request) {
                $q->search($request->get('encargado'));
            });
        }

        if ($request->has('sortBy')) {
            for ($i = 0; $i < count($request->sortBy); $i++) {
                $direction = $request->boolean("sortDesc.$i") ? 'DESC' : 'ASC';
                $column = $request->sortBy[$i];
                $collection->orderBy($column, $direction);
            }
        } else {
            $collection->latest();
        }

        //dd($collection->simplePaginate($request->itemsPerPage ?? 10));

        $solicitudes = $collection->paginate($request->itemsPerPage ?? 10);
        $pagina = $solicitudes->currentPage();
        $total = $solicitudes->total();
        $contador = $total - ($request->itemsPerPage * ($pagina - 1));

        foreach ($solicitudes as $solicitud) {
            /*$imagenes = [];
            $otros = [];
            foreach ($solicitud->adjuntos as $file) {
                $ext = pathinfo($file->path, PATHINFO_EXTENSION);
                if (in_array($ext, self::$imgExtensions)) {
                    $imagenes[] = $file->path;
                } else {
                    $otros[] = $file->path;
                }
            }
            $solicitud->imagenes = $imagenes;
            $solicitud->otros = $otros;*/
            $solicitud->contador = $contador;
            $solicitud->fecha = $solicitud->created_at->format('d/m/Y');
            $contador--;
        }

        return $solicitudes;
    }

    public function guardarImagen(Request $request) {
        if ($request->image && strlen($request->image) > 2000) {
            $image = $request->image;  // your base64 encoded
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $uniqueName = md5(Str::random(10) . time()) . '.' .'png';;
            Storage::disk('ftp')->put("marketing/$uniqueName", base64_decode($image));
            return $uniqueName;
        }
        return null;
    }

    public function prioridades(){
        return SolicitudPrioridad::all();
    }

    public function estados() {
        return SolicitudEstado::all();
    }

    public function proyectos() {
        return Proyecto::all();
    }
}
