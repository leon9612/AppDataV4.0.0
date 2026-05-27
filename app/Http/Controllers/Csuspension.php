<?php

namespace App\Http\Controllers;

use App\Models\Malineacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Libraries\Encrypt;
use App\Traits\EventosTrait;

class Csuspension extends Controller
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
                if ($objeto->conf_idtipo_prueba == 9 && $objeto->activo == 1) {
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
            // p.idtipo_prueba=9 and p.estado = 0 and
            // date_format(p.fechainicial, '%y-%m-%d') = date_format(curdate(), '%y-%m-%d') and (v.tipo_vehiculo = 1 or v.tipo_vehiculo=2) order by p.fechainicial asc ");
            $request = new Request();
            $request->merge([
                'tipoprueba' => '9',
                'tipovehiculo' => '1',
                'tipoejecucionprueba' => 'corregir'
            ]);

            $data['placas'] = $this->getPlacas($request);
            $data['usuarios'] = DB::select("select u.IdUsuario, concat(u.nombres,' ',u.apellidos ) as 'nombre' from usuarios u where u.idperfil = 2 and u.estado = 1");
            // $data['maquinas'] = DB::select("select  m.idmaquina, concat(m.nombre, ' ', m.marca, ' ', m.serie) as 'maquina' from maquina m where m.estado = 1 and m.idtipo_prueba = 9");
            //$data['placas'] = Malineacion::paginate(5);
            return view('TipoPrueba.suspension', $data);
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
            'eje1d' => 'required',
            'eje1i' => 'required',
            'eje2d' => 'required',
            'eje2i' => 'required',
            'idprueba' => 'required',
            'selEstado' => 'required',
            'selUsuario' => 'required',
            'selMaquina' => 'required',
        ]);
        $idmaquina = explode('|', request()->input('selMaquina'))[0];
        $idmaquina = intval($idmaquina);
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
        //insert version software
        if (versionAplicaction() == 1) {
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'Version software', '1.0' , '" . $now . "', 'EasyTecmmas', '100', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'Version software', '1.0', $now, 'EasyTecmmas', '100') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'delantera_derecha', '" . request()->input('eje1d') . "' , '" . $now . "', 'Suspension delantera derecha', '142', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'delantera_derecha', request()->input('eje1d'), $now, 'Suspension delantera derecha', '142') . "','" . $this->key . "'))");

            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'delantera_izquierda', '" . request()->input('eje1i') . "' , '" . $now . "', 'Suspension delantera izquierda', '143', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'delantera_izquierda', request()->input('eje1i'), $now, 'Suspension delantera izquierda', '143') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'trasera_derecha', '" . request()->input('eje2d') . "' , '" . $now . "', 'Suspension trasera derecha', '144', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'trasera_derecha', request()->input('eje2d'), $now, 'Suspension trasera derecha', '144') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'trasera_izquierda', '" . request()->input('eje2i') . "' , '" . $now . "', 'Suspension trasera izquierda', '145', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'trasera_izquierda', request()->input('eje2i'), $now, 'Suspension trasera izquierda', '145') . "','" . $this->key . "'))");
        } else {
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'delantera_izquierda', '" . request()->input('eje1i') . "' , '" . $now . "', 'Suspension delantera izquierda', '143', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'delantera_izquierda', request()->input('eje1i'), $now, 'Suspension delantera izquierda', '143') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'trasera_izquierda', '" . request()->input('eje2i') . "' , '" . $now . "', 'Suspension trasera izquierda', '145', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'trasera_izquierda', request()->input('eje2i'), $now, 'Suspension trasera izquierda', '145') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'delantera_derecha', '" . request()->input('eje1d') . "' , '" . $now . "', 'Suspension delantera derecha', '142', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'delantera_derecha', request()->input('eje1d'), $now, 'Suspension delantera derecha', '142') . "','" . $this->key . "'))");
            DB::insert("INSERT INTO resultados VALUES (NULL, " . request()->input('idprueba') . " , 'trasera_derecha', '" . request()->input('eje2d') . "' , '" . $now . "', 'Suspension trasera derecha', '144', AES_ENCRYPT('" . $this->encr(request()->input('idprueba'), 'trasera_derecha', request()->input('eje2d'), $now, 'Suspension trasera derecha', '144') . "','" . $this->key . "'))");
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
        // DB::update("UPDATE pruebas p set p.estado = " . request()->input('selEstado') . ", p.idmaquina = " . request()->input('selMaquina') . ", p.idusuario = " . request()->input('selUsuario') . ", p.fechafinal = '" . $now . "' , p.enc = " . "AES_ENCRYPT('" . $this->updateEncr(request()->input('idprueba'), request()->input('selEstado'), request()->input('selMaquina'), request()->input('selUsuario'), $now) . "','" . $this->key . "')" . " where p.idprueba = " . request()->input('idprueba') . "  ");
        // if (sicov() == 'INDRA')
        //     $this->eventosindra(request()->input('placa'));
        // return back()->with("succses", "Datos Guardados correctamente");
    }




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
        $dat['idtipo_prueba'] = "9";
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
