<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleFactory> */
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
