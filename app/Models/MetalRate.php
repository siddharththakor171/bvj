<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetalRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'metal_name',
        'metal_code',
        'purity',
        'rate_per_gram',
        'rate_per_10g',
        'unit',
        'previous_rate',
        'trend',
    ];

    protected function casts(): array
    {
        return [
            'rate_per_gram' => 'decimal:2',
            'rate_per_10g' => 'decimal:2',
            'previous_rate' => 'decimal:2',
        ];
    }
}
