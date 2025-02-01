<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'stock',
        'description'
    ];

    protected $casts = [
        'price' => 'integer'
    ];

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'detail_transaction')->withPivot('qty', 'subtotal');
    }
}
