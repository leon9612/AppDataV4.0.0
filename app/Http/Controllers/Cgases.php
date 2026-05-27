<?php

namespace App\Http\Controllers;

use App\Models\Malineacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Libraries\Encrypt;
use App\Traits\EventosTrait;

class Cgases extends Controller
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

                if (
                    $objeto->conf_idtipo_prueba == 3 && $objeto->activo == 1 &&
                    (
                        $objeto->idconf_linea_inspeccion == 1 ||
                        $objeto->idconf_linea_inspeccion == 7 ||
                        $objeto->idconf_linea_inspeccion == 8 ||
                        $objeto->idconf_linea_inspeccion == 4 ||
                        $objeto->idconf_linea_inspeccion == 11 ||
                        $objeto->idconf_linea_inspeccion == 12
                    )
                ) {
                    // Crear un objeto con la estructura que espera la vista
                    $data['maquinas'][] = (object)[
                        'idmaquina' => $objeto->idconf_maquina . '|' . strtoupper($objeto->serie_maquina),
                        'maquina' => strtoupper($objeto->nombre) . '-' . strtoupper($objeto->marca) . '-' . strtoupper($objeto->serie_maquina) . '-' . strtoupper($objeto->serie_banco)
                    ];
                }
            }

            // $data['placas'] = DB::select("select
            //                                     p.idprueba,
            //                                     v.numero_placa as 'placa'
            //                                     from vehiculos v, hojatrabajo h, pruebas p
            //                                     where
            //                                     v.idvehiculo = h.idvehiculo and h.idhojapruebas = p.idhojapruebas  and p.idtipo_prueba=3 and p.estado = 0 and
            //                                     date_format(p.fechainicial, '%y-%m-%d') = date_format(curdate(), '%y-%m-%d') and (v.tipo_vehiculo = 1 or v.tipo_vehiculo=2) order by p.fechainicial asc ");
            $request = new Request();
            $request->merge([
                'tipoprueba' => '3',
                'tipovehiculo' => '1',
                'tipoejecucionprueba' => 'corregir'
            ]);

            $data['placas'] = $this->getPlacas($request);
            $data['usuarios'] = DB::select("select u.IdUsuario, concat(u.nombres,' ',u.apellidos ) as 'nombre' from usuarios u where u.idperfil = 2 and u.estado = 1");
            // $data['maquinas'] = DB::select("select  m.idmaquina, concat(m.nombre, ' ', m.marca, ' ', m.serie) as 'maquina' from maquina m where m.estado = 1 and m.idtipo_prueba = 3 and (m.idbanco = 1 or m.idbanco = 2)");
            $data['tempMotor'] = rand(50, 70);
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
            //$data['placas'] = Malineacion::paginate(5);
            return view('TipoPrueba.gases', $data);
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
            'hc_crucero' => 'required',
            'co_crucero' => 'required',
            'co2_crucero' => 'required',
            'o2_crucero' => 'required',
            'rpm_crucero' => 'required',
            'idprueba' => 'required',
            'selEstado' => 'required',
            'selUsuario' => 'required',
            'selMaquina' => 'required',
            'tempMotor' => 'required',
            'humedad' => 'required',
            'tempAmbiente' => 'required',
        ]);
        date_default_timezone_set('America/bogota');
        $now = date('Y-m-d H:i:s'); //Fomat Date and time3
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
        //insert version software
        // $temR = DB::select("select IFNULL((SELECT  ifnull(AES_DECRYPT(c.parametro ,'353E9D61B66D77CAE6BF97DE8F7CAWYJFLLD2D765SD4894165SD81SD'), c.parametro) FROM config_maquina c WHERE c.tipo_parametro = 'Temperatura Ambiente' LIMIT 1),'18.9') AS 'val'");
        $ranTemp = request()->input('tempAmbiente');
        // if ($ranTemp == 0 || $ranTemp == '0') {
        //     $ranTemp = rand(16 * 10, 17 * 10) / 10;
        // }

        // $humR = DB::select("select IHumedad RelativaFNULL((SELECT c.parametro FROM config_maquina c WHERE c.tipo_parametro = 'Humedad Relativa' LIMIT 1),'68.9') AS 'val'");
        // $humR = DB::select("select IFNULL((SELECT  ifnull(AES_DECRYPT(c.parametro ,'353E9D61B66D77CAE6BF97DE8F7CAWYJFLLD2D765SD4894165SD81SD'), c.parametro) FROM config_maquina c WHERE c.tipo_parametro = 'Humedad Relativa' LIMIT 1),'68.9') AS 'val'");
        $ranHum = request()->input('humedad');
        // if ($ranHum == 0 || $ranHum == 0) {
        //     $ranHum = rand(68 * 10, 70 * 10) / 10;
        // }

        // $tempAceite = rand(50, 70);
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
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'rpm_crucero', '" . request()->input('rpm_crucero') . "' , '" . $now . "', 'rpm_crucero', '91', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'rpm_crucero', request()->input('rpm_crucero'), $now, 'rpm_crucero', '91') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'hc_crucero', '" . request()->input('hc_crucero') . "' , '" . $now . "', 'hc_crucero', '92', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'hc_crucero', request()->input('hc_crucero'), $now, 'hc_crucero', '92') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'co_crucero', '" . request()->input('co_crucero') . "' , '" . $now . "', 'co_crucero', '93', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'co_crucero', request()->input('co_crucero'), $now, 'co_crucero', '93') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'co2_crucero', '" . request()->input('co2_crucero') . "' , '" . $now . "', 'co2_crucero', '94', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'co2_crucero', request()->input('co2_crucero'), $now, 'co2_crucero', '94') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'o2_crucero', '" . request()->input('o2_crucero') . "' , '" . $now . "', 'o2_crucero', '95', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'o2_crucero', request()->input('o2_crucero'), $now, 'o2_crucero', '95') . "','" . $this->key . "'))");
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
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'rpm_crucero', '" . request()->input('rpm_crucero') . "' , '" . $now . "', 'rpm_crucero', '91', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'rpm_crucero', request()->input('rpm_crucero'), $now, 'rpm_crucero', '91') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'hc_ralenti', '" . request()->input('hc_ralenti') . "' , '" . $now . "', 'hc_ralenti', '87', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'hc_ralenti', request()->input('hc_ralenti'), $now, 'hc_ralenti', '87') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'co_crucero', '" . request()->input('co_crucero') . "' , '" . $now . "', 'co_crucero', '93', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'co_crucero', request()->input('co_crucero'), $now, 'co_crucero', '93') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'co2_crucero', '" . request()->input('co2_crucero') . "' , '" . $now . "', 'co2_crucero', '94', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'co2_crucero', request()->input('co2_crucero'), $now, 'co2_crucero', '94') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'o2_crucero', '" . request()->input('o2_crucero') . "' , '" . $now . "', 'o2_crucero', '95', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'o2_crucero', request()->input('o2_crucero'), $now, 'o2_crucero', '95') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'rpm_ralenti', '" . request()->input('rpm_ralenti') . "' , '" . $now . "', 'rpm_ralenti', '86', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'rpm_ralenti', request()->input('rpm_ralenti'), $now, 'rpm_ralenti', '86') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'co_ralenti', '" . request()->input('co_ralenti') . "' , '" . $now . "', 'co_ralenti', '88', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'co_ralenti', request()->input('co_ralenti'), $now, 'co_ralenti', '88') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'co2_ralenti', '" . request()->input('co2_ralenti') . "' , '" . $now . "', 'co2_ralenti', '89', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'co2_ralenti', request()->input('co2_ralenti'), $now, 'co2_ralenti', '89') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'o2_ralenti', '" . request()->input('o2_ralenti') . "' , '" . $now . "', 'o2_ralenti', '90', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'o2_ralenti', request()->input('o2_ralenti'), $now, 'o2_ralenti', '90') . "','" . $this->key . "'))");
            // DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'promhcra_ant', '" . request()->input('hc_ralenti') . "' , '" . $now . "', 'promhcra_ant', '222', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'promhcra_ant', request()->input('hc_ralenti'), $now, 'promhcra_ant', '222') . "','" . $this->key . "'))");
            // DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'promcora_ant', '" . request()->input('co_ralenti') . "' , '" . $now . "', 'promcora_ant', '223', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'promcora_ant', request()->input('co_ralenti'), $now, 'promcora_ant', '223') . "','" . $this->key . "'))");
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
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'hc_crucero', '" . request()->input('hc_crucero') . "' , '" . $now . "', 'hc_crucero', '92', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'hc_crucero', request()->input('hc_crucero'), $now, 'hc_crucero', '92') . "','" . $this->key . "'))");
        }

        DB::update("UPDATE vehiculos v set v.convertidor = '" . request()->input('selCatalizador') . "' where v.numero_placa = '" . request()->input('placa') . "'  ");
        DB::update("UPDATE vehiculos v set v.scooter = '" . request()->input('selCatalizador') . "' where v.numero_placa = '" . request()->input('placa') . "'  ");
        DB::update("UPDATE pruebas p set p.estado = " . request()->input('selEstado') . ", p.idmaquina = " . $idmaquina . ", p.idusuario = " . request()->input('selUsuario') . ", p.fechafinal = '" . $now . "' , p.enc = " . "AES_ENCRYPT('" . $this->updateEncr(request()->input('idprueba'), request()->input('selEstado'), $idmaquina, request()->input('selUsuario'), $now) . "','" . $this->key . "')" . " where p.idprueba = " . request()->input('idprueba') . "  ");

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
        $datosCrucero = "";
        if ($rta[0]->rpm_crucero > 0) {
            $datosCrucero = $this->generarCrucero($rta[0]);
            $datosCrucero = json_encode($datosCrucero);
        }

        // Guardar en base de datos
        $datos = [
            "idprueba" => $rta[0]->idprueba,
            "exosto" => 1,
            "datos_ciclo_ralenti" => json_encode($datosRalenti),
            "datos_ciclo_crucero" => $datosCrucero
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
    function generarCrucero($datosPrueba)
    {
        // Datos patrón para crucero (60 muestras - 30 segundos)
        $hc = [68, 68, 85, 85, 95, 95, 107, 107, 111, 121, 121, 121, 127, 127, 140, 140, 154, 154, 154, 172, 172, 172, 175, 175, 187, 187, 187, 187, 191, 193, 193, 193, 196, 196, 208, 208, 211, 211, 223, 224, 224, 224, 230, 230, 231, 231, 234, 234, 234, 234, 234, 234, 236, 236, 238, 238, 238, 245, 247, 253];
        $co = [0.15, 0.15, 0.19, 0.19, 0.22, 0.22, 0.24, 0.24, 0.26, 0.26, 0.26, 0.26, 0.29, 0.29, 0.3, 0.3, 0.33, 0.33, 0.33, 0.36, 0.36, 0.36, 0.37, 0.37, 0.38, 0.38, 0.39, 0.39, 0.4, 0.4, 0.4, 0.4, 0.4, 0.4, 0.41, 0.41, 0.41, 0.41, 0.42, 0.43, 0.43, 0.43, 0.44, 0.44, 0.44, 0.44, 0.45, 0.45, 0.45, 0.45, 0.45, 0.45, 0.45, 0.45, 0.45, 0.45, 0.45, 0.45, 0.45, 0.45];
        $co2 = [5.25, 5.25, 6.1, 6.1, 7.03, 7.03, 7.82, 7.82, 8.61, 9.31, 9.31, 9.31, 9.9, 9.9, 10.4, 10.4, 10.8, 10.8, 10.8, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12.6, 12.8, 12.8, 12.8, 13, 13, 13.2, 13.2, 13.3, 13.3, 13.5, 13.6, 13.6, 13.6, 13.7, 13.7, 13.8, 13.8, 13.8, 13.8, 13.9, 13.9, 13.9, 13.9, 14, 14, 14.1, 14.1, 14.1, 14.2, 14.2, 14.2];
        $o2 = [15.07, 15.07, 13.8, 13.8, 12.53, 12.53, 11.32, 11.32, 10.19, 9.13, 9.13, 9.13, 8.16, 8.16, 7.29, 7.29, 6.52, 6.52, 6.52, 5.27, 5.27, 5.27, 4.75, 4.75, 4.3, 4.3, 3.91, 3.91, 3.57, 3.27, 3.27, 3.27, 3.01, 3.01, 2.8, 2.8, 2.6, 2.6, 2.43, 2.29, 2.29, 2.29, 2.17, 2.17, 2.05, 2.05, 1.95, 1.95, 1.86, 1.79, 1.79, 1.79, 1.72, 1.72, 1.66, 1.66, 1.66, 1.6, 1.56, 1.51];
        $rpm = [2280, 2350, 2330, 2280, 2310, 2270, 2350, 2360, 2270, 2280, 2350, 2300, 2290, 2350, 2360, 2310, 2320, 2310, 2280, 2300, 2310, 2350, 2360, 2340, 2310, 2360, 2340, 2360, 2310, 2340, 2270, 2320, 2360, 2280, 2350, 2300, 2290, 2310, 2310, 2330, 2340, 2290, 2350, 2350, 2300, 2300, 2340, 2280, 2310, 2340, 2300, 2350, 2320, 2310, 2360, 2360, 2340, 2330, 2300, 2360];

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
        $dfhc = $promedioPatronHC - round($datosPrueba->hc_crucero);
        $dfco = $promedioPatronCO - $datosPrueba->co_crucero;
        $dfco2 = $promedioPatronCO2 - $datosPrueba->co2_crucero;
        $dfo2 = $promedioPatronO2 - $datosPrueba->o2_crucero;
        $dfrpm = $promedioPatronRPM - $datosPrueba->rpm_crucero;

        // Generar muestras del ciclo
        $datosCiclo = $this->generarMuestrasCiclo($hc, $co, $co2, $o2, $rpm, $dfhc, $dfco, $dfco2, $dfo2, $dfrpm);

        // Ajustar promedios finales
        $datosCiclo = $this->ajustarPromediosCrucero($datosCiclo, $datosPrueba);

        return $datosCiclo;
    }

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



    // function getBitacoraGases($idprueba)
    // {
    //     $hc = [1796, 1796, 1796, 3390, 3390, 4490, 4490, 4860, 5170, 5170, 5430, 6560, 6560, 6410, 6410, 6340, 6340, 6270, 6270, 6190, 6190, 6190, 6100, 6100, 6020, 6020, 6020, 5880, 5880, 5880, 5820, 5820, 5790, 5790, 5750, 5750, 5750, 5750, 5740, 5740, 5740, 5800, 5800, 5800, 5830, 5880, 5880, 5880, 5880, 5950, 5950, 5980, 5980, 6000, 6000, 6020, 6020, 6020, 6020, 6020];
    //     $co = [2.31, 2.31, 2.31, 3.97, 3.97, 4.75, 4.75, 4.97, 5.12, 5.12, 5.21, 5.59, 5.59, 5.43, 5.43, 5.34, 5.34, 5.25, 5.25, 5.19, 5.19, 5.19, 5.14, 5.14, 5.09, 5.09, 5.09, 5, 5, 5, 4.98, 4.98, 4.97, 4.97, 4.96, 4.96, 4.96, 4.96, 4.96, 4.96, 4.96, 4.94, 4.94, 4.94, 4.93, 4.92, 4.92, 4.92, 4.92, 4.93, 4.93, 4.95, 4.95, 4.98, 4.98, 5, 5, 5.01, 5.01, 5.01];
    //     $co2 = [1.4, 1.4, 1.4, 2.4, 2.4, 3, 3, 3.1, 3.1, 3.1, 3.2, 3.8, 3.8, 4.2, 4.2, 4.4, 4.4, 4.5, 4.5, 4.7, 4.7, 4.7, 4.8, 4.8, 4.9, 4.9, 4.9, 5.1, 5.1, 5.1, 5.2, 5.2, 5.2, 5.2, 5.2, 5.2, 5.2, 5.2, 5.2, 5.2, 5.2, 5.1, 5.1, 5.1, 5.1, 5, 5, 5, 5, 5, 5, 4.9, 4.9, 4.9, 4.9, 4.9, 4.9, 4.9, 4.9, 4.9];
    //     $o2 = [16.5, 16.5, 16.5, 13.9, 13.9, 12.4, 12.4, 11.9, 11.6, 11.6, 11.3, 10.1, 10.1, 9.8, 9.8, 9.6, 9.6, 9.5, 9.5, 9.4, 9.4, 9.4, 9.3, 9.3, 9.2, 9.2, 9.2, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9, 9.2, 9.2, 9.2, 9.3, 9.4, 9.4, 9.4, 9.4, 9.4, 9.4, 9.4, 9.4, 9.4, 9.4, 9.4, 9.4, 9.4, 9.4, 9.3];
    //     $rpm = [1380, 1460, 1340, 1310, 1320, 1320, 1340, 1350, 1340, 1340, 1320, 1370, 1380, 1400, 1400, 1420, 1400, 1380, 1380, 1380, 1400, 1410, 1400, 1390, 1390, 1400, 1400, 1400, 1400, 1410, 1380, 1400, 1410, 1400, 1400, 1400, 1400, 1400, 1380, 1370, 1390, 1400, 1420, 1400, 1380, 1390, 1410, 1400, 1410, 1390, 1410, 1420, 1380, 1370, 1390, 1390, 1420, 1420, 1420, 1440];
    //     $ultimosSegundosHC_1 = [-3, -3, -3, -2, 0, 0, 2, 3, 3, 3];
    //     $ultimosSegundosHC_2 = [-2, -2, -2, -1, 0, 0, 1, 2, 2, 2];
    //     $ultimosSegundosHC_3 = [-1, -1, -1, 0, 0, 0, 0, 1, 1, 1];
    //     $ultimosSegundosCO_1 = [-0.03, -0.03, -0.03, -0.02, 0, 0, 0.02, 0.03, 0.03, 0.03];
    //     $ultimosSegundosCO_2 = [-0.02, -0.02, -0.02, -0.01, 0, 0, 0.01, 0.02, 0.02, 0.02];
    //     $ultimosSegundosCO_3 = [-0.01, -0.01, -0.01, 0, 0, 0, 0, 0.01, 0.01, 0.01];

    //     $rta = Malineacion::getBitacoraGases($idprueba);
    //     if ($rta[0]->control == 0) {
    //         // se valida si aplica o no la correcion de oxigeno
    //         if (($rta[0]->o2_ralenti >= 11.0 && $rta[0]->tiempos == '2' && $rta[0]->tipo_vehiculo == 3 && $rta[0]->ano_modelo < 2010)) {
    //             $dfhc = 6001 - round($rta[0]->promhcra_ant);
    //             $dfco = 4.98 - $rta[0]->promcora_ant;
    //         } else if (($rta[0]->o2_ralenti >= 6.0 && $rta[0]->tiempos == '4' && $rta[0]->tipo_vehiculo == 3) || ($rta[0]->o2_ralenti >= 6.0 && $rta[0]->tiempos == '2' && $rta[0]->tipo_vehiculo == 3 && $rta[0]->ano_modelo >= 2010)) {
    //             $dfhc = 6001 - round($rta[0]->promhcra_ant);
    //             $dfco = 4.98 - $rta[0]->promcora_ant;
    //         } else {
    //             $dfhc = 6001 - round($rta[0]->hc_ralenti);
    //             $dfco = 4.98 - $rta[0]->co_ralenti;
    //         }
    //         $dfco2 = 4.91 - $rta[0]->co2_ralenti;
    //         $dfo2 = 9.39 - $rta[0]->o2_ralenti;
    //         $dfrpm = 1406 - $rta[0]->rpm_ralenti;
    //         // se calcula el modulo para poder sumarlo a las rpm y que de el promedio 
    //         $rpmreal = $rpm[0] - $dfrpm;
    //         $rpmmod = $rpmreal % 10;
    //         $t = 0.0;
    //         $hcfinal = [];
    //         $cofinal = [];
    //         $co2final = [];
    //         $o2final = [];
    //         $rpmfinal = [];
    //         $fechahora = [];
    //         $j_hc = 0;
    //         $rhc = rand(1, 3);
    //         $rco = rand(1, 3);
    //         $encontradoo2 = false;
    //         $o2v_ = 0;
    //         $rpmdata = ceil($dfrpm / 10) * 10;
    //         $ralentiData = [];
    //         for ($i = 1; $i <= 60; $i++) {
    //             if ($i > 1) {
    //                 $t = $t + 0.5;
    //             } else {
    //                 $t = $t;
    //             }
    //             $rpm_ = $rpm[$i - 1] - $rpmdata;
    //             $hc_ = $hc[$i - 1] - $dfhc;
    //             $co_ = round($co[$i - 1] - $dfco, 3);
    //             $co2_ = round($co2[$i - 1] - $dfco2, 2);
    //             $o2_ = round($o2[$i - 1] - $dfo2, 2);
    //             if ($o2_ > 18 && !$encontradoo2) {
    //                 $encontradoo2 = true;
    //                 $o2v_ = $o2_;
    //             }
    //             // cuadre de hc y co mediante los vectores de los ultimo 10 datos
    //             if ($i > 50) {
    //                 switch ($rhc) {
    //                     case 1:
    //                         $hc_ = $ultimosSegundosHC_1[$j_hc] + $hc_;
    //                         break;
    //                     case 2:
    //                         $hc_ = $ultimosSegundosHC_2[$j_hc] + $hc_;
    //                         break;
    //                     case 3:
    //                         $hc_ = $ultimosSegundosHC_3[$j_hc] + $hc_;
    //                         break;
    //                 }
    //                 switch ($rco) {
    //                     case 1:
    //                         $co_ = $ultimosSegundosCO_1[$j_hc] + $co_;
    //                         break;
    //                     case 2:
    //                         $co_ = $ultimosSegundosCO_2[$j_hc] + $co_;
    //                         break;
    //                     case 3:
    //                         $co_ = $ultimosSegundosCO_3[$j_hc] + $co_;
    //                         break;
    //                 }
    //                 //cuadre de rpm
    //                 if ($rpmmod > 0) {
    //                     $rpm_ = $rpm_ + 10;
    //                 }
    //                 $rpmmod--;
    //                 $j_hc++;
    //             }
    //             $arrayRal = [
    //                 "tiempo" => $t,
    //                 "hc" => $hc_,
    //                 "co" => $co_,
    //                 "co2" => $co2_,
    //                 "o2" => $o2_,
    //                 "rpm" => $rpm_
    //             ];

    //             array_push($ralentiData, $arrayRal);
    //         }

    //         // se cambian los datos negativos por el primer valor positivo del vector
    //         // cuadre HC raelnti
    //         $promediohc = 0;
    //         for ($a = 0; $a < count($ralentiData); $a++) {
    //             if ($ralentiData[$a]["hc"] < 0) {
    //                 $ralentiData[$a]['hc'] = $ralentiData[$a]['hc'] * -1;
    //             }
    //             if ($a >= 50) {
    //                 $promediohc = $promediohc + $ralentiData[$a]['hc'];
    //             }
    //         }

    //         //            echo floatval($rta[0]->hc_ralenti) ."<br>".floatval($promediohc / 10);
    //         if (($rta[0]->o2_ralenti >= 11.0 && $rta[0]->tiempos == '2' && $rta[0]->tipo_vehiculo == 3 && $rta[0]->ano_modelo < 2010)) {
    //             if (floatval($rta[0]->promhcra_ant) !== floatval($promediohc / 10)) {
    //                 $ralentiData = $this->promedioCalculo($rta[0]->promhcra_ant, $ralentiData, 0.1, 'hc');
    //             }
    //         } else if (($rta[0]->o2_ralenti >= 6.0 && $rta[0]->tiempos == '4' && $rta[0]->tipo_vehiculo == 3) || ($rta[0]->o2_ralenti >= 6.0 && $rta[0]->tiempos == '2' && $rta[0]->tipo_vehiculo == 3 && $rta[0]->ano_modelo >= 2010)) {
    //             if (floatval($rta[0]->promhcra_ant) !== floatval($promediohc / 10)) {
    //                 $ralentiData = $this->promedioCalculo($rta[0]->promhcra_ant, $ralentiData, 0.1, 'hc');
    //             }
    //         } else {
    //             if (floatval($rta[0]->hc_ralenti) !== floatval($promediohc / 10)) {
    //                 //                    echo floatval($rta[0]->hc_ralenti) . "<br>" . floatval($promediohc / 10);
    //                 $ralentiData = $this->promedioCalculo($rta[0]->hc_ralenti, $ralentiData, 0.1, 'hc');
    //             }
    //         }

    //         //
    //         //
    //         //            // cuadre CO raelnti
    //         $PromedioCo = 0;
    //         for ($b = 0; $b < count($ralentiData); $b++) {
    //             if ($ralentiData[$b]['co'] < 0) {
    //                 $ralentiData[$b]['co'] = $ralentiData[$b]['co'] * -1;
    //             }
    //             if ($b >= 50) {
    //                 $PromedioCo = $PromedioCo + $ralentiData[$b]['co'];
    //             }
    //         }
    //         if (($rta[0]->o2_ralenti >= 11.0 && $rta[0]->tiempos == '2' && $rta[0]->tipo_vehiculo == 3 && $rta[0]->ano_modelo < 2010)) {
    //             if (floatval($rta[0]->promcora_ant) !== floatval($PromedioCo / 10)) {
    //                 $ralentiData = $this->promedioCalculo($rta[0]->promcora_ant, $ralentiData, 0.0001, 'co');
    //             }
    //         } else if (($rta[0]->o2_ralenti >= 6.0 && $rta[0]->tiempos == '4' && $rta[0]->tipo_vehiculo == 3) || ($rta[0]->o2_ralenti >= 6.0 && $rta[0]->tiempos == '2' && $rta[0]->tipo_vehiculo == 3 && $rta[0]->ano_modelo >= 2010)) {
    //             if (floatval($rta[0]->promcora_ant) !== floatval($PromedioCo / 10)) {
    //                 $ralentiData = $this->promedioCalculo($rta[0]->promcora_ant, $ralentiData, 0.0001, 'co');
    //             }
    //         } else {
    //             if (floatval($rta[0]->co_ralenti) !== floatval($PromedioCo / 10)) {
    //                 $ralentiData = $this->promedioCalculo($rta[0]->co_ralenti, $ralentiData, 0.0001, 'co');
    //             }
    //         }
    //         //
    //         //
    //         //            // cuadre CO2 raelnti
    //         $promedioCO2 = 0;
    //         for ($c = 0; $c < count($ralentiData); $c++) {
    //             if ($ralentiData[$c]['co2'] < 0) {
    //                 $ralentiData[$c]['co2'] = $ralentiData[$c]['co2'] * -1;
    //             }
    //             if ($c >= 50) {
    //                 $promedioCO2 = $promedioCO2 + $ralentiData[$c]['co2'];
    //             }
    //         }
    //         if (floatval($rta[0]->co2_ralenti) !== floatval($promedioCO2 / 10)) {
    //             $ralentiData = $this->promedioCalculo($rta[0]->co2_ralenti, $ralentiData, 0.0001, 'co2');
    //         }

    //         for ($d = 0; $d < count($ralentiData); $d++) {
    //             if ($ralentiData[$d]['o2'] > 18) {
    //                 $ralentiData[$d]['o2'] = $o2v_;
    //             }
    //             if ($ralentiData[$d]['o2'] < 0) {
    //                 $ralentiData[$d]['o2'] = 0.0;
    //             }
    //         }

    //         if ($rta[0]->rpm_crucero > 0) {
    //             $r = $this->logCrucero($rta);
    //             $res = json_encode($r);
    //         } else {
    //             $res = "";
    //         }

    //         $datos["idprueba"] = $rta[0]->idprueba;
    //         $datos["exosto"] = 1;
    //         $datos["datos_ciclo_ralenti"] = json_encode($ralentiData);
    //         $datos["datos_ciclo_crucero"] = $res;
    //         $rta = Malineacion::logGasesInsert($datos);
    //         //$this->Mambientales->logGasesInsert($datos);
    //     }
    // }

    // public function logCrucero($rta)
    // {
    //     $hc = [68, 68, 85, 85, 95, 95, 107, 107, 111, 121, 121, 121, 127, 127, 140, 140, 154, 154, 154, 172, 172, 172, 175, 175, 187, 187, 187, 187, 191, 193, 193, 193, 196, 196, 208, 208, 211, 211, 223, 224, 224, 224, 230, 230, 231, 231, 234, 234, 234, 234, 234, 234, 236, 236, 238, 238, 238, 245, 247, 253];
    //     $co = [0.15, 0.15, 0.19, 0.19, 0.22, 0.22, 0.24, 0.24, 0.26, 0.26, 0.26, 0.26, 0.29, 0.29, 0.3, 0.3, 0.33, 0.33, 0.33, 0.36, 0.36, 0.36, 0.37, 0.37, 0.38, 0.38, 0.39, 0.39, 0.4, 0.4, 0.4, 0.4, 0.4, 0.4, 0.41, 0.41, 0.41, 0.41, 0.42, 0.43, 0.43, 0.43, 0.44, 0.44, 0.44, 0.44, 0.45, 0.45, 0.45, 0.45, 0.45, 0.45, 0.45, 0.45, 0.45, 0.45, 0.45, 0.45, 0.45, 0.45];
    //     $co2 = [5.25, 5.25, 6.1, 6.1, 7.03, 7.03, 7.82, 7.82, 8.61, 9.31, 9.31, 9.31, 9.9, 9.9, 10.4, 10.4, 10.8, 10.8, 10.8, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12.6, 12.8, 12.8, 12.8, 13, 13, 13.2, 13.2, 13.3, 13.3, 13.5, 13.6, 13.6, 13.6, 13.7, 13.7, 13.8, 13.8, 13.8, 13.8, 13.9, 13.9, 13.9, 13.9, 14, 14, 14.1, 14.1, 14.1, 14.2, 14.2, 14.2];
    //     $o2 = [15.07, 15.07, 13.8, 13.8, 12.53, 12.53, 11.32, 11.32, 10.19, 9.13, 9.13, 9.13, 8.16, 8.16, 7.29, 7.29, 6.52, 6.52, 6.52, 5.27, 5.27, 5.27, 4.75, 4.75, 4.3, 4.3, 3.91, 3.91, 3.57, 3.27, 3.27, 3.27, 3.01, 3.01, 2.8, 2.8, 2.6, 2.6, 2.43, 2.29, 2.29, 2.29, 2.17, 2.17, 2.05, 2.05, 1.95, 1.95, 1.86, 1.79, 1.79, 1.79, 1.72, 1.72, 1.66, 1.66, 1.66, 1.6, 1.56, 1.51];
    //     $rpm = [2280, 2350, 2330, 2280, 2310, 2270, 2350, 2360, 2270, 2280, 2350, 2300, 2290, 2350, 2360, 2310, 2320, 2310, 2280, 2300, 2310, 2350, 2360, 2340, 2310, 2360, 2340, 2360, 2310, 2340, 2270, 2320, 2360, 2280, 2350, 2300, 2290, 2310, 2310, 2330, 2340, 2290, 2350, 2350, 2300, 2300, 2340, 2280, 2310, 2340, 2300, 2350, 2320, 2310, 2360, 2360, 2340, 2330, 2300, 2360];
    //     $ultimosSegundosHC_1 = [-3, -3, -3, -2, 0, 0, 2, 3, 3, 3];
    //     $ultimosSegundosHC_2 = [-2, -2, -2, -1, 0, 0, 1, 2, 2, 2];
    //     $ultimosSegundosHC_3 = [-1, -1, -1, 0, 0, 0, 0, 1, 1, 1];
    //     $ultimosSegundosCO_1 = [-0.03, -0.03, -0.03, -0.02, 0, 0, 0.02, 0.03, 0.03, 0.03];
    //     $ultimosSegundosCO_2 = [-0.02, -0.02, -0.02, -0.01, 0, 0, 0.01, 0.02, 0.02, 0.02];
    //     $ultimosSegundosCO_3 = [-0.01, -0.01, -0.01, 0, 0, 0, 0, 0.01, 0.01, 0.01];

    //     // se valida si aplica o no la correcion de oxigeno

    //     $dfhc = 239 - round($rta[0]->hc_crucero);
    //     $dfco = 0.45 - $rta[0]->co_crucero;
    //     $dfco2 = 14.07 - $rta[0]->co2_crucero;
    //     $dfo2 = 1.667 - $rta[0]->o2_crucero;
    //     $dfrpm = 2333 - $rta[0]->rpm_crucero;
    //     // se calcula el modulo para poder sumarlo a las rpm y que de el promedio 
    //     $rpmreal = $rpm[0] - $dfrpm;
    //     $rpmmod = $rpmreal % 10;
    //     $t = 0.0;
    //     $hcfinal = [];
    //     $cofinal = [];
    //     $co2final = [];
    //     $o2final = [];
    //     $rpmfinal = [];
    //     $fechahora = [];
    //     $j_hc = 0;
    //     $rhc = rand(1, 3);
    //     $rco = rand(1, 3);
    //     $encontradoo2 = false;
    //     $o2v_ = 0;
    //     $rpmdata = ceil($dfrpm / 10) * 10;
    //     $cruceroData = [];
    //     for ($i = 1; $i <= 60; $i++) {
    //         if ($i > 1) {
    //             $t = $t + 0.5;
    //         } else {
    //             $t = $t;
    //         }
    //         $rpm_ = $rpm[$i - 1] - $rpmdata;
    //         $hc_ = $hc[$i - 1] - $dfhc;
    //         $co_ = round($co[$i - 1] - $dfco, 3);
    //         $co2_ = round($co2[$i - 1] - $dfco2, 2);
    //         $o2_ = round($o2[$i - 1] - $dfo2, 2);
    //         if ($o2_ > 18 && !$encontradoo2) {
    //             $encontradoo2 = true;
    //             $o2v_ = $o2_;
    //         }
    //         // cuadre de hc y co mediante los vectores de los ultimo 10 datos
    //         if ($i > 50) {
    //             switch ($rhc) {
    //                 case 1:
    //                     $hc_ = $ultimosSegundosHC_1[$j_hc] + $hc_;
    //                     break;
    //                 case 2:
    //                     $hc_ = $ultimosSegundosHC_2[$j_hc] + $hc_;
    //                     break;
    //                 case 3:
    //                     $hc_ = $ultimosSegundosHC_3[$j_hc] + $hc_;
    //                     break;
    //             }
    //             switch ($rco) {
    //                 case 1:
    //                     $co_ = $ultimosSegundosCO_1[$j_hc] + $co_;
    //                     break;
    //                 case 2:
    //                     $co_ = $ultimosSegundosCO_2[$j_hc] + $co_;
    //                     break;
    //                 case 3:
    //                     $co_ = $ultimosSegundosCO_3[$j_hc] + $co_;
    //                     break;
    //             }
    //             //cuadre de rpm
    //             if ($rpmmod > 0) {
    //                 $rpm_ = $rpm_ + 10;
    //             }
    //             $rpmmod--;
    //             $j_hc++;
    //         }
    //         $arrayRal = [
    //             "tiempo" => $t,
    //             "hc" => $hc_,
    //             "co" => $co_,
    //             "co2" => $co2_,
    //             "o2" => $o2_,
    //             "rpm" => $rpm_
    //         ];

    //         array_push($cruceroData, $arrayRal);
    //     }
    //     // se cambian los datos negativos por el primer valor positivo del vector
    //     $promedioHC = 0;
    //     for ($c = 0; $c < count($cruceroData); $c++) {
    //         if ($cruceroData[$c]['hc'] < 0) {
    //             $cruceroData[$c]['hc'] = $cruceroData[$c]['hc'] * -1;
    //         }
    //         if ($c >= 50) {
    //             $promedioHC = $promedioHC + $cruceroData[$c]['hc'];
    //         }
    //     }
    //     if (floatval($rta[0]->hc_crucero) !== floatval($promedioHC / 10)) {
    //         $cruceroData = $this->promedioCalculo($rta[0]->hc_crucero, $cruceroData, 0.1, 'hc');
    //     }

    //     $promedioCO = 0;
    //     for ($c = 0; $c < count($cruceroData); $c++) {
    //         if ($cruceroData[$c]['co'] < 0) {
    //             $cruceroData[$c]['co'] = $cruceroData[$c]['co'] * -1;
    //         }
    //         if ($c >= 50) {
    //             $promedioCO = $promedioCO + $cruceroData[$c]['co'];
    //         }
    //     }
    //     if (floatval($rta[0]->co_crucero) !== floatval($promedioCO / 10)) {
    //         $cruceroData = $this->promedioCalculo($rta[0]->co_crucero, $cruceroData, 0.0001, 'co');
    //     }

    //     $promedioCO2 = 0;
    //     for ($c = 0; $c < count($cruceroData); $c++) {
    //         if ($cruceroData[$c]['co2'] < 0) {
    //             $cruceroData[$c]['co2'] = $cruceroData[$c]['co2'] * -1;
    //         }
    //         if ($c >= 50) {
    //             $promedioCO2 = $promedioCO2 + $cruceroData[$c]['co2'];
    //         }
    //     }
    //     if (floatval($rta[0]->co2_crucero) !== floatval($promedioCO2 / 10)) {
    //         $cruceroData = $this->promedioCalculo($rta[0]->co2_crucero, $cruceroData, 0.0001, 'co2');
    //     }

    //     for ($d = 0; $d < count($cruceroData); $d++) {
    //         if ($cruceroData[$d]['o2'] > 18) {
    //             $cruceroData[$d]['o2'] = $o2v_;
    //         }
    //         if ($cruceroData[$d]['o2'] < 0) {
    //             $cruceroData[$d]['o2'] = 0.0;
    //         }
    //     }
    //     return $cruceroData;
    // }

    // function promedioCalculo($rta, $ralentiData, $resolucion, $tipo)
    // {
    //     do {
    //         $varPromedioCo_ = 0;
    //         for ($b = 0; $b < count($ralentiData); $b++) {
    //             if ($b >= 50) {
    //                 if ($rta == 0) {
    //                     $ralentiData[$b][$tipo] = 0;
    //                 } else {
    //                     if ($ralentiData[$b][$tipo] - $resolucion > 0) {
    //                         $ralentiData[$b][$tipo] = round($ralentiData[$b][$tipo] - $resolucion, 4);
    //                     }
    //                 }

    //                 $varPromedioCo_ = $varPromedioCo_ + $ralentiData[$b][$tipo];
    //             }
    //         }
    //         $var_ = $varPromedioCo_ / 10;
    //     } while ($rta < $var_);

    //     //        echo "rta:" . $rta . "<br>";
    //     //        echo "promedio: " . round($var_,3) . "<br>";

    //     if (floatval($rta) !== floatval($var_)) {
    //         for ($b = 0; $b < count($ralentiData); $b++) {
    //             if ($b >= 50) {
    //                 $ralentiData[$b][$tipo] = floatval($rta);
    //             }
    //         }
    //     }
    //     for ($b = 0; $b < count($ralentiData); $b++) {
    //         if ($b >= 50) {
    //             if ($tipo == "hc") {
    //                 $ralentiData[$b][$tipo] = round($ralentiData[$b][$tipo]);
    //             }
    //             if ($tipo == "co") {
    //                 $ralentiData[$b][$tipo] = round($ralentiData[$b][$tipo], 3);
    //             }
    //             if ($tipo == "co2") {
    //                 $ralentiData[$b][$tipo] = round($ralentiData[$b][$tipo], 2);
    //             }
    //         }
    //     }
    //     return $ralentiData;
    // }

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
