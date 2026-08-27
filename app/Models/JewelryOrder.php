<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JewelryOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_city',
        'order_type',
        'items_summary',
        'metal_rate_applied',
        'total_weight',
        'subtotal_amount',
        'making_charges_total',
        'gst_amount',
        'total_amount',
        'advance_paid',
        'balance_due',
        'status',
        'delivery_due_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'metal_rate_applied' => 'decimal:2',
            'total_weight' => 'decimal:3',
            'subtotal_amount' => 'decimal:2',
            'making_charges_total' => 'decimal:2',
            'gst_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'advance_paid' => 'decimal:2',
            'balance_due' => 'decimal:2',
            'delivery_due_date' => 'date',
        ];
    }
}
