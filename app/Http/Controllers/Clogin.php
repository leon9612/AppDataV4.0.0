<?php

namespace App\Http\Controllers;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: *');

use App\Models\Malineacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class Clogin extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // $response = Http::withoutVerifying()
        //     ->get('https://cdatecmmas.tecmmas.com/cda/index.php/Cservicio/getLineas');

        // if ($response->successful()) {
        //     $data = $response->json();
        //     var_dump($data);
        //     // Ruta absoluta dentro de storage
        //     $path = storage_path('app/system/lineas.json');

        //     // Crear directorio si no existe
        //     $dir = dirname($path);
        //     if (!file_exists($dir)) {
        //         mkdir($dir, 0777, true);
        //     }

        //     // Guardar el archivo
        //     file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
        // }

        return view('Vlogin');
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
        // $Mal = new Malineacion();
        // $data = request()->except('_token');
        // //validacion de campos
        // $validated = $request->validate([
        //     'email' => 'required',
        //     'password' => 'required',
        // ]);




        // $email = "Bsrojas6@gmail.com";
        // $password = "1";

        // if (request()->input('email') == $email && request()->input('password') == $password) {
        //     session::put('sesionUser', "Bienvenid@ ingeniera@gmail.com");
        //     return redirect()->intended('/cpr')->with('status', "Bienvenido");
        // } else {
        //     return back()->with("error", "Usuario y contraseña incorrectos");
        // }

        //return request()->input('email');

        // return back()->with("succses", "Datos Guardados correctamente");

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

    public function cerrarSesion()
    {
        Session::forget('sesionUser');
        return redirect()->intended('/');
    }

    public function getMac()
    {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $macAddr = false;
        $arp = `arp -a $ipAddress`;
        $lines = explode("\n", $arp);
        foreach ($lines as $line) {
            $cols = preg_split('/\s+/', trim($line));
            if ($cols[0] == $ipAddress) {
                $macAddr = $cols[1];
            }
        }

        if ($macAddr == false || $macAddr == null || $macAddr == "") {

            $macAddr = exec('getmac | findstr "Device"');
            $macAddr = strtok($macAddr, ' ');
        }
        echo json_encode(utf8_encode($macAddr));
    }

    public function getSession()
    {
        Session::put('sesionUser', "Bienvenid@ que tenga un buen dia.");
        return response()->json([
            'session' => Session::get('sesionUser'),
        ]);
    }
}
