<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Vehicle::query();

        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        $vehicles = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $vehicles->items(),
            'meta' => [
                'current_page' => $vehicles->currentPage(),
                'last_page' => $vehicles->lastPage(),
                'per_page' => $vehicles->perPage(),
                'total' => $vehicles->total(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:car,motorcycle',
            'brand' => 'nullable|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:2000',
            'price' => 'required|numeric|min:0.01',
            'color' => 'required|string|max:255',
            'mileage' => 'required|integer|min:0',
        ]);

        $validated['brand'] = $validated['brand'] ?? 'Honda';

        $vehicle = Vehicle::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Veículo criado com sucesso.',
            'data' => $vehicle,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        return response()->json([
            'success' => true,
            'data' => $vehicle,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'type' => 'sometimes|required|in:car,motorcycle',
            'brand' => 'sometimes|nullable|string|max:255',
            'model' => 'sometimes|required|string|max:255',
            'year' => 'sometimes|required|integer|min:2000',
            'price' => 'sometimes|required|numeric|min:0.01',
            'color' => 'sometimes|required|string|max:255',
            'mileage' => 'sometimes|required|integer|min:0',
        ]);

        $vehicle->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Veículo atualizado com sucesso.',
            'data' => $vehicle,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return response()->json([
            'success' => true,
            'message' => 'Veículo removido com sucesso.',
        ], 204);
    }
}
