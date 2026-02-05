<?php

namespace App\Http\Controllers;

use App\Models\duenos;
use App\Models\animales;
use App\Http\Resources\duenoresorce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class DuenoController extends Controller
{
    /**
     * Esta funcion nos muestra una lista de los dueños
     */
    public function index()
    {
        try {
            $duenos = duenos::all();
            return duenoresorce::collection($duenos);
            
        } catch (Exception $e) {
            Log::error('Error al obtener lista de dueños: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al obtener la lista de dueños',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Esta funcion almacena un nuevo dueño en la base de datos
     */
    public function store(Request $request)
    {
        try {
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
            
        } catch (Exception $e) {
            Log::error('Error al crear dueño: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al crear el dueño',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Esta funcion muestra el dueño especifico por la ID
     */
    public function show(string $id)
    {
        try {
            $dueno = duenos::find($id);

            if (!$dueno) {
                return response()->json([
                    'message' => 'Dueño no encontrado'
                ], 404);
            }

            return new duenoresorce($dueno);
            
        } catch (Exception $e) {
            Log::error('Error al obtener dueño: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al obtener el dueño',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Esta funcion actualiza los datos del dueño
     */
    public function update(Request $request, string $id)
    {
        try {
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
            
        } catch (Exception $e) {
            Log::error('Error al actualizar dueño: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al actualizar el dueño',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * En esta funcion eliminamos el dueño por su ID
     * IMPORTANTE: También elimina TODOS los animales asociados (CONSISTENCIA DE DATOS)
     */
    public function destroy(string $id)
    {
        try {
            $dueno = duenos::find($id);

            if (!$dueno) {
                return response()->json([
                    'message' => 'Dueño no encontrado'
                ], 404);
            }

            // Contar animales antes de eliminar (para la respuesta)
            $animalesCount = animales::where('dueno_id', $id)->count();

            // La base de datos eliminará automáticamente los animales por CASCADE
            $dueno->delete();

            return response()->json([
                'message' => 'Dueño eliminado correctamente',
                'animales_eliminados' => $animalesCount
            ], 200);
            
        } catch (Exception $e) {
            Log::error('Error al eliminar dueño: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al eliminar el dueño',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}