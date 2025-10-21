@extends('layouts.user_type.auth')

@section('content')

  <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Comidas del Día</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">

              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dia</th>
                      <th class="text-secondary text-secondary text-xxs font-weight-bolder opacity-7">Comida</th>
                      <th class="text-secondary text-secondary text-xxs font-weight-bolder opacity-7">Descripcion</th>
                      <th class="text-secondary text-secondary text-xxs font-weight-bolder opacity-7">Imagen</th>
                      <th class="text-secondary text-secondary text-xxs font-weight-bolder opacity-7">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($comidas as $comida)
                    <tr>
                      
                      <td>
                        <p class="text-xs font-weight-bold mb-0">{{ $comida->dia_semana }}</p>
                      </td>
                      <td class="align-middle">
                        <p class="text-xs font-weight-bold mb-0">{{ $comida->nombre }}</p>
                      </td>
                      <td class="align-middle">
                        <span class="text-secondary text-xs font-weight-bold">{{ $comida->descripcion }}</span>
                      </td>
                      <td class="align-middle">
                        <span class="text-secondary text-xs font-weight-bold"><img src="{{ Storage::url($comida->imagen) }}" alt="imagen comida" width="200" height="200"></span>
                      </td>
                      <td class="align-middle">
                        <a href="javascript:;" class="text-secondary font-weight-bold text-xs" onclick="editComidaDia({{ $comida->id }},'{{ $comida->dia_semana }}','{{ $comida->nombre }}','{{ $comida->descripcion }}','{{ $comida->imagen }}')">
                          Edit
                        </a>
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
      function editComidaDia(id, dia, nombre, descripcion, imagen) {
       Swal.fire({
            title: 'Editar comida del día',
            html: `
              <form id="edit-comida-form" method="POST" action="/add-comida-dia/${id}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
              <div class="mb-3 text-start">
                <label for="dia" class="form-label">Día</label>
                <input type="text" class="form-control" id="dia" name="dia" value="${dia}" readonly>
              </div>
              <div class="mb-3 text-start">
                <label for="comida" class="form-label">Comida</label>
                <input type="text" class="form-control" id="comida" name="comida" value="${nombre}" required>
              </div>
              <div class="form-check mb-3 text-start">
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3">${descripcion}</textarea>
              </div>
              <div class="mb-3 text-start">
                <label for="imagen" class="form-label">Imagen</label>
                <input type="file" class="form-control" id="imagen" name="imagen">
              </div>
            </form>
          `,
          showCancelButton: true,
          confirmButtonText: 'Guardar',
          cancelButtonText: 'Cancelar',
          preConfirm: () => {
            const form = document.getElementById('edit-comida-form');
            if (form.checkValidity()) {
              form.submit();
            } else {
              Swal.showValidationMessage('Por favor, complete todos los campos requeridos.');
            }
          }
        });
      }
    </script>
  @endpush