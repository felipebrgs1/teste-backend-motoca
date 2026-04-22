<?php

namespace App\Models;

use Database\Factories\VehicleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'Veículo',
    description: 'Modelo de veículo da concessionária',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'type', type: 'string', enum: ['car', 'motorcycle'], example: 'car'),
        new OA\Property(property: 'brand', type: 'string', example: 'Honda'),
        new OA\Property(property: 'model', type: 'string', example: 'Civic'),
        new OA\Property(property: 'year', type: 'integer', example: 2024),
        new OA\Property(property: 'price', type: 'number', example: 150000.00),
        new OA\Property(property: 'color', type: 'string', example: 'Prata'),
        new OA\Property(property: 'mileage', type: 'integer', example: 0),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class Vehicle extends Model
{
    /** @use HasFactory<VehicleFactory> */
    use HasFactory;

    protected $fillable = [
        'type',
        'brand',
        'model',
        'year',
        'price',
        'color',
        'mileage',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}
