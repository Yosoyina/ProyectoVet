<?php

namespace App\Http\Controllers;

use App\Models\duenos;
use App\Models\animales;
use App\Http\Resources\duenoresorce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DuenoController extends Controller
{
    public function index()
    {
        $duenos = duenos::all();
        return duenoresorce::collection($duenos);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
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

    public function update(Request $request, string $id)
    {
        $dueno = duenos::find($id);

        if (!$dueno) {
            return response()->json([
                'message' => 'Dueño no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:255',
            'apellido' => 'sometimes|required|string|max:255',
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