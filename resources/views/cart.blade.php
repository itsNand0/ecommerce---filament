@extends('layout')

@section('title', 'Carrito de Compras - E-commerce')

@section('content')
<div class="max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Carrito de Compras</h1>

    @if($cartItems->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    @foreach($cartItems as $item)
                        <div class="p-6 border-b last:border-b-0 flex items-center space-x-4">
                            <!-- Product Image -->
                            <div class="flex-shrink-0">
                                @if($item->product->images && count($item->product->images) > 0)
                                    <img src="{{ asset('storage/' . $item->product->images[0]) }}" 
                                         alt="{{ $item->product->name }}"
                                         class="w-24 h-24 object-cover rounded">
                                @else
                                    <div class="w-24 h-24 bg-gray-200 rounded flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    <a href="/producto/{{ $item->product->slug }}" class="hover:text-blue-600">
                                        {{ $item->product->name }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-600">SKU: {{ $item->product->sku }}</p>
                                <p class="text-lg font-bold text-gray-900 mt-2">
                                    Gs {{ number_format($item->price, 0, ',', '.') }}
                                </p>
                            </div>

                            <!-- Quantity Controls -->
                            <div class="flex items-center space-x-2">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex items-center border border-gray-300 rounded-lg">
                                        <button type="submit" name="quantity" value="{{ max(1, $item->quantity - 1) }}" 
                                                class="px-3 py-1 hover:bg-gray-100">-</button>
                                        <input type="number" value="{{ $item->quantity }}" 
                                               class="w-16 text-center border-x border-gray-300 py-1 focus:outline-none" readonly>
                                        <button type="submit" name="quantity" value="{{ min($item->product->stock, $item->quantity + 1) }}" 
                                                class="px-3 py-1 hover:bg-gray-100">+</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Subtotal -->
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-900">
                                    Gs {{ number_format($item->getSubtotal(), 0, ',', '.') }}
                                </p>
                            </div>

                            <!-- Remove Button -->
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <!-- Clear Cart Button -->
                <div class="mt-4">
                    <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('¿Estás seguro de vaciar el carrito?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">
                            Vaciar Carrito
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Resumen del Pedido</h2>
                    
                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal ({{ $cartItems->sum('quantity') }} productos)</span>
                            <span>Gs {{ number_format($cart->getTotal(), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Envío</span>
                            <span class="text-green-600">Gratis</span>
                        </div>
                    </div>

                    <div class="border-t pt-4 mb-6">
                        <div class="flex justify-between text-xl font-bold text-gray-900">
                            <span>Total</span>
                            <span>Gs {{ number_format($cart->getTotal(), 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @auth
                        <a href="{{ route('checkout.index') }}" class="block w-full text-center bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold mb-3">
                            Proceder al Pago
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="block w-full text-center bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold mb-3">
                            Iniciar Sesión para Comprar
                        </a>
                    @endauth

                    <a href="/" class="block w-full text-center bg-gray-200 text-gray-800 py-3 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                        Continuar Comprando
                    </a>

                    <!-- Security Badge -->
                    <div class="mt-6 flex items-center justify-center text-sm text-gray-600">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Pago seguro
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h8.5"></path>
            </svg>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Tu carrito está vacío</h2>
            <p class="text-gray-600 mb-6">¡Agrega algunos productos para empezar!</p>
            <a href="/" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                Explorar Productos
            </a>
        </div>
    @endif
</div>
@endsection
