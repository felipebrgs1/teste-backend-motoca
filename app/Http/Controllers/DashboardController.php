<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class DashboardController extends Controller
{
    #[OA\Get(
        path: '/dashboard',
        summary: 'Dashboard',
        description: 'Retorna estatísticas do sistema. Requer autenticação.',
        tags: ['Dashboard'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Estatísticas do sistema',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', type: 'object',
                            properties: [
                                new OA\Property(property: 'total_vehicles', type: 'integer', example: 30),
                                new OA\Property(property: 'total_leads', type: 'integer', example: 85),
                                new OA\Property(property: 'most_requested_vehicle', type: 'string', example: 'Honda Civic', nullable: true),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Não autorizado'),
        ]
    )]
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
            'success' => true,
            'data' => [
                'total_vehicles' => $totalVehicles,
                'total_leads' => $totalLeads,
                'most_requested_vehicle' => $mostRequestedVehicle ? $mostRequestedVehicle->brand.' '.$mostRequestedVehicle->model : null,
            ],
        ]);
    }
}
