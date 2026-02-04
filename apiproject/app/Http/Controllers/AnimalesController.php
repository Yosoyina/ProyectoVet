<?php

namespace App\Http\Controllers;

use App\Models\animales;
use App\Http\Resources\animalresorce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnimalesController extends Controller
{

    //Esta funcion nos mustra una lista del los animales

    public function index()
    {
        $animales = animales::all();
        return animalresorce::collection($animales);
    }


    /*
     * Almacena un nuevo animales en la base de datos.
    */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:25',
            'tipo' => 'required|in:perro,gato,hámster,conejo',
            'peso' => 'nullable|numeric|min:0',
            'enfermedad' => 'nullable|string|max:25',
            'comentarios' => 'nullable|string',
            'dueno_id' => 'required|exists:duenos,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $animal = animales::create($validator->validated());
        
        return new animalresorce($animal);
    }

    // Esta funcion lo que hace es mostarnos el animales especifico por la ID

    public function show(string $id)
    {
        $animal = animales::find($id);

        if (!$animal) {
            return response()->json([
                'message' => 'Animal no encontrado'
            ], 404);
        }

        return new animalresorce($animal);
    }

    //Esta funcion nos actualiza lso datos del animales

    public function update(Request $request, string $id)
    {
        $animal = animales::find($id);

        if (!$animal) {
            return response()->json([
                'message' => 'Animal no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:25',
            'tipo' => 'sometimes|required|in:perro,gato,hámster,conejo',
            'peso' => 'nullable|numeric|min:0',
            'enfermedad' => 'nullable|string|max:255',
            'comentarios' => 'nullable|string',
            'dueno_id' => 'sometimes|required|exists:duenos,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $animal->update($validator->validated());

        return new animalresorce($animal);
    }

    // En esta funcion eliminamos el animal por su ID

    public function destroy(string $id)
    {
        $animal = animales::find($id);

        if (!$animal) {
            return response()->json([
                'message' => 'Animal no encontrado'
            ], 404);
        }

        $animal->delete();

        return response()->json([
            'message' => 'Animal eliminado correctamente'
        ], 200);
    }
}