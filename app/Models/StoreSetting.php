<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    protected $fillable = [
        'establishment',
        'tagline',
        'bis_certificate',
        'bis_note',
        'gstin',
        'gst_note',
    ];
}
