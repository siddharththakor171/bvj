<?php

namespace App\Models;

use Database\Factories\JewelryCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JewelryCategory extends Model
{
    /** @use HasFactory<JewelryCategoryFactory> */
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    public function products(): HasMany
    {
        return $this->hasMany(JewelryProduct::class, 'category', 'name');
    }
}
