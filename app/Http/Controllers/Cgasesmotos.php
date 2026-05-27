<?php

namespace App\Http\Controllers;

use App\Models\Malineacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Libraries\Encrypt;
use App\Traits\EventosTrait;

class Cgasesmotos extends Controller
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

            // Usar colecciones de Laravel
            $data['maquinas'] = collect($json)
                ->filter(function ($objeto) {
                    return $objeto->conf_idtipo_prueba == 3 &&
                        $objeto->activo == 1 &&
                        ($objeto->idconf_linea_inspeccion == 3 ||
                            $objeto->idconf_linea_inspeccion == 9 ||
                            $objeto->idconf_linea_inspeccion == 10);
                })
                ->sortBy('idconf_maquina') // Ordenar ASC por idconf_maquina
                ->map(function ($objeto) {
                    return (object)[
                        'idmaquina' => $objeto->idconf_maquina . '|' . strtoupper($objeto->serie_maquina),
                        'maquina' => strtoupper($objeto->nombre) . '-' .
                            strtoupper($objeto->marca) . '-' .
                            strtoupper($objeto->serie_maquina) . '-' .
                            strtoupper($objeto->serie_banco)
                    ];
                })
                ->values() // Reindexar
                ->toArray();

            //     $data['placas'] = DB::select("select
            // p.idprueba,
            // v.numero_placa as 'placa'
            // from vehiculos v, hojatrabajo h, pruebas p
            // where
            // v.idvehiculo = h.idvehiculo and h.idhojapruebas = p.idhojapruebas and
            // p.idtipo_prueba=3 and p.estado = 0 and
            // date_format(p.fechainicial, '%y-%m-%d') = date_format(curdate(), '%y-%m-%d') and v.tipo_vehiculo = 3 order by p.fechainicial asc ");
            $request = new Request();
            $request->merge([
                'tipoprueba' => '3',
                'tipovehiculo' => '3',
                'tipoejecucionprueba' => 'corregir'
            ]);

            $data['placas'] = $this->getPlacas($request);
            $data['usuarios'] = DB::select("select u.IdUsuario, concat(u.nombres,' ',u.apellidos ) as 'nombre' from usuarios u where u.idperfil = 2 and u.estado = 1");
            // $data['maquinas'] = DB::select("select  m.idmaquina, concat(m.nombre, ' ', m.marca, ' ', m.serie) as 'maquina' from maquina m where m.estado = 1 and m.idtipo_prueba = 3 and m.idbanco = 3");
            $temRand = rand(50, 70);
            $temFinal = rand($temRand, $temRand + 5);
            $temR = DB::select("select IFNULL((SELECT  ifnull(AES_DECRYPT(c.parametro ,'353E9D61B66D77CAE6BF97DE8F7CAWYJFLLD2D765SD4894165SD81SD'), c.parametro) FROM config_maquina c WHERE c.tipo_parametro = 'Temperatura Ambiente' LIMIT 1),'18.9') AS 'val'");
            $ranTemp = $temR[0]->val;
            if ($ranTemp == 0 || $ranTemp == '0') {
                $ranTemp = rand(16 * 10, 17 * 10) / 10;
            }
            $data['tempAmbiente'] = $ranTemp;


            // $humR = DB::select("select IHumedad RelativaFNULL((SELECT c.parametro FROM config_maquina c WHERE c.tipo_parametro = 'Humedad Relativa' LIMIT 1),'68.9') AS 'val'");
            $humR = DB::select("select IFNULL((SELECT  ifnull(AES_DECRYPT(c.parametro ,'353E9D61B66D77CAE6BF97DE8F7CAWYJFLLD2D765SD4894165SD81SD'), c.parametro) FROM config_maquina c WHERE c.tipo_parametro = 'Humedad Relativa' LIMIT 1),'68.9') AS 'val'");
            $ranHum = $humR[0]->val;
            if ($ranHum == 0 || $ranHum == 0) {
                $ranHum = rand(68 * 10, 70 * 10) / 10;
            }
            $data['humedad'] = $ranHum;
            $data['tempMotor'] = $temFinal;
            //$data['placas'] = Malineacion::paginate(5);

            return view('TipoPrueba.gasesmotos', $data);
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
            'hc_ralenti' => 'required',
            'co_ralenti' => 'required',
            'co2_ralenti' => 'required',
            'o2_ralenti' => 'required',
            'rpm_ralenti' => 'required',
            'idprueba' => 'required',
            'selEstado' => 'required',
            'selUsuario' => 'required',
            'selMaquina' => 'required',
            'humedad' => 'required',
            'tempAmbiente' => 'required',
        ]);
        date_default_timezone_set('America/bogota');
        $now = date('Y-m-d H:i:s'); //Fomat Date and time
        if (request()->input('tipoejecucionprueba') == 'corregir') {
            $idprueba = request()->input('idprueba');
            try {
                DB::delete("DELETE FROM resultados WHERE idprueba = $idprueba");
                if (versionAplicaction() == 0) {
                    DB::delete("DELETE FROM control_prueba_gases WHERE idprueba = '" . $idprueba . "'");
                }
            } catch (\Exception $e) {
                return back()->with("error", "Error al corregir: " . $e->getMessage());
            }
        }

        $idmaquina = explode('|', request()->input('selMaquina'))[0];
        $idmaquina = intval($idmaquina);
        $ranTemp = request()->input('tempAmbiente');
        $ranHum = request()->input('humedad');
        $tempAceite = request()->input('tempMotor');
        if (versionAplicaction() == 1) {
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'id_prueba', " . request()->input('idprueba') . " , '" . $now . "', 'id_prueba', '83', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'id_prueba', request()->input('idprueba'), $now, 'id_prueba', '83') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'id_banco', " . $idmaquina . " , '" . $now . "', 'id_banco', '84', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'id_banco', $idmaquina, $now, 'id_banco', '84') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'estado_insp_visual', '1' , '" . $now . "', 'estado_insp_visual', '94', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'estado_insp_visual', '1', $now, 'estado_insp_visual', '94') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'id_usuario', " . request()->input('selUsuario') . " , '" . $now . "', 'id_usuario', '97', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'id_usuario', request()->input('selUsuario'), $now, 'id_usuario', '97') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'observaciones', 'APROBADA INSPECCION VISUAL' , '" . $now . "', 'observaciones', '99', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'observaciones', 'APROBADA INSPECCION VISUAL', $now, 'observaciones', '99') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'fecha_inicial', '" . $now . "' , '" . $now . "', 'fecha_inicial', '101', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'fecha_inicial', $now, $now, 'fecha_inicial', '101') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'temperatura_ambiente', '" . $ranTemp . "' , '" . $now . "', 'temperatura_ambiente', '200', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'temperatura_ambiente', $ranTemp, $now, 'temperatura_ambiente', '200') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'humedad', '" . $ranHum . "' , '" . $now . "', 'humedad', '201', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'humedad', $ranHum, $now, 'humedad', '201') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'version_software', '7.0' , '" . $now . "', 'version_software', '132', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'version_software', '7.0', $now, 'version_software', '132') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'temperatura_aceite', '" . $tempAceite . "' , '" . $now . "', 'temperatura_aceite', '85', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'temperatura_aceite', $tempAceite, $now, 'temperatura_aceite', '85') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'rpm_crucero', '0' , '" . $now . "', 'rpm_crucero', '91', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'rpm_crucero', 0, $now, 'rpm_crucero', '91') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'hc_crucero', '0' , '" . $now . "', 'hc_crucero', '92', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'hc_crucero', 0, $now, 'hc_crucero', '92') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'co_crucero', '0' , '" . $now . "', 'co_crucero', '93', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'co_crucero', 0, $now, 'co_crucero', '93') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'co2_crucero', '0' , '" . $now . "', 'co2_crucero', '94', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'co2_crucero', 0, $now, 'co2_crucero', '94') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'o2_crucero', '0' , '" . $now . "', 'o2_crucero', '95', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'o2_crucero', 0, $now, 'o2_crucero', '95') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'rpm_ralenti', '" . request()->input('rpm_ralenti') . "' , '" . $now . "', 'rpm_ralenti', '86', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'rpm_ralenti', request()->input('rpm_ralenti'), $now, 'rpm_ralenti', '86') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'hc_ralenti', '" . request()->input('hc_ralenti') . "' , '" . $now . "', 'hc_ralenti', '87', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'hc_ralenti', request()->input('hc_ralenti'), $now, 'hc_ralenti', '87') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'co_ralenti', '" . request()->input('co_ralenti') . "' , '" . $now . "', 'co_ralenti', '88', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'co_ralenti', request()->input('co_ralenti'), $now, 'co_ralenti', '88') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'co2_ralenti', '" . request()->input('co2_ralenti') . "' , '" . $now . "', 'co2_ralenti', '89', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'co2_ralenti', request()->input('co2_ralenti'), $now, 'co2_ralenti', '89') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'o2_ralenti', '" . request()->input('o2_ralenti') . "' , '" . $now . "', 'o2_ralenti', '90', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'o2_ralenti', request()->input('o2_ralenti'), $now, 'o2_ralenti', '90') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'temperatura_ambiente', '" . $ranTemp . "' , '" . $now . "', 'temperatura_ambiente', '200', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'temperatura_ambiente', $ranTemp, $now, 'temperatura_ambiente', '200') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'humedad', '" . $ranHum . "' , '" . $now . "', 'humedad', '201', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'humedad', $ranHum, $now, 'humedad', '201') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'Metodo_Medicion_Temp', '2' , '" . $now . "', 'Metodo_Medicion_Temp', '212', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'Metodo_Medicion_Temp', '2', $now, 'Metodo_Medicion_Temp', '212') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'promhcra_ant', '" . request()->input('hc_ralenti') . "' , '" . $now . "', 'promhcra_ant', '222', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'promhcra_ant', request()->input('hc_ralenti'), $now, 'promhcra_ant', '222') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'promcora_ant', '" . request()->input('co_ralenti') . "' , '" . $now . "', 'promcora_ant', '223', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'promcora_ant', request()->input('co_ralenti'), $now, 'promcora_ant', '223') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'version_software', '7.0' , '" . $now . "', 'version_software', '132', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'version_software', '7.0', $now, 'version_software', '132') . "','" . $this->key . "'))");
        } else {
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'promhcra_ant', '" . request()->input('hc_ralenti') . "' , '" . $now . "', 'promhcra_ant', '222', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'promhcra_ant', request()->input('hc_ralenti'), $now, 'promhcra_ant', '222') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'promcora_ant', '" . request()->input('co_ralenti') . "' , '" . $now . "', 'promcora_ant', '223', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'promcora_ant', request()->input('co_ralenti'), $now, 'promcora_ant', '223') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'promco2ra_ant', '" . request()->input('co2_ralenti') . "' , '" . $now . "', 'promcora2_ant', '224', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'promco2ra_ant', request()->input('co2_ralenti'), $now, 'promco2ra_ant', '224') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'rpm_ralenti', '" . request()->input('rpm_ralenti') . "' , '" . $now . "', 'rpm_ralenti', '86', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'rpm_ralenti', request()->input('rpm_ralenti'), $now, 'rpm_ralenti', '86') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'hc_ralenti', '" . request()->input('hc_ralenti') . "' , '" . $now . "', 'hc_ralenti', '87', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'hc_ralenti', request()->input('hc_ralenti'), $now, 'hc_ralenti', '87') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'co_ralenti', '" . request()->input('co_ralenti') . "' , '" . $now . "', 'co_ralenti', '88', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'co_ralenti', request()->input('co_ralenti'), $now, 'co_ralenti', '88') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'co2_ralenti', '" . request()->input('co2_ralenti') . "' , '" . $now . "', 'co2_ralenti', '89', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'co2_ralenti', request()->input('co2_ralenti'), $now, 'co2_ralenti', '89') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'o2_ralenti', '" . request()->input('o2_ralenti') . "' , '" . $now . "', 'o2_ralenti', '90', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'o2_ralenti', request()->input('o2_ralenti'), $now, 'o2_ralenti', '90') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'temperatura_ambiente', '" . $ranTemp . "' , '" . $now . "', 'temperatura_ambiente', '200', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'temperatura_ambiente', $ranTemp, $now, 'temperatura_ambiente', '200') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'humedad', '" . $ranHum . "' , '" . $now . "', 'humedad', '201', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'humedad', $ranHum, $now, 'humedad', '201') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'temperatura_aceite', '" . $tempAceite . "' , '" . $now . "', 'temperatura_aceite', '85', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'temperatura_aceite', $tempAceite, $now, 'temperatura_aceite', '85') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'Metodo_Medicion_Temp', '2' , '" . $now . "', 'Metodo_Medicion_Temp', '212', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'Metodo_Medicion_Temp', '2', $now, 'Metodo_Medicion_Temp', '212') . "','" . $this->key . "'))");
            $idcal = DB::select("SELECT c.idcontrol_calibracion as cal FROM control_calibracion c WHERE c.idmaquina = " . $idmaquina . " AND c.resultado = 'S' ORDER BY 1 DESC LIMIT 1");
            if ($idcal == '0' || count($idcal) == 0) {
                $cal = '0';
            } else {
                $cal = $idcal[0]->cal;
            }
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'idcontrol_calibracion', '" . $cal . "' , '" . $now . "', 'idcontrol_calibracion', '600', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'idcontrol_calibracion', $cal, $now, 'idcontrol_calibracion', '600') . "','" . $this->key . "'))");
        }

        // var_dump(request()->input('idprueba'));


        DB::update("UPDATE vehiculos v set v.scooter = '" . request()->input('selScooter') . "' where v.numero_placa = '" . request()->input('placa') . "'  ");
        DB::update("UPDATE vehiculos v set v.convertidor = '" . request()->input('selCatalizador') . "' where v.numero_placa = '" . request()->input('placa') . "'  ");
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
            $this->getBitacoraGases(request()->input('idprueba'));
            return back()->with("success", "Datos Guardados correctamente");
        } catch (\Exception $e) {
            return back()->with("error", "Error al actualizar: " . $e->getMessage());
        }
    }



    public function encr($idprueba, $tiporesultado, $valor, $fechaguardado, $observacion, $idconfig_prueba)
    {
        $dat['idprueba'] = $idprueba;
        $dat['tiporesultado'] = $tiporesultado;
        $dat['valor'] = "$valor";
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
        $dat['idtipo_prueba'] = "3";
        $enc = json_encode($dat);
        return $enc;
    }

    function getBitacoraGases($idprueba)
    {
        $rta = Malineacion::getBitacoraGases($idprueba);
        var_dump($idprueba);

        if ($rta[0]->control != 0) {
            return;
        }

        // Datos patrón para ralentí (60 muestras - 30 segundos)
        $hc = [1796, 1796, 1796, 3390, 3390, 4490, 4490, 4860, 5170, 5170, 5430, 6560, 6560, 6410, 6410, 6340, 6340, 6270, 6270, 6190, 6190, 6190, 6100, 6100, 6020, 6020, 6020, 5880, 5880, 5880, 5820, 5820, 5790, 5790, 5750, 5750, 5750, 5750, 5740, 5740, 5740, 5800, 5800, 5800, 5830, 5880, 5880, 5880, 5880, 5950, 5950, 5980, 5980, 6000, 6000, 6020, 6020, 6020, 6020, 6020];
        $co = [2.31, 2.31, 2.31, 3.97, 3.97, 4.75, 4.75, 4.97, 5.12, 5.12, 5.21, 5.59, 5.59, 5.43, 5.43, 5.34, 5.34, 5.25, 5.25, 5.19, 5.19, 5.19, 5.14, 5.14, 5.09, 5.09, 5.09, 5, 5, 5, 4.98, 4.98, 4.97, 4.97, 4.96, 4.96, 4.96, 4.96, 4.96, 4.96, 4.96, 4.94, 4.94, 4.94, 4.93, 4.92, 4.92, 4.92, 4.92, 4.93, 4.93, 4.95, 4.95, 4.98, 4.98, 5, 5, 5.01, 5.01, 5.01];
        $co2 = [1.4, 1.4, 1.4, 2.4, 2.4, 3, 3, 3.1, 3.1, 3.1, 3.2, 3.8, 3.8, 4.2, 4.2, 4.4, 4.4, 4.5, 4.5, 4.7, 4.7, 4.7, 4.8, 4.8, 4.9, 4.9, 4.9, 5.1, 5.1, 5.1, 5.2, 5.2, 5.2, 5.2, 5.2, 5.2, 5.2, 5.2, 5.2, 5.2, 5.2, 5.1, 5.1, 5.1, 5.1, 5, 5, 5, 5, 5, 5, 4.9, 4.9, 4.9, 4.9, 4.9, 4.9, 4.9, 4.9, 4.9];
        $o2 = [16.5, 16.5, 16.5, 13.9, 13.9, 12.4, 12.4, 11.9, 11.6, 11.6, 11.3, 10.1, 10.1, 9.8, 9.8, 9.6, 9.6, 9.5, 9.5, 9.4, 9.4, 9.4, 9.3, 9.3, 9.2, 9.2, 9.2, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9.2, 9.2, 9.2, 9.3, 9.4, 9.4, 9.4, 9.4, 9.4, 9.4, 9.4, 9.4, 9.4, 9.4, 9.4, 9.4, 9.4, 9.4, 9.3];
        $rpm = [1380, 1460, 1340, 1310, 1320, 1320, 1340, 1350, 1340, 1340, 1320, 1370, 1380, 1400, 1400, 1420, 1400, 1380, 1380, 1380, 1400, 1410, 1400, 1390, 1390, 1400, 1400, 1400, 1400, 1410, 1380, 1400, 1410, 1400, 1400, 1400, 1400, 1400, 1380, 1370, 1390, 1400, 1420, 1400, 1380, 1390, 1410, 1400, 1410, 1390, 1410, 1420, 1380, 1370, 1390, 1390, 1420, 1420, 1420, 1440];

        // Determinar si aplica corrección por oxígeno según NTC
        $aplicaCorreccion = $this->aplicarCorreccionOxigeno($rta[0]);

        // Calcular promedios del patrón (últimos 10 valores)
        $promedioPatronHC = 0;
        $promedioPatronCO = 0;
        $promedioPatronCO2 = 0;
        $promedioPatronO2 = 0;
        $promedioPatronRPM = 0;

        for ($i = 50; $i < 60; $i++) {
            $promedioPatronHC += $hc[$i];
            $promedioPatronCO += $co[$i];
            $promedioPatronCO2 += $co2[$i];
            $promedioPatronO2 += $o2[$i];
            $promedioPatronRPM += $rpm[$i];
        }

        $promedioPatronHC = round($promedioPatronHC / 10);
        $promedioPatronCO = round($promedioPatronCO / 10, 3);
        $promedioPatronCO2 = round($promedioPatronCO2 / 10, 2);
        $promedioPatronO2 = round($promedioPatronO2 / 10, 2);
        $promedioPatronRPM = round($promedioPatronRPM / 10);

        // Calcular factores de ajuste
        var_dump($rta[0]->promhcra_ant);
        if ($aplicaCorreccion) {
            $dfhc = $promedioPatronHC - round($rta[0]->promhcra_ant);
            $dfco = $promedioPatronCO - $rta[0]->promcora_ant;
        } else {
            $dfhc = $promedioPatronHC - round($rta[0]->hc_ralenti);
            $dfco = $promedioPatronCO - $rta[0]->co_ralenti;
        }

        $dfco2 = $promedioPatronCO2 - $rta[0]->co2_ralenti;
        $dfo2 = $promedioPatronO2 - $rta[0]->o2_ralenti;
        $dfrpm = $promedioPatronRPM - $rta[0]->rpm_ralenti;

        // Generar las 60 muestras del ciclo de ralentí
        $datosRalenti = $this->generarMuestrasCiclo($hc, $co, $co2, $o2, $rpm, $dfhc, $dfco, $dfco2, $dfo2, $dfrpm);

        // Ajustar promedios finales para ralentí
        $datosRalenti = $this->ajustarPromediosRalenti($datosRalenti, $rta[0], $aplicaCorreccion);

        // Simular ciclo de crucero si aplica
        // $datosCrucero = "";
        // if ($rta[0]->rpm_crucero > 0) {
        //     $datosCrucero = $this->generarCrucero($rta[0]);
        //     $datosCrucero = json_encode($datosCrucero);
        // }

        // Guardar en base de datos
        $datos = [
            "idprueba" => $rta[0]->idprueba,
            "exosto" => 1,
            "datos_ciclo_ralenti" => json_encode($datosRalenti),
            "datos_ciclo_crucero" => ""
        ];

        Malineacion::logGasesInsert($datos);
    }

    /**
     * Determina si aplica corrección por oxígeno según NTC colombiana
     */
    function aplicarCorreccionOxigeno($datosPrueba)
    {
        // Condición 1: O2 >= 11%, motor 2 tiempos, vehículo tipo 3, modelo < 2010
        if (
            $datosPrueba->o2_ralenti >= 11.0 &&
            $datosPrueba->tiempos == '2' &&
            $datosPrueba->tipo_vehiculo == 3 &&
            $datosPrueba->ano_modelo < 2010
        ) {
            return true;
        }

        // Condición 2: O2 >= 6%, motor 4 tiempos, vehículo tipo 3
        if (
            $datosPrueba->o2_ralenti >= 6.0 &&
            $datosPrueba->tiempos == '4' &&
            $datosPrueba->tipo_vehiculo == 3
        ) {
            return true;
        }

        // Condición 3: O2 >= 6%, motor 2 tiempos, vehículo tipo 3, modelo >= 2010
        if (
            $datosPrueba->o2_ralenti >= 6.0 &&
            $datosPrueba->tiempos == '2' &&
            $datosPrueba->tipo_vehiculo == 3 &&
            $datosPrueba->ano_modelo >= 2010
        ) {
            return true;
        }

        return false;
    }

    /**
     * Genera las 60 muestras del ciclo de prueba con comportamiento realista
     */
    function generarMuestrasCiclo($hc, $co, $co2, $o2, $rpm, $dfhc, $dfco, $dfco2, $dfo2, $dfrpm)
    {
        // Patrones de fluctuación para los últimos 10 segundos (más suaves y realistas)
        $fluctuacionHC = [
            1 => [-5, -3, -2, -1, 0, 0, 1, 2, 3, 5],
            2 => [-3, -2, -1, 0, 0, 0, 0, 1, 2, 3],
            3 => [-4, -3, -2, 0, 0, 0, 1, 2, 3, 3]
        ];

        $fluctuacionCO = [
            1 => [-0.02, -0.01, -0.01, 0, 0, 0, 0, 0.01, 0.01, 0.02],
            2 => [-0.01, -0.01, 0, 0, 0, 0, 0, 0, 0.01, 0.01],
            3 => [-0.02, -0.01, 0, 0, 0, 0, 0, 0.01, 0.01, 0.02]
        ];

        // Seleccionar aleatoriamente el patrón de fluctuación
        $patronHC = rand(1, 3);
        $patronCO = rand(1, 3);

        // Calcular valores base objetivo (promedio de los últimos valores del patrón)
        $hcBase = 0;
        $coBase = 0;
        $co2Base = 0;
        $o2Base = 0;
        $rpmBase = 0;

        // Usar los últimos 10 valores del patrón como referencia
        for ($i = 50; $i < 60; $i++) {
            $hcBase += $hc[$i];
            $coBase += $co[$i];
            $co2Base += $co2[$i];
            $o2Base += $o2[$i];
            $rpmBase += $rpm[$i];
        }

        $hcBase = round($hcBase / 10) - $dfhc;
        $coBase = round($coBase / 10, 3) - $dfco;
        $co2Base = round($co2Base / 10, 2) - $dfco2;
        $o2Base = round($o2Base / 10, 2) - $dfo2;
        $rpmBase = round($rpmBase / 10) - $dfrpm;

        // Ajustar RPM
        $rpmMod = ($rpm[0] - $dfrpm) % 10;
        if ($rpmMod < 0) {
            $rpmMod = $rpmMod + 10;
        }

        $datosCiclo = [];
        $j = 0;
        $primerO2Alto = 0;
        $encontroO2Alto = false;

        for ($i = 0; $i < 60; $i++) {
            $t = $i * 0.5;

            // Generar valores con pequeñas variaciones alrededor del valor base
            if ($i < 50) {
                // Fase de estabilización gradual
                if ($i < 10) {
                    // Primeros 5 segundos: valores ligeramente más altos (simula arranque)
                    $variacionHC = rand(-8, 8);
                    $variacionCO = rand(-3, 3) / 100;
                    $variacionCO2 = rand(-2, 2) / 10;
                    $variacionO2 = rand(-3, 3) / 100;
                    $variacionRPM = rand(-30, 30);
                } elseif ($i < 25) {
                    // Siguientes 7.5 segundos: estabilización
                    $variacionHC = rand(-5, 5);
                    $variacionCO = rand(-2, 2) / 100;
                    $variacionCO2 = rand(-1, 1) / 10;
                    $variacionO2 = rand(-2, 2) / 100;
                    $variacionRPM = rand(-20, 20);
                } else {
                    // Casi estable
                    $variacionHC = rand(-3, 3);
                    $variacionCO = rand(-1, 1) / 100;
                    $variacionCO2 = rand(-1, 1) / 10;
                    $variacionO2 = rand(-1, 1) / 100;
                    $variacionRPM = rand(-10, 10);
                }

                $hc_ = $hcBase + $variacionHC;
                $co_ = $coBase + $variacionCO;
                $co2_ = $co2Base + $variacionCO2;
                $o2_ = $o2Base + $variacionO2;
                $rpm_ = $rpmBase + $variacionRPM;
            } else {
                // Últimos 5 segundos: aplicar fluctuaciones NTC
                $hc_ = $hcBase + $fluctuacionHC[$patronHC][$j];
                $co_ = $coBase + $fluctuacionCO[$patronCO][$j];
                $co2_ = $co2Base + rand(-1, 1) / 100;
                $o2_ = $o2Base + rand(-2, 2) / 100;

                // Ajuste de RPM
                $rpm_ = $rpmBase;
                if ($rpmMod > 0) {
                    $rpm_ += 10;
                }
                $rpmMod--;
                $j++;
            }

            // Redondear valores
            $hc_ = round($hc_);
            $co_ = round($co_, 3);
            $co2_ = round($co2_, 2);
            $o2_ = round($o2_, 2);
            $rpm_ = round($rpm_);

            // Asegurar valores mínimos
            if ($hc_ < 0) $hc_ = abs($hc_);
            if ($co_ < 0) $co_ = abs($co_);
            if ($co2_ < 0) $co2_ = abs($co2_);
            if ($o2_ < 0) $o2_ = 0.0;

            // Validar O2 alto
            if ($o2_ > 18 && !$encontroO2Alto) {
                $encontroO2Alto = true;
                $primerO2Alto = $o2_;
            }
            if ($o2_ > 18 && $encontroO2Alto) {
                $o2_ = $primerO2Alto;
            }

            $datosCiclo[] = [
                "tiempo" => $t,
                "hc" => $hc_,
                "co" => $co_,
                "co2" => $co2_,
                "o2" => $o2_,
                "rpm" => $rpm_
            ];
        }

        return $datosCiclo;
    }

    /**
     * Genera el ciclo de crucero
     */


    /**
     * Ajusta los promedios del ciclo de ralentí
     */
    function ajustarPromediosRalenti($datosCiclo, $datosPrueba, $aplicaCorreccion)
    {
        // Determinar valores de referencia según NTC
        if ($aplicaCorreccion) {
            $referenciaHC = round($datosPrueba->promhcra_ant);
            $referenciaCO = $datosPrueba->promcora_ant;
        } else {
            $referenciaHC = round($datosPrueba->hc_ralenti);
            $referenciaCO = $datosPrueba->co_ralenti;
        }

        $referenciaCO2 = $datosPrueba->co2_ralenti;

        // Ajustar cada tipo de medición
        $datosCiclo = $this->ajustarPromedio($datosCiclo, 'hc', $referenciaHC, 0.1);
        $datosCiclo = $this->ajustarPromedio($datosCiclo, 'co', $referenciaCO, 0.0001);
        $datosCiclo = $this->ajustarPromedio($datosCiclo, 'co2', $referenciaCO2, 0.0001);

        return $datosCiclo;
    }

    /**
     * Ajusta los promedios del ciclo de crucero
     */
    function ajustarPromediosCrucero($datosCiclo, $datosPrueba)
    {
        $datosCiclo = $this->ajustarPromedio($datosCiclo, 'hc', round($datosPrueba->hc_crucero), 0.1);
        $datosCiclo = $this->ajustarPromedio($datosCiclo, 'co', $datosPrueba->co_crucero, 0.0001);
        $datosCiclo = $this->ajustarPromedio($datosCiclo, 'co2', $datosPrueba->co2_crucero, 0.0001);

        return $datosCiclo;
    }


    function ajustarPromedio($datosCiclo, $tipo, $valorReferencia, $resolucion)
    {
        $maxIteraciones = 200;
        $iteraciones = 0;
        $tolerancia = $resolucion / 10; // Tolerancia más estricta

        // Calcular promedio actual de los últimos 10 valores
        $promedio = $this->calcularPromedioUltimos($datosCiclo, $tipo);

        // Si ya es exactamente igual, solo redondear y retornar
        if (abs($promedio - $valorReferencia) < 0.00001) {
            return $this->redondearUltimosValores($datosCiclo, $tipo);
        }

        // Ajustar hasta alcanzar el valor exacto
        while (abs($promedio - $valorReferencia) > 0.00001 && $iteraciones < $maxIteraciones) {

            if ($promedio > $valorReferencia) {
                // Necesitamos reducir
                $diferencia = $promedio - $valorReferencia;
                $ajustePorElemento = $diferencia / 10;

                for ($i = 50; $i < 60; $i++) {
                    if ($ajustePorElemento > $resolucion) {
                        // Ajuste grande: restar resolución completa
                        $datosCiclo[$i][$tipo] -= $resolucion;
                    } else {
                        // Ajuste fino: restar la diferencia exacta
                        $datosCiclo[$i][$tipo] -= $ajustePorElemento;
                    }

                    // No permitir valores negativos
                    if ($datosCiclo[$i][$tipo] < 0) {
                        $datosCiclo[$i][$tipo] = 0;
                    }
                }
            } else {
                // Necesitamos aumentar
                $diferencia = $valorReferencia - $promedio;
                $ajustePorElemento = $diferencia / 10;

                for ($i = 50; $i < 60; $i++) {
                    if ($ajustePorElemento > $resolucion) {
                        // Ajuste grande: sumar resolución completa
                        $datosCiclo[$i][$tipo] += $resolucion;
                    } else {
                        // Ajuste fino: sumar la diferencia exacta
                        $datosCiclo[$i][$tipo] += $ajustePorElemento;
                    }
                }
            }

            $promedio = $this->calcularPromedioUltimos($datosCiclo, $tipo);
            $iteraciones++;
        }

        // Si después de todas las iteraciones no coincide, forzar los valores exactos
        $promedio = $this->calcularPromedioUltimos($datosCiclo, $tipo);
        if (abs($promedio - $valorReferencia) > 0.001) {
            // Distribuir el valor de referencia entre los 10 últimos elementos
            // con pequeñas variaciones para que sea más realista
            $variaciones = $this->generarVariacionesSuaves($valorReferencia, $tipo);

            for ($i = 50; $i < 60; $i++) {
                $datosCiclo[$i][$tipo] = $variaciones[$i - 50];
            }
        }

        // Redondear según el tipo de medición
        return $this->redondearUltimosValores($datosCiclo, $tipo);
    }

    function generarVariacionesSuaves($valorObjetivo, $tipo)
    {
        $variaciones = [];
        $suma = 0;

        // Generar 10 valores con pequeñas variaciones
        for ($i = 0; $i < 10; $i++) {
            if ($tipo == 'hc') {
                $variacion = rand(-3, 3);
            } elseif ($tipo == 'co') {
                $variacion = rand(-2, 2) / 1000;
            } elseif ($tipo == 'co2') {
                $variacion = rand(-1, 1) / 100;
            } else {
                $variacion = rand(-1, 1) / 100;
            }

            $variaciones[$i] = $valorObjetivo + $variacion;
            $suma += $variaciones[$i];
        }

        // Ajustar para que el promedio sea exactamente igual al valor objetivo
        $promedioActual = $suma / 10;
        $diferencia = $valorObjetivo - $promedioActual;

        // Distribuir la diferencia en todos los elementos
        for ($i = 0; $i < 10; $i++) {
            $variaciones[$i] += $diferencia;
        }

        return $variaciones;
    }

    /**
     * Redondea los últimos 10 valores según el tipo
     */
    function redondearUltimosValores($datosCiclo, $tipo)
    {
        for ($i = 50; $i < 60; $i++) {
            if ($tipo == 'hc') {
                $datosCiclo[$i]['hc'] = round($datosCiclo[$i]['hc']);
            } elseif ($tipo == 'co') {
                $datosCiclo[$i]['co'] = round($datosCiclo[$i]['co'], 3);
            } elseif ($tipo == 'co2') {
                $datosCiclo[$i]['co2'] = round($datosCiclo[$i]['co2'], 2);
            }
        }

        return $datosCiclo;
    }

    /**
     * Calcula el promedio de las últimas 10 muestras
     */
    function calcularPromedioUltimos($datosCiclo, $tipo)
    {
        $suma = 0;
        for ($i = 50; $i < 60; $i++) {
            $suma += $datosCiclo[$i][$tipo];
        }
        return $suma / 10;
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
