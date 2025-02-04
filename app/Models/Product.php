<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(mixed $validated)
 */
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'price',
        'image'
    ];
}
