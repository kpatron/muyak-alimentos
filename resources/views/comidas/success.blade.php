@extends('layouts.app')

@section('guest')
<!-- CONFIRMACION DE ASISTENCIA EXITOSA -->
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="text-center">
        <h1 class="mb-4">Comida Confirmada</h1>
        <p>Gracias, {{ session('empleado')->employee_name }}. Tu {{ session('tipoComida') }} ha sido confirmada exitosamente.</p>
    </div>
</div>
@endsection