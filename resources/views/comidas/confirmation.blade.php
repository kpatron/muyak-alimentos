@extends('layouts.app')

@section('guest')
<!-- MOSTRAR AL EMPLEADO PARA PODER CONFIRMAR SU ASISTENCIA A LA COMIDA -->
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="text-center">
        <h1 class="mb-4">{{ $empleado->employee_name }}</h1>
        <p>Hola, {{ $empleado->employee_name }}. Por favor, confirma tu asistencia a la comida del día de hoy.</p>

        <form method="POST" action="{{ route('comidas-empleados.confirmation', ['signature' => $empleado->employee_signature]) }}">
            @csrf
            <button type="submit" class="btn btn-primary">Confirmar Asistencia</button>
        </form>
    </div>
</div>
@endsection
@push('scripts')
    <script>
        //Dar una doble confirmacion al empleado
        document.querySelector('form').addEventListener('submit', function(event) {
            if (!confirm('¿Estás seguro de que deseas confirmar tu asistencia a la comida?')) {
                event.preventDefault();
            }
        });
    </script>
@endpush