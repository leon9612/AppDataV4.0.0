<?php

namespace App\Http\Controllers;

use App\Models\Malineacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cfotos extends Controller
{



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function getVehiculo(Request $request)
    // {
    //     $r = DB::select("SELECT r.* FROM vehiculos v, hojatrabajo h, pruebas p, resultados r
    //                         WHERE v.idvehiculo = h.idvehiculo AND h.idhojapruebas = p.idhojapruebas AND p.idprueba = r.idprueba AND 
    //                         p.idtipo_prueba = " . $request->input('idtipo_prueba') . " AND v.numero_placa = '" . $request->input('placa') . "' AND p.estado = 2  ORDER BY h.idhojapruebas DESC ");

    //     return response($r);
    // }

    public function index()
    {


        if (session('sesionUser') !== "" && session('sesionUser') !== false && session('sesionUser') !== null) {
            return view('TipoPrueba.fotos');
        } else {
            return redirect()->intended('/');
        }
    }

    public function consultarImagen(Request $request)
    {
        // $r = DB::select("SELECT f.* FROM fotos_pruebas f
        //                     WHERE f.idprueba = " . $request->input('idprueba') . " AND f.tipo_foto = '" . $request->input('tipo_foto') . "' ");
        $r = DB::select("SELECT h.idhojapruebas, i.idimagen, i.idprueba, i.imagen
                            FROM hojatrabajo h, pruebas p, imagenes_bd.imagenes  i
                            WHERE 
                            h.idhojapruebas = p.idhojapruebas AND p.idtipo_prueba = 5 AND 
                            p.idprueba = i.idprueba AND p.estado = 2 AND
                            h.idhojapruebas = '" . $request->input('idprueba') . "' ");

        return json_encode ($r);
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
