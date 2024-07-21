<?php

namespace App\Http\Controllers;
use App\Models\Alicuota;


use Illuminate\Http\Request;

class AlicuotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Alicuota::all(), 200);
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
            'id_residente' => 'required|integer|exists:residentes,id_residente',
            'fecha' => 'required|date',
            'mes' => 'required|string|max:255',
            'monto_por_cobrar' => 'required|numeric',
        ]);

        $alicuota = Alicuota::create($request->all());
        return response()->json($alicuota, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $alicuota = Alicuota::findOrFail($id);
        return response()->json($alicuota, 200);
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
            'id_residente' => 'sometimes|required|integer|exists:residentes,id_residente',
            'fecha' => 'sometimes|required|date',
            'mes' => 'sometimes|required|string|max:255',
            'monto_por_cobrar' => 'sometimes|required|numeric',
        ]);

        $alicuota = Alicuota::findOrFail($id);
        $alicuota->update($request->all());
        return response()->json($alicuota, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Alicuota::destroy($id);
        return response()->json(null, 204);
    }
}
