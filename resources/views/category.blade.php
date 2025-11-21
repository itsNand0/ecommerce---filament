@extends('layout')

@section('title', $category->name . ' - E-commerce')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
    @if($category->description)
        <p class="mt-2 text-gray-600">{{ $category->description }}</p>
    @endif
</div>

<!-- Products Grid -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
    @forelse($category->products->where('is_active', true) as $product)
        <a href="/producto/{{ $product->slug }}" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 block">
            <!-- Product Image -->
            <div class="relative h-40 bg-gray-200">
                @if($product->images && count($product->images) > 0)
                    <img src="{{ asset('storage/' . $product->images[0]) }}" 
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
                
                <!-- Badges -->
                <div class="absolute top-1 left-1 right-1 flex justify-between items-start">
                    @if($product->sale_price)
                        <span class="bg-red-500 text-white px-1.5 py-0.5 rounded text-xs font-semibold">
                            Oferta
                        </span>
                    @else
                        <span></span>
                    @endif
                    
                    @if($product->is_featured)
                        <span class="bg-yellow-500 text-white px-1.5 py-0.5 rounded text-xs font-semibold">
                            ⭐
                        </span>
                    @endif
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="p-3">
                <h3 class="text-sm font-semibold text-gray-900 mb-1 line-clamp-2 h-10">
                    {{ $product->name }}
                </h3>
                
                <!-- Price -->
                <div class="mb-2">
                    @if($product->sale_price)
                        <div class="flex items-center space-x-1">
                            <span class="text-lg font-bold text-red-600">Gs {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                        </div>
                        <span class="text-xs text-gray-500 line-through">Gs {{ number_format($product->price, 0, ',', '.') }}</span>
                    @else
                        <span class="text-lg font-bold text-gray-900">Gs {{ number_format($product->price, 0, ',', '.') }}</span>
                    @endif
                </div>
                
                <!-- Stock Status -->
                <div class="mb-2">
                    @if($product->stock > 0)
                        <span class="text-xs text-green-600 flex items-center">
                            <svg class="w-3 h-3 inline mr-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Stock: {{ $product->stock }}
                        </span>
                    @else
                        <span class="text-xs text-red-600 flex items-center">
                            <svg class="w-3 h-3 inline mr-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            Agotado
                        </span>
                    @endif
                </div>
                
                <!-- Add to Cart Button -->
                <button class="w-full bg-blue-600 text-white px-3 py-1.5 rounded-md hover:bg-blue-700 transition-colors duration-300 disabled:bg-gray-400 disabled:cursor-not-allowed text-sm"
                        {{ $product->stock <= 0 ? 'disabled' : '' }}>
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h8.5"></path>
                    </svg>
                    Agregar
                </button>
            </div>
        </a>
    @empty
        <div class="col-span-full text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay productos</h3>
            <p class="mt-1 text-sm text-gray-500">Esta categoría aún no tiene productos disponibles.</p>
        </div>
    @endforelse
</div>
@endsection
