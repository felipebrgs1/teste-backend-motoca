<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class VehicleController extends Controller
{
    #[OA\Get(
        path: '/vehicles',
        summary: 'Listar veículos',
        description: 'Retorna uma lista paginada de veículos com suporte a filtros.',
        tags: ['Veículos'],
        parameters: [
            new OA\Parameter(name: 'type', in: 'query', description: 'Tipo do veículo', schema: new OA\Schema(type: 'string', enum: ['car', 'motorcycle'])),
            new OA\Parameter(name: 'max_price', in: 'query', description: 'Preço máximo', schema: new OA\Schema(type: 'number')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de veículos',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Vehicle')),
                        new OA\Property(property: 'meta', type: 'object',
                            properties: [
                                new OA\Property(property: 'current_page', type: 'integer', example: 1),
                                new OA\Property(property: 'last_page', type: 'integer', example: 3),
                                new OA\Property(property: 'per_page', type: 'integer', example: 15),
                                new OA\Property(property: 'total', type: 'integer', example: 45),
                            ]
                        ),
                    ]
                )
            ),
        ]
    )]
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

    #[OA\Post(
        path: '/vehicles',
        summary: 'Criar veículo',
        description: 'Cria um novo veículo. Requer autenticação.',
        tags: ['Veículos'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['type', 'model', 'year', 'price', 'color', 'mileage'],
                properties: [
                    new OA\Property(property: 'type', type: 'string', enum: ['car', 'motorcycle'], example: 'car'),
                    new OA\Property(property: 'brand', type: 'string', example: 'Honda'),
                    new OA\Property(property: 'model', type: 'string', example: 'Civic'),
                    new OA\Property(property: 'year', type: 'integer', example: 2024),
                    new OA\Property(property: 'price', type: 'number', example: 150000),
                    new OA\Property(property: 'color', type: 'string', example: 'Prata'),
                    new OA\Property(property: 'mileage', type: 'integer', example: 0),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Veículo criado com sucesso'),
            new OA\Response(response: 401, description: 'Não autorizado'),
            new OA\Response(response: 422, description: 'Erro de validação'),
        ]
    )]
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

    #[OA\Get(
        path: '/vehicles/{id}',
        summary: 'Detalhes do veículo',
        description: 'Retorna os detalhes de um veículo específico.',
        tags: ['Veículos'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID do veículo', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Detalhes do veículo'),
            new OA\Response(response: 404, description: 'Veículo não encontrado'),
        ]
    )]
    public function show(Vehicle $vehicle)
    {
        return response()->json([
            'success' => true,
            'data' => $vehicle,
        ]);
    }

    #[OA\Put(
        path: '/vehicles/{id}',
        summary: 'Atualizar veículo',
        description: 'Atualiza os dados de um veículo. Requer autenticação.',
        tags: ['Veículos'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID do veículo', schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'type', type: 'string', enum: ['car', 'motorcycle']),
                    new OA\Property(property: 'brand', type: 'string'),
                    new OA\Property(property: 'model', type: 'string'),
                    new OA\Property(property: 'year', type: 'integer'),
                    new OA\Property(property: 'price', type: 'number'),
                    new OA\Property(property: 'color', type: 'string'),
                    new OA\Property(property: 'mileage', type: 'integer'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Veículo atualizado com sucesso'),
            new OA\Response(response: 401, description: 'Não autorizado'),
            new OA\Response(response: 404, description: 'Veículo não encontrado'),
            new OA\Response(response: 422, description: 'Erro de validação'),
        ]
    )]
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

    #[OA\Delete(
        path: '/vehicles/{id}',
        summary: 'Remover veículo',
        description: 'Remove um veículo do sistema. Requer autenticação.',
        tags: ['Veículos'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID do veículo', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Veículo removido com sucesso'),
            new OA\Response(response: 401, description: 'Não autorizado'),
            new OA\Response(response: 404, description: 'Veículo não encontrado'),
        ]
    )]
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return response()->json([
            'success' => true,
            'message' => 'Veículo removido com sucesso.',
        ], 204);
    }
}
