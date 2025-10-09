<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comida;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function employee(Request $request)
    {
        $fechaInicio = $request->input('start_date') ?? now()->toDateString();
        $fechaFin = $request->input('end_date') ?? now()->toDateString();

        $comidas = Comida::whereDate('created_at', '>=', $fechaInicio)
            ->whereDate('created_at', '<=', $fechaFin)
            ->with('empleado')
            ->get()
            ->groupBy('empleado.employee_name');
  
        return view('reportes.empleado', compact('fechaInicio', 'fechaFin', 'comidas'));
    }

    public function exportEmployee(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio') ?? now()->toDateString();
        $fechaFin = $request->input('fecha_fin') ?? now()->toDateString();

        $comidas = Comida::whereDate('created_at', '>=', $fechaInicio)
            ->whereDate('created_at', '<=', $fechaFin)
            ->with('empleado')
            ->get()
            ->groupBy('empleado.employee_name');

        $filename = 'reporte_concentrado_empleados_' . now()->format('Y_m_d') . '.csv';
        $handle = fopen(storage_path('app/public/' . $filename), 'w+');

        // Escribir encabezados
        fputcsv($handle, ['Empleado', 'Área', 'Total Comidas', 'Fechas a Considerar']);

        foreach ($comidas as $employee => $comida) {
            fputcsv($handle, [
                $employee,
                $comida->first()->empleado->employee_area,
                $comida->count(),
                $fechaInicio . ' a ' . $fechaFin
            ]);
        }

        fclose($handle);

        return response()->download(storage_path('app/public/' . $filename))->deleteFileAfterSend(true);
    }

    public function dateRange(Request $request)
    {
        $fechaInicio = $request->input('start_date') ?? now()->toDateString();
        $fechaFin = $request->input('end_date') ?? now()->toDateString();
        $tipoComida = $request->input('tipo') ?? 'all';

        $comidas = Comida::selectRaw('DATE(created_at) as date, COUNT(*) as total')
        ->whereDate('created_at', '>=', $fechaInicio)
        ->whereDate('created_at', '<=', $fechaFin)
        ->when($tipoComida !== 'all', function ($query) use ($tipoComida) {
            return $query->where('tipo', $tipoComida);
        })
        ->groupBy('date')
        ->get();

        return view('reportes.fechas', compact('fechaInicio', 'fechaFin', 'tipoComida', 'comidas'));
    }

    public function exportDateRange(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio') ?? now()->toDateString();
        $fechaFin = $request->input('fecha_fin') ?? now()->toDateString();
        $tipoComida = $request->input('tipo_comida') ?? 'all';

        $comidas = Comida::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereDate('created_at', '>=', $fechaInicio)
            ->whereDate('created_at', '<=', $fechaFin)
            ->when($tipoComida !== 'all', function ($query) use ($tipoComida) {
                return $query->where('tipo', $tipoComida);
            })
            ->groupBy('date')
            ->get();

        $filename = 'reporte_concentrado_fechas_' . now()->format('Y_m_d') . '.csv';
        $handle = fopen(storage_path('app/public/' . $filename), 'w+');

        // Escribir encabezados
        fputcsv($handle, ['Fecha', 'Total Comidas']);

        foreach ($comidas as $comida) {
            fputcsv($handle, [
                $comida->date,
                $comida->total
            ]);
        }

        fclose($handle);

        return response()->download(storage_path('app/public/' . $filename))->deleteFileAfterSend(true);
    }
}
