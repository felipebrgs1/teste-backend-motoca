<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalVehicles = Vehicle::count();
        $totalLeads = Lead::count();

        $mostRequestedVehicle = Vehicle::select('vehicles.*')
            ->selectRaw('COUNT(leads.id) as leads_count')
            ->leftJoin('leads', 'vehicles.id', '=', 'leads.vehicle_id')
            ->groupBy('vehicles.id')
            ->orderByDesc('leads_count')
            ->first();

        return response()->json([
            'total_vehicles' => $totalVehicles,
            'total_leads' => $totalLeads,
            'most_requested_vehicle' => $mostRequestedVehicle ? $mostRequestedVehicle->brand . ' ' . $mostRequestedVehicle->model : null,
        ]);
    }
}
