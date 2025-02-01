<?php

namespace App\Listeners;

use App\Events\TransactionCreated;
use App\Models\Product;

class ReduceStockAfterTransaction
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\TransactionCreated  $event
     * @return void
     */
    public function handle(TransactionCreated $event)
    {
        // Loop untuk mengurangi stok setiap item di dalam transaksi
        foreach ($event->transaction->products as $productPivot) {
            $product = Product::find($productPivot->pivot->product_id);
            if ($product) {
                $product->stock -= $productPivot->pivot->qty;
                $product->save();
            }
        }
    }
}
