@extends('layout')

@section('title', 'Favoritos - E-commerce')

@section('content')
<div class="max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Mis Favoritos</h1>

    @if($favorites->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            @foreach($favorites as $favorite)
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow">
                    <a href="/producto/{{ $favorite->product->slug }}">
                        @if($favorite->product->images && count($favorite->product->images) > 0)
                            <img src="{{ asset('storage/' . $favorite->product->images[0]) }}" 
                                 alt="{{ $favorite->product->name }}"
                                 class="w-full h-48 object-cover rounded-t-lg">
                        @else
                            <div class="w-full h-48 bg-gray-200 rounded-t-lg flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </a>
                    
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 text-sm mb-2 line-clamp-2">
                            <a href="/producto/{{ $favorite->product->slug }}" class="hover:text-blue-600">
                                {{ $favorite->product->name }}
                            </a>
                        </h3>
                        
                        @if($favorite->product->sale_price)
                            <div class="mb-2">
                                <span class="text-lg font-bold text-red-600">Gs {{ number_format($favorite->product->sale_price, 0, ',', '.') }}</span>
                                <span class="text-sm text-gray-500 line-through block">Gs {{ number_format($favorite->product->price, 0, ',', '.') }}</span>
                            </div>
                        @else
                            <p class="text-lg font-bold text-gray-900 mb-2">Gs {{ number_format($favorite->product->price, 0, ',', '.') }}</p>
                        @endif
                        
                        <form action="{{ route('favorites.remove', $favorite->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full text-red-600 hover:text-red-800 text-sm font-semibold py-2">
                                Eliminar de Favoritos
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">No tienes favoritos aún</h2>
            <p class="text-gray-600 mb-6">Guarda tus productos favoritos para encontrarlos fácilmente después</p>
            <a href="/" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                Explorar Productos
            </a>
        </div>
    @endif
</div>
@endsection
