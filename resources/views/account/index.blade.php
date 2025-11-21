@extends('layout')

@section('title', 'Mi Cuenta - E-commerce')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Mi Cuenta</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Información Personal -->
        <div class="bg-white rounded-lg shadow p-6">
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
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Mis Pedidos</h2>
            <p class="text-gray-600 mb-4">No tienes pedidos aún</p>
            <a href="/" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                Explorar Productos
            </a>
        </div>

        <!-- Favoritos -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Favoritos</h2>
            <p class="text-gray-600 mb-4">No tienes productos favoritos</p>
            <a href="{{ route('favorites.index') }}" class="inline-block text-blue-600 hover:text-blue-800">
                Ver Favoritos →
            </a>
        </div>

        <!-- Carrito -->
        <div class="bg-white rounded-lg shadow p-6">
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
