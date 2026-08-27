<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JewelryInquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'inquiry_number',
        'customer_name',
        'customer_phone',
        'customer_email',
        'interested_category',
        'budget_range',
        'status',
        'message',
    ];
}
