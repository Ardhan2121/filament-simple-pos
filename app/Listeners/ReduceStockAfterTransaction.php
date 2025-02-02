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
        // Mengambil transaksi dengan eager load produk dan data pivot-nya
        $transaction = $event->transaction->load('products');

        // Loop untuk mengurangi stok setiap item di dalam transaksi
        foreach ($transaction->products as $productPivot) {
            // Produk sudah ter-load melalui eager loading
            $product = $productPivot; // Tidak perlu query tambahan
            if ($product) {
                // Mengurangi stok
                $product->decrement('stock', $productPivot->pivot->qty);

                // Menambahkan histori stok
                $product->stockHistories()->create([
                    'transaction_id' => $transaction->id,
                    'qty' => $productPivot->pivot->qty,
                    'reason' => 'transaction',
                ]);
            }
        }
    }
}
