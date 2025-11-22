@extends('layout')

@section('content')
<div class="container mt-4">

    <!-- Banner tipo carrusel -->
    <div id="welcomeCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('images/Accesorios.jpg') }}" class="d-block w-100 object-cover" alt="Banner 1" style="height: 350px; max-height: 350px;">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/Pantalones.jpg') }}" class="d-block w-100 object-cover" alt="Banner 2" style="height: 350px; max-height: 350px;">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/Ropas.jpg') }}" class="d-block w-100 object-cover" alt="Banner 3" style="height: 350px; max-height: 350px;">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#welcomeCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#welcomeCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
    <!-- Sección adicional: Información de bienvenida -->
    <div class="card">
        <div class="card-body text-center">
            <h2>Bienvenido a nuestra tienda en línea</h2>
            <p>Descubre nuestros productos y ofertas exclusivas. ¡Explora y disfruta tu experiencia de compra!</p>
        </div>
    </div>

</div>
@endsection