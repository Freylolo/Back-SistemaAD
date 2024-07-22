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
        $alicuotas = Alicuota::with('residente')->get();
        return response()->json($alicuotas->map(function ($alicuota) {
        $alicuota->fecha = $alicuota->fecha->format('Y-m-d'); // Formato YYYY-MM-DD
        return $alicuota;
    }), 200);
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
            'mes' => 'required|in:Enero,Febrero,Marzo,Abril,Mayo,Junio,Julio,Agosto,Septiembre,Octubre,Noviembre,Diciembre',
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
            'mes' => 'required|in:Enero,Febrero,Marzo,Abril,Mayo,Junio,Julio,Agosto,Septiembre,Octubre,Noviembre,Diciembre',
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

    // Método para marcar una alícuota como pagada y recalcular la deuda
    public function marcarPago($id_alicuota)
    {
        $alicuota = Alicuota::findOrFail($id_alicuota);
        $alicuota->pagado = true;
        $alicuota->save();

        // Calcular la deuda total restante para el residente
        $totalAdeudado = Alicuota::where('id_residente', $alicuota->id_residente)
            ->where('pagado', false)
            ->sum('monto_por_cobrar');

        return response()->json(['message' => 'Pago registrado exitosamente', 'totalAdeudado' => $totalAdeudado], 200);
    }


}
