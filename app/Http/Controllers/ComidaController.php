<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comida;
use App\Models\ComidaDia;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComidaController extends Controller
{
    public function confirm($signature)
    {
        // Aquí puedes agregar la lógica para confirmar la comida del empleado
        $empleado = Empleado::where('employee_signature', $signature)->first();
        if ($empleado) {
            return view('comidas.confirmation', compact('empleado'));
        } else {
            return view('comidas.invalid');
        }
    }

    public function final_confirmation(Request $request, $signature)
    {
        $empleado = Empleado::where('employee_signature', $signature)->first();
        if ($empleado) {
            //Verificar si el empleado ya ha registrado comida hoy
            /*$comidaHoy = Comida::where('empleado_id', $empleado->id)
                ->whereDate('created_at', now()->toDateString())
                ->count();

            if ($comidaHoy > 0 && !$empleado->double_meal) {
                return redirect()->route('comidas-empleados.already');
            }else if ($comidaHoy > 1 && $empleado->double_meal) {
                return redirect()->route('comidas-empleados.already');
            }*/
            //Si es menos de mediodia es comida 'desayuno', si no es 'comida'
            $horaActual = now()->format('H:i:s');
            $tipoComida = ($horaActual > '07:00:00' && $horaActual < '12:00:00') ? 'desayuno' : 'comida';

            // Registrar la comida en la base de datos
            Comida::create([
                'empleado_id' => $empleado->id,
                'tipo' => $tipoComida,
            ]);
            //REDIRECCIONAR A VISTA DE EXITO
            return redirect()->route('comidas-empleados.success')->with('empleado', $empleado)->with('tipoComida', $tipoComida);
        } else {
            return redirect()->route('comidas-empleados.invalid');
        }
    }

    public function index(Request $request)
    {
        // Mostrar las comidas de hoy por defecto o las del filtro
        $fechaInicio = $request->input('start_date') ?? now()->toDateString();
        $fechaFin = $request->input('end_date') ?? now()->toDateString();
        $tipoComida = $request->input('tipo') ?? 'all';

        $comidas = Comida::whereDate('created_at', '>=', $fechaInicio)
            ->whereDate('created_at', '<=', $fechaFin)
            ->when($tipoComida !== 'all', function ($query) use ($tipoComida) {
                return $query->where('tipo', $tipoComida);
            })
            ->with('empleado')
            ->get();

        return view('comidas.index', ['comidas' => $comidas]);
    }

    public function export(Request $request)
    {
        //Conseguir los filtros de fechas, comidas o default a hoy
        $fechaInicio = $request->input('fecha_inicio', now()->toDateString());
        $fechaFin = $request->input('fecha_fin', now()->toDateString());
        $tipoComida = $request->input('tipo_comida', 'all');

        $comidas = Comida::whereDate('created_at', '>=', $fechaInicio)
            ->whereDate('created_at', '<=', $fechaFin)
            ->when($tipoComida !== 'all', function ($query) use ($tipoComida) {
                return $query->where('tipo', $tipoComida);
            })
            ->with('empleado')
            ->get();

        //Hay que crear en el storage path normal sin otra carpeta
        $filename = 'comidas_' . now()->format('Y_m_d') . '.csv';
        $handle = fopen(storage_path('app/public/' . $filename), 'w+');
        fputcsv($handle, ['Empleado', 'Area', 'Tipo de Comida', 'Fecha y Hora']);

        foreach ($comidas as $comida) {
            fputcsv($handle, [
                $comida->empleado->employee_name,
                $comida->empleado->employee_area,
                $comida->tipo,
                $comida->created_at->format('Y-m-d H:i:s'),
            ]);
        }

        fclose($handle);

        return response()->download(storage_path('app/public/' . $filename))->deleteFileAfterSend(true);
    }

    public function today()
    {
        //Obtener la comida del dia
        $dia_semana = now()->format('N'); //1 (Lunes) a 7 (Domingo)
        $comida = ComidaDia::where('id', $dia_semana)->first();

        return view('comidas.today', compact('comida'));
    }

    public function createComidaDia()
    {
        $comidas = ComidaDia::all();
        return view('comidas.comida_dia', compact('comidas'));
    }

    public function updateComidaDia(Request $request, $id)
    {
        $request->validate([
            'comida' => 'required|string',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|file|image|max:2048',
        ]);

        $comidaDia = ComidaDia::find($id);
        
        $comidaDia->nombre = $request->input('comida');
        $comidaDia->descripcion = $request->input('descripcion');
        if ($request->hasFile('imagen')) {
            //Eliminar la imagen anterior si existe
            if ($comidaDia->imagen) {
                $previousImagePath = str_replace('/storage/', '', $comidaDia->imagen);
                Storage::disk('public')->delete($previousImagePath);
            }
            //Guardar la nueva imagen
            $path = $request->file('imagen')->store('comidas_dia', 'public');
            $comidaDia->imagen = $path;
        }
        $comidaDia->save();

        return redirect()->route('comida.dia.create')->with('success', 'Comida del día actualizada correctamente.');
    }
}
