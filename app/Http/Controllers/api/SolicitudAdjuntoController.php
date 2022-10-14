<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Solicitud;
use App\Models\SolicitudAdjunto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use ZipArchive;
use Illuminate\Support\Facades\File;

class SolicitudAdjuntoController extends Controller
{
    public function show(SolicitudAdjunto $adjunto) {
        //dd($adjunto);
        $path = "marketing/" . $adjunto->path;
        $file = Storage::disk('ftp')->get($path);

        return Image::make($file)->response();
    }

    public function delete(SolicitudAdjunto $adjunto) {
        $adjunto->delete();

        return response([
            'message' => 'Foto eliminada correctamente'
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'id_solicitud' => 'required',
            'photos' => 'required'
        ]);

        foreach ($request->file('photos') as $file) {
            $fileName = Str::random(10);
            $uniqueName = md5($fileName . time()) . '.' . $file->extension();
            Storage::disk('ftp')->put("marketing/$uniqueName", file_get_contents($file));
            $adjunto = new SolicitudAdjunto();
            $adjunto->id_solicitud = $request->get('id_solicitud');
            $adjunto->path = $uniqueName;
            $adjunto->save();
        }

        return response([
            'message' => 'Fotos subidas correctamente'
        ]);
    }

    public function download(SolicitudAdjunto $solicitudAdjunto) {
//        dd($solicitudAdjunto);
        $tempPath = "temp/";
        $tempFilePath = $tempPath . $solicitudAdjunto->path;
        Storage::makeDirectory($tempPath);
        Storage::disk('local')->put(
            $tempFilePath,
            Storage::disk('ftp')->get('marketing/'. $solicitudAdjunto->path)
        );
        return response()->download(Storage::path($tempFilePath))->deleteFileAfterSend(true);
    }

    public function downloadAll(Solicitud $solicitud) {

        $id = $solicitud->id_solicitud;

        $zipFileName = "zipFile$id.zip";
        $zipTempFolder = "zips_temporal";

        $zipTempPath= "$zipTempFolder/$zipFileName";

        $filesTempPath = "zip_temporal_files/$id/";

        //Crear carpeta temporal para almacenar los archivos
        Storage::makeDirectory($filesTempPath);
        //Crear carpeta para almacenar zips
        Storage::makeDirectory($zipTempFolder);

        $zip = new ZipArchive();
        $res = $zip->open(Storage::path($zipTempPath), ZipArchive::CREATE);

        if ($res === TRUE) {
            //GUARDAR FOTOS DE EVIDENCIAS EN STORAGE LOCAL TEMPORALMENTE
            foreach ($solicitud->adjuntos as $foto) {
                try {
                    $file = Storage::disk('ftp')->get('marketing/'. $foto->path);
                    Storage::put( $filesTempPath. $foto->path, $file);
                } catch (FileNotFoundException $exception) {

                }
            }

            //AÑADIR LOS ARCHIVOS DEL STORAGE LOCAL TEMPORAL AL ZIP
            $files = File::files(Storage::path($filesTempPath));
            foreach ($files as $key => $value){
                $relativeName = basename($value);
                $zip->addFile($value, $relativeName);
            }
            $zip->close();

            //ELIMINAR CARPETA TEMPORAL DE FOTOS Y PDFS DEL STORAGE
            Storage::deleteDirectory($filesTempPath);
        }
        //DESCARGAR ZIP Y DESPUES ELIMINAR
        return response()->download(Storage::path($zipTempPath))->deleteFileAfterSend(true);
    }
}
