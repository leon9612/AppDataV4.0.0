<?php

namespace App\Http\Controllers;

use App\Models\Malineacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mockery\Undefined;

use App\Libraries\Encrypt;
use App\Traits\EventosTrait;

class Cvisual extends Controller
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
                if ($objeto->conf_idtipo_prueba == 8 && $objeto->activo == 1) {
                    // Crear un objeto con la estructura que espera la vista
                    $data['maquinas'][] = (object)[
                        'idmaquina' => $objeto->idconf_maquina . '|' . strtoupper($objeto->serie_maquina),
                        'maquina' => strtoupper($objeto->nombre) . '-' . strtoupper($objeto->marca) . '-' . strtoupper($objeto->serie_maquina) . '-' . strtoupper($objeto->serie_banco)
                    ];
                }
            }

               $request = new Request();
            $request->merge([
                'tipoprueba' => '8',
                'tipovehiculo' => '4',
                'tipoejecucionprueba' => 'corregir'
            ]);

            $data['placas'] = $this->getPlacas($request);
        //     $data['placas'] = DB::select("SELECT 
        //     p.*, 
        //     v.*,
        //     CASE 
        //     WHEN v.migrateLineaMarca <> 1 THEN
        //         IFNULL((SELECT lr.nombre FROM linearunt lr WHERE lr.idlineaRUNT = v.idlinea LIMIT 1), 'SIN LINEA')
        //     ELSE 
        //         IFNULL((SELECT nl.nombre FROM newlineas nl WHERE nl.idlineas = v.idlinea LIMIT 1), 'SIN LINEA')
        // END as linea,
        //     CASE 
        //     WHEN v.migrateLineaMarca <> 1 THEN
        //         IFNULL((SELECT mr.nombre FROM marcarunt mr WHERE mr.idmarcaRUNT = (SELECT lr.idmarcarunt FROM linearunt lr WHERE lr.idlineaRUNT = v.idlinea)), 0)
        //     ELSE 
        //         IFNULL((SELECT nm.nombre FROM newmarcas nm WHERE nm.idmarcas = (SELECT nl.idmarcas FROM newlineas nl WHERE nl.idlineas = v.idlinea)), 0)
        // END as marca,
        //     v.numero_placa as 'placa',
        //     IFNULL((SELECT t.nombre FROM tipo_combustible t WHERE v.idtipocombustible = t.idtipocombustible LIMIT 1), '') AS 'combustible'
        //         FROM vehiculos v, hojatrabajo h, pruebas p 
        //         WHERE v.idvehiculo = h.idvehiculo 
        //         AND h.idhojapruebas = p.idhojapruebas 
        //         AND p.idtipo_prueba = 8 
        //         AND (p.estado = 0 OR p.estado = 2 OR p.estado = 1) 
        //         AND h.estadototal <> 4 
        //         AND h.estadototal <> 7
        //         AND NOT (h.estadototal = 2 AND (h.reinspeccion = 4444 OR h.reinspeccion = 44441))
        //         AND DATE_FORMAT(p.fechainicial, '%y-%m-%d') = DATE_FORMAT(CURDATE(), '%y-%m-%d')  
        //         ORDER BY p.fechainicial ASC");


            $data['usuarios'] = DB::select("select u.IdUsuario, concat(u.nombres,' ',u.apellidos ) as 'nombre' from usuarios u where u.idperfil = 2 and u.estado = 1");
            // $data['maquinas'] = DB::select("select  m.idmaquina, concat(m.nombre, ' ', m.marca, ' ', m.serie) as 'maquina' from maquina m where m.estado = 1 and m.idtipo_prueba = 8 and (m.idbanco = 1 or m.idbanco = 2) ");
            //$data['placas'] = Malineacion::paginate(5);
            return view('TipoPrueba.visual', $data);
        } else {
            return redirect()->intended('/');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDefectos(Request $request)
    {
        $defectos = json_decode(utf8_encode(file_get_contents('https://appdataingeniersoftware.com/defectos.json', true)), true);
        $resultados = DB::select("SELECT  r.idresultados, r.tiporesultado, r.valor, r.fechaguardado, r.observacion, r.idconfig_prueba FROM resultados r WHERE   r.idprueba = " . $request->input('idprueba') . "");
        foreach ($resultados as $resultado) {
            $resultVal = explode('|', $resultado->valor)[0];
            foreach ($defectos as $key => $defecto) {
                if ($defecto['codigo'] == $resultVal) {
                    $resultado->tipo = $defecto['tipo'];
                    $resultado->descripcion = $defecto['descripcion'];

                    break;
                }
            }
        }
        $data['resultados'] = $resultados;
        $data['defectos'] = $defectos;
        return response()->json($data);

        // return response()->json($defectos);
        //var_dump($resultados);
        //  echo file_get_contents('https://appdataingeniersoftware.com/defectos.json', true) ;
        //  echo json_encode(file_get_contents('https://appdataingeniersoftware.com/defectos.json', true))  ;
    }

    public function deleteDefectos(Request $request)
    {
        $resultados = DB::select("DELETE FROM resultados WHERE idresultados = " . $request->input('idresultados') . "");
        return response()->json($resultados);
    }

    public function saveDefectos(Request $request)
    {
        $now = date("Y-m-d H:i:s");
        if ($request->input('rechazoFrenos') == '1') {
            DB::update("UPDATE pruebas p set p.estado = 1, p.idmaquina = " . request()->input('selMaquina') . ", p.idusuario = " . request()->input('selUsuario') . ", p.fechafinal = '" . $now . "' , p.enc = " . "AES_ENCRYPT('" . $this->updateEncr(request()->input('idprueba'), 1, request()->input('selMaquina'), request()->input('selUsuario'), $now) . "','" . $this->key . "')" . " where p.idhojapruebas = " . request()->input('idhojapruebas') . " and p.idtipo_prueba = 7 and p.estado = 0  ");
            // $resultados = DB::select("INSERT INTO resultados  VALUES (NULL," . $request->input('idprueba') . ",'defecto','" . $request->input('defecto') . "',NOW(),'','153',AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'defecto', request()->input('defecto'), $now, '', '153') . "','" . $this->key . "'))");
        }
        $resultados = DB::select("INSERT INTO resultados  VALUES (NULL," . $request->input('idprueba') . ",'defecto','" . $request->input('defecto') . "',NOW(),'','153',AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'defecto', request()->input('defecto'), $now, '', '153') . "','" . $this->key . "'))");
        return response()->json($resultados);
    }

    public function saveObservacionAdicional(Request $request)
    {
        $now = date("Y-m-d H:i:s");
        $resultados = DB::select("INSERT INTO resultados  VALUES (NULL," . $request->input('idprueba') . ",'COMENTARIOSADICIONALES','" . $request->input('comentario') . "',NOW(),'','153',AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'COMENTARIOSADICIONALES', request()->input('comentario'), $now, '', '153') . "','" . $this->key . "'))");
        return response()->json($resultados);
    }

    public function updateObservacion(Request $request)
    {
        $now = date("Y-m-d H:i:s");

        $triggerDefinition = "
CREATE TRIGGER `auditres_jz` 
BEFORE UPDATE ON `resultados` 
FOR EACH ROW 
BEGIN
    if (OLD.valor<>NEW.valor AND DATE_FORMAT(OLD.fechaguardado, '%Y%m%d')=DATE_FORMAT(NOW(), '%Y%m%d')) OR NEW.fechaguardado<>OLD.fechaguardado then          
      INSERT INTO cron_audit VALUES(NULL,(SELECT v.numero_placa FROM pruebas p,hojatrabajo h,vehiculos v where p.idhojapruebas=h.idhojapruebas and v.idvehiculo=h.idvehiculo and p.idprueba=OLD.idprueba limit 1),OLD.idprueba,OLD.idresultados,concat((SELECT p.idtipo_prueba FROM pruebas p where p.idprueba=OLD.idprueba limit 1),'|',OLD.valor,'|',NEW.valor),NOW(),0);
      SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Operacion no permitida';
    end if;
END
    ";

        try {
            // 1. Deshabilitar el trigger
            DB::statement('DROP TRIGGER IF EXISTS auditres_jz');

            // 2. Ejecutar el UPDATE
            if ($request->input('comentariosadicionales') == '1') {
                $resultados = DB::update("UPDATE resultados SET valor = ?, enc = AES_ENCRYPT(?, ?) WHERE idresultados = ?", [
                    $request->input('observacion'),
                    $this->encr(request()->input('idprueba'), 'defecto', request()->input('defecto'), $now, request()->input('observacion'), '153'),
                    $this->key,
                    $request->input('idresultado')
                ]);
            } else {
                $resultados = DB::update("UPDATE resultados SET observacion = ?, enc = AES_ENCRYPT(?, ?) WHERE idresultados = ?", [
                    $request->input('observacion'),
                    $this->encr(request()->input('idprueba'), 'defecto', request()->input('defecto'), $now, request()->input('observacion'), '153'),
                    $this->key,
                    $request->input('idresultado')
                ]);
            }

            // 3. Volver a crear el trigger
            DB::statement($triggerDefinition);

            return response()->json(['success' => true, 'affected_rows' => $resultados]);
        } catch (\Exception $e) {
            // Intentar recrear el trigger si falla
            try {
                DB::statement($triggerDefinition);
            } catch (\Exception $triggerError) {
                // \Log::error('Error recreando trigger: ' . $triggerError->getMessage());
            }

            // \Log::error('Error en updateObservacion: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'No se pudo guardar la observación: ' . $e->getMessage()
            ], 500);
        }
    }

    public function saveLabrado(Request $request)
    {
        $now = date("Y-m-d H:i:s");
        if ($request->input('idresultados') !== null && $request->input('idresultados') !== 'undefined') {
            if ($request->input('valor') == '' || $request->input('valor') == null) {
                $res = DB::select("DELETE FROM resultados where idresultados = " . $request->input('idresultados') . "");
            }
            $res = DB::select("DELETE FROM resultados where idresultados = " . $request->input('idresultados') . "");
            // $res = DB::select("UPDATE resultados r set r.valor = '" . $request->input('valor') . "', r.fechaguardado = r.fechaguardado, r.enc = AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), $request->input('tiporesultado'), request()->input('valor'), $now, 'OBSERVACIONLABRADO', '96') . "','" . $this->key . "') where r.idresultados = " . $request->input('idresultados') . "");

            $res = DB::select("INSERT INTO resultados  VALUES (NULL," . $request->input('idprueba') . ",'" . $request->input('tiporesultado') . "','" . $request->input('valor') . "',NOW(),'OBSERVACIONLABRADO','96',AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), $request->input('tiporesultado'), request()->input('valor'), $now, 'OBSERVACIONLABRADO', '96') . "','" . $this->key . "'))");
        } else {
            $res = DB::select("INSERT INTO resultados  VALUES (NULL," . $request->input('idprueba') . ",'" . $request->input('tiporesultado') . "','" . $request->input('valor') . "',NOW(),'OBSERVACIONLABRADO','96',AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), $request->input('tiporesultado'), request()->input('valor'), $now, 'OBSERVACIONLABRADO', '96') . "','" . $this->key . "'))");
        }
        return response()->json($res);
    }

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
            'idprueba' => 'required',
            'selEstado' => 'required',
            'selUsuario' => 'required',
            'selMaquina' => 'required',
        ]);
        date_default_timezone_set('America/bogota');
        $now = date("Y-m-d H:i:s");

        $idmaquina = explode('|', request()->input('selMaquina'))[0];
        $idmaquina = intval($idmaquina);
        //        $now = date('Y-m-d H:i:s'); //Fomat Date and time
        //insert version software


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

    // public function eventosindra($placa)
    // {
    //     date_default_timezone_set('America/bogota');
    //     $date = date("Y-m-d H:i:s");
    //     $datos = DB::select("SELECT c.valor,
    //                     (SELECT p.idmaquina FROM vehiculos v, hojatrabajo h, pruebas p
    //                     WHERE v.idvehiculo= h.idvehiculo AND h.idhojapruebas=p.idhojapruebas AND p.idtipo_prueba=7 AND (v.tipo_vehiculo = 1 or v.tipo_vehiculo = 2)  ORDER BY 1 DESC LIMIT 1) AS 'idmaquina'
    //                     FROM config_prueba c
    //                     WHERE
    //                     c.idconfiguracion=34 AND c.descripcion LIKE '%runt%' ");
    //     $r = DB::select("SELECT valor AS 'serial' from config_prueba where idconfig_prueba=20000+" . strval($datos[0]->idmaquina) . " LIMIT 1");
    //     if ($r == null || $r == "" || count($r) == 0)
    //         $serial = '515554858';
    //     else
    //         $serial = $r[0]->serial;
    //     $cadenasicov = "862|" . $date . "|Visual|" . $placa . "|" . $serial . "|2|" . $datos[0]->valor;
    //     DB::insert("INSERT INTO eventosindra VALUES (NULL,'" . $placa . "-Visual','" . $cadenasicov . "','" . $date . "','e',0,'Operación pendiente')");
    //     //$r = DB::select("SELECT valor AS 'serial' from config_prueba where idconfig_prueba=20000+" + $datos[0]->idmaquina + " LIMIT 1");
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
        $dat['idtipo_prueba'] = "8";
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
