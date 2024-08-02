<?php

namespace App\Http\Controllers;
use App\Models\Residente;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;


use Illuminate\Http\Request;

class ResidenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $residentes = Residente::with('usuario')->get(); 
        return response()->json($residentes);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validatedData = $request->validate([
        'id_usuario' => 'required|exists:usuarios,id_usuario',
        'cedula' => 'required|string|max:20|unique:residentes',
        'sexo' => 'required|string|max:10',
        'perfil' => 'required|string|max:255',
        'direccion' => 'required|string|max:255',
        'solar' => 'sometimes|required|string|max:255',
        'm2' => 'sometimes|required|numeric|min:0',
        'celular' => 'required|string|max:20',
        'cantidad_vehiculos' => 'required|integer',
        'vehiculo1_placa' => 'nullable|string|max:20',
        'vehiculo1_observaciones' => 'nullable|string',
        'vehiculo2_placa' => 'nullable|string|max:20',
        'vehiculo2_observaciones' => 'nullable|string',
        'vehiculo3_placa' => 'nullable|string|max:20',
        'vehiculo3_observaciones' => 'nullable|string',
        'observaciones' => 'nullable|string',
    ]);

    $user = Usuario::find($validatedData['id_usuario']);
    
    if (!$user) {
        return response()->json(['error' => 'Usuario no encontrado'], 404);
    }

     try {
        $residente = Residente::create($validatedData);
        return response()->json([
            'residente' => $residente,
            'usuario' => $user
         ], 201);
     } catch (\Exception $e) {
         return response()->json(['error' => 'Error al crear el residente', 'details' => $e->getMessage()], 500);
     }
   }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $residente = Residente::with('usuario') // Asegúrate de que la relación esté correctamente definida
        ->where('id_residente', $id)
        ->firstOrFail();

        return response()->json($residente);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    // Validar los datos de entrada
    $validatedData = $request->validate([
        'id_usuario' => 'required|exists:usuarios,id_usuario',
        'nombre' => 'sometimes|required|string|max:255',
        'apellido' => 'sometimes|required|string|max:255',
        'cedula' => 'sometimes|required|string|max:20|unique:residentes,cedula,' . $id . ',id_residente',
        'sexo' => 'sometimes|required|string|max:10',
        'perfil' => 'sometimes|required|string|max:255',
        'direccion' => 'sometimes|required|string|max:255',
        'solar' => 'sometimes|required|string|max:255',
        'm2' => 'sometimes|required|numeric|min:0',
        'celular' => 'sometimes|required|string|max:20',
        'correo_electronico' => 'sometimes|required|string|email|max:255',
        'cantidad_vehiculos' => 'sometimes|required|integer',
        'vehiculo1_placa' => 'nullable|string|max:20',
        'vehiculo1_observaciones' => 'nullable|string',
        'vehiculo2_placa' => 'nullable|string|max:20',
        'vehiculo2_observaciones' => 'nullable|string',
        'vehiculo3_placa' => 'nullable|string|max:20',
        'vehiculo3_observaciones' => 'nullable|string',
        'observaciones' => 'nullable|string',
    ]);

    // Encontrar el residente por ID
    $residente = Residente::findOrFail($id);

    try {
        // Actualizar la información del residente
        $residente->update($validatedData);

        // Actualizar la información del usuario asociado
        $usuario = Usuario::findOrFail($residente->id_usuario);
        $usuario->update([
            'nombre' => $request->input('nombre'),
            'apellido' => $request->input('apellido'),
            'correo_electronico' => $request->input('correo_electronico'),
        ]);

        return response()->json($residente, 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al actualizar el residente', 'details' => $e->getMessage()], 500);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    try {
        // Encuentra el residente
        $residente = Residente::findOrFail($id);

        // Elimina el usuario asociado
        if ($residente->id_usuario) {
            // Elimina el usuario asociado si no hay residentes restantes con el mismo id_usuario
            Usuario::where('id_usuario', $residente->id_usuario)->delete();
        }

        // Elimina el residente
        $residente->delete();

        return response()->json(null, 204);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al eliminar el residente', 'details' => $e->getMessage()], 500);
    }
}

    

    public function checkCorreo($correo_electronico)
    {
        $exists = Residente::where('correo_electronico', $correo_electronico)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function checkCedula($cedula)
    {
    $exists = Residente::where('cedula', $cedula)->exists();
    return response()->json(['exists' => $exists]);
    }

    public function checkCelularR($celular)
    {
        $residente = Residente::where('celular', $celular)->first();
    
        if ($residente) {
            return response()->json(['exists' => true]);
        } else {
            return response()->json(['exists' => false]);
        }
    }

    public function getResidenteById($id_residente) {
        $residente = Residente::where('id_residente', $id_residente)
                               ->with('usuario') 
                               ->first();
        if (!$residente) {
            return response()->json(['error' => 'Residente no encontrado'], 404);
        }
        return response()->json($residente);
    }

    public function getResidentePorPlaca($placa)
   {
    // Transformar la placa ingresada a minúsculas y eliminar guiones
    $placa = strtolower(str_replace('-', '', $placa));

    // Buscar al residente basado en la placa del vehículo
    $residente = Residente::whereRaw('LOWER(REPLACE(vehiculo1_placa, "-", "")) = ?', [$placa])
        ->orWhereRaw('LOWER(REPLACE(vehiculo2_placa, "-", "")) = ?', [$placa])
        ->orWhereRaw('LOWER(REPLACE(vehiculo3_placa, "-", "")) = ?', [$placa])
        ->first();
    
    if ($residente) {
        return response()->json($residente);
    } else {
        return response()->json(['message' => 'Residente no encontrado'], 404);
    }
   }

}
