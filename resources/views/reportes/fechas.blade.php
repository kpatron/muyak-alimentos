@extends('layouts.user_type.auth')

@section('content')

  <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Reporte Concentrado de Comidas por Fecha</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              
              <div class="table-responsive p-0">
                <!-- añadir filtros de fechas y tipos comidas -->
                <form method="GET" action="{{ route('reportes.fechas') }}" class="d-flex justify-content-start align-items-center mb-3 ms-3">
                  <div class="me-3">
                    <label for="start_date" class="form-label">Fecha Inicio:</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                  </div>
                  <div class="me-3">
                    <label for="end_date" class="form-label">Fecha Fin:</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                  </div>
                  <div class="align-self-end">
                    <button type="submit" class="btn btn-primary btn-sm mb-3">Filtrar</button>
                  </div>
                  <div class="align-self-end ms-3">
                    <a href="{{ route('reportes.fechas.export', ['fecha_inicio' => request('start_date'), 'fecha_fin' => request('end_date')]) }}" class="btn btn-primary btn-sm mb-3">Descargar CSV</a>
                  </div>
                </form>
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha</th>
                      <th class="text-secondary text-secondary text-xxs font-weight-bolder opacity-7">Total Comidas</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($comidas as $comida)

                    <tr>
                        <td>
                            <p class="text-xs font-weight-bold mb-0">{{ $comida->date }}</p>
                        </td>
                        <td>
                            <p class="text-xs font-weight-bold mb-0">{{ $comida->total }}</p>
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