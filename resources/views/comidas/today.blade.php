@extends('layouts.app')

@section('guest')
<!-- CENTRADO DE MANERA VERTICAL Y HORIZONTAL -->
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="text-center">
        <h1 class="mb-4">Comida del Dia - {{ date('d/m/Y') }}</h1>
        <h3>{{ $comida->nombre }}</h3>
        <img src="{{ $comida->imagen }}" alt="imagen comida" class="img-fluid my-4" style="max-height: 300px;">
        <p>{{ $comida->descripcion }}</p>
        <p> Contactanos por whatsapp: <a href="https://wa.me/+529981815324">+52 998 181 5324</a></p>
    </div>
</div>
@endsection