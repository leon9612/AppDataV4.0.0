<?php

namespace App\Http\Controllers;

use App\Models\Malineacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Libraries\Encrypt;
use App\Traits\EventosTrait;

class Cfrenometro extends Controller
{
    use EventosTrait;


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
                    $objeto->conf_idtipo_prueba == 7 && $objeto->activo == 1 &&
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
            //     $data['placas'] = DB::select("select
            // p.idprueba,
            // v.numero_placa as 'placa'
            // from vehiculos v, hojatrabajo h, pruebas p
            // where
            // v.idvehiculo = h.idvehiculo and h.idhojapruebas = p.idhojapruebas and
            //  p.idtipo_prueba=7 and p.estado = 0 and
            // date_format(p.fechainicial, '%y-%m-%d') = date_format(curdate(), '%y-%m-%d') and (v.tipo_vehiculo = 1 or v.tipo_vehiculo=2) order by p.fechainicial asc ");
            $request = new Request();
            $request->merge([
                'tipoprueba' => '7',
                'tipovehiculo' => '1',
                'tipoejecucionprueba' => 'corregir'
            ]);

            $data['placas'] = $this->getPlacas($request);
            $data['usuarios'] = DB::select("select u.IdUsuario, concat(u.nombres,' ',u.apellidos ) as 'nombre' from usuarios u where u.idperfil = 2 and u.estado = 1");
            // $data['maquinas'] = DB::select("select  m.idmaquina, concat(m.nombre, ' ', m.marca, ' ', m.serie) as 'maquina' from maquina m where m.estado = 1 and m.idtipo_prueba = 7 and (m.idbanco = 1 or m.idbanco = 2) ");
            //$data['placas'] = Malineacion::paginate(5);
            return view('TipoPrueba.frenos', $data);
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
            'fuerza1d' => 'required',
            'fuerza1i' => 'required',
            'fuerza2d' => 'required',
            'fuerza2i' => 'required',
            'pesaje1d' => 'required',
            'pesaje1i' => 'required',
            'pesaje2i' => 'required',
            'pesaje2d' => 'required',
            'fuerzaauxd' => 'required',
            'fuerzaauxi' => 'required',
            'idprueba' => 'required',
            'selEstado' => 'required',
            'selUsuario' => 'required',
            'selMaquina' => 'required',
        ]);
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
        $idmaquina = explode('|', request()->input('selMaquina'))[0];
        $idmaquina = intval($idmaquina);
        //        $now = date('Y-m-d H:i:s'); //Fomat Date and time
        //insert version software
        if (versionAplicaction() == 1) {
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'Version software', '7.0' , '" . $now . "', 'EasyTecmmas', '100', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'Version software', '7.0', $now, 'EasyTecmmas', '100') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '1', " . request()->input('pesaje1d') . " , '" . $now . "', 'Pesaje eje 1 derecho', '146', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '1', request()->input('pesaje1d'), $now, 'Pesaje eje 1 derecho', '146') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '2', " . request()->input('pesaje2d') . " , '" . $now . "', 'Pesaje eje 2 derecho', '146', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '2', request()->input('pesaje2d'), $now, 'Pesaje eje 2 derecho', '146') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '1', " . request()->input('pesaje1i') . " , '" . $now . "', 'Pesaje eje 1 Izquierdo', '147', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '1', request()->input('pesaje1i'), $now, 'Pesaje eje 1 Izquierdo', '147') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '2', " . request()->input('pesaje2i') . " , '" . $now . "', 'Pesaje eje 2 Izquierdo', '147', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '2', request()->input('pesaje2i'), $now, 'Pesaje eje 2 Izquierdo', '147') . "','" . $this->key . "'))");
            //eje 1 fuerza derecha
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '1', " . request()->input('fuerza1d') . " , '" . $now . "', 'Frenos eje 1 derecho', '148', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '1', request()->input('fuerza1d'), $now, 'Frenos eje 1 derecho', '148') . "','" . $this->key . "'))");


            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '2', " . request()->input('fuerza2d') . " , '" . $now . "', 'Frenos eje 2 derecho', '148', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '2', request()->input('fuerza2d'), $now, 'Frenos eje 2 derecho', '148') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '1', " . request()->input('fuerza1i') . " , '" . $now . "', 'Frenos eje 1 Izquierdo', '149', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '1', request()->input('fuerza1i'), $now, 'Frenos eje 1 Izquierdo', '149') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '2', " . request()->input('fuerza2i') . " , '" . $now . "', 'Frenos eje 2 Izquierdo', '149', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '2', request()->input('fuerza2i'), $now, 'Frenos eje 2 Izquierdo', '149') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '7', " . request()->input('fuerzaauxd') . " , '" . $now . "', 'FrenoAuxs eje 2 derecho', '148', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '7', request()->input('fuerzaauxd'), $now, 'FrenoAuxs eje 2 derecho', '148') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '7', " . request()->input('fuerzaauxi') . " , '" . $now . "', 'FrenoAuxs eje 2 Izquierdo', '149', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '7', request()->input('fuerzaauxi'), $now, 'FrenoAuxs eje 2 Izquierdo', '149') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '1', " . request()->input('deseje1_') . " , '" . $now . "', 'Desequilibrio eje 1', '150', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '1', request()->input('deseje1_'), $now, 'Desequilibrio eje 1', '150') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '2', " . request()->input('deseje2_') . " , '" . $now . "', 'Desequilibrio eje 2', '150', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '2', request()->input('deseje2_'), $now, 'Desequilibrio eje 2', '150') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'eficacia_total', " . request()->input('efitotal_') . " , '" . $now . "', 'eficacia_maxima', '151', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'eficacia_total', request()->input('efitotal_'), $now, 'eficacia_maxima', '151') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'eficacia_auxiliar', " . request()->input('efiaux_') . " , '" . $now . "', 'eficacia_auxiliar', '152', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'eficacia_auxiliar', request()->input('efiaux_'), $now, 'eficacia_auxiliar', '152') . "','" . $this->key . "'))");

            if (request()->input('pesaje3i') !== "" && request()->input('pesaje3d') !== null) {
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '3', " . request()->input('pesaje3i') . " , '" . $now . "', 'Pesaje eje 3 Izquierdo', '147', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '3', request()->input('pesaje3i'), $now, 'Pesaje eje 3 Izquierdo', '147') . "','" . $this->key . "'))");
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '3', " . request()->input('pesaje3d') . " , '" . $now . "', 'Pesaje eje 3 derecho', '146', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '3', request()->input('pesaje3d'), $now, 'Pesaje eje 3 derecho', '146') . "','" . $this->key . "'))");
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '3', " . request()->input('fuerza3i') . " , '" . $now . "', 'Frenos eje 3 Izquierdo', '149', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '3', request()->input('fuerza3i'), $now, 'Frenos eje 3 Izquierdo', '149') . "','" . $this->key . "'))");
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '3', " . request()->input('fuerza3d') . " , '" . $now . "', 'Frenos eje 3 derecho', '148', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '3', request()->input('fuerza3d'), $now, 'Frenos eje 3 derecho', '148') . "','" . $this->key . "'))");
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '3', " . request()->input('deseje3_') . " , '" . $now . "', 'Desequilibrio eje 3', '150', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '3', request()->input('deseje3_'), $now, 'Desequilibrio eje 3', '150') . "','" . $this->key . "'))");
            }

            if (request()->input('pesaje4i') !== "" && request()->input('pesaje4d') !== null) {
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '4', " . request()->input('pesaje4i') . " , '" . $now . "', 'Pesaje eje 4 Izquierdo', '147', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '4', request()->input('pesaje4i'), $now, 'Pesaje eje 4 Izquierdo', '147') . "','" . $this->key . "'))");
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '4', " . request()->input('pesaje4d') . " , '" . $now . "', 'Pesaje eje 4 derecho', '146', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '4', request()->input('pesaje4d'), $now, 'Pesaje eje 4 derecho', '146') . "','" . $this->key . "'))");
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '4', " . request()->input('fuerza4i') . " , '" . $now . "', 'Frenos eje 4 Izquierdo', '149', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '4', request()->input('fuerza4i'), $now, 'Frenos eje 4 Izquierdo', '149') . "','" . $this->key . "'))");
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '4', " . request()->input('fuerza4d') . " , '" . $now . "', 'Frenos eje 4 derecho', '148', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '4', request()->input('fuerza4d'), $now, 'Frenos eje 4 derecho', '148') . "','" . $this->key . "'))");
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '4', " . request()->input('deseje4_') . " , '" . $now . "', 'Desequilibrio eje 4', '150', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '4', request()->input('deseje4_'), $now, 'Desequilibrio eje 4', '150') . "','" . $this->key . "'))");
            }

            if (request()->input('pesaje5i') !== "" && request()->input('pesaje5d') !== null) {
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '5', " . request()->input('pesaje5i') . " , '" . $now . "', 'Pesaje eje 5 Izquierdo', '147', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '5', request()->input('pesaje5i'), $now, 'Pesaje eje 5 Izquierdo', '147') . "','" . $this->key . "'))");
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '5', " . request()->input('pesaje5d') . " , '" . $now . "', 'Pesaje eje 5 derecho', '146', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '5', request()->input('pesaje5d'), $now, 'Pesaje eje 5 derecho', '146') . "','" . $this->key . "'))");
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '5', " . request()->input('fuerza5i') . " , '" . $now . "', 'Frenos eje 5 Izquierdo', '149', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '5', request()->input('fuerza5i'), $now, 'Frenos eje 5 Izquierdo', '149') . "','" . $this->key . "'))");
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '5', " . request()->input('fuerza5d') . " , '" . $now . "', 'Frenos eje 5 derecho', '148', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '5', request()->input('fuerza5d'), $now, 'Frenos eje 5 derecho', '148') . "','" . $this->key . "'))");
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '5', " . request()->input('deseje5_') . " , '" . $now . "', 'Desequilibrio eje 5', '150', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '5', request()->input('deseje5_'), $now, 'Desequilibrio eje 5', '150') . "','" . $this->key . "'))");
            }
        } else {
            //fuerza eje 1 izquierda
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '1', " . request()->input('fuerza1i') . " , '" . $now . "', 'Frenos eje 1 izquierdo', '149', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '1', request()->input('fuerza1i'), $now, 'Frenos eje 1 izquierdo', '149') . "','" . $this->key . "'))");
            $this->createLog(request()->input('placa'), $idmaquina, request()->input('fuerza1i'), 1, 'izquierda');
            //fuerza eje 2 izquierda
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '2', " . request()->input('fuerza2i') . " , '" . $now . "', 'Frenos eje 2 izquierdo', '149', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '2', request()->input('fuerza2i'), $now, 'Frenos eje 2 izquierdo', '149') . "','" . $this->key . "'))");
            $this->createLog(request()->input('placa'), $idmaquina, request()->input('fuerza2i'), 2, 'izquierda');
            // fuerza auxiliar izquierda
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '7', " . request()->input('fuerzaauxi') . " , '" . $now . "', 'FrenoAuxs eje 7 izquierdo', '149', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '7', request()->input('fuerzaauxi'), $now, 'FrenoAuxs eje 2 izquierdo', '149') . "','" . $this->key . "'))");
            $this->createLog(request()->input('placa'), $idmaquina, request()->input('fuerzaauxi'), 2, 'izquierda auxiliar');

            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '1', " . request()->input('deseje1_') . " , '" . $now . "', 'Desequilibrio eje 1', '150', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '1', request()->input('deseje1_'), $now, 'Desequilibrio eje 1', '150') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '2', " . request()->input('deseje2_') . " , '" . $now . "', 'Desequilibrio eje 2', '150', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '2', request()->input('deseje2_'), $now, 'Desequilibrio eje 2', '150') . "','" . $this->key . "'))");
            // fuerza auxiliar derecha
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '7', " . request()->input('fuerzaauxd') . " , '" . $now . "', 'FrenoAuxs eje 2 derecho', '148', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '7', request()->input('fuerzaauxd'), $now, 'FrenoAuxs eje 2 derecho', '148') . "','" . $this->key . "'))");
            $this->createLog(request()->input('placa'), $idmaquina, request()->input('fuerzaauxd'), 2, 'derecha auxiliar');

            //fuerza 1 derecha
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '1', " . request()->input('fuerza1d') . " , '" . $now . "', 'Frenos eje 1 derecho', '148', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '1', request()->input('fuerza1d'), $now, 'Frenos eje 1 derecho', '148') . "','" . $this->key . "'))");
            $this->createLog(request()->input('placa'), $idmaquina, request()->input('fuerza1d'), 1, 'derecha');

            //fuerza 2 derecha
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '2', " . request()->input('fuerza2d') . " , '" . $now . "', 'Frenos eje 2 derecho', '148', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '2', request()->input('fuerza2d'), $now, 'Frenos eje 2 derecho', '148') . "','" . $this->key . "'))");
            $this->createLog(request()->input('placa'), $idmaquina, request()->input('fuerza2d'), 2, 'derecha');




            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '1', " . request()->input('pesaje1i') . " , '" . $now . "', 'Pesaje eje 1 izquierdo', '147', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '1', request()->input('pesaje1i'), $now, 'Pesaje eje 1 izquierdo', '147') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '2', " . request()->input('pesaje2i') . " , '" . $now . "', 'Pesaje eje 2 izquierdo', '147', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '2', request()->input('pesaje2i'), $now, 'Pesaje eje 2 izquierdo', '147') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '1', " . request()->input('pesaje1d') . " , '" . $now . "', 'Pesaje eje 1 derecho', '146', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '1', request()->input('pesaje1d'), $now, 'Pesaje eje 1 derecho', '146') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '2', " . request()->input('pesaje2d') . " , '" . $now . "', 'Pesaje eje 2 derecho', '146', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '2', request()->input('pesaje2d'), $now, 'Pesaje eje 2 derecho', '146') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'eficacia_total', " . request()->input('efitotal_') . " , '" . $now . "', 'eficacia_maxima', '151', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'eficacia_total', request()->input('efitotal_'), $now, 'eficacia_maxima', '151') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'eficacia_auxiliar', " . request()->input('efiaux_') . " , '" . $now . "', 'eficacia_auxiliar', '152', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'eficacia_auxiliar', request()->input('efiaux_'), $now, 'eficacia_auxiliar', '152') . "','" . $this->key . "'))");

            if (request()->input('pesaje3i') !== "" && request()->input('pesaje3d') !== null) {

                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '3', " . request()->input('pesaje3i') . " , '" . $now . "', 'Pesaje eje 3 izquierdo', '147', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '3', request()->input('pesaje3i'), $now, 'Pesaje eje 3 izquierdo', '147') . "','" . $this->key . "'))");
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '3', " . request()->input('pesaje3d') . " , '" . $now . "', 'Pesaje eje 3 derecho', '146', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '3', request()->input('pesaje3d'), $now, 'Pesaje eje 3 derecho', '146') . "','" . $this->key . "'))");
                //fuerza eje3 izquierda
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '3', " . request()->input('fuerza3i') . " , '" . $now . "', 'Frenos eje 3 izquierdo', '149', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '3', request()->input('fuerza3i'), $now, 'Frenos eje 3 izquierdo', '149') . "','" . $this->key . "'))");
                $this->createLog(request()->input('placa'), $idmaquina, request()->input('fuerza3i'), 3, 'izquierda');
                //fuerza eje3 derecha
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '3', " . request()->input('fuerza3d') . " , '" . $now . "', 'Frenos eje 3 derecho', '148', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '3', request()->input('fuerza3d'), $now, 'Frenos eje 3 derecho', '148') . "','" . $this->key . "'))");
                $this->createLog(request()->input('placa'), $idmaquina, request()->input('fuerza3d'), 3, 'derecha');

                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '3', " . request()->input('deseje3_') . " , '" . $now . "', 'Desequilibrio eje 3', '150', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '3', request()->input('deseje3_'), $now, 'Desequilibrio eje 3', '150') . "','" . $this->key . "'))");
            }

            if (request()->input('pesaje4i') !== "" && request()->input('pesaje4d') !== null) {
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '4', " . request()->input('pesaje4i') . " , '" . $now . "', 'Pesaje eje 4 izquierdo', '147', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '4', request()->input('pesaje4i'), $now, 'Pesaje eje 4 izquierdo', '147') . "','" . $this->key . "'))");
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '4', " . request()->input('pesaje4d') . " , '" . $now . "', 'Pesaje eje 4 derecho', '146', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '4', request()->input('pesaje4d'), $now, 'Pesaje eje 4 derecho', '146') . "','" . $this->key . "'))");
                // fuerza eje4 izquierda
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '4', " . request()->input('fuerza4i') . " , '" . $now . "', 'Frenos eje 4 izquierdo', '149', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '4', request()->input('fuerza4i'), $now, 'Frenos eje 4 izquierdo', '149') . "','" . $this->key . "'))");
                $this->createLog(request()->input('placa'), $idmaquina, request()->input('fuerza4i'), 4, 'izquierda');
                //fuerza eje4 derecha
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '4', " . request()->input('fuerza4d') . " , '" . $now . "', 'Frenos eje 4 derecho', '148', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '4', request()->input('fuerza4d'), $now, 'Frenos eje 4 derecho', '148') . "','" . $this->key . "'))");
                $this->createLog(request()->input('placa'), $idmaquina, request()->input('fuerza4d'), 4, 'derecha');
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '4', " . request()->input('deseje4_') . " , '" . $now . "', 'Desequilibrio eje 4', '150', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '4', request()->input('deseje4_'), $now, 'Desequilibrio eje 4', '150') . "','" . $this->key . "'))");
            }

            if (request()->input('pesaje5i') !== "" && request()->input('pesaje5d') !== null) {
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '5', " . request()->input('pesaje5i') . " , '" . $now . "', 'Pesaje eje 5 izquierdo', '147', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '5', request()->input('pesaje5i'), $now, 'Pesaje eje 5 izquierdo', '147') . "','" . $this->key . "'))");
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '5', " . request()->input('pesaje5d') . " , '" . $now . "', 'Pesaje eje 5 derecho', '146', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '5', request()->input('pesaje5d'), $now, 'Pesaje eje 5 derecho', '146') . "','" . $this->key . "'))");
                // fuerza eje5 izquierda
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '5', " . request()->input('fuerza5i') . " , '" . $now . "', 'Frenos eje 5 izquierdo', '149', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '5', request()->input('fuerza5i'), $now, 'Frenos eje 5 izquierdo', '149') . "','" . $this->key . "'))");
                $this->createLog(request()->input('placa'), $idmaquina, request()->input('fuerza5i'), 5, 'izquierda');
                //fuerza eje5 derecha
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '5', " . request()->input('fuerza5d') . " , '" . $now . "', 'Frenos eje 5 derecho', '148', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '5', request()->input('fuerza5d'), $now, 'Frenos eje 5 derecho', '148') . "','" . $this->key . "'))");
                $this->createLog(request()->input('placa'), $idmaquina, request()->input('fuerza5d'), 5, 'derecha');
                DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , '5', " . request()->input('deseje5_') . " , '" . $now . "', 'Desequilibrio eje 5', '150', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), '5', request()->input('deseje5_'), $now, 'Desequilibrio eje 5', '150') . "','" . $this->key . "'))");
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

    function generarLogConPico($valorMaximo, $cantidad = 24)
    {
        $log = [];

        // Determinar la posición del pico (entre 50% y 80% del array)
        $posicionPico = floor($cantidad * (0.5 + (mt_rand() / mt_getrandmax()) * 0.3));
        $posicionPico = max(3, min($cantidad - 3, $posicionPico));

        // Valor inicial más realista (entre 5% y 15% del máximo)
        $valorActual = $valorMaximo * (0.05 + (mt_rand() / mt_getrandmax()) * 0.1);
        $valorActual = round($valorActual, 1);
        $log[] = $valorActual;

        // Fase de subida (hasta el pico)
        for ($i = 1; $i <= $posicionPico; $i++) {
            // Progresión más suave al inicio, más acelerada al final
            $progresion = $i / $posicionPico;
            $factorAceleracion = $progresion * $progresion;

            // Incremento base que aumenta con la progresión
            $incrementoBase = ($valorMaximo - $valorActual) / ($posicionPico - $i + 1);
            $incremento = $incrementoBase * (0.8 + $factorAceleracion * 0.6);

            // Variación aleatoria
            $variacion = (mt_rand() / mt_getrandmax()) * ($incremento * 0.3);
            $nuevoValor = $valorActual + $incremento + $variacion;

            // Suavizar la aproximación al pico
            if ($progresion > 0.8) {
                $distanciaAlMaximo = $valorMaximo - $nuevoValor;
                $nuevoValor += $distanciaAlMaximo * (mt_rand() / mt_getrandmax()) * 0.5;
            }

            // Asegurar que no sobrepase el máximo durante la subida
            $nuevoValor = min($valorMaximo * 0.99, max(0, $nuevoValor));

            $nuevoValor = round($nuevoValor, 1);
            $log[] = $nuevoValor;
            $valorActual = $nuevoValor;
        }

        // FORZAR que el pico sea exactamente el valor máximo
        $log[$posicionPico] = round($valorMaximo, 1);

        // Fase de bajada (más suave y gradual)
        $elementosRestantes = $cantidad - $posicionPico - 1;
        $valorActual = $log[$posicionPico];
        $valorFinal = $valorMaximo * (0.1 + (mt_rand() / mt_getrandmax()) * 0.1);

        for ($i = $posicionPico + 1; $i < $cantidad; $i++) {
            $progresionBajada = ($i - $posicionPico) / $elementosRestantes;

            // Bajada más suave al principio, más pronunciada al final
            $factorDesaceleracion = $progresionBajada * $progresionBajada;

            // Decremento que se ajusta progresivamente
            $decrementoBase = ($valorActual - $valorFinal) / ($cantidad - $i);
            $decremento = $decrementoBase * (0.7 + $factorDesaceleracion * 0.6);

            // Variación en la bajada
            $variacion = (mt_rand() / mt_getrandmax()) * ($decremento * 0.4) - ($decremento * 0.2);
            $nuevoValor = $valorActual - $decremento + $variacion;

            $nuevoValor = round(max($valorFinal * 0.8, $nuevoValor), 1);
            $log[] = $nuevoValor;
            $valorActual = $nuevoValor;
        }

        return $log;
    }

    function createLog($placa, $idmaquina, $valor, $eje, $lado)
    {
        date_default_timezone_set('America/bogota');
        $now = date("Y-m-d H:i:s");
        $fuerza4dK = $this->rdnr(floatval($valor) / 9.81);
        $fuerza4d = $this->generarLogConPico($fuerza4dK);
        $fuerza4d_json = json_encode($fuerza4d);
        DB::insert("INSERT INTO control_frenos VALUES (NULL,'" . $placa . "' , " . $idmaquina . " , '" . $now . "',  " . $eje . " , '" . $lado . "' , " . $fuerza4dK . "  , '" . $fuerza4d_json . "')");
    }

    private function rdnr($valor)
    {
        $dato = '';
        if ($valor !== '') {
            if (floatval($valor) === 0.00 || floatval($valor) === 0.0 || floatval($valor) === 0) {
                $dato = "0.00";
            } else {
                if (intval($valor) < 10) {
                    $valorNegativo = false;
                    $dato = abs(round($valor, 2));
                    if ($valor < 0) {
                        $valorNegativo = true;
                        if (intval($valor) > -10) {
                            if (substr($dato, 2) == "") {
                                $dato = $dato . ".00";
                            } elseif (substr($dato, 3) == "" || substr($dato, 3) == '0') {
                                $dato = $dato . "0";
                            }
                        } elseif (intval($valor) <= -10 && intval($valor) > -100) {
                            $dato = abs(round($valor, 1));
                            if (substr($dato, 2) == "") {
                                $dato = $dato . ".0";
                            }
                        } else {
                            $dato = abs(round($valor));
                        }
                    } else {
                        if (substr($dato, 1) == "") {
                            $dato = $dato . ".00";
                        } elseif (substr($dato, 3) == "" || substr($dato, 3) == '0') {
                            $dato = $dato . "0";
                        }
                    }
                    if ($valorNegativo) {
                        $dato = "-" . $dato;
                    }
                } elseif (intval($valor) >= 10 && intval($valor) < 100) {
                    $dato = round($valor, 1);
                    if (substr($dato, 2) == "") {
                        $dato = $dato . ".0";
                    }
                } else {
                    $dato = round($valor);
                }
            }
        }
        return $dato;
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
    //         $serial = '51553358';
    //     else
    //         $serial = $r[0]->serial;
    //     $cadenasicov = "862|" . $date . "|Frenos|" . $placa . "|" . $serial . "|2|" . $datos[0]->valor;
    //     DB::insert("INSERT INTO eventosindra VALUES (NULL,'" . $placa . "-Frenos','" . $cadenasicov . "','" . $date . "','e',0,'Operación pendiente')");
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
        $dat['idtipo_prueba'] = "7";
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
