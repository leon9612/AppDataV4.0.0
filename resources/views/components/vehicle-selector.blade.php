<style>
    .placa-destacada-container {
        display: inline-block;
        text-align: center;
    }

    .placa-destacada {
        background: linear-gradient(135deg, #ffcc00 0%, #ff9900 100%);
        border: 3px solid #003366;
        border-radius: 10px;
        padding: 10px 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        position: relative;
        display: inline-block;
        margin-bottom: 5px;
    }

    .placa-texto {
        background: transparent !important;
        border: none !important;
        font-family: 'Arial Black', 'Arial Bold', sans-serif;
        font-size: 1.8rem;
        font-weight: 900;
        color: #003366;
        letter-spacing: 3px;
        text-align: center;
        text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.5);
        width: 200px;
    }

    .placa-texto:disabled {
        background: transparent !important;
        color: #003366 !important;
        opacity: 1 !important;
    }

    /* Efecto de brillo */
    .placa-destacada::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 50%;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.4) 0%, rgba(255, 255, 255, 0) 100%);
        border-radius: 7px 7px 0 0;
        pointer-events: none;
    }

    /* Estilos para el tipo de operación - DESTACADO */
    .tipo-operacion {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 20px 20px 15px 20px;
        margin-bottom: 25px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        border: 2px solid #ffffff;
    }

    .tipo-operacion h5 {
        color: white;
        border-bottom-color: rgba(255, 255, 255, 0.3) !important;
        font-size: 1.3rem;
        font-weight: bold;
    }

    .tipo-operacion h5 i {
        margin-right: 8px;
    }

    .tipo-operacion .form-check {
        background: rgba(255, 255, 255, 0.2);
        padding: 12px 20px;
        border-radius: 50px;
        margin-right: 15px;
        transition: all 0.3s ease;
    }

    .tipo-operacion .form-check:hover {
        background: rgba(255, 255, 255, 0.35);
        transform: scale(1.02);
    }

    .tipo-operacion .form-check-input {
        width: 20px;
        height: 20px;
        margin-top: 2px;
        cursor: pointer;
    }

    .tipo-operacion .form-check-label {
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
        margin-left: 8px;
        cursor: pointer;
    }

    .tipo-operacion .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.3);
    }

    /* Badge de obligatorio */
    .obligatorio-badge {
        background-color: #ff4757;
        color: white;
        font-size: 0.7rem;
        padding: 3px 8px;
        border-radius: 20px;
        margin-left: 10px;
        vertical-align: middle;
    }
</style>

<!-- TIPO DE OPERACIÓN - Lo primero que debe seleccionar el cliente (DESTACADO) -->
<div class="row mb-4" style="background: #e8f0fe; border-left: 5px solid #0d6efd; border-radius: 8px; padding: 15px 0 10px 0; margin-top: 10px;">
    <div class="col-md-12">
        <h5 class="mb-3 border-bottom pb-2">
            <strong>⚠️ PASO OBLIGATORIO - Tipo de operación</strong>
        </h5>
    </div>
    <div class="col-sm-12">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="opcionPlaca" id="radioCorregirPlaca" value="corregir">
            <label class="form-check-label" for="radioCorregirPlaca">
                <strong>✏️ Corregir placa</strong>
            </label>
        </div>
        <div class="form-check form-check-inline ms-4">
            <input class="form-check-input" type="radio" name="opcionPlaca" id="radioRealizaPrueba" value="prueba" checked>
            <label class="form-check-label" for="radioRealizaPrueba">
                <strong>✅ Realizar prueba</strong>
            </label>
        </div>
        <small class="text-muted ms-3">
            <i class="bi bi-info-circle"></i> Seleccione una opción antes de elegir la placa
        </small>
    </div>
</div>

<!-- Sección de placa destacada -->
<div class="row mb-4">
    <div class="col-md-12 text-center">
        <div class="placa-destacada-container">
            <div class="placa-destacada">
                <input type="text" name="Vplaca" class="form-control Vplaca placa-texto" id="floatingInput"
                    placeholder="" disabled style="text-transform: uppercase;">
            </div>

            <input type="hidden" name="idprueba" id="idprueba" class="form-control">
            <input type="hidden" name="tipoejecucionprueba" id="tipoejecucionprueba" class="form-control">
            <input type="hidden" name="placa" id="placa" class="form-control">
            @if ($errors->has('idprueba'))
            <span class="error text-danger">{{ $errors->first('idprueba') }}</span>
            @endif
        </div>
    </div>
