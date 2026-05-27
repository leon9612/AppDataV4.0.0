<?php

namespace App\Http\Controllers;

use App\Models\Malineacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Libraries\Encrypt;
use App\Traits\EventosTrait;

class Copacidad extends Controller
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
                if ($objeto->conf_idtipo_prueba == 2 && $objeto->activo == 1) {
                    // Crear un objeto con la estructura que espera la vista
                    $data['maquinas'][] = (object)[
                        'idmaquina' => $objeto->idconf_maquina . '|' . strtoupper($objeto->serie_maquina),
                        'maquina' => strtoupper($objeto->nombre) . '-' . strtoupper($objeto->marca) . '-' . strtoupper($objeto->serie_maquina) . '-' . strtoupper($objeto->serie_banco)
                    ];
                }
            }

            //     $data['placas'] = DB::select("select
            // p.idprueba,
            // v.numero_placa as 'placa'
            // from vehiculos v, hojatrabajo h, pruebas p
            // where
            // v.idvehiculo = h.idvehiculo and h.idhojapruebas = p.idhojapruebas and
            // p.idtipo_prueba=2 and p.estado = 0 and
            // date_format(p.fechainicial, '%y-%m-%d') = date_format(curdate(), '%y-%m-%d') and (v.tipo_vehiculo = 1 or v.tipo_vehiculo=2) order by p.fechainicial asc ");
            $request = new Request();
            $request->merge([
                'tipoprueba' => '2',
                'tipovehiculo' => '1',
                'tipoejecucionprueba' => 'corregir'
            ]);

            $data['placas'] = $this->getPlacas($request);
            $data['usuarios'] = DB::select("select u.IdUsuario, concat(u.nombres,' ',u.apellidos ) as 'nombre' from usuarios u where u.idperfil = 2 and u.estado = 1");
            // $data['maquinas'] = DB::select("select  m.idmaquina, concat(m.nombre, ' ', m.marca, ' ', m.serie) as 'maquina' from maquina m where m.estado = 1 and m.idtipo_prueba = 2 ");
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
            $temRand = rand(50, 70);
            $data['tmpinicial'] = $temRand;
            $data['tmpfinal'] = rand($temRand, $temRand + 5);
            // $data['tempMotor'] = rand(50, 70);
            //$data['placas'] = Malineacion::paginate(5);
            return view('TipoPrueba.opacidad', $data);
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
            'opa1' => 'required',
            'opa2' => 'required',
            'opa3' => 'required',
            'opa4' => 'required',
            'opa1k' => 'required',
            'opa2k' => 'required',
            'opa3k' => 'required',
            'opa4k' => 'required',
            'Rpm_gobernada' => 'required',
            'Rpm_ralenti' => 'required',
            'ltoe' => 'required',
            'idprueba' => 'required',
            'selEstado' => 'required',
            'selUsuario' => 'required',
            'selMaquina' => 'required',
            'humedad' => 'required',
            'tempAmbiente' => 'required',
            'tmpinicial' => 'required',
            'tmpfinal' => 'required',
        ]);


        $idmaquina = explode('|', request()->input('selMaquina'))[0];
        $idmaquina = intval($idmaquina);
        date_default_timezone_set('America/bogota');
        $now = date("Y-m-d H:i:s");
          if (request()->input('tipoejecucionprueba') == 'corregir') {
            $idprueba = request()->input('idprueba');
            try {
                DB::delete("DELETE FROM resultados WHERE idprueba = $idprueba");
                
            } catch (\Exception $e) {
                return back()->with("error", "Error al corregir: " . $e->getMessage());
            }
        }
        //$now = date('Y-m-d H:i:s'); //Fomat Date and time
        //insert version software
        $temp = rand(50, 60);
        $opaTotal = request()->input('opa2') + request()->input('opa3') + request()->input('opa4');
        $opafinal = round($opaTotal / 3, 2);
        $opaTotalK = request()->input('opa2k') + request()->input('opa3k') + request()->input('opa4k');
        $opafinalK = round($opaTotalK / 3, 2);
        $rpm1 = rand(request()->input('Rpm_gobernada') + 80, request()->input('Rpm_gobernada'));
        $rpm2 = rand(request()->input('Rpm_gobernada') + 80, request()->input('Rpm_gobernada'));
        $rpm3 = rand(request()->input('Rpm_gobernada') + 80, request()->input('Rpm_gobernada'));
        $rpm4 = rand(request()->input('Rpm_gobernada') + 80, request()->input('Rpm_gobernada'));

        $ranTemp = request()->input('tempAmbiente');
        $ranHum = request()->input('humedad');
        $temRand = request()->input('tmpinicial');
        $temFinal = request()->input('tmpfinal');
        if (versionAplicaction() == 1) {
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'defecto', '' , '" . $now . "', 'APROBADA INSPECCION VISUAL', '33', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'defecto', '', $now, 'APROBADA INSPECCION VISUAL', '33') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'op_ciclo1', '" . request()->input('opa1') . "' , '" . $now . "', 'op_ciclo1', '34', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'op_ciclo1', request()->input('opa1'), $now, 'op_ciclo1', '34') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'op_ciclo2', '" . request()->input('opa2') . "' , '" . $now . "', 'op_ciclo2', '35', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'op_ciclo2', request()->input('opa2'), $now, 'op_ciclo2', '35') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'op_ciclo3', '" . request()->input('opa3') . "' , '" . $now . "', 'op_ciclo3', '36', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'op_ciclo3', request()->input('opa3'), $now, 'op_ciclo3', '36') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'op_ciclo4', '" . request()->input('opa4') . "' , '" . $now . "', 'op_ciclo4', '37', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'op_ciclo4', request()->input('opa4'), $now, 'op_ciclo4', '37') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'op_ciclo1K', '" . request()->input('opa1k') . "' , '" . $now . "', 'op_ciclo1K', '501', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'op_ciclo1K', request()->input('opa1k'), $now, 'op_ciclo1K', '501') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'op_ciclo2K', '" . request()->input('opa2k') . "' , '" . $now . "', 'op_ciclo2K', '502', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'op_ciclo2K', request()->input('opa2k'), $now, 'op_ciclo2K', '502') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'op_ciclo3K', '" . request()->input('opa3k') . "' , '" . $now . "', 'op_ciclo3K', '503', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'op_ciclo3K', request()->input('opa3k'), $now, 'op_ciclo3K', '503') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'op_ciclo4K', '" . request()->input('opa4k') . "' , '" . $now . "', 'op_ciclo4K', '504', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'op_ciclo4K', request()->input('opa4k'), $now, 'op_ciclo4K', '504') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'rpm_gobernada', '" . request()->input('Rpm_gobernada') . "' , '" . $now . "', 'rpm_gobernada', '41', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'rpm_gobernada', request()->input('Rpm_gobernada'), $now, 'rpm_gobernada', '41') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'rpm_ralenti', '" . request()->input('Rpm_ralenti') . "' , '" . $now . "', 'rpm_ralenti', '38', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'rpm_ralenti', request()->input('Rpm_ralenti'), $now, 'rpm_ralenti', '38') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'opacidad_total', '" . $opafinal . "' , '" . $now . "', 'opacidad_total', '61', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'opacidad_total', $opafinal, $now, 'opacidad_total', '61') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'opacidad_totalK', '" . $opafinalK . "' , '" . $now . "', 'opacidad_totalK', '505', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'opacidad_totalK', $opafinalK, $now, 'opacidad_totalK', '505') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'rpm_ciclo1', '" . $rpm1 . "' , '" . $now . "', 'rpm_ciclo1', '62', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'rpm_ciclo1', $rpm1, $now, 'rpm_ciclo1', '62') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'rpm_ciclo2', '" . $rpm2 . "' , '" . $now . "', 'rpm_ciclo2', '63', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'rpm_ciclo2', $rpm2, $now, 'rpm_ciclo2', '63') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'rpm_ciclo3', '" . $rpm3 . "' , '" . $now . "', 'rpm_ciclo3', '64', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'rpm_ciclo3', $rpm3, $now, 'rpm_ciclo3', '64') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'rpm_ciclo4', '" . $rpm4 . "' , '" . $now . "', 'rpm_ciclo4', '65', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'rpm_ciclo4', $rpm4, $now, 'rpm_ciclo4', '65') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'temperatura_ambiente', '" . $ranTemp . "' , '" . $now . "', 'temperatura_ambiente', '200', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'temperatura_ambiente', $ranTemp, $now, 'temperatura_ambiente', '200') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'humedad', '" . $ranHum . "' , '" . $now . "', 'humedad', '201', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'humedad', $ranHum, $now, 'humedad', '201') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'temp_inicial', '" . $temRand . "' , '" . $now . "', 'temp_inicial', '224', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'temp_inicial', $temRand, $now, 'temp_inicial', '224') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'temp_final', '" . $temFinal . "' , '" . $now . "', 'temp_final', '39', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'temp_final', $temFinal, $now, 'temp_final', '39') . "','" . $this->key . "'))");
        } else {
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'op_ciclo2', '" . request()->input('opa2') . "' , '" . $now . "', 'op_ciclo2', '35', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'op_ciclo2', request()->input('opa2'), $now, 'op_ciclo2', '35') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'op_ciclo3', '" . request()->input('opa3') . "' , '" . $now . "', 'op_ciclo3', '36', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'op_ciclo3', request()->input('opa3'), $now, 'op_ciclo3', '36') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'op_ciclo4', '" . request()->input('opa4') . "' , '" . $now . "', 'op_ciclo4', '37', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'op_ciclo4', request()->input('opa4'), $now, 'op_ciclo4', '37') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'op_ciclo1K', '" . request()->input('opa1k') . "' , '" . $now . "', 'op_ciclo1K', '501', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'op_ciclo1K', request()->input('opa1k'), $now, 'op_ciclo1K', '501') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'op_ciclo2K', '" . request()->input('opa2k') . "' , '" . $now . "', 'op_ciclo2K', '502', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'op_ciclo2K', request()->input('opa2k'), $now, 'op_ciclo2K', '502') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'op_ciclo3K', '" . request()->input('opa3k') . "' , '" . $now . "', 'op_ciclo3K', '503', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'op_ciclo3K', request()->input('opa3k'), $now, 'op_ciclo3K', '503') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'op_ciclo4K', '" . request()->input('opa4k') . "' , '" . $now . "', 'op_ciclo4K', '504', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'op_ciclo4K', request()->input('opa4k'), $now, 'op_ciclo4K', '504') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'opacidad_total', '" . $opafinal . "' , '" . $now . "', 'opacidad_total', '61', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'opacidad_total', $opafinal, $now, 'opacidad_total', '61') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'rpm_gobernada', '" . request()->input('Rpm_gobernada') . "' , '" . $now . "', 'rpm_gobernada', '41', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'rpm_gobernada', request()->input('Rpm_gobernada'), $now, 'rpm_gobernada', '41') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'rpm_ciclo1', '" . $rpm1 . "' , '" . $now . "', 'rpm_ciclo1', '62', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'rpm_ciclo1', $rpm1, $now, 'rpm_ciclo1', '62') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'rpm_ciclo2', '" . $rpm2 . "' , '" . $now . "', 'rpm_ciclo2', '63', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'rpm_ciclo2', $rpm2, $now, 'rpm_ciclo2', '63') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'rpm_ciclo3', '" . $rpm3 . "' , '" . $now . "', 'rpm_ciclo3', '64', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'rpm_ciclo3', $rpm3, $now, 'rpm_ciclo3', '64') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'rpm_ciclo4', '" . $rpm4 . "' , '" . $now . "', 'rpm_ciclo4', '65', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'rpm_ciclo4', $rpm4, $now, 'rpm_ciclo4', '65') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'rpm_ralenti', '" . request()->input('Rpm_ralenti') . "' , '" . $now . "', 'rpm_ralenti', '38', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'rpm_ralenti', request()->input('Rpm_ralenti'), $now, 'rpm_ralenti', '38') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'temp_inicial', '" . $temRand . "' , '" . $now . "', 'temp_inicial', '224', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'temp_inicial', $temRand, $now, 'temp_inicial', '224') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'temp_final', '" . $temFinal . "' , '" . $now . "', 'temp_final', '39', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'temp_final', $temFinal, $now, 'temp_final', '39') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'temp_ambiente', '" . $ranTemp . "' , '" . $now . "', 'temp_ambiente', '200', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'temp_ambiente', $ranTemp, $now, 'temp_ambiente', '200') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'humedad', '" . $ranHum . "' , '" . $now . "', 'humedad', '201', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'humedad', $ranHum, $now, 'humedad', '201') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'op_ciclo1', '" . request()->input('opa1') . "' , '" . $now . "', 'op_ciclo1', '34', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'op_ciclo1', request()->input('opa1'), $now, 'op_ciclo1', '34') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'opacidad_totalK', '" . $opafinalK . "' , '" . $now . "', 'opacidad_totalK', '505', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'opacidad_totalK', $opafinalK, $now, 'opacidad_totalK', '505') . "','" . $this->key . "'))");
        }
        //        DB::table('vehiculos')
        //                ->where('numero_placa', request()->input('placa'))
        //                ->update([
        //                    'diametro_escape' => request()->input('ltoe')]);
        DB::update("UPDATE vehiculos v set v.diametro_escape = '" . request()->input('ltoe') . "' where v.numero_placa = '" . request()->input('Vplaca') . "'  ");
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
        // DB::update("UPDATE pruebas p set p.estado = " . request()->input('selEstado') . ", p.idmaquina = " . request()->input('selMaquina') . ", p.idusuario = " . request()->input('selUsuario') . ", p.fechafinal = '" . $now . "' , p.enc = " . "AES_ENCRYPT('" . $this->updateEncr(request()->input('idprueba'), request()->input('selEstado'), request()->input('selMaquina'), request()->input('selUsuario'), $now) . "','" . $this->key . "')" . " where p.idprueba = " . request()->input('idprueba') . "  ");
        // if (sicov() == 'INDRA')
        //     $this->eventosindra(request()->input('placa'));
        // return back()->with("succses", "Datos Guardados correctamente");
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
        $dat['idtipo_prueba'] = "2";
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
