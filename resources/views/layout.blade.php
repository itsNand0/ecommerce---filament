<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'E-commerce')</title>
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <!-- Logo -->
                    <a href="/" class="flex-shrink-0 flex items-center">
                        <span class="text-xl font-bold text-gray-800">E-Commerce</span>
                    </a>
                    
                    <!-- Navigation Links -->
                    <div class="hidden md:ml-6 md:flex md:space-x-8">
                        <a href="/" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium">Inicio</a>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium flex items-center">
                                Categorías
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 transform scale-100"
                                 x-transition:leave-end="opacity-0 transform scale-95"
                                 class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50"
                                 style="display: none;">
                                <div class="py-2">
                                    @foreach(\App\Models\Category::all() as $category)
                                        <a href="/categoria/{{ $category->slug }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ $category->name }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right side -->
                <div class="flex items-center space-x-4">
                    <!-- Search -->
                    <div class="hidden md:block">
                        <form class="flex">
                            <input type="text" placeholder="Buscar productos..." 
                                   class="px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                    
                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-600 hover:text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h8.5"></path>
                        </svg>
                        @php
                            $cart = Auth::check() 
                                ? \App\Models\Cart::where('user_id', Auth::id())->first()
                                : \App\Models\Cart::where('session_id', session()->getId())->first();
                            $cartCount = $cart ? \App\Models\CartItem::where('cart_id', $cart->id)->sum('quantity') : 0;
                        @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $cartCount }}</span>
                        @endif
                    </a>
                    
                    <!-- User Menu -->
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-gray-600 hover:text-blue-600">
                                <span class="mr-2">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <!-- User Dropdown Menu -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 transform scale-100"
                                 x-transition:leave-end="opacity-0 transform scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50"
                                 style="display: none;">
                                <div class="py-2">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mi Perfil</a>
                                    <a href="{{ route('account.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mi Cuenta</a>
                                    <a href="{{ route('favorites.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Favoritos</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Cerrar Sesión</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="space-x-2">
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 text-sm font-medium">Iniciar Sesión</a>
                            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 text-sm font-medium rounded-md hover:bg-blue-700">Registrarse</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-auto">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">E-Commerce</h3>
                    <p class="text-gray-300">Tu tienda en línea de confianza</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Enlaces</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li><a href="#" class="hover:text-white">Sobre nosotros</a></li>
                        <li><a href="#" class="hover:text-white">Contacto</a></li>
                        <li><a href="#" class="hover:text-white">Términos y condiciones</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contacto</h3>
                    <p class="text-gray-300">Email: info@ecommerce.com</p>
                    <p class="text-gray-300">Teléfono: +1 234 567 8900</p>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-center text-gray-300">
                <p>&copy; {{ date('Y') }} E-Commerce. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>