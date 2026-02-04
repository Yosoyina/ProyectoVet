<?php

namespace App\Http\Controllers;

use App\Models\duenos;
use App\Models\animales;
use App\Http\Resources\duenoresorce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DuenoController extends Controller
{

    //Esta funcion nos mustra una lista del los dueños
    
    public function index()
    {
        $duenos = duenos::all();
        return duenoresorce::collection($duenos);
    }

    
    //Esta funcion almacena un nuevo dueño en la base de datos.
    

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:25',
            'apellido' => 'required|string|max:25',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $dueno = duenos::create($validator->validated());

        return new duenoresorce($dueno);
    }

    // Esta funcion lo que hace es mostarnos el dueño especifico por la ID

    public function show(string $id)
    {
        $dueno = duenos::find($id);

        if (!$dueno) {
            return response()->json([
                'message' => 'Dueño no encontrado'
            ], 404);
        }

        return new duenoresorce($dueno);
    }


    //Esta funcion nos actualiza los datos del dueño

    public function update(Request $request, string $id)
    {
        $dueno = duenos::find($id);

        if (!$dueno) {
            return response()->json([
                'message' => 'Dueño no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:25',
            'apellido' => 'sometimes|required|string|max:25',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $dueno->update($validator->validated());

        return new duenoresorce($dueno);
    }

    // En esta funcion eliminamos el dueño por su ID


    public function destroy(string $id)
    {
        $dueno = duenos::find($id);

        if (!$dueno) {
            return response()->json([
                'message' => 'Dueño no encontrado'
            ], 404);
        }

        // Eliminar el dueño (el cascade eliminará los animales automáticamente)
        $dueno->delete();

        return response()->json([
            'message' => 'Dueño eliminado correctamente'
        ], 200);
    }
}