<?php


use App\Http\Controllers\Cvh;
use App\Http\Controllers\Cal;
use App\Http\Controllers\Csuspension;
use App\Http\Controllers\Csonometro;
use App\Http\Controllers\Ctaximetro;
use App\Http\Controllers\Cfrenometro;
use App\Http\Controllers\Copacidad;
use App\Http\Controllers\Clogin;
use App\Http\Controllers\Cgases;
use App\Http\Controllers\Cgasesmotos;
use App\Http\Controllers\Cluces;
use App\Http\Controllers\Clucesmotos;
use App\Http\Controllers\Cprincipal;
use App\Http\Controllers\Cfrenomotos;
use App\Http\Controllers\Cfrenomotocarro;
use App\Http\Controllers\Cvisual;
use App\Http\Controllers\Cactualizar;
use App\Http\Controllers\Calibracion;
use App\Http\Controllers\Cfotos;
use Illuminate\Support\Facades\Route;
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

Route::get('/', function () {
    return view('welcome');
});
Route::resource('/', Clogin::class);
Route::resource('al', Cal::class);
Route::resource('su', Csuspension::class);
Route::resource('op', Copacidad::class);
Route::resource('so', Csonometro::class);
Route::resource('tax', Ctaximetro::class);
Route::resource('fr', Cfrenometro::class);
Route::resource('frm', Cfrenomotos::class);
Route::resource('ga', Cgases::class);
Route::resource('gam', Cgasesmotos::class);
Route::resource('lu', Cluces::class);
Route::resource('lum', Clucesmotos::class);
Route::resource('cpr', Cprincipal::class);
Route::resource('frmotocarro', Cfrenomotocarro::class);
Route::resource('visual', Cvisual::class);
Route::resource('update', Cactualizar::class);
Route::resource('cal', Calibracion::class);
Route::resource('fot', Cfotos::class);

Route::get('/close', [Clogin::class, 'cerrarSesion']);
Route::post('/buscarvehiculo', [Cprincipal::class, 'getVehiculo']);
Route::post('/getMaquina', [Cprincipal::class, 'getMaquina']);
Route::post('/getActualizacion', [Cactualizar::class, 'getActualizacion']);
Route::post('/getCalibracion', [Calibracion::class, 'getCalibracion']);

Route::post('/consultarImagen', [Cfotos::class, 'consultarImagen']);

Route::post('/getevento', [Cprincipal::class, 'eventosindra']);
Route::post('/getlineas', [Cprincipal::class, 'getlineas']);
Route::get('/getmac', [Clogin::class, 'getMac']);
// Route::get('index.php/getSession', [Clogin::class, 'getSession']);
Route::get('/getSession', [Clogin::class, 'getSession']);
Route::post('/getDefectos', [Cvisual::class, 'getDefectos']);
Route::post('/updateObservacion', [Cvisual::class, 'updateObservacion']);
Route::post('/saveObservacionAdicional', [Cvisual::class, 'saveObservacionAdicional']);
Route::post('/deleteDefectos', [Cvisual::class, 'deleteDefectos']);
Route::post('/saveDefectos', [Cvisual::class, 'saveDefectos']);
Route::post('/saveLabrado', [Cvisual::class, 'saveLabrado']);
//Route::resource('principal', Cprincipal::class);