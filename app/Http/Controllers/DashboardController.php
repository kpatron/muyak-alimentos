<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comida;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        //Comidas del dia de hoy
        $comidas = Comida::whereDate('created_at', Carbon::today())->count();

        //Comidas de la semana actual
        $comidasSemana = Comida::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

        //Comidas del mes actual
        $comidasMes = Comida::whereMonth('created_at', Carbon::now()->month)->count();

        //Comidas mes anterior
        $comidasMesAnterior = Comida::whereMonth('created_at', Carbon::now()->subMonth()->month)->count();

        //Comidas de los ultimos 6 meses
        foreach (range(0, 5) as $i) {
            $mes = Carbon::now()->subMonths($i)->format('F');
            $comidasMeses[$mes] = Comida::whereBetween('created_at', [Carbon::now()->subMonths($i)->startOfMonth(), Carbon::now()->subMonths($i)->endOfMonth()])->count();
        }

        return view('dashboard', compact('comidas', 'comidasSemana', 'comidasMes', 'comidasMesAnterior', 'comidasMeses'));
    }
}
