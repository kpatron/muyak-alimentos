<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ComidaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpleadoController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware' => 'auth'], function () {

    Route::get('/', [HomeController::class, 'home']);

	Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/logout', [SessionsController::class, 'destroy']);

    Route::get('/login', function () {
		return view('dashboard');
	})->name('sign-up');

	Route::resource('empleados', EmpleadoController::class);

	Route::get('/comidas-empleados', [ComidaController::class, 'index'])->name('comidas-empleados.index');
	Route::get('/comidas/export', [ComidaController::class, 'export'])->name('comidas-empleados.export');
});



Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [SessionsController::class, 'create']);
    Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');

});

Route::get('/login', function () {
    return view('session/login-session');
})->name('login');

Route::get('/comidas-empleados/{signature}', [ComidaController::class, 'confirm'])->name('comidas-empleados.info');
Route::post('/comidas-empleados/{signature}/confirm', [ComidaController::class, 'final_confirmation'])->name('comidas-empleados.confirmation');
Route::view('/comidas-success', 'comidas.success')->name('comidas-empleados.success');
Route::view('/comidas-invalid', 'comidas.invalid')->name('comidas-empleados.invalid');