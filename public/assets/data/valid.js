
const rangosConfig = {
    alineacion: {
        campos: {
            'eje1': { minimo: -10, maximo: 10, unidad: 'mm' },
            'eje2': { minimo: -10, maximo: 10, unidad: 'mm' },
            'eje3': { minimo: -10, maximo: 10, unidad: 'mm' },
            'eje4': { minimo: -10, maximo: 10, unidad: 'mm' },
            'eje5': { minimo: -10, maximo: 10, unidad: 'mm' }
        }
    },
    frenos: {
        campos: {
            'deseje1': { minimo: 0, maximo: 30, unidad: '%' },
            'deseje2': { minimo: 0, maximo: 30, unidad: '%' },
            'deseje3': { minimo: 0, maximo: 30, unidad: '%' },
            'deseje4': { minimo: 0, maximo: 30, unidad: '%' },
            'deseje5': { minimo: 0, maximo: 30, unidad: '%' },
            'efitotal': { minimo: 50, maximo: 98, unidad: '%' },
            'efiaux': { minimo: 18, maximo: 98, unidad: '%' }

        }
    },
    frenosmotos: {
        campos: {
            'efitotal': { minimo: 30, maximo: 98, unidad: '%' },
        }
    },
    luces: {
        campos: {
            'baja_izquierda': { minimo: 2.5, maximo: 80, unidad: '%' },
            'baja_derecha': { minimo: 2.5, maximo: 80, unidad: '%' },
            'baja_derecha_1': { minimo: 2.5, maximo: 80, unidad: '%' },
            'baja_izquierda_1': { minimo: 2.5, maximo: 80, unidad: '%' },
            'incli_izquierda': { minimo: 0.5, maximo: 3.5, unidad: '%' },
            'incli_derecha': { minimo: 0.5, maximo: 3.5, unidad: '%' },
            'incli_derecha_1': { minimo: 0.5, maximo: 3.5, unidad: '%' },
            'incli_izquierda_1': { minimo: 0.5, maximo: 3.5, unidad: '%' }
        }
    },
    suspension: {
        campos: {
            'eje1d': { minimo: 40, maximo: 80, unidad: '%' },
            'eje1i': { minimo: 40, maximo: 80, unidad: '%' },
            'eje2d': { minimo: 40, maximo: 80, unidad: '%' },
            'eje2i': { minimo: 40, maximo: 80, unidad: '%' }
        }
    },
    sonometro: {
        campos: {
            'valson': { minimo: 0, maximo: 80, unidad: '%' }
        }
    },
    taximetro: {
        campos: {
            'err_distancia': { minimo: -2, maximo: 2, unidad: '%' },
            'err_tiempo': { minimo: -2, maximo: 2, unidad: '%' }
        }
    },
    gaseselivianomenor1984: {
        campos: {
            'hc_ralenti': { minimo: 0, maximo: 650, unidad: '%' },
            'hc_crucero': { minimo: 0, maximo: 650, unidad: '%' },
            'co_ralenti': { minimo: 0, maximo: 4.0, unidad: '%' },
            'co_crucero': { minimo: 0, maximo: 4.0, unidad: '%' },
            'co2_ralenti': { minimo: 7, maximo: 30, unidad: '%' },
            'co2_crucero': { minimo: 7, maximo: 30, unidad: '%' },
            'o2_ralenti': { minimo: 0, maximo: 5.0, unidad: '%' },
            'o2_crucero': { minimo: 0, maximo: 5.0, unidad: '%' },
        }
    },
    gaseselivianoentre1984y1997: {
        campos: {
            'hc_ralenti': { minimo: 0, maximo: 400, unidad: '%' },
            'hc_crucero': { minimo: 0, maximo: 400, unidad: '%' },
            'co_ralenti': { minimo: 0, maximo: 3.0, unidad: '%' },
            'co_crucero': { minimo: 0, maximo: 3.0, unidad: '%' },
            'co2_ralenti': { minimo: 7, maximo: 30, unidad: '%' },
            'co2_crucero': { minimo: 7, maximo: 30, unidad: '%' },
            'o2_ralenti': { minimo: 0, maximo: 5.0, unidad: '%' },
            'o2_crucero': { minimo: 0, maximo: 5.0, unidad: '%' },
        }
    },
    gaseselivianoentre1997y2009: {
        campos: {
            'hc_ralenti': { minimo: 0, maximo: 200, unidad: '%' },
            'hc_crucero': { minimo: 0, maximo: 200, unidad: '%' },
            'co_ralenti': { minimo: 0, maximo: 1.0, unidad: '%' },
            'co_crucero': { minimo: 0, maximo: 1.0, unidad: '%' },
            'co2_ralenti': { minimo: 7, maximo: 30, unidad: '%' },
            'co2_crucero': { minimo: 7, maximo: 30, unidad: '%' },
            'o2_ralenti': { minimo: 0, maximo: 5.0, unidad: '%' },
            'o2_crucero': { minimo: 0, maximo: 5.0, unidad: '%' },
        }
    },
    gaseseliviano2010: {
        campos: {
            'hc_ralenti': { minimo: 0, maximo: 160, unidad: '%' },
            'hc_crucero': { minimo: 0, maximo: 160, unidad: '%' },
            'co_ralenti': { minimo: 0, maximo: 0.8, unidad: '%' },
            'co_crucero': { minimo: 0, maximo: 0.8, unidad: '%' },
            'co2_ralenti': { minimo: 7, maximo: 30, unidad: '%' },
            'co2_crucero': { minimo: 7, maximo: 30, unidad: '%' },
            'o2_ralenti': { minimo: 0, maximo: 5.0, unidad: '%' },
            'o2_crucero': { minimo: 0, maximo: 5.0, unidad: '%' },
        }
    },
    gasese2tmotosmayor2010: {
        campos: {
            'hc_ralenti': { minimo: 0, maximo: 1600, unidad: '%' },
            'co_ralenti': { minimo: 0, maximo: 3.5, unidad: '%' },
            // 'co2_ralenti': { minimo: 7, maximo: 30, unidad: '%' },
            'o2_ralenti': { minimo: 0, maximo: 11, unidad: '%' },
        }
    },
    gasese2tmotosmenor2010: {
        campos: {
            'hc_ralenti': { minimo: 0, maximo: 8000, unidad: '%' },
            'co_ralenti': { minimo: 0, maximo: 3.5, unidad: '%' },
            // 'co2_ralenti': { minimo: 7, maximo: 30, unidad: '%' },
            'o2_ralenti': { minimo: 0, maximo: 11, unidad: '%' },
        }
    },
    gasese4tmotos: {
        campos: {
            'hc_ralenti': { minimo: 0, maximo: 1300, unidad: '%' },
            'co_ralenti': { minimo: 0, maximo: 3.5, unidad: '%' },
            // 'co2_ralenti': { minimo: 7, maximo: 30, unidad: '%' },
            'o2_ralenti': { minimo: 0, maximo: 6, unidad: '%' },
        }
    },
    opacidadmenor5000mayor2016: {
        campos: {
            'opa1k': { minimo: 0, maximo: 2.5, unidad: '%' },
            'opa2k': { minimo: 0, maximo: 2.5, unidad: '%' },
            'opa3k': { minimo: 0, maximo: 2.5, unidad: '%' },
            'opa4k': { minimo: 0, maximo: 2.5, unidad: '%' },
        }
    },
    opacidadmenor5000entre2001y2015: {
        campos: {
            'opa1k': { minimo: 0, maximo: 3.5, unidad: '%' },
            'opa2k': { minimo: 0, maximo: 3.5, unidad: '%' },
            'opa3k': { minimo: 0, maximo: 3.5, unidad: '%' },
            'opa4k': { minimo: 0, maximo: 3.5, unidad: '%' },
        }
    },
     opacidadmenor5000: {
        campos: {
            'opa1k': { minimo: 0, maximo: 4.5, unidad: '%' },
            'opa2k': { minimo: 0, maximo: 4.5, unidad: '%' },
            'opa3k': { minimo: 0, maximo: 4.5, unidad: '%' },
            'opa4k': { minimo: 0, maximo: 4.5, unidad: '%' },
        }
    },
    opacidadmayor5000mayor2016: {
        campos: {
            'opa1k': { minimo: 0, maximo: 2.0, unidad: '%' },
            'opa2k': { minimo: 0, maximo: 2.0, unidad: '%' },
            'opa3k': { minimo: 0, maximo: 2.0, unidad: '%' },
            'opa4k': { minimo: 0, maximo: 2.0, unidad: '%' },
        }
    },
    opacidadmayor5000entre2001y2015: {
        campos: {
            'opa1k': { minimo: 0, maximo: 3.0, unidad: '%' },
            'opa2k': { minimo: 0, maximo: 3.0, unidad: '%' },
            'opa3k': { minimo: 0, maximo: 3.0, unidad: '%' },
            'opa4k': { minimo: 0, maximo: 3.0, unidad: '%' },
        }
    },
     opacidadmayor5000: {
        campos: {
            'opa1k': { minimo: 0, maximo: 4.0, unidad: '%' },
            'opa2k': { minimo: 0, maximo: 4.0, unidad: '%' },
            'opa3k': { minimo: 0, maximo: 4.0, unidad: '%' },
            'opa4k': { minimo: 0, maximo: 4.0, unidad: '%' },
        }
    },

};

