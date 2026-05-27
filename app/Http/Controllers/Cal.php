<?php

namespace App\Http\Controllers;

use App\Models\Malineacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use App\Libraries\Encrypt;
use App\Traits\EventosTrait;

class Cal extends Controller
{
    use EventosTrait;

    //    var $key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQSflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c";
    var $key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQSflKxwRJSMeKKF2QT4fwpMeJf36POk6yJVadQssw5c";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {


        if (session('sesionUser') !== "" && session('sesionUser') !== false && session('sesionUser') !== null) {

            $encrypt = new Encrypt();

            // Obtener la IP del servidor desde .env

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

            $request = new Request();
            $request->merge([
                'tipoprueba' => '10',
                'tipovehiculo' => '1',
                'tipoejecucionprueba' => 'corregir'
            ]);

            $data['placas'] = $this->getPlacas($request);

            $data['usuarios'] = DB::select("select u.IdUsuario, concat(u.nombres,' ',u.apellidos ) as 'nombre' from usuarios u where u.idperfil = 2 and u.estado = 1");

            return view('TipoPrueba.alineacion', $data);
        } else {
            return redirect()->intended('/');
        }
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
    public function store(Request $request)
    {
        $Mal = new Malineacion();
        $data = request()->except('_token');
        //validacion de campos
        $validated = $request->validate([
            'eje1' => 'required',
            'eje2' => 'required',
            'idprueba' => 'required',
            'selEstado' => 'required',
            'selUsuario' => 'required',
            'selMaquina' => 'required',
        ]);
        $idmaquina = explode('|', request()->input('selMaquina'))[0];
        $idmaquina = intval($idmaquina);
        // var_dump($idmaquina);

        date_default_timezone_set('America/bogota');
        $now = date("Y-m-d H:i:s");

        if (request()->input('tipoejecucionprueba') == 'corregir') {
            $idprueba = request()->input('idprueba');
            try {
                DB::delete("DELETE FROM resultados WHERE idprueba = $idprueba");
                if (versionAplicaction() == 0) {
                    DB::delete("DELETE FROM control_alineacion WHERE placa = '" . request()->input('placa') . "' AND idmaquina = " . $idmaquina);
                }
            } catch (\Exception $e) {
                return back()->with("error", "Error al corregir: " . $e->getMessage());
            }
        }
        //        $now = date('Y-m-d H:i:s'); //Fomat Date and time
        if (versionAplicaction() == 1) {
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'Version software', '1.0' , '" . $now . "', 'EasyTecmmas', '100', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'Version software', '1.0', $now, 'EasyTecmmas', '100') . "','" . $this->key . "'))");
        }
        DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '1', " . request()->input('eje1') . " , '" . $now . "', 'Alineacion eje 1', '141', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '1', request()->input('eje1'), $now, 'Alineacion eje 1', '141') . "','" . $this->key . "'))");
        if (versionAplicaction() == 0) {
            $logeje1 = $this->generarLogNatural(request()->input('eje1'));
            $logeje1_json = json_encode($logeje1);
            DB::insert("INSERT INTO control_alineacion VALUES (NULL,'" . request()->input('placa') . "' , " . $idmaquina . " , '" . $now . "',  1 , " . request()->input('eje1') . "  , '" . $logeje1_json . "')");
        }

        //eje 2
        DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '2', " . request()->input('eje2') . " , '" . $now . "', 'Alineacion eje 2', '141', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '2', request()->input('eje2'), $now, 'Alineacion eje 2', '141') . "','" . $this->key . "'))");
        if (versionAplicaction() == 0) {
            $logeje2 = $this->generarLogNatural(request()->input('eje2'));
            $logeje2_json = json_encode($logeje2);
            DB::insert("INSERT INTO control_alineacion VALUES (NULL,'" . request()->input('placa') . "' , " . $idmaquina . " , '" . $now . "',  2 , " . request()->input('eje2') . "  , '" . $logeje2_json . "')");
        }

        //insert Al Eje3
        if (request()->input('eje3') != "" || request()->input('eje3') != null) {
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '3', " . request()->input('eje3') . " , '" . $now . "', 'Alineacion eje 3', '141', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '3', request()->input('eje3'), $now, 'Alineacion eje 3', '141') . "','" . $this->key . "'))");
            if (versionAplicaction() == 0) {
                $logeje3 = $this->generarLogNatural(request()->input('eje3'));
                $logeje3_json = json_encode($logeje3);
                DB::insert("INSERT INTO control_alineacion VALUES (NULL,'" . request()->input('placa') . "' , " . $idmaquina . " , '" . $now . "',  3 , " . request()->input('eje3') . "  , '" . $logeje3_json . "')");
            }
        }
        //insert Al Eje4
        if (request()->input('eje4') != "" || request()->input('eje4') != null) {

            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '4', " . request()->input('eje4') . " , '" . $now . "', 'Alineacion eje 4', '141', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '4', request()->input('eje4'), $now, 'Alineacion eje 4', '141') . "','" . $this->key . "'))");
            if (versionAplicaction() == 0) {
                $logeje4 = $this->generarLogNatural(request()->input('eje4'));
                $logeje4_json = json_encode($logeje4);
                DB::insert("INSERT INTO control_alineacion VALUES (NULL,'" . request()->input('placa') . "' , " . $idmaquina . " , '" . $now . "',  4 , " . request()->input('eje4') . "  , '" . $logeje4_json . "')");
            }
        }
        //insert Al Eje5
        if (request()->input('eje5') != "" || request()->input('eje5') != null) {
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '5', " . request()->input('eje5') . " , '" . $now . "', 'Alineacion eje 5', '141', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '5', request()->input('eje5'), $now, 'Alineacion eje 5', '141') . "','" . $this->key . "'))");
            if (versionAplicaction() == 0) {
                $logeje5 = $this->generarLogNatural(request()->input('eje5'));
                $logeje5_json = json_encode($logeje5);
                DB::insert("INSERT INTO control_alineacion VALUES (NULL,'" . request()->input('placa') . "' , " . $idmaquina . " , '" . $now . "',  5 , " . request()->input('eje5') . "  , '" . $logeje5_json . "')");
            }
        }
        $seriemaquina = explode('|', request()->input('selMaquina'))[1];
        if (request()->input('tipoejecucionprueba') !== 'corregir') {
            if (sicov() == 'INDRA') {
                $resultado = $this->eventosindra(request()->input('placa'), $seriemaquina, request()->input('prueba'));
                if (!$resultado['success']) {
                    return back()->with("error", "Datos guardados pero falló el registro en SINDRA");
                }
            } else {
                $resultado = $this->eventosci2($request);
                if (!$resultado['success']) {
                    $mensajeError = $resultado['message'] ?? $resultado['error'] ?? 'Error desconocido';
                    return back()->with("error", "Error en el evento de finalizacion de sicov: " . $mensajeError);
                }
            }
        }

        try {
            DB::update("UPDATE pruebas p set p.fechainicial= p.fechainicial, p.estado = " . request()->input('selEstado') . ", p.idmaquina = " . $idmaquina . ", p.idusuario = " . request()->input('selUsuario') . ", p.fechafinal = '" . $now . "' , p.enc = " . "AES_ENCRYPT('" . $this->updateEncr(request()->input('idprueba'), request()->input('selEstado'), $idmaquina, request()->input('selUsuario'), $now) . "','" . $this->key . "')" . " where p.idprueba = " . request()->input('idprueba'));
            return back()->with("success", "Datos Guardados correctamente");
        } catch (\Exception $e) {
            return back()->with("error", "Error al actualizar: " . $e->getMessage());
        }
    }

    function generarLogNatural($valorReferencia, $cantidad = 20)
    {
        $log = [];

        $valorReferencia = min(9, $valorReferencia);
        $esPositivo = $valorReferencia >= 0;

        // Valor inicial bajo
        if ($esPositivo) {
            $valorInicial = (mt_rand() / mt_getrandmax()) * min(3, $valorReferencia); // 0 a 3
        } else {
            $valorInicial = - ((mt_rand() / mt_getrandmax()) * 8 + 1); // -1 a -9
        }

        $valorActual = round($valorInicial, 1);
        $log[] = $valorActual;

        // Calcular incremento promedio por paso
        $incrementoPromedio = ($valorReferencia - $valorActual) / ($cantidad - 1);

        for ($i = 1; $i < $cantidad - 1; $i++) {
            // Variación alrededor del incremento promedio
            $variacion = $incrementoPromedio + (mt_rand() / mt_getrandmax()) * 0.4 - 0.2;

            $nuevoValor = $valorActual + $variacion;

            // Asegurar que no sobrepase el valor referencia
            if ($esPositivo) {
                $nuevoValor = min($valorReferencia, max(0, $nuevoValor));
            } else {
                $nuevoValor = min($valorReferencia, max(-9, $nuevoValor));
            }

            $nuevoValor = round($nuevoValor, 1);
            $log[] = $nuevoValor;
            $valorActual = $nuevoValor;
        }

        // Último valor siempre es el valor referencia
        $log[] = round($valorReferencia, 1);

        return $log;
    }

    // public function eventosindra($placa, $seriemaquina)
    // {
    //     date_default_timezone_set('America/bogota');
    //     $date = date("Y-m-d H:i:s");
    //     $idrunt = env('ID_RUNT');
    //     $cadenasicov = "862|" . $date . "|Alineacion|" . $placa . "|" . $seriemaquina . "|2|" . $idrunt;
    //     DB::insert("INSERT INTO eventosindra VALUES (NULL,'" . $placa . "-Alineacion','" . $cadenasicov . "','" . $date . "','e',0,'Operación pendiente')");
    // }

    // public function eventosci2(Request $request)
    // {
    //     date_default_timezone_set('America/bogota');
    //     $date = date("Ymd H:i:s");
    //     // Obtener la IP desde el .env (DB_HOST)
    //     $ipBaseDatos = env('SERVER_HOST');
    //     $url = "http://{$ipBaseDatos}/et/index.php/oficina/ci2/Cci2/ciclo_prueba";
    //     // Hacer la petición GET con los parámetros
    //     $response = Http::get($url, [
    //         'fecha' => $date,
    //         'tipoPrueba' => $request->input('tipopruebaCi2'),
    //         'placa' => trim($request->input('placa')),
    //         'serialEquipo' =>  explode('-', request()->input('selMaquina'))[1],
    //         'idEvento' => 2,
    //         'mensajeEvento' => "FIN DE PRUEBA"
    //     ]);

    //     // var_dump($response);

    //     // Verificar si fue exitosa
    //     if ($response->successful()) {
    //         $respuesta = $response->body();
    //         echo json_encode(['success' => true, 'data' => $respuesta]);
    //     } else {
    //         echo json_encode(['success' => false, 'error' => $response->status()]);
    //     }
    // }

    public function encr($idprueba, $tiporesultado, $valor, $fechaguardado, $observacion, $idconfig_prueba)
    {
        $dat['idprueba'] = $idprueba;
        $dat['tiporesultado'] = $tiporesultado;
        $dat['valor'] = $valor;
        $dat['fechaguardado'] = $fechaguardado;
        $dat['observacion'] = $observacion;
        $dat['idconfig_prueba'] = $idconfig_prueba;
        $enc = json_encode($dat);
        return $enc;
    }

    public function updateEncr($idprueba, $estado, $idmaquina, $idusuario, $fechafinal)
    {
        $res = DB::select("select * from pruebas p where p.idprueba = $idprueba");
        $idhojapruebas = $res[0]->idhojapruebas;
        $dat['idhojapruebas'] = (string)$idhojapruebas;
        $dat['fechainicial'] = (string)$res[0]->fechainicial;
        $dat['prueba'] = "0";
        $dat['estado'] = (string)$estado;
        $dat['fechafinal'] = (string)$fechafinal;
        $dat['idmaquina'] = (string)$idmaquina;
        $dat['idusuario'] = (string)$idusuario;
        $dat['idtipo_prueba'] = "10";
        $enc = json_encode($dat);
        return $enc;
    }

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
