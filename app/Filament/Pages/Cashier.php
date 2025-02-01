<?php

namespace App\Filament\Pages;

use App\Models\Product;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;

class Cashier extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.cashier';

    public $products;
    public Collection $cart;

    public function mount()
    {
        $this->products = Product::all();
        $this->cart = collect(); // Tetap pakai Collection
    }

    public function addToCart($id)
    {
        $product = Product::find($id);
        if (!$product) {
            Notification::make()
                ->title('Product doesnt exist')
                ->danger()
                ->send();
            return;
        }

        $this->cart = $this->cart->map(function ($item) use ($id, $product) {
            if ($item['id'] === $id) {
                if ($item['qty'] < $product->stock) {
                    $item['qty'] += 1;
                } else {
                    Notification::make()
                        ->title('Not enough stock')
                        ->danger()
                        ->send();
                }
            }
            return $item;
        });

        // Jika produk belum ada di keranjang, tambahkan
        if (!$this->cart->contains('id', $id)) {
            $this->cart->push([
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'qty' => 1,
            ]);
        }

        // Assign ulang supaya Livewire tahu ada perubahan
        $this->cart = $this->cart->values();
    }

    public function removeFromCart($id)
    {
        $this->cart = $this->cart->map(function ($item) use ($id) {
            if ($item['id'] === $id) {
                if ($item['qty'] > 1) {
                    $item['qty'] -= 1;
                    return $item;
                }
                return null; // Jika qty = 1, hapus produk
            }
            return $item;
        })->filter()->values(); // Hapus null dan reset index
    }

    public function isProductInCart($id)
    {
        return $this->cart->contains('id', $id);
    }

    public function getProductQtyInCart($id)
    {
        return optional($this->cart->firstWhere('id', $id))['qty'] ?? 0;
    }

    public function getTotalPrice()
    {
        return $this->cart->sum(fn ($item) => $item['price'] * $item['qty']);
    }
}
