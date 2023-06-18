<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Articulo;
use File;
use App\Events\articuloCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

//use Illuminate\Support\Facades\Auth;

class PhotoController extends Controller
{
    public function updatephoto(Request $request, $id): JsonResponse
    {
        $articulo = Articulo::find($id);
        $filename = $articulo->foto_articulo;
        if ($filename != null) {
            $path = public_path("/images/$filename");
            File::delete($path);
        }
        if (str_contains($request->file('foto_articulo')->getClientOriginalName(), '.png')) {
            $name_foto =  $articulo->nombre_articulo . '.' . 'png';
        } elseif (str_contains($request->file('foto_articulo')->getClientOriginalName(), '.jpg')) {
            $name_foto =  $articulo->nombre_articulo . '.' . 'jpg';
        }
        $request->foto_articulo->move(public_path('images'), $name_foto);
        $articulo->foto_articulo = $name_foto;
        $articulo->save();
        try {
            event(new articuloCreated($articulo));
        } catch (\Throwable $th) {
        }
        return response()->json(["message" => "success"], 200);
    }
}