</div>

<!-- Sección de selección de vehículo -->
<div class="row mb-4">
    <div class="col-md-12">
        <h5 class="mb-3 border-bottom pb-2">Selección de vehículo</h5>
    </div>

    <div class="col-sm-12 col-md-6 col-lg-6 mb-2">
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-car-front"></i>
            </span>

            <select class="form-select selPlaca" name="selPlaca">
                <option selected>Seleccione una placa</option>
                @foreach ($placas as $placa)
                <option value="{{ $placa->idprueba . '-' . $placa->placa }}"
                    data-linea="{{ $placa->linea }}"
                    data-marca="{{ $placa->marca }}"
                    data-ano_modelo="{{ $placa->ano_modelo }}"
                    data-kilometraje="{{ $placa->kilometraje }}"
                    data-combustible="{{ $placa->combustible }}"
                    data-tiempos="{{ $placa->tiempos }}"
                    data-cilindraje="{{ $placa->cilindraje }}">
                    {{ $placa->placa }}
                </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-sm-12 col-md-3 col-lg-3 mb-2">
        <button style="width: 100%;" class="btn btn-outline-primary" id="btn-actuplacas" type="button"
            onclick="location.reload();">
            Actualizar placas
        </button>
    </div>

    <div class="col-sm-12 col-md-3 col-lg-3 mb-2">
        <button style="width: 100%;" class="btn btn-outline-success" id="btn-buscar-placa" type="button">
            Buscar datos
        </button>
    </div>

    @if(sicov() == 'INDRA' || sicov() == 'CI2')
    <div class="col-sm-12 col-md-3 col-lg-3 mb-2">
        <button style="width: 100%;" class="btn btn-outline-warning" id="btn-evento" type="button">
            Evento inicial
        </button>
    </div>
    @endif
    <div class="col-sm-12 col-md-4 col-lg-3 mb-2">
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-stopwatch"></i>
            </span>
            <input type="number" name="tiempoPrueba" id="tiempoPrueba"
                placeholder="Segundos" value="1800" min="1" max="10800" onchange="saveTiempoPrueba(this.value)">
            <span class="input-group-text">Segundos</span>
        </div>
    </div>
</div>

<!-- Información del vehículo -->
<div class="row mb-4">
    <div class="col-md-12">
        <h5 class="mb-3 border-bottom pb-2">Información del vehículo</h5>
    </div>

    <div class="col-sm-12 col-md-3 col-lg-3 mb-2">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="veh_marca" name="veh_marca"
                placeholder="Marca" value="" readonly disabled>
            <label for="veh_linea">Marca</label>
        </div>
    </div>

    <div class="col-sm-12 col-md-3 col-lg-3 mb-2">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="veh_linea" name="veh_linea"
                placeholder="Línea" value="" readonly disabled>
            <label for="veh_linea">Línea</label>
        </div>
    </div>

    <div class="col-sm-12 col-md-3 col-lg-3 mb-2">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="veh_anio" name="veh_anio"
                placeholder="Año Modelo" value="" readonly disabled>
            <label for="veh_anio">Año Modelo</label>
        </div>
    </div>

    <div class="col-sm-12 col-md-3 col-lg-3 mb-2">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="veh_kilometraje" name="veh_kilometraje"
                placeholder="Kilometraje" value="" readonly disabled>
            <label for="veh_kilometraje">Kilometraje</label>
        </div>
    </div>
    <div class="col-sm-12 col-md-3 col-lg-3 mb-2">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="veh_tiempos" name="veh_tiempos"
                placeholder="Tiempos" value="" readonly disabled>
            <label for="veh_tiempos">Tiempos</label>
        </div>
    </div>

    <div class="col-sm-12 col-md-3 col-lg-3 mb-2">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="veh_combustible" name="veh_combustible"
                placeholder="Tipo Combustible" value="" readonly disabled>
            <label for="veh_combustible">Tipo Combustible</label>
        </div>
    </div>

    <div class="col-sm-12 col-md-3 col-lg-3 mb-2">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="veh_cilindraje" name="veh_cilindraje"
                placeholder="Cilindraje" value="" readonly disabled>
            <label for="veh_cilindraje">Cilindraje</label>
        </div>
    </div>
