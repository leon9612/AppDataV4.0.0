@include('layout.heder')

<main id="main">
    <section id="visor" class="contact">
        <div class="container">
            <div class="section-title">
                <h2>Luces Motos</h2>
            </div>

            <div class="row" data-aos="fade-in">

                <div class="col-lg-12 mt-12 mt-lg-12 d-flex align-items-stretch">
                    <form action="{{ url('/lum') }}" method="POST" class="form-control">
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
                        <div style="margin-top: 15px">
                            <x-vehicle-selector :placas="$placas" :usuarios="$usuarios" :maquinas="$maquinas" />
                            <div class="row">
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

                            <div class="container" style=" margin-top: 2%; justify-content: center; display: flex ">
                                <div class="row" style="width: 100%;">
                                    <label
                                        style="color: rgb(0, 4, 255); font-size: 18px; text-align: center; width: 100%; background-color: lightgoldenrodyellow; padding: 10px; margin-bottom: 20px;">
                                        DATO LUCES MOTOS
                                    </label>

                                    <div style="justify-content: center; display: flex; margin-top: 15px; width: 100%;">
                                        <!-- COLUMNA IZQUIERDA: BAJA (INTENSIDAD) -->
                                        <div class="col-sm-12 col-md-6"
                                            style="border-right: 1px solid #ddd; padding-right: 25px;">
                                            <!-- Título de la columna BAJA -->
                                            <div style="text-align: center; margin-bottom: 20px;">
                                                <label
                                                    style="color: rgb(0, 4, 255); font-size: 16px; font-weight: bold; background-color: #e6f7ff; padding: 5px 15px; border-radius: 5px;">
                                                    INTENSIDAD
                                                </label>
                                            </div>

                                            <!-- BAJA 1 -->
                                            <div style="margin-bottom: 15px;">
                                                <div class="input-group" style="align-content: center">
                                                    <div class="form-floating" style="width: 100%;">
                                                        <input type="text" class="form-control" name="baja_derecha"
                                                            id="baja_derecha" placeholder="1"
                                                            value="{{ old('baja_derecha') }}">
                                                        <label for="baja_derecha">BAJA 1</label>
                                                        @if ($errors->has('baja_derecha'))
                                                        <span
                                                            class="error text-danger">{{ $errors->first('baja_derecha') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- BAJA 2 -->
                                            <div style="margin-bottom: 15px;">
                                                <div class="input-group" style="align-content: center">
                                                    <div class="form-floating" style="width: 100%;">
                                                        <input type="text" class="form-control" name="baja_derecha1"
                                                            id="baja_derecha1" placeholder="1"
                                                            value="{{ old('baja_derecha1') }}">
                                                        <label for="baja_derecha1">BAJA 2</label>
                                                        @if ($errors->has('baja_derecha1'))
                                                        <span
                                                            class="error text-danger">{{ $errors->first('baja_derecha1') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- BAJA 3 (NUEVO) -->
                                            <div style="margin-bottom: 15px;">
                                                <div class="input-group" style="align-content: center">
                                                    <div class="form-floating" style="width: 100%;">
                                                        <input type="text" class="form-control" name="baja_derecha2"
                                                            id="baja_derecha2" placeholder="1"
                                                            value="{{ old('baja_derecha2') }}">
                                                        <label for="baja_derecha2">BAJA 3</label>
                                                        @if ($errors->has('baja_derecha2'))
                                                        <span
                                                            class="error text-danger">{{ $errors->first('baja_derecha2') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- COLUMNA DERECHA: INCLI (INCLINACIÓN) -->
                                        <div class="col-sm-12 col-md-6" style="padding-left: 25px;">
                                            <!-- Título de la columna INCLI -->
                                            <div style="text-align: center; margin-bottom: 20px;">
                                                <label
                                                    style="color: rgb(0, 4, 255); font-size: 16px; font-weight: bold; background-color: #e6f7ff; padding: 5px 15px; border-radius: 5px;">
                                                    INCLINACIÓN
                                                </label>
                                            </div>

                                            <!-- INCLI 1 -->
                                            <div style="margin-bottom: 15px;">
                                                <div class="input-group" style="align-content: center">
                                                    <div class="form-floating" style="width: 100%;">
                                                        <input type="text" class="form-control" name="incli_derecha"
                                                            id="incli_derecha" placeholder="1"
                                                            value="{{ old('incli_derecha') }}">
                                                        <label for="incli_derecha">INCLI 1</label>
                                                        @if ($errors->has('incli_derecha'))
                                                        <span
                                                            class="error text-danger">{{ $errors->first('incli_derecha') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- INCLI 2 -->
                                            <div style="margin-bottom: 15px;">
                                                <div class="input-group" style="align-content: center">
                                                    <div class="form-floating" style="width: 100%;">
                                                        <input type="text" class="form-control" name="incli_derecha1"
                                                            id="incli_derecha1" placeholder="1"
                                                            value="{{ old('incli_derecha1') }}">
                                                        <label for="incli_derecha1">INCLI 2</label>
                                                        @if ($errors->has('incli_derecha1'))
                                                        <span
                                                            class="error text-danger">{{ $errors->first('incli_derecha1') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- INCLI 3 (NUEVO) -->
                                            <div style="margin-bottom: 15px;">
                                                <div class="input-group" style="align-content: center">
                                                    <div class="form-floating" style="width: 100%;">
                                                        <input type="text" class="form-control" name="incli_derecha2"
                                                            id="incli_derecha2" placeholder="1"
                                                            value="{{ old('incli_derecha2') }}">
                                                        <label for="incli_derecha2">INCLI 3</label>
                                                        @if ($errors->has('incli_derecha2'))
                                                        <span
                                                            class="error text-danger">{{ $errors->first('incli_derecha2') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>




                        <div class="row">
                            <div style="text-align: center">
                                <input type="hidden" name="tipoprueba" id="tipoprueba" value="1">
                                <input type="hidden" name="tipopruebaCi2" id="tipopruebaCi2" value="9">
                                <input type="hidden" name="prueba" id="prueba" value="Luces">
                                <button style="height: 55px; width: 150px" id="btn-guardar"
                                    class="btn btn-outline-success" type="submit">Guardar</button>

                            </div>
                        </div>
                    </form>
                </div>
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

    document.addEventListener('DOMContentLoaded', function() {
        // Cargar el tiempo guardado en el input
        const tiempoInput = document.getElementById('tiempoPrueba');
        if (tiempoInput) {
            const tiempoGuardado = getTiempoPrueba();
            tiempoInput.value = tiempoGuardado;
            // console.log(`📌 Vista: ${document.querySelector('.section-title h2')?.textContent}, Tiempo cargado: ${tiempoGuardado} minutos`);
        }
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

    $(document).ready(function() {
        if (localStorage.getItem('motocarro') == '1') {
            $('#selMotocarro').val(localStorage.getItem('motocarro'));
            getMaquina();
        }
        document.getElementById("btn-guardar").disabled = true;
    });
    $("#selMotocarro").change(function(e) {
        e.preventDefault();
        let motocarro = $('#selMotocarro option:selected').attr('value');
        localStorage.setItem('motocarro', motocarro);

        getMaquina();

    });

    var getMaquina = function() {
        $.ajax({
            url: 'getMaquina/',
            type: 'post',
            dataType: 'json',
            data: {
                idtipo_prueba: 1,
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

    $('#sum_bajas').change(function() {
        var sumBajas = document.getElementById('sum_bajas').checked;
        if (sumBajas) {
            var totalBajas = 0;
            var bajad = $("#baja_derecha").val() ? $("#baja_derecha").val() : 0;
            var baja1d = $("#baja_derecha_1").val() ? $("#baja_derecha_1").val() : 0;
            var bajai = $("#baja_izquierda").val() ? $("#baja_izquierda").val() : 0;
            var baja1i = $("#baja_izquierda_1").val() ? $("#baja_izquierda_1").val() : 0;
            var totalBajas = (parseFloat(bajad) + parseFloat(bajai) + parseFloat(baja1d) + parseFloat(baja1i));
            var total = $("#intensidad_total").val() ? $("#intensidad_total").val() : 0;
            var n = parseFloat(total) + parseFloat(totalBajas);
            $("#intensidad_total").val(parseFloat(total) + parseFloat(totalBajas));
            $("#int_total").html("Intensidad total: " + n);
        } else {
            $("#int_total").html("");
            $("#intensidad_total").val("");
            document.getElementById('sum_altas').checked = false;
            document.getElementById('sum-anti').checked = false;
        }
    });

    $('#sum_altas').change(function() {
        var sumAltas = document.getElementById('sum_altas').checked;
        if (sumAltas) {
            var totalALtas = 0;
            var altad = $("#alta_derecha").val() ? $("#alta_derecha").val() : 0;
            var alta1d = $("#alta_derecha_1").val() ? $("#alta_derecha_1").val() : 0;
            var altai = $("#alta_izquierda").val() ? $("#alta_izquierda").val() : 0;
            var alta1i = $("#alta_izquierda_1").val() ? $("#alta_izquierda_1").val() : 0;
            var totalalta = (parseFloat(altad) + parseFloat(altai) + parseFloat(alta1d) + parseFloat(alta1i));
            var total = $("#intensidad_total").val() ? $("#intensidad_total").val() : 0;
            var n = parseFloat(total) + parseFloat(totalalta);
            $("#intensidad_total").val(parseFloat(total) + parseFloat(totalalta));
            $("#int_total").html("Intensidad total: " + n);
        } else {
            $("#int_total").html("");
            $("#intensidad_total").val("");
            document.getElementById('sum_bajas').checked = false;
            document.getElementById('sum-anti').checked = false;
        }
    });

    $('#sum-anti').change(function() {
        var sumAnti = document.getElementById('sum-anti').checked;
        if (sumAnti) {
            var totalanti = 0;
            var antid = $("#anti_derecha").val() ? $("#anti_derecha").val() : 0;
            var anti1d = $("#anti_derecha_1").val() ? $("#anti_derecha_1").val() : 0;
            var antii = $("#anti_izquierda").val() ? $("#anti_izquierda").val() : 0;
            var anti1i = $("#anti_izquierda_1").val() ? $("#anti_izquierda_1").val() : 0;
            var totalanti = (parseFloat(antid) + parseFloat(antii) + parseFloat(anti1d) + parseFloat(anti1i));
            var total = $("#intensidad_total").val() ? $("#intensidad_total").val() : 0;
            var n = parseFloat(total) + parseFloat(totalanti);
            $("#intensidad_total").val(parseFloat(total) + parseFloat(totalanti));
            $("#int_total").html("Intensidad total: " + n);
        } else {
            $("#int_total").html("");
            $("#intensidad_total").val("");
            document.getElementById('sum_bajas').checked = false;
            document.getElementById('sum_altas').checked = false;

        }
    });


    
    $("#btn-buscar-placa").click(function(e) {
        e.preventDefault();

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
                    idtipo_prueba: 1,
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

                            if (res.observacion == 'baja_derecha')
                                $("#baja_derecha").val(res.valor);
                            if (res.observacion == 'inclinacion_derecha')
                                $("#incli_derecha").val(res.valor);




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
    });


    $("#selMotocarro").change(function(e) {
        e.preventDefault();
        let motocarro = $('#selMotocarro option:selected').attr('value');
        localStorage.setItem('motocarro', motocarro);

        getMaquina();

    });

    var getMaquina = function() {
        $.ajax({
            url: 'getMaquina/',
            type: 'post',
            dataType: 'json',
            data: {
                desdemixta: 1,
                idtipo_prueba: 1,
                motocarro: $('#selMotocarro').val(),
                _token: $("input[name='_token']").val()
            },
            success: function(data, textStatus, jqXHR) {
                console.log(data)
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