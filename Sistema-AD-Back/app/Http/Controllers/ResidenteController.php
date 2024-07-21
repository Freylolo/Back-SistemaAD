<?php

namespace App\Http\Controllers;
use App\Models\Residente;
use Illuminate\Support\Facades\Hash;



use Illuminate\Http\Request;

class ResidenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Residente::all(), 200);
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
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'cedula' => 'required|string|max:20|unique:residentes',
            'sexo' => 'required|string|max:10',
            'perfil' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'solar' => 'sometimes|required|string|max:255',
            'm2' => 'sometimes|required|numeric|min:0',
            'celular' => 'required|string|max:20',
            'correo_electronico' => 'required|string|email|max:255|unique:residentes',
            'cantidad_vehiculos' => 'required|integer',
            'vehiculo1_placa' => 'nullable|string|max:20',
            'vehiculo1_observaciones' => 'nullable|string',
            'vehiculo2_placa' => 'nullable|string|max:20',
            'vehiculo2_observaciones' => 'nullable|string',
            'vehiculo3_placa' => 'nullable|string|max:20',
            'vehiculo3_observaciones' => 'nullable|string',
            'observaciones' => 'nullable|string',
        ]);

        $residente = Residente::create($request->all());
        return response()->json($residente, 201);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $residente = Residente::findOrFail($id);
        return response()->json($residente, 200);
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
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'apellido' => 'sometimes|required|string|max:255',
            'cedula' => 'sometimes|required|string|max:20|unique:residentes,cedula,' . $id . ',id_residente',
            'sexo' => 'sometimes|required|string|max:10',
            'perfil' => 'sometimes|required|string|max:255',
            'direccion' => 'sometimes|required|string|max:255',
            'solar' => 'sometimes|required|string|max:255',
            'sometimes|required|numeric|min:0',
            'celular' => 'sometimes|required|string|max:20',
            'correo_electronico' => 'sometimes|required|string|email|max:255|unique:residentes,correo_electronico,' . $id . ',id_residente',
            'cantidad_vehiculos' => 'sometimes|required|integer',
            'vehiculo1_placa' => 'nullable|string|max:20',
            'vehiculo1_observaciones' => 'nullable|string',
            'vehiculo2_placa' => 'nullable|string|max:20',
            'vehiculo2_observaciones' => 'nullable|string',
            'vehiculo3_placa' => 'nullable|string|max:20',
            'vehiculo3_observaciones' => 'nullable|string',
            'observaciones' => 'nullable|string',
        ]);

        $residente = Residente::findOrFail($id);
        $residente->update($request->all());
        return response()->json($residente, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Residente::destroy($id);
        return response()->json(null, 204);
    }

    public function checkCedula($cedula)
    {
    $exists = Residente::where('cedula', $cedula)->exists();
    return response()->json(['exists' => $exists]);
    }

    public function checkCorreo($correo_electronico)
    {
        $exists = Residente::where('correo_electronico', $correo_electronico)->exists();
        return response()->json(['exists' => $exists]);
    }
}