</div>

<!-- Configuración de prueba -->
<div class="row mb-4">
    <div class="col-md-12">
        <h5 class="mb-3 border-bottom pb-2">Configuración de prueba</h5>
    </div>
    


    <div class="col-sm-12 col-md-3 col-lg-3 mb-2">
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-clipboard-check"></i>
            </span>
            <select class="form-select selEstado" id="inputGroupSelect01" name="selEstado">
                <option value="2">Aprobado</option>
                <option value="1">Rechazado</option>
            </select>
        </div>
    </div>

    <div class="col-sm-12 col-md-4 col-lg-4 mb-2">
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-person"></i>
            </span>
            <select class="form-select" id="inputGroupSelect01" name="selUsuario" id="selUsuario">
                @foreach ($usuarios as $us)
                <option value="{{ $us->IdUsuario }}">{{ $us->nombre }} </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-sm-12 col-md-5 col-lg-5 mb-2">
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-gear"></i>
            </span>
            <select class="form-select" name="selMaquina" id="selMaquina">
                @foreach ($maquinas as $ma)
                <option value="{{ $ma->idmaquina }}">{{ $ma->maquina }} </option>
                @endforeach
            </select>
        </div>
        @if ($errors->has('selMaquina'))
        <span class="error text-danger">{{ $errors->first('selMaquina') }}</span>
        @endif
    </div>
</div>

