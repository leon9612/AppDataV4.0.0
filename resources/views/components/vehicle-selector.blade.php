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
</style>

<!-- Sección de placa destacada -->
<div class="row mb-4">
    <div class="col-md-12 text-center">
        <div class="placa-destacada-container">
            <div class="placa-destacada">
                <input type="text" name="Vplaca" class="form-control Vplaca placa-texto" id="floatingInput"
                    placeholder="" disabled style="text-transform: uppercase;">
            </div>

            <input type="hidden" name="idprueba" id="idprueba" class="form-control">
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
</div>

<!-- NUEVA SECCIÓN: Información del vehículo -->
<!-- NUEVA SECCIÓN: Información del vehículo -->
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

<!-- Sección de configuración de prueba -->
<div class="row mb-4">
    <div class="col-md-12">
        <h5 class="mb-3 border-bottom pb-2">Configuración de prueba</h5>
    </div>
    <div class="col-sm-12 col-md-4 col-lg-3 mb-2">
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-stopwatch"></i>
            </span>
            <input type="number" class="form-control" name="tiempoPrueba" id="tiempoPrueba" 
                   placeholder="Segundos" value="1800" min="1" max="10800" onchange="saveTiempoPrueba(this.value)">
            <span class="input-group-text">Segundos</span>
        </div>
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



    <!-- Nuevo item: Contador de tiempo de prueba -->
    

     