// Función principal para validar rangos
function validarRango(valor, tipoPrueba, campoId) {

    const configuracion = rangosConfig[tipoPrueba];

    if (!configuracion || !configuracion.campos[campoId]) {
        console.warn(`No hay configuración para ${tipoPrueba} - ${campoId}`);
        return true;
    }

    const rango = configuracion.campos[campoId];
    // console.log("rango:", rango);
    valor = parseFloat(valor);

    if (isNaN(valor)) {
        $(`#${campoId}`).removeClass('fuera-rango');
        $(`#${campoId}`).removeClass('dentro-rango');
        return false;
    }

    if (valor < rango.minimo || valor > rango.maximo) {
        $(`#${campoId}`).addClass('fuera-rango');
        $(`#${campoId}`).removeClass('dentro-rango');
        $(`#${campoId}`).attr('title', `Valor fuera de rango (${rango.minimo} - ${rango.maximo} ${rango.unidad})`);

        return false;
    } else {

        $(`#${campoId}`).removeClass('fuera-rango');
        $(`#${campoId}`).addClass('dentro-rango');
        $(`#${campoId}`).removeAttr('title');
        return true;
    }
}


$(".selPlaca").change(function (e) {
    e.preventDefault();
    var placa = $('.selPlaca option:selected').attr('value');
    var placa2 = placa.split("-");

    // Tu código existente
    $(".Vplaca").val(placa2[1]);
    $("#placa").val(placa2[1]);
    $("#idprueba").val(placa2[0]);

    // NUEVO: Obtener los datos adicionales del vehículo desde los atributos data
    var selectedOption = $('.selPlaca option:selected');
    var linea = selectedOption.data('linea');
    var marca = selectedOption.data('marca');
    var ano_modelo = selectedOption.data('ano_modelo');
    var kilometraje = selectedOption.data('kilometraje');
    var idcolor = selectedOption.data('idcolor');
    var combustible = selectedOption.data('combustible');
    var cilindraje = selectedOption.data('cilindraje');
    var tiempos = selectedOption.data('tiempos');

    // Asignar los valores a los campos de información del vehículo
    // $("#veh_placa").val(placa2[1]);
    $("#veh_linea").val(linea || '');
    $("#veh_marca").val(marca || '');
    $("#veh_anio").val(ano_modelo || '');
    $("#veh_kilometraje").val(kilometraje || '');
    $("#veh_color").val(idcolor || '');
    $("#veh_combustible").val(combustible || '');
    $("#veh_cilindraje").val(cilindraje || '');
    $("#veh_tiempos").val(tiempos || '');

    // Tu código existente
    $("#btn-buscar-placa").click();
});


// Exportar funciones para uso global
window.validarRango = validarRango;
// window.validarAlineacionCompleta = validarAlineacionCompleta;
// window.validarFrenosCompleta = validarFrenosCompleta;
window.rangosConfig = rangosConfig;
