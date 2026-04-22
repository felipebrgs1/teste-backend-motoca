<?php

namespace App\Models;

use Database\Factories\LeadFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'Lead',
    description: 'Lead de interesse em um veículo',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'João Silva'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'joao@teste.com'),
        new OA\Property(property: 'phone', type: 'string', example: '(11) 99999-9999'),
        new OA\Property(property: 'vehicle_id', type: 'integer', example: 1),
        new OA\Property(property: 'message', type: 'string', example: 'Tenho interesse neste veículo'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class Lead extends Model
{
    /** @use HasFactory<LeadFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'vehicle_id',
        'message',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
