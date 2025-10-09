<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empleados = Empleado::all();
        return view('empleados.index', compact('empleados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_name' => 'required|string|max:255',
            'employee_area' => 'required|string|max:50',
            'double_meal' => 'nullable|boolean',
        ]);

        //CREATE A SPECIFIC SIGNATURE AND QR CODE FOR THE EMPLOYEE
        $signature = $this->generateSignature(time());
        $url = route('comidas-empleados.info', ['signature' => $signature]);
        $qrCode = $this->generateQrCode($url);

        $empleado = new Empleado();
        $empleado->employee_name = $request->input('employee_name');
        $empleado->employee_signature = $signature;
        $empleado->qr_code = $qrCode;
        $empleado->employee_area = $request->input('employee_area');
        $empleado->double_meal = $request->has('double_meal') ? $request->input('double_meal') : 0;
        $empleado->save();

        return redirect()->route('empleados.index')->with('success', 'Empleado creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'employee_name' => 'required|string|max:255',
            'employee_area' => 'required|string|max:50',
            'double_meal' => 'nullable|boolean',
        ]);

        $empleado = Empleado::findOrFail($id);
        $empleado->employee_name = $request->input('employee_name');
        $empleado->employee_area = $request->input('employee_area');
        $empleado->double_meal = $request->has('double_meal') ? $request->input('double_meal') : 0;
        $empleado->save();

        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Generate a unique signature for the employee.
     */
    private function generateSignature(int $firma): string
    {
        return hash('sha256', $firma);
    }

    /**
     * Generate a QR code for the employee.
     */
    private function generateQrCode($url): string
    {
        //DO A GET REQUEST TO THE API TO GENERATE THE QR CODE
        return "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($url);
    }
}
