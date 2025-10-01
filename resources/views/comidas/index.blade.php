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
              <!-- CONTADOR DE COMIDAS -->
              <div class="d-flex justify-content-around mb-4">
                <div class="text-center">
                  <h5 class="mb-0">{{ $comidas->where('tipo', 'desayuno')->count() }}</h5>
                  <p class="text-sm mb-0">Desayunos</p>
                </div>
                <div class="text-center">
                  <h5 class="mb-0">{{ $comidas->where('tipo', 'comida')->count() }}</h5>
                  <p class="text-sm mb-0">Comidas</p>
                </div>
                <div class="text-center">
                  <h5 class="mb-0">{{ $comidas->count() }}</h5>
                  <p class="text-sm mb-0">Total</p>
                </div>
              </div>
              <div class="table-responsive p-0">
                <!-- añadir filtros de fechas y tipos comidas -->
                <form method="GET" action="{{ route('comidas-empleados.index') }}" class="d-flex justify-content-start align-items-center mb-3 ms-3">
                  <div class="me-3">
                    <label for="start_date" class="form-label">Fecha Inicio:</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                  </div>
                  <div class="me-3">
                    <label for="end_date" class="form-label">Fecha Fin:</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                  </div>
                  <div class="me-3">
                    <label for="tipo" class="form-label">Tipo Comida:</label>
                    <select id="tipo" name="tipo" class="form-select">
                      <option value="all">Todos</option>
                      <option value="desayuno" {{ request('tipo') == 'desayuno' ? 'selected' : '' }}>Desayuno</option>
                      <option value="comida" {{ request('tipo') == 'comida' ? 'selected' : '' }}>Comida</option>
                    </select>
                  </div>
                  <div class="align-self-end">
                    <button type="submit" class="btn btn-primary btn-sm mb-3">Filtrar</button>
                  </div>
                  <div class="align-self-end ms-3">
                    <a href="{{ route('comidas-empleados.export', ['fecha_inicio' => request('start_date'), 'fecha_fin' => request('end_date'), 'tipo_comida' => request('tipo')]) }}" class="btn btn-primary btn-sm mb-3">Descargar CSV</a>
                  </div>
                </form>
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