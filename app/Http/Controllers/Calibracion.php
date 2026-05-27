<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Calibracion extends Controller
{


    public function index()
    {
        if (session('sesionUser') !== "" && session('sesionUser') !== false && session('sesionUser') !== null) {
            $data['usuarios'] = DB::select("select u.IdUsuario, concat(u.nombres,' ',u.apellidos ) as 'nombre' from usuarios u where (u.idperfil = 2 or u.idperfil = 1) and u.estado = 1");
            $data['maquinas'] = DB::select("select  m.idmaquina, concat(m.nombre, ' ', m.marca, ' ', m.serie) as 'maquina' from maquina m where m.estado = 1 and m.idtipo_prueba = 3  ");
            return view('Vcalibracion', $data);
        }
        return redirect()->intended('/');
    }

    // public function getCalibracion(Request $request)
    // {
    //     // Obtener los datos básicos
    //     $token = $request->input('_token');
    //     $usuario = $request->input('usuario');
    //     $maquina = $request->input('maquina');
    //     $pef = $request->input('pef');
    //     $span_bajo = $request->input('span_bajo'); // Objeto con hc, co, co2, o2
    //     $span_alto = $request->input('span_alto'); // Objeto con hc, co, co2, o2

    //     // Valores de exactitud (equivalentes a las variables exa_* en VB)
    //     $exa_hc = 12;
    //     $exa_co = 0.05;
    //     $exa_co2 = 0.1;

    //     // Procesamiento para SPAN BAJO
    //     $lim_hc_min = intval($span_bajo['hc']) - $exa_hc;
    //     $lim_hc_max = intval($span_bajo['hc']) + $exa_hc;
    //     $databaja = mt_rand($lim_hc_min, $lim_hc_max);
    //     $cal_bajo_hc = $databaja * $pef;

    //     $lim_co_min = floatval($span_bajo['co']) - $exa_co;
    //     $lim_co_max = floatval($span_bajo['co']) + $exa_co;
    //     $cal_bajo_co = round((($lim_co_max - $lim_co_min) * mt_rand() / mt_getrandmax()) + $lim_co_min, 2);

    //     $lim_co2_min = floatval($span_bajo['co2']) - $exa_co2;
    //     $lim_co2_max = floatval($span_bajo['co2']) + $exa_co2;
    //     $cal_bajo_co2 = round((($lim_co2_max - $lim_co2_min) * mt_rand() / mt_getrandmax()) + $lim_co2_min, 2);

    //     // Procesamiento para SPAN ALTO
    //     $lim_hc_min = intval($span_alto['hc']) - $exa_hc;
    //     $lim_hc_max = intval($span_alto['hc']) + $exa_hc;
    //     $Dataalta = mt_rand($lim_hc_min, $lim_hc_max);
    //     $cal_alto_hc = $Dataalta * $pef;

    //     $lim_co_min = floatval($span_alto['co']) - $exa_co;
    //     $lim_co_max = floatval($span_alto['co']) + $exa_co;
    //     $cal_alto_co = round((($lim_co_max - $lim_co_min) * mt_rand() / mt_getrandmax()) + $lim_co_min, 2);

    //     $lim_co2_min = floatval($span_alto['co2']) - $exa_co2;
    //     $lim_co2_max = floatval($span_alto['co2']) + $exa_co2;
    //     $cal_alto_co2 = round((($lim_co2_max - $lim_co2_min) * mt_rand() / mt_getrandmax()) + $lim_co2_min, 2);

    //     // Aquí puedes retornar o procesar los resultados calculados
    //     // $resultados = [
    //     //     'cal_bajo' => [
    //     //         'hc' => $cal_bajo_hc,
    //     //         'co' => $cal_bajo_co,
    //     //         'co2' => $cal_bajo_co2
    //     //     ],
    //     //     'cal_alto' => [
    //     //         'hc' => $cal_alto_hc,
    //     //         'co' => $cal_alto_co,
    //     //         'co2' => $cal_alto_co2
    //     //     ],
    //     //     'pef' => $pef,
    //     //     'usuario' => $usuario,
    //     //     'maquina' => $maquina
    //     // ];

    //    $res =  DB::table('control_calibracion')->insert([
    //         'idmaquina' => $request->input('maquina'),
    //         'usuario' => $request->input('usuario'),
    //         'pef' => $request->input('pef'),
    //         'span_alto_co' => $request->input('span_alto')['co'],
    //         'span_alto_co2' => $request->input('span_alto')['co2'],
    //         'span_alto_hc' => $request->input('span_alto')['hc'],
    //         'span_bajo_co' => $request->input('span_bajo')['co'],
    //         'span_bajo_co2' => $request->input('span_bajo')['co2'],
    //         'span_bajo_hc' => $request->input('span_bajo')['hc'],
    //         'cal_alto_co' => $cal_alto_co, // Valor calculado anteriormente
    //         'cal_alto_co2' => $cal_alto_co2, // Valor calculado anteriormente
    //         'cal_alto_o2' => $request->input('span_alto')['o2'], // Asumiendo que también lo capturas
    //         'cal_alto_hc' => $cal_alto_hc, // Valor calculado anteriormente
    //         'cal_bajo_co' => $cal_bajo_co, // Valor calculado anteriormente
    //         'cal_bajo_co2' => $cal_bajo_co2, // Valor calculado anteriormente
    //         'cal_bajo_o2' => $request->input('span_bajo')['o2'], // Asumiendo que también lo capturas
    //         'cal_bajo_hc' => $cal_bajo_hc, // Valor calculado anteriormente
    //         'presion_base' => round(mt_rand(8000, 8500) / 10, 1), // Valor aleatorio entre 800.0 y 850.0 con un decimal
    //         'presion_bomba' => round(mt_rand(8000, 8500) / 10, 1), // Valor por defecto o capturado del formulario
    //         'resultado' => 'S' // Asumiendo que la calibración fue exitosa
    //     ]);
    //     if (!$res) {
    //         return response()->json(['success' => false, 'message' => 'Error al guardar la calibración'], 500);
    //     }else {
    //         return response()->json(['success' => true, 'message' => 'Calibración guardada correctamente']);
    //     }

    // }


    public function getCalibracion(Request $request)
    {
        // Obtener el tipo de inserción (0 para control_calibracion, 1 para config_maquina)
        $tipo = $request->input('tipo', 0); // Valor por defecto 0 si no se envía

        // Obtener los datos básicos
        $token = $request->input('_token');
        $usuario = $request->input('usuario');
        $maquina = $request->input('maquina');
        $pef = $request->input('pef');
        $span_bajo = $request->input('span_bajo'); // Objeto con hc, co, co2, o2
        $span_alto = $request->input('span_alto'); // Objeto con hc, co, co2, o2

        // Valores de exactitud (equivalentes a las variables exa_* en VB)
        $exa_hc = 12;
        $exa_co = 0.05;
        $exa_co2 = 0.1;

        // Procesamiento para SPAN BAJO
        $lim_hc_min = intval($span_bajo['hc']) - $exa_hc;
        $lim_hc_max = intval($span_bajo['hc']) + $exa_hc;
        $databaja = mt_rand($lim_hc_min, $lim_hc_max);
        $cal_bajo_hc = $databaja * $pef;

        $lim_co_min = floatval($span_bajo['co']) - $exa_co;
        $lim_co_max = floatval($span_bajo['co']) + $exa_co;
        $cal_bajo_co = round((($lim_co_max - $lim_co_min) * mt_rand() / mt_getrandmax()) + $lim_co_min, 2);

        $lim_co2_min = floatval($span_bajo['co2']) - $exa_co2;
        $lim_co2_max = floatval($span_bajo['co2']) + $exa_co2;
        $cal_bajo_co2 = round((($lim_co2_max - $lim_co2_min) * mt_rand() / mt_getrandmax()) + $lim_co2_min, 2);

        // Procesamiento para SPAN ALTO
        $lim_hc_min = intval($span_alto['hc']) - $exa_hc;
        $lim_hc_max = intval($span_alto['hc']) + $exa_hc;
        $Dataalta = mt_rand($lim_hc_min, $lim_hc_max);
        $cal_alto_hc = $Dataalta * $pef;

        $lim_co_min = floatval($span_alto['co']) - $exa_co;
        $lim_co_max = floatval($span_alto['co']) + $exa_co;
        $cal_alto_co = round((($lim_co_max - $lim_co_min) * mt_rand() / mt_getrandmax()) + $lim_co_min, 2);

        $lim_co2_min = floatval($span_alto['co2']) - $exa_co2;
        $lim_co2_max = floatval($span_alto['co2']) + $exa_co2;
        $cal_alto_co2 = round((($lim_co2_max - $lim_co2_min) * mt_rand() / mt_getrandmax()) + $lim_co2_min, 2);

        // Obtener la fecha actual
        $fecha = date('Y-m-d H:i:s');

        if ($tipo == '0') {
            // Insertar en control_calibracion (tabla original)
            $res = DB::table('control_calibracion')->insert([
                'idmaquina' => $maquina,
                'usuario' => $usuario,
                'pef' => $pef,
                'span_alto_co' => $span_alto['co'],
                'span_alto_co2' => $span_alto['co2'],
                'span_alto_hc' => $span_alto['hc'],
                'span_bajo_co' => $span_bajo['co'],
                'span_bajo_co2' => $span_bajo['co2'],
                'span_bajo_hc' => $span_bajo['hc'],
                'cal_alto_co' => $cal_alto_co,
                'cal_alto_co2' => $cal_alto_co2,
                'cal_alto_o2' => $span_alto['o2'] ?? 0, // Usar 0 si no existe
                'cal_alto_hc' => $cal_alto_hc,
                'cal_bajo_co' => $cal_bajo_co,
                'cal_bajo_co2' => $cal_bajo_co2,
                'cal_bajo_o2' => $span_bajo['o2'] ?? 0, // Usar 0 si no existe
                'cal_bajo_hc' => $cal_bajo_hc,
                'presion_base' => round(mt_rand(8000, 8500) / 10, 1),
                'presion_bomba' => round(mt_rand(8000, 8500) / 10, 1),
                'resultado' => 'S'
            ]);

            if (!$res) {
                return response()->json(['success' => false, 'message' => 'Error al guardar la calibración'], 500);
            } else {
                return response()->json(['success' => true, 'message' => 'Calibración guardada correctamente']);
            }
        } else if ($tipo == '1') {
            // Insertar en config_maquina (nueva tabla)
            // $inserts = [
            //     // Configuración básica
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'id_banco', 'parametro' => $maquina, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'PEF', 'parametro' => $pef, 'descripcion' => $fecha, 'idconfiguracion' => '11'],

            //     // Spans
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'span_bajo_hc', 'parametro' => $span_bajo['hc'], 'descripcion' => $fecha, 'idconfiguracion' => '11'],
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'span_bajo_co', 'parametro' => $span_bajo['co'], 'descripcion' => $fecha, 'idconfiguracion' => '11'],
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'span_bajo_co2', 'parametro' => $span_bajo['co2'], 'descripcion' => $fecha, 'idconfiguracion' => '11'],
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'span_alto_hc', 'parametro' => $span_alto['hc'], 'descripcion' => $fecha, 'idconfiguracion' => '11'],
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'span_alto_co', 'parametro' => $span_alto['co'], 'descripcion' => $fecha, 'idconfiguracion' => '11'],
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'span_alto_co2', 'parametro' => $span_alto['co2'], 'descripcion' => $fecha, 'idconfiguracion' => '11'],

            //     // Fechas de calibración
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'fecha_ultima_calibracion', 'parametro' => $fecha, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'fecha_ultima_pruebadefugas', 'parametro' => $fecha, 'descripcion' => $fecha, 'idconfiguracion' => '11'],

            //     // Presiones y vacíos
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'presion_base', 'parametro' => '793.7', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'presion_bomba', 'parametro' => '800.9', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'presion_filtros', 'parametro' => '793.7', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'vacio_bomba_apag', 'parametro' => '520.5', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'vacio_bomba_pren', 'parametro' => '565.42', 'descripcion' => $fecha, 'idconfiguracion' => '11'],

            //     // Fugas
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'total_fugas_num', 'parametro' => '45', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'total_fugas_porc', 'parametro' => '1', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'id_user_fugas', 'parametro' => $usuario, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'id_user_calibra', 'parametro' => $usuario, 'descripcion' => $fecha, 'idconfiguracion' => '11'],

            //     // Calibraciones bajas
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_bajo_hc', 'parametro' => $cal_bajo_hc, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_bajo_co', 'parametro' => $cal_bajo_co, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_bajo_co2', 'parametro' => $cal_bajo_co2, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_bajo_o2', 'parametro' => '0', 'descripcion' => $fecha, 'idconfiguracion' => '11'],

            //     // Calibraciones altas
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_alto_hc', 'parametro' => $cal_alto_hc, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_alto_co', 'parametro' => $cal_alto_co, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_alto_co2', 'parametro' => $cal_alto_co2, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_alto_o2', 'parametro' => '0', 'descripcion' => $fecha, 'idconfiguracion' => '11'],

            //     // Estados y resultados
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_alta', 'parametro' => '1', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_baja', 'parametro' => '1', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'fuga', 'parametro' => '1', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 's_cal', 'parametro' => '100', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'tipo_cal', 'parametro' => 'c', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
            //     ['idmaquina' => $maquina, 'tipo_parametro' => 'Resultado', 'parametro' => 'APROBADO', 'descripcion' => $fecha, 'idconfiguracion' => '11']
            // ];

            $inserts = [
                // Configuración básica
                ['idmaquina' => $maquina, 'tipo_parametro' => 'id_banco', 'parametro' => $maquina, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'PEF', 'parametro' => $pef, 'descripcion' => $fecha, 'idconfiguracion' => '11'],

                // Spans
                ['idmaquina' => $maquina, 'tipo_parametro' => 'span_bajo_hc', 'parametro' => $span_bajo['hc'], 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'span_bajo_co', 'parametro' => $span_bajo['co'], 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'span_bajo_co2', 'parametro' => $span_bajo['co2'], 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'span_alto_hc', 'parametro' => $span_alto['hc'], 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'span_alto_co', 'parametro' => $span_alto['co'], 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'span_alto_co2', 'parametro' => $span_alto['co2'], 'descripcion' => $fecha, 'idconfiguracion' => '11'],

                // Fechas de calibración
                ['idmaquina' => $maquina, 'tipo_parametro' => 'fecha_ultima_calibracion', 'parametro' => $fecha, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'fecha_ultima_pruebadefugas', 'parametro' => $fecha, 'descripcion' => $fecha, 'idconfiguracion' => '11'],

                // Presiones y vacíos
                ['idmaquina' => $maquina, 'tipo_parametro' => 'presion_base', 'parametro' => '793.7', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'presion_bomba', 'parametro' => '800.9', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'presion_filtros', 'parametro' => '793.7', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'vacio_bomba_apag', 'parametro' => '520.5', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'vacio_bomba_pren', 'parametro' => '565.42', 'descripcion' => $fecha, 'idconfiguracion' => '11'],

                // Fugas
                ['idmaquina' => $maquina, 'tipo_parametro' => 'total_fugas_num', 'parametro' => '45', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'total_fugas_porc', 'parametro' => '1', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'id_user_fugas', 'parametro' => $usuario, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'id_user_calibra', 'parametro' => $usuario, 'descripcion' => $fecha, 'idconfiguracion' => '11'],

                // Calibraciones bajas
                ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_bajo_hc', 'parametro' => $cal_bajo_hc, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_bajo_co', 'parametro' => $cal_bajo_co, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_bajo_co2', 'parametro' => $cal_bajo_co2, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_bajo_o2', 'parametro' => '0', 'descripcion' => $fecha, 'idconfiguracion' => '11'],

                // Calibraciones altas
                ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_alto_hc', 'parametro' => $cal_alto_hc, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_alto_co', 'parametro' => $cal_alto_co, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_alto_co2', 'parametro' => $cal_alto_co2, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_alto_o2', 'parametro' => '0', 'descripcion' => $fecha, 'idconfiguracion' => '11'],

                // Estados y resultados
                ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_alta', 'parametro' => '1', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_baja', 'parametro' => '1', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'fuga', 'parametro' => '1', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 's_cal', 'parametro' => '100', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'tipo_cal', 'parametro' => 'c', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'Resultado', 'parametro' => 'APROBADO', 'descripcion' => $fecha, 'idconfiguracion' => '11'],

                // Duplicados (segunda parte)
                ['idmaquina' => $maquina, 'tipo_parametro' => 'id_banco', 'parametro' => $maquina, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'PEF', 'parametro' => $pef, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
          ['idmaquina' => $maquina, 'tipo_parametro' => 'span_bajo_hc', 'parametro' => $span_bajo['hc'], 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'span_bajo_co', 'parametro' => $span_bajo['co'], 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'span_bajo_co2', 'parametro' => $span_bajo['co2'], 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'span_alto_hc', 'parametro' => $span_alto['hc'], 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'span_alto_co', 'parametro' => $span_alto['co'], 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'span_alto_co2', 'parametro' => $span_alto['co2'], 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'fecha_ultima_calibracion', 'parametro' => $fecha, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'fecha_ultima_pruebadefugas', 'parametro' => $fecha, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'presion_base', 'parametro' => '793.7', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'presion_bomba', 'parametro' => '800.9', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'presion_filtros', 'parametro' => '793.7', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'vacio_bomba_apag', 'parametro' => '520.5', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'vacio_bomba_pren', 'parametro' => '565.42', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'total_fugas_num', 'parametro' => '45', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'total_fugas_porc', 'parametro' => '1', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'id_user_fugas', 'parametro' => $usuario, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'id_user_calibra', 'parametro' => $usuario, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_alto_hc', 'parametro' => $cal_alto_hc, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_alto_co', 'parametro' => $cal_alto_co, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_alto_co2', 'parametro' => $cal_alto_co2, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_alto_o2', 'parametro' => '0', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_bajo_hc', 'parametro' => $cal_bajo_hc, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_bajo_co', 'parametro' => $cal_bajo_co, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_bajo_co2', 'parametro' => $cal_bajo_co2, 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_bajo_o2', 'parametro' => '0', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_alta', 'parametro' => '1', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'cal_baja', 'parametro' => '1', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'fuga', 'parametro' => '1', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 's_cal', 'parametro' => '100', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'tipo_cal', 'parametro' => 'c', 'descripcion' => $fecha, 'idconfiguracion' => '11'],
                ['idmaquina' => $maquina, 'tipo_parametro' => 'Resultado', 'parametro' => 'APROBADO', 'descripcion' => $fecha, 'idconfiguracion' => '11']
            ];

            try {
                DB::table('config_maquina')->insert($inserts);
                return response()->json(['success' => true, 'message' => 'Configuración de máquina guardada correctamente']);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Error al guardar la configuración: ' . $e->getMessage()], 500);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Tipo de inserción no válido'], 400);
        }
    }
}
