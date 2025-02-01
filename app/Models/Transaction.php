<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class Transaction extends Model
{
    public function products()
    {
        return $this->belongsToMany(Product::class, 'detail_transaction')
            ->withPivot('qty', 'subtotal');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }


    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            // Membuat invoice_number dengan format ORDER-{Tahun}{ID otomatis}
            $model->invoice_number = 'ORDER-' . date('y') . strtoupper(Str::random(6));
        });
    }


    protected $casts = [
        'total' => 'integer'
    ];
}
