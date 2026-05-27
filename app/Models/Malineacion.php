<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Malineacion extends Model
{
    protected $table = "resultados";
    public $timestamps = false;
    use HasFactory;


    public static function getBitacoraGases($idprueba)
    {
        return \Illuminate\Support\Facades\DB::select("
        SELECT 
            IFNULL((SELECT c.idprueba FROM control_prueba_gases c WHERE c.idprueba = p.idprueba LIMIT 1),'0') AS 'control',
            h.idhojapruebas, p.idprueba, p.fechainicial, v.tipo_vehiculo, v.tiempos, v.ano_modelo,
            IFNULL((SELECT r.valor FROM resultados r WHERE r.idprueba = p.idprueba AND r.observacion = 'rpm_ralenti' LIMIT 1),'0') AS 'rpm_ralenti',
            IFNULL((SELECT r.valor FROM resultados r WHERE r.idprueba = p.idprueba AND r.observacion = 'hc_ralenti' LIMIT 1),'0') AS 'hc_ralenti',
            IFNULL((SELECT r.valor FROM resultados r WHERE r.idprueba = p.idprueba AND r.observacion = 'co_ralenti' LIMIT 1),'0') AS 'co_ralenti', 
            IFNULL((SELECT r.valor FROM resultados r WHERE r.idprueba = p.idprueba AND r.observacion = 'co2_ralenti' LIMIT 1),'0') AS 'co2_ralenti', 
            IFNULL((SELECT r.valor FROM resultados r WHERE r.idprueba = p.idprueba AND r.observacion = 'o2_ralenti' LIMIT 1),'0') AS 'o2_ralenti',
            IFNULL((SELECT r.valor FROM resultados r WHERE r.idprueba = p.idprueba AND r.observacion = 'rpm_crucero' LIMIT 1),'0') AS 'rpm_crucero',
            IFNULL((SELECT r.valor FROM resultados r WHERE r.idprueba = p.idprueba AND r.observacion = 'hc_crucero' LIMIT 1),'0') AS 'hc_crucero',
            IFNULL((SELECT r.valor FROM resultados r WHERE r.idprueba = p.idprueba AND r.observacion = 'co_crucero' LIMIT 1),'0') AS 'co_crucero', 
            IFNULL((SELECT r.valor FROM resultados r WHERE r.idprueba = p.idprueba AND r.observacion = 'co2_crucero' LIMIT 1),'0') AS 'co2_crucero', 
            IFNULL((SELECT r.valor FROM resultados r WHERE r.idprueba = p.idprueba AND r.observacion = 'o2_crucero' LIMIT 1),'0') AS 'o2_crucero',
            IFNULL((SELECT r.valor FROM resultados r WHERE r.idprueba = p.idprueba AND r.observacion = 'promhcra_ant' LIMIT 1),'0') AS 'promhcra_ant',
            IFNULL((SELECT r.valor FROM resultados r WHERE r.idprueba = p.idprueba AND r.observacion = 'promcora_ant' LIMIT 1),'0') AS 'promcora_ant' 
        FROM vehiculos v, hojatrabajo h, pruebas p
        WHERE 
            v.idvehiculo = h.idvehiculo AND 
            h.idhojapruebas = p.idhojapruebas AND  
            p.idtipo_prueba = 3 AND (p.estado = 2 OR p.estado = 1 OR p.estado= 3) AND p.idprueba = ?", [$idprueba]);
    }

    public static function logGasesInsert($datos)
    {
        \Illuminate\Support\Facades\DB::table('control_prueba_gases')->insert($datos);
    }
}
