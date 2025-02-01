<x-filament-panels::page>
    {{-- Product List --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pb-[90px]">
        @foreach ($products as $product)
            <div class="bg-white p-5 rounded-lg {{ $this->isProductInCart($product->id) ? 'border-2 border-primary-300' : '' }}" wire:key="product-{{ $product->id }}">
                <div class="flex items-center gap-4">
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="size-16 object-cover rounded-lg">
                    <div class="flex-1">
                        <h5 class="text-sm">{{ $product->name }}</h5>
                        <p class="text-lg font-bold">@money($product->price)</p>
                    </div>
                    <div>
                        @if ($this->isProductInCart($product->id))
                            <x-filament::button outlined color="gray" wire:click="removeFromCart({{ $product->id }})">-</x-filament::button>
                            <span class="px-2">{{ $this->getProductQtyInCart($product->id) }}</span>
                            <x-filament::button outlined color="gray" wire:click="addToCart({{ $product->id }})">+</x-filament::button>
                        @else
                            <x-filament::button outlined color="gray" wire:click="addToCart({{ $product->id }})">+</x-filament::button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Checkout --}}
    <div class="fixed bottom-0 left-0 right-0 p-4 bg-white flex items-center border-t">
        <div class="flex-1">
            <p>Total Amount</p>
            <p class="text-2xl font-bold">@money($this->getTotalPrice())</p>
        </div>

        {{-- Tambahkan wire:ignore.self untuk modal agar tidak re-render --}}
        <x-filament::modal size="3xl" wire:ignore.self>
            <x-slot name="trigger">
                <x-filament::button size="lg">
                    Checkout
                </x-filament::button>
            </x-slot>

            <x-slot name="heading">
                Checkout
            </x-slot>

            <x-slot name="description">
                Lorem, ipsum dolor.
            </x-slot>

            <div>
                @foreach ($cart as $c)
                    <div class="flex justify-between border-b py-2">
                        <span>{{ $c['name'] }}</span>
                        <span>{{ $c['qty'] }} x @money($c['price'])</span>
                    </div>
                @endforeach

                <div class="flex justify-between font-bold text-lg mt-4">
                    <span>Total:</span>
                    <span>@money($this->getTotalPrice())</span>
                </div>

                <x-filament::button class="mt-4 w-full bg-primary-500 text-white" wire:click="checkout">
                    Bayar Sekarang
                </x-filament::button>
            </div>
        </x-filament::modal>
    </div>
</x-filament-panels::page>
