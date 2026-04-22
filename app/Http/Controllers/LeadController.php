<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class LeadController extends Controller
{
    #[OA\Get(
        path: '/leads',
        summary: 'Listar leads',
        description: 'Retorna uma lista paginada de leads. Requer autenticação.',
        tags: ['Leads'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de leads',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Lead')),
                        new OA\Property(property: 'meta', type: 'object'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Não autorizado'),
        ]
    )]
    public function index()
    {
        $leads = Lead::with('vehicle')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $leads->items(),
            'meta' => [
                'current_page' => $leads->currentPage(),
                'last_page' => $leads->lastPage(),
                'per_page' => $leads->perPage(),
                'total' => $leads->total(),
            ],
        ]);
    }

    #[OA\Post(
        path: '/leads',
        summary: 'Criar lead',
        description: 'Cria um novo lead de interesse em um veículo. Endpoint público.',
        tags: ['Leads'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'phone', 'vehicle_id'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'João Silva'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'joao@teste.com'),
                    new OA\Property(property: 'phone', type: 'string', example: '(11) 99999-9999'),
                    new OA\Property(property: 'vehicle_id', type: 'integer', example: 1),
                    new OA\Property(property: 'message', type: 'string', example: 'Tenho interesse neste veículo', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Lead criado com sucesso'),
            new OA\Response(response: 422, description: 'Erro de validação'),
        ]
    )]
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

        return response()->json([
            'success' => true,
            'message' => 'Lead criado com sucesso.',
            'data' => $lead->load('vehicle'),
        ], 201);
    }

    #[OA\Get(
        path: '/leads/{id}',
        summary: 'Detalhes do lead',
        description: 'Retorna os detalhes de um lead específico. Requer autenticação.',
        tags: ['Leads'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID do lead', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Detalhes do lead'),
            new OA\Response(response: 401, description: 'Não autorizado'),
            new OA\Response(response: 404, description: 'Lead não encontrado'),
        ]
    )]
    public function show(Lead $lead)
    {
        return response()->json([
            'success' => true,
            'data' => $lead->load('vehicle'),
        ]);
    }

    #[OA\Get(
        path: '/vehicles/{id}/leads',
        summary: 'Leads por veículo',
        description: 'Retorna os leads associados a um veículo específico. Requer autenticação.',
        tags: ['Leads'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID do veículo', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de leads do veículo',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Lead')),
                        new OA\Property(property: 'meta', type: 'object'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Não autorizado'),
            new OA\Response(response: 404, description: 'Veículo não encontrado'),
        ]
    )]
    public function byVehicle(Vehicle $vehicle)
    {
        $leads = $vehicle->leads()->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $leads->items(),
            'meta' => [
                'current_page' => $leads->currentPage(),
                'last_page' => $leads->lastPage(),
                'per_page' => $leads->perPage(),
                'total' => $leads->total(),
            ],
        ]);
    }
}
