<?php

namespace App\Http\Controllers;

use App\Models\Malineacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Undefined;
use Illuminate\Support\Facades\Http;

use App\Libraries\Encrypt;
use App\Traits\EventosTrait;

class Cprincipal extends Controller
{

    var $key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQSflKxwRJSMeKKF2QT4fwpMeJf36POk6yJVadQssw5c";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    var $data;
    use EventosTrait;

    public function index()
    {
        
        if (session('sesionUser')) {
            return view('layout.Vprincipal');
        } else {
            return redirect()->intended('/');
        }
    }

    public function getVehiculo(Request $request)
    {
        // $r = DB::select("SELECT r.tiporesultado, r.valor, r.observacion, r.idconfig_prueba  FROM vehiculos v, hojatrabajo h, pruebas p, resultados r
        //                     WHERE v.idvehiculo = h.idvehiculo AND h.idhojapruebas = p.idhojapruebas AND p.idprueba = r.idprueba AND 
        //                     p.idtipo_prueba = " . $request->input('idtipo_prueba') . " AND v.numero_placa = '" . $request->input('placa') . "' AND p.estado = 2  ORDER BY h.idhojapruebas DESC ");
        $r = DB::select("SELECT h.idhojapruebas, r.tiporesultado, r.valor, r.observacion, r.idconfig_prueba, p.estado, r.idresultados, p.idprueba 
                                FROM vehiculos v
                                INNER JOIN hojatrabajo h ON v.idvehiculo = h.idvehiculo
                                INNER JOIN pruebas p ON h.idhojapruebas = p.idhojapruebas
                                INNER JOIN resultados r ON p.idprueba = r.idprueba
                                WHERE p.idtipo_prueba = " . $request->input('idtipo_prueba') . " 
                                AND v.numero_placa = '" . $request->input('placa') . "' 
                                AND p.estado IN (1,2,9)
                                AND h.idhojapruebas = (
                                    SELECT MAX(h2.idhojapruebas)
                                    FROM hojatrabajo h2
                                    INNER JOIN vehiculos v2 ON h2.idvehiculo = v2.idvehiculo
                                    INNER JOIN pruebas p2 ON h2.idhojapruebas = p2.idhojapruebas
                                    WHERE v2.numero_placa = '" . $request->input('placa') . "'
                                    AND p2.idtipo_prueba = " . $request->input('idtipo_prueba') . "  
                                    AND p2.estado IN (1, 2, 9)
                                ); ");
        // $r = DB::select("SELECT r.* FROM vehiculos v, hojatrabajo h, pruebas p, resultados r
        // WHERE v.idvehiculo = h.idvehiculo AND h.idhojapruebas = p.idhojapruebas AND p.idprueba = r.idprueba AND 
        // p.idtipo_prueba = 10 AND v.numero_placa = 'aaa001' AND p.estado = 2  ORDER BY h.idhojapruebas DESC");

        //var_dump($r);
        echo json_encode($r);
    }

    public function eventosindra(Request $request)
    {
        if (sicov() == 'INDRA') {
            date_default_timezone_set('America/bogota');
            $date = date("Y-m-d H:i:s");
            // var_dump($request->all());
            $idmaquina = explode('|', request()->input('idmaquina'))[0];
            $idmaquina = intval($idmaquina);
            $seriemaquina = explode('|', request()->input('idmaquina'))[1];
            $idrunt = env('ID_RUNT');

            $cadenasicov = "862|" . $date . "|" . $request->input('prueba') . "|" . $request->input('placa') . "|" . $seriemaquina . "|1|" . $idrunt;
            //var_dump($cadenasicov);
            DB::insert("INSERT INTO eventosindra VALUES (NULL,'" . $request->input('placa') . '-' . $request->input('prueba') . "','" . $cadenasicov . "','" . $date . "','e',0,'Operación pendiente')");

            echo json_encode(1);
        } else {
            $this->eventosci2($request);
        }
    }

    // public function eventosci2(Request $request)
    // {
    //     date_default_timezone_set('America/bogota');
    //     $ipBaseDatos = env('SERVER_HOST');
    //     $url = "http://{$ipBaseDatos}/et/index.php/oficina/ci2/Cci2/ciclo_prueba";

    //     $response = Http::post($url, [
    //         'tipoPrueba' => $request->input('tipopruebaCi2'),
    //         'placa' => $request->input('placa'),
    //         'serialEquipo' => explode('-', $request->input('idmaquina'))[1],
    //         'idEvento' => 1,
    //         'mensajeEvento' => "INICIO DE PRUEBA"
    //     ]);

    //     // Decodificar la respuesta
    //     $respuesta = $response->json();
    //     var_dump($respuesta);

    //     // Verificar si hay error (tanto por HTTP como por contenido)
    //     // if (!$response->successful() || (isset($respuesta['success']) && $respuesta['success'] === false)) {
    //     //     // Obtener el mensaje de error
    //     //     $errorMessage = 'Error desconocido';

    //     //     if (isset($respuesta['mensaje'])) {
    //     //         $errorMessage = $respuesta['mensaje'];
    //     //     } elseif (isset($respuesta['message'])) {
    //     //         $errorMessage = $respuesta['message'];
    //     //     } elseif (!$response->successful()) {
    //     //         $errorMessage = 'Error HTTP: ' . $response->status();
    //     //     }

    //     //     // IMPORTANTE: Forzar código 400 y devolver SOLO el mensaje
    //     //     header('HTTP/1.1 400 Bad Request');
    //     //     header('Content-Type: application/json');
    //     //     echo json_encode(['message' => $errorMessage]);
    //     //     exit(); // Terminar la ejecución
    //     // }

    //     // // Éxito
    //     // return response()->json(['success' => true, 'data' => $respuesta]);
    // }

    public function eventosci2(Request $request)
    {
        date_default_timezone_set('America/bogota');
        $date = date("Ymd H:i:s");
        // Obtener la IP desde el .env (DB_HOST)
        $ipBaseDatos = env('SERVER_HOST');

        $url = "http://{$ipBaseDatos}/et/index.php/oficina/ci2/Cci2/ciclo_prueba";

        // Hacer la petición GET con los parámetros
        $response = Http::post($url, [
            // 'fecha' => $date,
            'tipoPrueba' => $request->input('tipopruebaCi2'),
            'placa' => $request->input('placa'),
            'serialEquipo' =>  explode('|', request()->input('idmaquina'))[1],
            'idEvento' => '1',
            'mensajeEvento' => "INICIO DE PRUEBA"
        ]);
        // var_dump($response->successful());
        //  var_dump($response->body());



        // Verificar si fue exitosa
        if ($response->successful()) {
            $respuesta = $response->body();
            echo json_encode(['success' => true, 'data' => $respuesta]);
        } else {
            echo json_encode(['error' => false, 'error' => $response->body()]);
        }
    }

    public function getMaquina(Request $request)
    {
        $encrypt = new Encrypt();
        $jsonPath = storage_path('app/system/lineas.json');
        $jsonContent = file_get_contents($jsonPath, true);
        $json = json_decode($encrypt->decrypt($jsonContent)); // Objetos stdClass

        $data['maquinas'] = [];

        // Recorrer y agregar cada máquina al array
        foreach ($json as $indice => $objeto) {
            if ($objeto->conf_idtipo_prueba == 10 && $objeto->activo == 1) {
                // Crear un objeto con la estructura que espera la vista
                $data['maquinas'][] = (object)[
                    'idmaquina' => $objeto->idconf_maquina . '|' . strtoupper($objeto->serie_maquina),
                    'maquina' => strtoupper($objeto->nombre) . '-' . strtoupper($objeto->marca) . '-' . strtoupper($objeto->serie_maquina) . '-' . strtoupper($objeto->serie_banco)
                ];
            }
        }
        if ($request->has('desdemixta') && $request->input('desdemixta') !== ""  && $request->input('desdemixta') == 1) {
            if ($request->input('motocarro') == 1) {
                foreach ($json as $indice => $objeto) {
                    if ($objeto->conf_idtipo_prueba == $request->input('idtipo_prueba') && $objeto->activo == 1) {
                        $maquina[] = (object)[
                            'idmaquina' => $objeto->idconf_maquina . '|' . strtoupper($objeto->serie_maquina),
                            'maquina' => strtoupper($objeto->nombre) . '-' . strtoupper($objeto->marca) . '-' . strtoupper($objeto->serie_maquina) . '-' . strtoupper($objeto->serie_banco)
                        ];
                    }
                }
                // $maquina = DB::select("select  m.idmaquina, concat(m.nombre, ' ', m.marca, ' ', m.serie) as 'maquina' from maquina m where m.estado = 1 and m.idtipo_prueba = " . $request->input('idtipo_prueba'));
            } else {
                foreach ($json as $indice => $objeto) {
                    if (
                        $objeto->conf_idtipo_prueba == $request->input('idtipo_prueba') && $objeto->activo == 1 &&

                        (
                            $objeto->idconf_linea_inspeccion == 1 ||
                            $objeto->idconf_linea_inspeccion == 7 ||
                            $objeto->idconf_linea_inspeccion == 8 ||
                            $objeto->idconf_linea_inspeccion == 4 ||
                            $objeto->idconf_linea_inspeccion == 11 ||
                            $objeto->idconf_linea_inspeccion == 12
                        )

                    ) {
                        $maquina[] = (object)[
                            'idmaquina' => $objeto->idconf_maquina . '|' . strtoupper($objeto->serie_maquina),
                            'maquina' => strtoupper($objeto->nombre) . '-' . strtoupper($objeto->marca) . '-' . strtoupper($objeto->serie_maquina) . '-' . strtoupper($objeto->serie_banco)
                        ];
                    }
                }
                // $maquina = DB::select("select  m.idmaquina, concat(m.nombre, ' ', m.marca, ' ', m.serie) as 'maquina' from maquina m where m.estado = 1 and m.idtipo_prueba = " . $request->input('idtipo_prueba') . " and (m.idbanco = 2 or m.idbanco = 1)");
            }
        } else {
            if ($request->input('motocarro') == 1) {
                foreach ($json as $indice => $objeto) {
                    if (
                        $objeto->conf_idtipo_prueba == $request->input('idtipo_prueba') && $objeto->activo == 1 &&

                        (
                            $objeto->idconf_linea_inspeccion == 1 ||
                            $objeto->idconf_linea_inspeccion == 7 ||
                            $objeto->idconf_linea_inspeccion == 8 ||
                            $objeto->idconf_linea_inspeccion == 4 ||
                            $objeto->idconf_linea_inspeccion == 11 ||
                            $objeto->idconf_linea_inspeccion == 12
                        )

                    ) {
                        $maquina[] = (object)[
                            'idmaquina' => $objeto->idconf_maquina . '|' . strtoupper($objeto->serie_maquina),
                            'maquina' => strtoupper($objeto->nombre) . '-' . strtoupper($objeto->marca) . '-' . strtoupper($objeto->serie_maquina) . '-' . strtoupper($objeto->serie_banco)
                        ];
                    }
                }
                // $maquina = DB::select("select  m.idmaquina, concat(m.nombre, ' ', m.marca, ' ', m.serie) as 'maquina' from maquina m where m.estado = 1 and m.idtipo_prueba = " . $request->input('idtipo_prueba'));
            } else {
                foreach ($json as $indice => $objeto) {
                    if (
                        $objeto->conf_idtipo_prueba == $request->input('idtipo_prueba') && $objeto->activo == 1 &&

                        (
                            $objeto->idconf_linea_inspeccion == 3 ||
                            $objeto->idconf_linea_inspeccion == 9 ||
                            $objeto->idconf_linea_inspeccion == 10
                        )

                    ) {
                        $maquina[] = (object)[
                            'idmaquina' => $objeto->idconf_maquina . '-' . strtoupper($objeto->serie_maquina),
                            'maquina' => strtoupper($objeto->nombre) . '-' . strtoupper($objeto->marca) . '-' . strtoupper($objeto->serie_maquina) . '-' . strtoupper($objeto->serie_banco)
                        ];
                    }
                }
                // $maquina = DB::select("select  m.idmaquina, concat(m.nombre, ' ', m.marca, ' ', m.serie) as 'maquina' from maquina m where m.estado = 1 and m.idtipo_prueba = " . $request->input('idtipo_prueba') . " and m.idbanco = 3");
            }
        }
        echo json_encode($maquina);


        //$r = DB::select("SELECT valor AS 'serial' from config_prueba where idconfig_prueba=20000+" + $datos[0]->idmaquina + " LIMIT 1");
    }

    public function getlineas(Request $request)
    {

        $linea = $request->input('linea');
        $jsonPath = storage_path('app/system/lineas.json');
        file_put_contents($jsonPath, $linea);
        echo json_encode(1);
    }

      public function getPlacasByTipo(Request $request)
    {
        $tipoejecucion = $request->input('tipoejecucion') ?? $request->route('tipoejecucion');
        $tipoprueba = $request->input('tipoprueba') ?? $request->route('tipoprueba');
        $tipovehiculo = $request->input('tipovehiculo') ?? $request->route('tipovehiculo');
        $requestParams = new Request();
        $requestParams->merge([
            'tipoprueba' => $tipoprueba,
            'tipovehiculo' => $tipovehiculo,
            'tipoejecucionprueba' => $tipoejecucion
        ]);

        $placas = $this->getPlacas($requestParams);
        return response()->json($placas);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {}

    public function getResultados() {}

    public function encriptaResult() {}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
