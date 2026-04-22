<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Lead::with('vehicle')->paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'vehicle_id' => 'required|exists:vehicles,id',
            'message' => 'nullable|string',
        ]);

        $lead = Lead::create($validated);

        return response()->json($lead->load('vehicle'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Lead $lead)
    {
        return $lead->load('vehicle');
    }

    /**
     * List leads for a specific vehicle.
     */
    public function byVehicle(Vehicle $vehicle)
    {
        return $vehicle->leads()->paginate(15);
    }
}
