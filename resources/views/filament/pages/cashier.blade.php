<x-filament-panels::page>
    {{-- Product List --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pb-[90px] z-0">
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
    <div class="fixed bottom-0 left-0 right-0 p-4 bg-white flex items-center border-t z-40">
        <div class="flex-1">
            <p>Total Amount</p>
            <p class="text-2xl font-bold">@money($this->getTotalPrice())</p>
        </div>

        {{-- Tambahkan wire:ignore.self untuk modal agar tidak re-render --}}
        <x-filament::modal wire:ignore.self width="xl" id="checkout">
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
                <div class="mb-9">
                    @forelse ($cart as $c)
                        <div class="flex justify-between border-b py-2">
                            <span>{{ $c['name'] }}</span>
                            <span>{{ $c['qty'] }} x @money($c['price'])</span>
                        </div>
                    @empty
                        <div class="min-h-24 w-full rounded-lg bg-gray-100 text-gray-900 flex items-center justify-center">
                            No Items
                        </div>
                    @endforelse
                </div>

                <div class="space-y-3">
                    <h5 class="font-bold">Payment Method</h5>
                    <div class="space-y-2">
                        @foreach ($payments as $payment)
                            <div>
                                <input class="sr-only peer" type="radio" id="{{ $payment->id }}" name="payment" wire:model="paymentSelected" value="{{ $payment->id }}">
                                <label class="flex border p-5 rounded-lg peer-checked:border-primary-600 " for="{{ $payment->id }}">{{ $payment->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>


                <div class="flex justify-between font-bold text-lg mt-4">
                    <span>Total:</span>
                    <span>@money($this->getTotalPrice())</span>
                </div>

                <x-filament::button class="mt-4 w-full" wire:click="createTransaction">
                    Create
                </x-filament::button>
            </div>
        </x-filament::modal>
    </div>
</x-filament-panels::page>
