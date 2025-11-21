@extends('layout')

@section('title', $product->name . ' - E-commerce')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="mb-6 text-sm">
        <a href="/" class="text-blue-600 hover:underline">Inicio</a>
        <span class="mx-2">/</span>
        <a href="/categoria/{{ $product->category->slug }}" class="text-blue-600 hover:underline">{{ $product->category->name }}</a>
        <span class="mx-2">/</span>
        <span class="text-gray-600">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Product Images -->
        <div>
            @if($product->images && count($product->images) > 0)
                <!-- Main Image -->
                <div class="mb-4 bg-gray-100 rounded-lg overflow-hidden">
                    <img id="mainImage" src="{{ asset('storage/' . $product->images[0]) }}" 
                         alt="{{ $product->name }}"
                         class="w-full h-96 object-contain">
                </div>
                
                <!-- Thumbnail Images -->
                @if(count($product->images) > 1)
                    <div class="grid grid-cols-5 gap-2">
                        @foreach($product->images as $image)
                            <img src="{{ asset('storage/' . $image) }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-20 object-cover rounded cursor-pointer hover:opacity-75 transition-opacity"
                                 onclick="document.getElementById('mainImage').src='{{ asset('storage/' . $image) }}';">
                        @endforeach
                    </div>
                @endif
            @else
                <div class="bg-gray-200 rounded-lg h-96 flex items-center justify-center">
                    <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            @endif
        </div>

        <!-- Product Info -->
        <div>
            <!-- Product Name -->
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
            
            <!-- Category & SKU -->
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-4 text-sm text-gray-600">
                    <span>Categoría: <a href="/categoria/{{ $product->category->slug }}" class="text-blue-600 hover:underline">{{ $product->category->name }}</a></span>
                    <span>SKU: {{ $product->sku }}</span>
                </div>
                
                <!-- Favorite Button -->
                @auth
                    <form action="{{ route('favorites.toggle', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="p-2 rounded-full hover:bg-gray-100 transition-colors" title="{{ $product->isFavoritedBy(Auth::id()) ? 'Eliminar de favoritos' : 'Agregar a favoritos' }}">
                            @if($product->isFavoritedBy(Auth::id()))
                                <svg class="w-6 h-6 text-red-500 fill-current" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-gray-400 hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            @endif
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="p-2 rounded-full hover:bg-gray-100 transition-colors" title="Inicia sesión para agregar a favoritos">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </a>
                @endauth
            </div>

            <!-- Badges -->
            <div class="flex gap-2 mb-4">
                @if($product->is_featured)
                    <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-semibold">⭐ Destacado</span>
                @endif
                @if($product->sale_price)
                    <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">🔥 En Oferta</span>
                @endif
            </div>

            <!-- Price -->
            <div class="mb-6">
                @if($product->sale_price)
                    <div class="flex items-baseline space-x-3">
                        <span class="text-4xl font-bold text-red-600">Gs {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                        <span class="text-xl text-gray-500 line-through">Gs {{ number_format($product->price, 0, ',', '.') }}</span>
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm font-semibold">
                            -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                        </span>
                    </div>
                @else
                    <span class="text-4xl font-bold text-gray-900">Gs {{ number_format($product->price, 0, ',', '.') }}</span>
                @endif
            </div>

            <!-- Stock Status -->
            <div class="mb-6">
                @if($product->stock > 0)
                    <div class="flex items-center text-green-600">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-semibold">En Stock ({{ $product->stock }} disponibles)</span>
                    </div>
                @else
                    <div class="flex items-center text-red-600">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-semibold">Producto Agotado</span>
                    </div>
                @endif
            </div>

            <!-- Quantity & Add to Cart -->
            @if($product->stock > 0)
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cantidad</label>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center border border-gray-300 rounded-lg">
                                <button type="button" class="px-4 py-2 hover:bg-gray-100" onclick="decrementQuantity()">-</button>
                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}" 
                                       class="w-20 text-center border-x border-gray-300 py-2 focus:outline-none">
                                <button type="button" class="px-4 py-2 hover:bg-gray-100" onclick="incrementQuantity()">+</button>
                            </div>
                            <button type="submit" class="flex-1 bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h8.5"></path>
                                </svg>
                                Agregar al Carrito
                            </button>
                        </div>
                    </div>
                </form>
            @endif

            <!-- Description -->
            @if($product->description)
                <div class="border-t pt-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">Descripción del Producto</h2>
                    <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
                </div>
            @endif

            <!-- Product Details -->
            <div class="border-t mt-6 pt-6">
                <h2 class="text-xl font-bold text-gray-900 mb-3">Detalles del Producto</h2>
                <ul class="space-y-2 text-gray-600">
                    <li class="flex">
                        <span class="font-semibold w-32">SKU:</span>
                        <span>{{ $product->sku }}</span>
                    </li>
                    <li class="flex">
                        <span class="font-semibold w-32">Categoría:</span>
                        <span>{{ $product->category->name }}</span>
                    </li>
                    <li class="flex">
                        <span class="font-semibold w-32">Disponibilidad:</span>
                        <span>{{ $product->stock > 0 ? 'En Stock' : 'Agotado' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    function incrementQuantity() {
        const input = document.getElementById('quantity');
        const max = parseInt(input.max);
        const current = parseInt(input.value);
        if (current < max) {
            input.value = current + 1;
        }
    }

    function decrementQuantity() {
        const input = document.getElementById('quantity');
        const current = parseInt(input.value);
        if (current > 1) {
            input.value = current - 1;
        }
    }
</script>
@endsection
