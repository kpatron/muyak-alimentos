@extends('layouts.user_type.auth')

@section('content')

  <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Comidas</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <div class="d-flex justify-content-end me-3">
                  <a href="{{ route('comidas-empleados.export') }}" class="btn btn-primary btn-sm mb-3">Descargar CSV</a>
                </div>
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Empleado</th>
                      <th class="text-secondary text-secondary text-xxs font-weight-bolder opacity-7">Tipo Comida</th>
                      <th class="text-secondary text-secondary text-xxs font-weight-bolder opacity-7">Fecha y Hora</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($comidas as $comida)
                    <tr>
                      
                      <td>
                        <p class="text-xs font-weight-bold mb-0">{{ $comida->empleado->employee_name }}</p>
                      </td>
                      <td class="align-middle">
                        <span class="text-secondary text-xs font-weight-bold">{{ ucfirst($comida->tipo) }}</span>
                      </td>
                      <td class="align-middle">
                        <span class="text-secondary text-xs font-weight-bold">{{ $comida->created_at->format('Y-m-d H:i:s') }}</span>
                      </td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </main>
  
  @endsection
  @push('scripts')
    <script>

    </script>
  @endpush