@extends('layout')

@section('title', 'Pedido Confirmado - E-commerce')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-8 text-center mb-8">
        <div class="mb-6">
            <svg class="w-20 h-20 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">¡Pedido Confirmado!</h1>
        <p class="text-gray-600 mb-6">Gracias por tu compra. Tu pedido ha sido recibido.</p>
        
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p class="text-sm text-gray-600">Número de Pedido</p>
            <p class="text-2xl font-bold text-blue-600">{{ $order->order_number }}</p>
        </div>
    </div>

    <!-- Detalles del Pedido -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Detalles del Pedido</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Información de Envío</h3>
                <div class="text-gray-600 space-y-1">
                    <p><strong>Nombre:</strong> {{ $order->shipping_name }}</p>
                    <p><strong>Dirección:</strong> {{ $order->shipping_address }}</p>
                    <p><strong>Ciudad:</strong> {{ $order->shipping_city }}</p>
                    <p><strong>Teléfono:</strong> {{ $order->shipping_phone }}</p>
                    @if($order->shipping_email)
                        <p><strong>Email:</strong> {{ $order->shipping_email }}</p>
                    @endif
                </div>
            </div>

            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Información del Pago</h3>
                <div class="text-gray-600 space-y-1">
                    <p><strong>Método:</strong> 
                        @if($order->payment_method == 'efectivo')
                            Efectivo
                        @elseif($order->payment_method == 'transferencia')
                            Transferencia Bancaria
                        @else
                            Tarjeta de Crédito/Débito
                        @endif
                    </p>
                    <p><strong>Estado:</strong> 
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-sm">Pendiente</span>
                    </p>
                </div>
            </div>
        </div>

        @if($order->notes)
            <div class="border-t pt-4">
                <h3 class="font-semibold text-gray-900 mb-2">Notas</h3>
                <p class="text-gray-600">{{ $order->notes }}</p>
            </div>
        @endif
    </div>

    <!-- Productos -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Productos</h2>
        
        <div class="space-y-4">
            @foreach($order->items as $item)
                <div class="flex items-center space-x-4 pb-4 border-b last:border-b-0">
                    @if($item->product->images && count($item->product->images) > 0)
                        <img src="{{ asset('storage/' . $item->product->images[0]) }}" 
                             alt="{{ $item->product->name }}"
                             class="w-20 h-20 object-cover rounded">
                    @else
                        <div class="w-20 h-20 bg-gray-200 rounded"></div>
                    @endif
                    
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">{{ $item->product->name }}</h3>
                        <p class="text-sm text-gray-600">Cantidad: {{ $item->quantity }}</p>
                        <p class="text-sm text-gray-600">Precio unitario: Gs {{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                    
                    <div class="text-right">
                        <p class="font-bold text-gray-900">Gs {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="border-t pt-4 mt-4 space-y-2">
            <div class="flex justify-between text-gray-600">
                <span>Subtotal</span>
                <span>Gs {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-gray-600">
                <span>Envío</span>
                <span class="text-green-600">Gratis</span>
            </div>
            <div class="flex justify-between text-xl font-bold text-gray-900 pt-2 border-t">
                <span>Total</span>
                <span>Gs {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Instrucciones de Pago -->
    @if($order->payment_method == 'transferencia')
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Instrucciones para Transferencia</h2>
            <div class="space-y-2 text-gray-700">
                <p><strong>Banco:</strong> Banco Ejemplo</p>
                <p><strong>Cuenta:</strong> 1234567890</p>
                <p><strong>A nombre de:</strong> E-Commerce SA</p>
                <p><strong>Monto:</strong> Gs {{ number_format($order->total, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-600 mt-4">Por favor, envía el comprobante de pago a: pagos@ecommerce.com</p>
            </div>
        </div>
    @endif

    <div class="text-center">
        <a href="/" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
            Continuar Comprando
        </a>
        <a href="{{ route('account.index') }}" class="inline-block ml-4 bg-gray-200 text-gray-800 px-8 py-3 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
            Ver Mis Pedidos
        </a>
    </div>
</div>
@endsection
