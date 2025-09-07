<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comida;
use App\Models\Empleado;
use Illuminate\Http\Request;

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

        $filename = 'comidas_' . now()->format('Y_m_d') . '.csv';
        $handle = fopen($filename, 'w+');
        fputcsv($handle, ['Empleado', 'Tipo de Comida', 'Fecha y Hora']);

        foreach ($comidas as $comida) {
            fputcsv($handle, [
                $comida->empleado->employee_name,
                $comida->tipo,
                $comida->created_at->format('Y-m-d H:i:s'),
            ]);
        }

        fclose($handle);

        return response()->download($filename)->deleteFileAfterSend(true);
    }
}
