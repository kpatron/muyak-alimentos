@extends('layouts.user_type.auth')

@section('content')

  <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Empleados</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <div class="d-flex justify-content-end me-3">
                  <a href="javascript:void(0);" class="btn btn-primary btn-sm mb-3" onclick="createEmployee()">Crear Empleado</a>
                </div>
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Empleado</th>
                      <th class="text-secondary text-secondary text-xxs font-weight-bolder opacity-7">Área</th>
                      <th class="text-secondary text-secondary text-xxs font-weight-bolder opacity-7">Comida Doble</th>
                      <th class="text-secondary text-secondary text-xxs font-weight-bolder opacity-7">QR</th>
                      <th class="text-secondary opacity-7"></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($empleados as $empleado)
                    <tr>
                      
                      <td>
                        <p class="text-xs font-weight-bold mb-0">{{ $empleado->employee_name }}</p>
                      </td>
                      <td class="align-middle">
                        <p class="text-xs font-weight-bold mb-0">{{ $empleado->employee_area }}</p>
                      </td>
                      <td class="align-middle">
                        <p class="text-xs font-weight-bold mb-0">{{ $empleado->double_meal ? 'Si' : 'No' }}</p>
                      </td>
                      <td class="align-middle">
                        <!-- Descargar QR -->
                        <a href="{{ $empleado->qr_code }}" target="_blank" class="text-secondary font-weight-bold text-xs">Descargar QR</a>
                      </td>
                      <td class="align-middle">
                        <a href="javascript:;" class="text-secondary font-weight-bold text-xs" onclick="editEmployee({{ $empleado->id }},'{{ $empleado->employee_name }}','{{ $empleado->employee_area }}')">
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
      //Funcion para mostrar un modal para crear un nuevo empleado
      function createEmployee() {
        Swal.fire({
            title: 'Crear nuevo empleado',
            html: `
              <form id="create-employee-form" method="POST" action="{{ route('empleados.store') }}" enctype="multipart/form-data">
                @csrf
              <div class="mb-3 text-start">
                <label for="employee_name" class="form-label">Nombre del empleado</label>
                <input type="text" class="form-control" id="employee_name" name="employee_name" required>
              </div>
              <div class="mb-3 text-start">
                <label for="employee_area" class="form-label">Área del empleado</label>
                <input type="text" class="form-control" id="employee_area" name="employee_area" required>
              </div>
              <div class="form-check mb-3 text-start">
                <input class="form-check-input" type="checkbox" value="1" id="double_meal" name="double_meal">
                <label class="form-check-label" for="double_meal">
                  ¿Permitir comida doble?
                </label>
              </div>
            </form>
          `,
          showCancelButton: true,
          confirmButtonText: 'Crear',
          cancelButtonText: 'Cancelar',
          preConfirm: () => {
            const form = document.getElementById('create-employee-form');
            if (form.checkValidity()) {
              form.submit();
            } else {
              Swal.showValidationMessage('Por favor, complete todos los campos requeridos.');
            }
          }
        });
      }

      //Funcion para editar un empleado con un modal como el de crear empleado
      function editEmployee(id,name,area) { 
        Swal.fire({
            title: 'Editar empleado',
            html: `
              <form id="edit-employee-form" method="POST" action="/empleados/${id}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
              <div class="mb-3 text-start">
                <label for="employee_name" class="form-label">Nombre del empleado</label>
                <input type="text" class="form-control" id="employee_name" name="employee_name" value="${name}" required>
              </div>
              <div class="mb-3 text-start">
                <label for="employee_area" class="form-label">Área del empleado</label>
                <input type="text" class="form-control" id="employee_area" name="employee_area" value="${area}" required>
              </div>
              <div class="form-check mb-3 text-start">
                <input class="form-check-input" type="checkbox" value="1" id="double_meal" name="double_meal">
                <label class="form-check-label" for="double_meal">
                  ¿Permitir comida doble?
                </label>
              </div>
            </form>
          `,
          showCancelButton: true,
          confirmButtonText: 'Guardar',
          cancelButtonText: 'Cancelar',
          preConfirm: () => {
            const form = document.getElementById('edit-employee-form');
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