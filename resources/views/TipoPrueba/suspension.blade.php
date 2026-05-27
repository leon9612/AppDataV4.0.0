@include('layout.heder')
<main id="main">
    <section id="visor" class="contact">
        <div class="container">

            <div class="section-title">
                <h2>Suspension</h2>
            </div>

            <div class="row" data-aos="fade-in">
                <div class="col-lg-12 mt-12 mt-lg-12 d-flex align-items-stretch">
                    <form action="{{ url('/su') }}" method="POST" class="form-control">
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

                            <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                <div class="input-group mb-3" style="align-content: center">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" step="0.01" name="eje1d" id="eje1d"
                                            placeholder="1" value="{{ old('eje1d') }}">
                                        <label for="floatingInput">Eje 1 Derecha</label>
                                        @if ($errors->has('eje1d'))
                                        <span class="error text-danger">{{ $errors->first('eje1d') }}</span>
                                        @endif
                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                <div class="input-group mb-3" style="align-content: center">
                                    <div class="form-floating mb-3">
                                        <input type="number" step="0.01" class="form-control" placeholder="2"
                                            name="eje1i" id="eje1i" value="{{ old('eje1i') }}">
                                        <label for="floatingInput">Eje 1 Izquierda</label>
                                        @if ($errors->has('eje1i'))
                                        <span class="error text-danger">{{ $errors->first('eje1i') }}</span>
                                        @endif
                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                <div class="input-group mb-3" style="align-content: center">
                                    <div class="form-floating mb-3">
                                        <input type="number" step="0.01" class="form-control" placeholder="3"
                                            name="eje2d" id="eje2d" value="{{ old('eje2d') }}">
                                        <label for="floatingInput">Eje 2 Derecha</label>
                                        @if ($errors->has('eje2d'))
                                        <span class="error text-danger">{{ $errors->first('eje2d') }}</span>
                                        @endif
                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                <div class="input-group mb-3" style="align-content: center">
                                    <div class="form-floating mb-3">
                                        <input type="number" step="0.01" class="form-control" placeholder="4"
                                            name="eje2i" id="eje2i" value="{{ old('eje2i') }}">
                                        <label for="floatingInput">Eje 2 Izquierda</label>
                                        @if ($errors->has('eje2i'))
                                        <span class="error text-danger">{{ $errors->first('eje2i') }}</span>
                                        @endif
                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-12 col-md-2 col-lg-2">
                                <input type="hidden" name="tipoprueba" id="tipoprueba" value="9">
                                <input type="hidden" name="tipopruebaCi2" id="tipopruebaCi2" value="6">
                                <input type="hidden" name="prueba" id="prueba" value="Suspension">
                                <button style="width: 100%; height: 55px;" id="btn-guardar"
                                    class="btn btn-outline-success" type="submit">Guardar</button>
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
        document.getElementById("btn-guardar").disabled = true; // Deshabilitar el botón al cargar la página
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
        $("#idprueba").val(placa2[0]);
        $("#placa").val(placa2[1]);
        $("#btn-buscar-placa").click();


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
                    idtipo_prueba: 9,
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
                            if (res.observacion == 'Suspensión delantera derecha' || res
                                .observacion == 'Suspension delantera derecha') {
                                $("#eje1d").val(res.valor);
                                validarRango(res.valor, 'suspension', 'eje1d');

                            }
                            if (res.observacion == 'Suspensión delantera izquierda' || res
                                .observacion == 'Suspension delantera izquierda') {
                                $("#eje1i").val(res.valor);
                                validarRango(res.valor, 'suspension', 'eje1i');
                            }
                            if (res.observacion == 'Suspension trasera derecha' || res
                                .observacion == 'Suspension trasera derecha') {
                                $("#eje2d").val(res.valor);
                                validarRango(res.valor, 'suspension', 'eje2d');
                            }
                            if (res.observacion == 'Suspensión trasera izquierda' || res
                                .observacion == 'Suspension trasera izquierda') {
                                $("#eje2i").val(res.valor);
                                validarRango(res.valor, 'suspension', 'eje2i');
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
    });

    $(document).on('keyup', '#eje1d, #eje1i, #eje2d, #eje2i', function() {
        const valor = $(this).val();
        const idCampo = $(this).attr('id');
        validarRango(valor, 'suspension', idCampo);
    });
</script>