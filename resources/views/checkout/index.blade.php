@extends('layout')

@section('title', 'Checkout - E-commerce')

@section('content')
<div class="max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Finalizar Compra</h1>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Formulario de envío -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Información de Envío -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Información de Envío</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo *</label>
                            <input type="text" name="shipping_name" value="{{ old('shipping_name', Auth::user()->name) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('shipping_name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dirección *</label>
                            <textarea name="shipping_address" rows="3" required
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ciudad *</label>
                                <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('shipping_city')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono *</label>
                                <input type="tel" name="shipping_phone" value="{{ old('shipping_phone') }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('shipping_phone')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email (opcional)</label>
                            <input type="email" name="shipping_email" value="{{ old('shipping_email', Auth::user()->email) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('shipping_email')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Método de Pago -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Método de Pago</h2>
                    
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="efectivo" required class="mr-3" {{ old('payment_method') == 'efectivo' ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold">Efectivo</p>
                                <p class="text-sm text-gray-600">Pago en efectivo al recibir el pedido</p>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="transferencia" class="mr-3" {{ old('payment_method') == 'transferencia' ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold">Transferencia Bancaria</p>
                                <p class="text-sm text-gray-600">Recibirás los datos bancarios por email</p>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="tarjeta" class="mr-3" {{ old('payment_method') == 'tarjeta' ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold">Tarjeta de Crédito/Débito</p>
                                <p class="text-sm text-gray-600">Pago seguro con tarjeta</p>
                            </div>
                        </label>
                    </div>
                    @error('payment_method')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notas adicionales -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Notas del Pedido (Opcional)</h2>
                    <textarea name="notes" rows="3" placeholder="Instrucciones especiales para la entrega, referencias, etc."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Resumen del Pedido -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Resumen del Pedido</h2>
                    
                    <!-- Productos -->
                    <div class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                        @foreach($cartItems as $item)
                            <div class="flex items-center space-x-3 pb-3 border-b">
                                @if($item->product->images && count($item->product->images) > 0)
                                    <img src="{{ asset('storage/' . $item->product->images[0]) }}" 
                                         alt="{{ $item->product->name }}"
                                         class="w-16 h-16 object-cover rounded">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 rounded"></div>
                                @endif
                                <div class="flex-1">
                                    <p class="font-semibold text-sm">{{ $item->product->name }}</p>
                                    <p class="text-sm text-gray-600">Cantidad: {{ $item->quantity }}</p>
                                    <p class="text-sm font-bold">Gs {{ number_format($item->getSubtotal(), 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Totales -->
                    <div class="space-y-2 mb-4 pt-4 border-t">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
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

                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                        Confirmar Pedido
                    </button>

                    <a href="{{ route('cart.index') }}" class="block w-full text-center text-gray-600 mt-3 hover:text-gray-800">
                        ← Volver al Carrito
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
