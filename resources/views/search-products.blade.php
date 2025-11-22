@extends('layout')

@section('title', 'Buscar productos')

@section('content')
<div class="max-w-4xl mx-auto px-4">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Resultados de búsqueda</h1>
    <form action="{{ route('products.search') }}" method="get" class="mb-6 flex">
        <input type="text" name="q" value="{{ $query }}" placeholder="Buscar productos..." class="px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full">
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </button>
    </form>
    @if($products->isEmpty())
        <p class="text-gray-600">No se encontraron productos con ese nombre.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-lg shadow p-4 flex flex-col">
                    <h2 class="text-lg font-semibold mb-2">{{ $product->name }}</h2>
                    @php
                        $image = is_array($product->images) ? ($product->images[0] ?? null) : $product->images;
                    @endphp
                    <img src="{{ $image ? asset('storage/' . $image) : 'https://via.placeholder.com/300x200?text=Sin+imagen' }}" alt="{{ $product->name }}" class="mb-2 w-full h-48 object-cover rounded">
                    <p class="text-blue-600 font-bold mb-2">${{ number_format($product->price, 2) }}</p>
                    <a href="/producto/{{ $product->slug }}" class="mt-auto inline-block text-blue-600 hover:text-blue-800">Ver producto →</a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