<script>
    // Función para guardar la selección en localStorage usando IDs
    function guardarSeleccionOperacion() {
        const radioCorregir = document.getElementById('radioCorregirPlaca');
        const radioPrueba = document.getElementById('radioRealizaPrueba');

        if (radioCorregir.checked) {

            radibuttoncorregir();
            cargarPlacasPorTipo(); // Cargar placas para corregir
        } else if (radioPrueba.checked) {
            radibuttonprueba();
            cargarPlacasPorTipo(); // Cargar placas para prueba
        }
    }

    // Función para cargar la selección guardada
    function cargarSeleccionOperacion() {
        const seleccionGuardada = localStorage.getItem('tipoOperacion');
        const radioCorregir = document.getElementById('radioCorregirPlaca');
        const radioPrueba = document.getElementById('radioRealizaPrueba');
        console.log('Selección guardada:', seleccionGuardada); // Verificar el valor guardado

        if (seleccionGuardada === 'corregir') {
            radioCorregir.checked = true;
            radioPrueba.checked = false;
            radibuttoncorregir();
            cargarPlacasPorTipo(); // Cargar placas para corregir
        } else if (seleccionGuardada === 'prueba') {
            radioCorregir.checked = false;
            radioPrueba.checked = true;
            radibuttonprueba();

            cargarPlacasPorTipo(); // Cargar placas para prueba
        } else {
            // Si no hay nada guardado, seleccionar "Realizar prueba" por defecto
            radioCorregir.checked = true;
            radibuttoncorregir();

            cargarPlacasPorTipo(); // Cargar placas para prueba
        }
    }

    // Eventos cuando cambie la selección
    document.addEventListener('DOMContentLoaded', function() {
        // Cargar la selección guardada
        cargarSeleccionOperacion();

        cargarPlacasPorTipo();



        // Guardar cuando cambie la selección
        const radioCorregir = document.getElementById('radioCorregirPlaca');
        const radioPrueba = document.getElementById('radioRealizaPrueba');

        radioCorregir.addEventListener('change', function() {
            if (this.checked) {
                guardarSeleccionOperacion();
            }
        });

        radioPrueba.addEventListener('change', function() {
            if (this.checked) {
                guardarSeleccionOperacion();
            }
        });

        cargarPlacasPorTipo();
    });

    function cargarPlacasPorTipo() {
        const radioCorregir = document.getElementById('radioCorregirPlaca');
        const tipoEjecucion = radioCorregir.checked ? 'corregir' : 'prueba';

        // Actualizar el hidden input
        document.getElementById('tipoejecucionprueba').value = tipoEjecucion;

        const urlActual = window.location.pathname;
        const ultimoSegmento = urlActual.split('/').pop(); // obtiene 'frm', 'al', 'fr', etc.

        let tipoprueba = '';
        let tipovehiculo = '';

        if (ultimoSegmento === 'frm') {
            tipoprueba = '7';
            tipovehiculo = '3';
        } else if (ultimoSegmento === 'gam') {
            tipoprueba = '3';
            tipovehiculo = '3';
        } else if (ultimoSegmento === 'frmotocarro') {
            tipoprueba = '7';
            tipovehiculo = '4';
        } else if (ultimoSegmento === 'visual') {
            tipoprueba = '8';
            tipovehiculo = '4';
        } else if (ultimoSegmento === 'al') {
            tipoprueba = '10';
            tipovehiculo = '1';
        } else if (ultimoSegmento === 'fr') {
            tipoprueba = '7';
            tipovehiculo = '1';
        } else if (ultimoSegmento === 'su') {
            tipoprueba = '9';
            tipovehiculo = '1';
        } else if (ultimoSegmento === 'ga') {
            tipoprueba = '3';
            tipovehiculo = '1';
        } else if (ultimoSegmento === 'op') {
            tipoprueba = '2';
            tipovehiculo = '1';
        } else if (ultimoSegmento === 'lu') {
            tipoprueba = '1';
            tipovehiculo = '1';
        } else if (ultimoSegmento === 'lum') {
            tipoprueba = '1';
            tipovehiculo = '3';
        } else if (ultimoSegmento === 'so') {
            tipoprueba = '4';
            tipovehiculo = '4';
        } else if (ultimoSegmento === 'tax') {
            tipoprueba = '6';
            tipovehiculo = '1';
        }

        // Hacer petición AJAX con jQuery
        $.ajax({
            url: './getPlacasByTipo',
            type: 'POST',
            // dataType: 'json',
            data: {
                tipoejecucion: tipoEjecucion,
                tipoprueba: tipoprueba,
                tipovehiculo: tipovehiculo,
                _token: $("input[name='_token']").val()
            },

            success: function(placas) {
                // console.log('Placas recibidas:', placas);   
                const selectPlaca = document.querySelector('.selPlaca');

                // Limpiar el select
                selectPlaca.innerHTML = '<option selected>Seleccione una placa</option>';

                if (placas.length === 0) {
                    selectPlaca.innerHTML = '<option selected>No hay placas disponibles</option>';
                    return;
                }

                // Agregar las nuevas placas
                placas.forEach(placa => {
                    const option = document.createElement('option');
                    option.value = `${placa.idprueba}-${placa.placa}`;
                    option.setAttribute('data-linea', placa.linea);
                    option.setAttribute('data-marca', placa.marca);
                    option.setAttribute('data-ano_modelo', placa.ano_modelo);
                    option.setAttribute('data-kilometraje', placa.kilometraje);
                    option.setAttribute('data-combustible', placa.combustible);
                    option.setAttribute('data-tiempos', placa.tiempos);
                    option.setAttribute('data-cilindraje', placa.cilindraje);
                    option.textContent = placa.placa;
                    selectPlaca.appendChild(option);
                });

                // Limpiar los campos del vehículo
                limpiarCamposVehiculo();
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    // Función para limpiar campos
    function limpiarCamposVehiculo() {
        document.getElementById('veh_marca').value = '';
        document.getElementById('veh_linea').value = '';
        document.getElementById('veh_anio').value = '';
        document.getElementById('veh_kilometraje').value = '';
        document.getElementById('veh_tiempos').value = '';
        document.getElementById('veh_combustible').value = '';
        document.getElementById('veh_cilindraje').value = '';
        document.getElementById('floatingInput').value = '';
        document.getElementById('idprueba').value = '';
        document.getElementById('placa').value = '';
    }

    function radibuttoncorregir() {
        console.log('Opción seleccionada: Corregir placa');
        localStorage.setItem('tipoOperacion', 'corregir');
        document.getElementById('tipoejecucionprueba').value = 'corregir';
        document.getElementById('btn-guardar').textContent = 'Corregir placa';
        document.getElementById('btn-guardar').disabled = false;
        document.getElementById('btn-evento').style.display = 'none';
    }

    function radibuttonprueba() {
        localStorage.setItem('tipoOperacion', 'prueba');
        document.getElementById('tipoejecucionprueba').value = 'prueba';
        document.getElementById('btn-guardar').textContent = 'Guardar prueba';
        document.getElementById('btn-guardar').disabled = true;
        document.getElementById('btn-evento').style.display = '';
    }
</script>
