<?php

namespace App\Http\Controllers;

use App\Models\animales;
use App\Http\Resources\animalresorce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\Log;

class AnimalesController extends Controller
{
    /**
     * Esta funcion nos muestra una lista de los animales
     */
    public function index()
    {
        try {
            $animales = animales::all();
            return animalresorce::collection($animales);
            
        } catch (Exception $e) {
            Log::error('Error al obtener lista de animales: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al obtener la lista de animales',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Almacena un nuevo animal en la base de datos
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:25',
                'tipo' => 'required|in:perro,gato,hámster,conejo',
                'peso' => 'nullable|numeric|min:0',
                'enfermedad' => 'nullable|string|max:255',
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
            
        } catch (Exception $e) {
            Log::error('Error al crear animal: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al crear el animal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Esta funcion muestra el animal especifico por la ID
     */
    public function show(string $id)
    {
        try {
            $animal = animales::find($id);

            if (!$animal) {
                return response()->json([
                    'message' => 'Animal no encontrado'
                ], 404);
            }

            return new animalresorce($animal);
            
        } catch (Exception $e) {
            Log::error('Error al obtener animal: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al obtener el animal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Esta funcion actualiza los datos del animal
     */
    public function update(Request $request, string $id)
    {
        try {
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
            
        } catch (Exception $e) {
            Log::error('Error al actualizar animal: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al actualizar el animal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * En esta funcion eliminamos el animal por su ID
     */
    public function destroy(string $id)
    {
        try {
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
            
        } catch (Exception $e) {
            Log::error('Error al eliminar animal: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al eliminar el animal',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}