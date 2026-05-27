@include('layout.heder')
<main id="main">
    <section id="visor" class="contact">
        <div class="container">

            <div class="section-title">
                <h2>Gases Motos</h2>
            </div>

            <div class="row" data-aos="fade-in">

                <div class="col-lg-12 mt-12 mt-lg-12 d-flex align-items-stretch">
                    <form action="{{ url('/gam') }}" method="POST" class="form-control">
                        @csrf
                        @if ($message = Session::get('success'))
                        <div class="alert alert-success" role="alert">
                            <h4 class="alert-heading">Exitoso</h4>
                            <p>{{ $message }}</p>
                        </div>
                        @endif
                        @if ($message = Session::get('error'))
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">Error</h4>
                            <p>{{ $message }}</p>
                        </div>
                        @endif


                        <x-vehicle-selector :placas="$placas" :usuarios="$usuarios" :maquinas="$maquinas" />
                        <div class="row">
                            <div class="col-sm-12 col-md-3 col-lg-3" style="align-content: center">
                                <div class="input-group mb-3" style="align-content: center">
                                    <label class="input-group-text" for="inputGroupSelect01">Scooter</label>
                                    <select class="form-select" id="inputGroupSelect01" name="selScooter"
                                        id="selScooter">
                                        <option value="0">NO</option>
                                        <option value="1">SI</option>
                                    </select>
                                    @if ($errors->has('selScooter'))
                                    <span class="error text-danger">{{ $errors->first('selScooter') }}</span>
                                    @endif

                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3 col-lg-3" style="align-content: center">
                                <div class="input-group mb-3" style="align-content: center">
                                    <label class="input-group-text" for="inputGroupSelect01">Catalizador</label>
                                    <select class="form-select" id="inputGroupSelect01" name="selCatalizador"
                                        id="selCatalizador">
                                        <option value="0">NO</option>
                                        <option value="1">SI</option>
                                    </select>
                                    @if ($errors->has('selCatalizador'))
                                    <span class="error text-danger">{{ $errors->first('selCatalizador') }}</span>
                                    @endif

                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3 col-lg-3" style="align-content: center">
                                <div class="input-group mb-3" style="align-content: center">
                                    <label class="input-group-text" for="inputGroupSelect01">Motocarro</label>
                                    <select class="form-select" name="selMotocarro" id="selMotocarro">
                                        <option value="0" selected>NO</option>
                                        <option value="1">SI</option>
                                    </select>


                                </div>
                            </div>

                        </div>
                        <div class="container" style="justify-content: center; display: flex ">
                            <div class="row">
                                <label
                                    style="color: rgb(0, 4, 255); font-size: 18px; text-align: center; width: 100%; margin-top: 15px; background-color: lightgoldenrodyellow">DATOS
                                    TH</label>
                                <div style="justify-content: center; display: flex; margin-top: 15px">

                                    <br>
                                    <div class="col-sm-12 col-md-4 col-lg-4" style="align-content: center">
                                        <div class="input-group mb-3" style="align-content: center">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" name="tempAmbiente"
                                                    id="tempAmbiente" placeholder="1"
                                                    value="{{ old('tempAmbiente', $tempAmbiente ?? '') }}">
                                                <label for="floatingInput">Temperatura</label>
                                                @if ($errors->has('tempAmbiente'))
                                                <span
                                                    class="error text-danger">{{ $errors->first('tempAmbiente') }}</span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-4 col-lg-4" style="align-content: center">
                                        <div class="input-group mb-3" style="align-content: center">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" step="0.01" name="humedad"
                                                    id="humedad" placeholder="1"
                                                    value="{{ old('humedad', $humedad ?? '') }}">
                                                <label for="floatingInput">Humedad</label>
                                                @if ($errors->has('humedad'))
                                                <span class="error text-danger">{{ $errors->first('humedad') }}</span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>




                                </div>
                            </div>
                        </div>
                        <div class="container" style="justify-content: center; display: flex ">
                            <div class="row">
                                <label
                                    style="color: rgb(0, 4, 255); font-size: 18px; text-align: center; width: 100%; margin-top: 15px; background-color: lightgoldenrodyellow">DATOS
                                    RALENTI</label>
                                <div style="justify-content: center; display: flex; margin-top: 15px">

                                    <br>
                                    <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                        <div class="input-group mb-3" style="align-content: center">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" name="hc_ralenti"
                                                    id="hc_ralenti" placeholder="1" value="{{ old('hc_ralenti') }}">
                                                <label for="floatingInput">HC RALENTI</label>
                                                @if ($errors->has('hc_ralenti'))
                                                <span
                                                    class="error text-danger">{{ $errors->first('hc_ralenti') }}</span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                        <div class="input-group mb-3" style="align-content: center">
                                            <div class="form-floating mb-3">
                                                <input type="number" class="form-control" step="0.01" name="co_ralenti"
                                                    id="co_ralenti" placeholder="1" value="{{ old('co_ralenti') }}">
                                                <label for="floatingInput">CO RALENTI</label>
                                                @if ($errors->has('co_ralenti'))
                                                <span
                                                    class="error text-danger">{{ $errors->first('co_ralenti') }}</span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                        <div class="input-group mb-3" style="align-content: center">
                                            <div class="form-floating mb-3">
                                                <input type="number" class="form-control" step="0.01" name="co2_ralenti"
                                                    id="co2_ralenti" placeholder="1" value="{{ old('co2_ralenti') }}">
                                                <label for="floatingInput">CO2 RALENTI</label>
                                                @if ($errors->has('co2_ralenti'))
                                                <span
                                                    class="error text-danger">{{ $errors->first('co2_ralenti') }}</span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                        <div class="input-group mb-3" style="align-content: center">
                                            <div class="form-floating mb-3">
                                                <input type="number" class="form-control" step="0.01" name="o2_ralenti"
                                                    id="o2_ralenti" placeholder="1" value="{{ old('o2_ralenti') }}">
                                                <label for="floatingInput">O2 RALENTI</label>
                                                @if ($errors->has('o2_ralenti'))
                                                <span
                                                    class="error text-danger">{{ $errors->first('o2_ralenti') }}</span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-lg-3" style="align-content: center">
                                        <div class="input-group mb-3" style="align-content: center">
                                            <div class="form-floating mb-3">
                                                <input type="number" class="form-control" step="0.01" name="rpm_ralenti"
                                                    id="rpm_ralenti" placeholder="1" value="{{ old('rpm_ralenti') }}">
                                                <label for="floatingInput">RPM RALENTI</label>
                                                @if ($errors->has('rpm_ralenti'))
                                                <span
                                                    class="error text-danger">{{ $errors->first('rpm_ralenti') }}</span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center align-items-center"
                            style="margin-top: 20px; margin-bottom: 20px">
                            <div class="col-sm-12 col-md-2 col-lg-2">
                                <div class="input-group mb-3">
                                    <div class="form-floating mb-3" style="margin-top: 29px">
                                        <input type="number" class="form-control" step="0.01" name="tempMotor"
                                            id="tempMotor" placeholder="1" value="{{ $tempMotor }}">
                                        <label for="floatingInput">TEMPERATURA MOTOR</label>
                                        @if ($errors->has('tempMotor'))
                                        <span class="error text-danger">{{ $errors->first('tempMotor') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3 col-lg-3 d-flex align-items-end">
                                <input type="hidden" name="tipoprueba" id="tipoprueba" value="3">
                                <input type="hidden" name="tipopruebaCi2" id="tipopruebaCi2" value="1">
                                <input type="hidden" name="prueba" id="prueba" value="Gases">
                                <button style="height: 55px; width: 150px" id="btn-guardar"
                                    class="btn btn-outline-success" type="submit">Guardar</button>
                            </div>
                        </div>


                </div>
                </form>
            </div>

        </div>
    </section>
    <!-- ======= Contact Section ======= -->
</main>
@include('layout.footer')
<script type="text/javascript">
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    $(document).ready(function() {
        if (localStorage.getItem('motocarro') == '1') {
            $('#selMotocarro').val(localStorage.getItem('motocarro'));
            getMaquina();
        }
        // document.getElementById("btn-guardar").disabled = true;
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Cargar el tiempo guardado en el input
        const tiempoInput = document.getElementById('tiempoPrueba');
        if (tiempoInput) {
            const tiempoGuardado = getTiempoPrueba();
            tiempoInput.value = tiempoGuardado;
            // console.log(`📌 Vista: ${document.querySelector('.section-title h2')?.textContent}, Tiempo cargado: ${tiempoGuardado} minutos`);
        }
    });

    $("#selMotocarro").change(function(e) {
        e.preventDefault();
        let motocarro = $('#selMotocarro option:selected').attr('value');
        localStorage.setItem('motocarro', motocarro);

        getMaquina();

    });

    $(".selPlaca").change(function(e) {
        e.preventDefault();
        var placa = $('.selPlaca option:selected').attr('value');
        var placa2 = placa.split("-");
        $(".Vplaca").val(placa2[1]);
        $("#placa").val(placa2[1]);
        $("#idprueba").val(placa2[0]);
        $("#btn-buscar-placa").click();

    });




   

    $("#btn-buscar-placa").click(function(e) {
        e.preventDefault();
        let veh_tiempos = parseInt(document.getElementById("veh_tiempos").value);
        let veh_anio = parseInt(document.getElementById("veh_anio").value);

        if ($(".Vplaca").val() == "" || $(".Vplaca").val() == null) {
            Swal.fire({
                icon: "info",
                title: 'Buscar placa',
                allowOutsideClick: false,
                html: '<div style= "font-size: 18px">Seleccione una placa primero<div>',
                showConfirmButton: true,
            });
        } else {
            $.ajax({
                url: 'buscarvehiculo/',
                type: 'post',
                dataType: 'json',
                data: {
                    placa: $(".Vplaca").val(),
                    idtipo_prueba: 3,
                    _token: $("input[name='_token']").val()
                },
                success: function(data, textStatus, jqXHR) {

                    if (data.length > 0) {
                        $.each(data, function(i, res) {
                            if (res.estado == 1) {
                                Toast.fire({
                                    icon: "info",
                                    title: "La prueba que se trajo esta en estado <span style='color: #dc3545; font-weight: bold;'>RECHAZADA</span>, por favor verifique bien los datos antes de enviarla nuevamente.",
                                    timeout: 100000
                                });
                            }
                            if (res.estado == 9) {
                                Toast.fire({
                                    icon: "info",
                                    title: "La prueba que se trajo esta en estado <span style='color: #dc3545; font-weight: bold;'>REASIGNADA</span>, por favor verifique bien los datos antes de enviarla nuevamente.",
                                    timeout: 100000
                                });
                            }
                            if (res.observacion == 'hc_ralenti') {

                                $("#hc_ralenti").val(res.valor);
                                if (veh_tiempos == 2 && veh_anio >= 2010) {
                                    validarRango(res.valor, 'gasese2tmotosmayor2010', 'hc_ralenti');
                                } else if (veh_tiempos == 2 && veh_anio <= 2009) {
                                    validarRango(res.valor, 'gasese2tmotosmenor2010', 'hc_ralenti');
                                } else {
                                    validarRango(res.valor, 'gasese4tmotos', 'hc_ralenti');
                                }
                            }
                            if (res.observacion == 'rpm_ralenti') {
                                $("#rpm_ralenti").val(res.valor);
                            }
                            if (res.observacion == 'co_ralenti') {
                                $("#co_ralenti").val(res.valor);
                                if (veh_tiempos == 2 && veh_anio >= 2010) {
                                    validarRango(res.valor, 'gasese2tmotosmayor2010', 'co_ralenti');
                                } else if (veh_tiempos == 2 && veh_anio <= 2009) {
                                    validarRango(res.valor, 'gasese2tmotosmenor2010', 'co_ralenti');
                                } else {
                                    validarRango(res.valor, 'gasese4tmotos', 'co_ralenti');
                                }
                            }
                            if (res.observacion == 'co2_ralenti') {
                                $("#co2_ralenti").val(res.valor);
                            }
                            if (res.observacion == 'o2_ralenti') {
                                $("#o2_ralenti").val(res.valor);
                                if (veh_tiempos == 2 && veh_anio >= 2010) {
                                    validarRango(res.valor, 'gasese2tmotosmayor2010', 'o2_ralenti');
                                } else if (veh_tiempos == 2 && veh_anio <= 2009) {
                                    validarRango(res.valor, 'gasese2tmotosmenor2010', 'o2_ralenti');
                                } else {
                                    validarRango(res.valor, 'gasese4tmotos', 'o2_ralenti');
                                }
                            }


                        });
                    } else {
                        Toast.fire({
                            icon: "error",
                            title: "No se encontraron registros."
                        });
                    }



                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('error')
                    console.log(jqXHR.responseText)
                    console.log(textStatus)
                    console.log(errorThrown)
                }
            });
        }
    })

    $(document).on('keyup', '#hc_ralenti, #co_ralenti, #co2_ralenti, #o2_ralenti', function() {
        let veh_anio = parseInt(document.getElementById("veh_anio").value);
        let veh_tiempos = parseInt(document.getElementById("veh_tiempos").value);
        const valor = $(this).val();
        const idCampo = $(this).attr('id');
        if (veh_tiempos == 2 && veh_anio >= 2010) {
            validarRango(valor, 'gasese2tmotosmayor2010', idCampo);
        } else if (veh_tiempos == 2 && veh_anio <= 2009) {
            validarRango(valor, 'gasese2tmotosmenor2010', idCampo);
        } else {
            validarRango(valor, 'gasese4tmotos', idCampo);
        }
        // validarRango(valor, 'alineacion', idCampo);
    });

    var getMaquina = function() {
        $.ajax({
            url: 'getMaquina/',
            type: 'post',
            dataType: 'json',
            data: {
                idtipo_prueba: 3,
                motocarro: $('#selMotocarro').val(),
                _token: $("input[name='_token']").val()
            },
            success: function(data, textStatus, jqXHR) {
                if (data.length > 0) {
                    $('#selMaquina').empty();
                    // $('.selMaquina').append('<option value="">Seleccione una maquina</option>');
                    $.each(data, function(i, res) {
                        $('#selMaquina').append('<option value="' + res.idmaquina + '">' + res
                            .maquina +
                            '</option>');
                    });
                } else {
                    Toast.fire({
                        icon: "error",
                        title: "No se encontraron maquinas."
                    });
                }



            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('error')
                console.log(jqXHR.responseText)
                console.log(textStatus)
                console.log(errorThrown)
            }
        });
    }
</script>