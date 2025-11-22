@extends('layout')

@section('title', 'Mi Cuenta - E-commerce')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-0">
    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-8 text-center sm:text-left">Mi Cuenta</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Información Personal -->
        <div class="bg-white rounded-lg shadow p-6 flex flex-col justify-between">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Información Personal</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">Nombre</p>
                    <p class="font-semibold">{{ Auth::user()->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="font-semibold">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800">
                Editar Perfil →
            </a>
        </div>

        <!-- Mis Pedidos -->
        <div class="bg-white rounded-lg shadow p-6 flex flex-col justify-between">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Mis Pedidos</h2>
            @php
                $orders = \App\Models\Order::where('user_id', Auth::id())->orderByDesc('created_at')->get();
            @endphp
            @if($orders->isEmpty())
                <p class="text-gray-600 mb-4">No tienes pedidos aún</p>
                <a href="/" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Explorar Productos
                </a>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 mb-4 rounded-lg overflow-hidden">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase"># Pedido</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Fecha</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Total</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Estado</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Detalle</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr class="bg-white hover:bg-blue-50 transition">
                                    <td class="px-3 py-2 font-semibold">{{ $order->order_number }}</td>
                                    <td class="px-3 py-2">{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td class="px-3 py-2 text-blue-600 font-bold">${{ number_format($order->total, 2) }}</td>
                                    <td class="px-3 py-2">
                                        <span class="px-2 py-1 rounded text-xs {{ $order->status == 'completado' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2">
                                        <a href="{{ route('orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">Ver Detalle</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Favoritos -->
        <div class="bg-white rounded-lg shadow p-6 flex flex-col justify-between">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Favoritos</h2>
            <a href="{{ route('favorites.index') }}" class="inline-block text-blue-600 hover:text-blue-800">
                Ver Favoritos →
            </a>
        </div>

        <!-- Carrito -->
        <div class="bg-white rounded-lg shadow p-6 flex flex-col justify-between">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Mi Carrito</h2>
            @php
                $cart = \App\Models\Cart::where('user_id', Auth::id())->first();
                $cartCount = $cart ? \App\Models\CartItem::where('cart_id', $cart->id)->count() : 0;
            @endphp
            <p class="text-gray-600 mb-4">{{ $cartCount }} producto(s) en el carrito</p>
            <a href="{{ route('cart.index') }}" class="inline-block text-blue-600 hover:text-blue-800">
                Ver Carrito →
            </a>
        </div>
    </div>
</div>
@endsection
