@include('layout.heder')
<style>

</style>
<main id="main">
    <section id="visor" class="contact">
        <div class="container">

            <div class="section-title">
                <h2>Frenometro Motocarro</h2>
            </div>

            <div class="row" data-aos="fade-in">
                <div class="col-lg-12 mt-12 mt-lg-12 d-flex align-items-stretch">
                    <form action="{{ url('/frmotocarro') }}" method="POST" class="form-control">
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
                        <br>
                        <div class="container" style=" margin-top: 2%; justify-content: center; display: flex ">
                            <div class="row">
                                <label
                                    style="color: rgb(0, 4, 255); font-size: 18px; text-align: center; background-color: lemonchiffon; width: 100%">PESAJES
                                    MOTOCARRO</label>
                                <div style="justify-content: center; display: flex; margin-top: 15px">

                                    {{-- <div class="col-sm-12 col-md-1 col-lg-1" style="align-content: center">
                                            <div class="mb-1">
                                                <input type="number" class="form-control" step="0.01"
                                                    placeholder="Eje 1 iz" name="pesaje1i" id="pesaje1i"
                                                    value="{{ old('pesaje1i') }}">
                                    @if ($errors->has('pesaje1i'))
                                    <span class="error text-danger">{{ $errors->first('pesaje1i') }}</span>
                                    @endif
                                </div>
                            </div> --}}
                            <div class="col-sm-4 col-md-1 col-lg-1" style="align-content: center">
                                <div class="mb-1">
                                    <input type="text" class="form-control" id="pesaje1d" placeholder="Eje 1 dr"
                                        name="pesaje1d" value="{{ old('pesaje1d') }}">
                                    @if ($errors->has('pesaje1d'))
                                    <span class="error text-danger">{{ $errors->first('pesaje1d') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-1 col-lg-1" style="align-content: center">
                                <div class="mb-1">
                                    <input type="number" class="form-control" step="0.01" placeholder="Eje 2 iz"
                                        name="pesaje2i" id="pesaje2i" value="{{ old('pesaje2i') }}" style="width: 100%">
                                    @if ($errors->has('pesaje2i'))
                                    <span class="error text-danger">{{ $errors->first('pesaje2i') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-1 col-lg-1" style="align-content: center">
                                <div class="mb-1">
                                    <input type="number" class="form-control" step="0.01" placeholder="Eje 2 dr"
                                        name="pesaje2d" id="pesaje2d" value="{{ old('pesaje2d') }}">
                                    @if ($errors->has('pesaje2d'))
                                    <span class="error text-danger">{{ $errors->first('pesaje2d') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <label
                            style="color: rgb(0, 4, 255); font-size: 18px; text-align: center; width: 100%; background-color: lightgoldenrodyellow; margin-top: 15px">FUERZAS
                            MOTOCARRO</label>
                        <div style="justify-content: center; display: flex; margin-top: 15px">
                            <br>

                            {{-- <div class="col-sm-12 col-md-1 col-lg-1" style="align-content: center">
                                            <div class="mb-1">
                                                <input type="number" class="form-control" step="0.01"
                                                    name="fuerza1i" id="fuerza1i" placeholder="Eje 1 iz"
                                                    value="{{ old('fuerza1i') }}">
                            @if ($errors->has('fuerza1i'))
                            <span class="error text-danger">{{ $errors->first('fuerza1i') }}</span>
                            @endif
                        </div>

                </div> --}}
                <div class="col-sm-12 col-md-1 col-lg-1" style="align-content: center">
                    <div class="mb-1">
                        <input type="text" class="form-control" name="fuerza1d" id="fuerza1d" placeholder="Eje 1 dr"
                            value="{{ old('fuerza1d') }}">
                        @if ($errors->has('fuerza1d'))
                        <span class="error text-danger">{{ $errors->first('fuerza1d') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-sm-12 col-md-1 col-lg-1" style="align-content: center">
                    <div class="mb-1">
                        <input type="number" class="form-control" step="0.01" name="fuerza2i" id="fuerza2i"
                            placeholder="Eje 2 iz" value="{{ old('fuerza2i') }}">
                        @if ($errors->has('fuerza2i'))
                        <span class="error text-danger">{{ $errors->first('fuerza2i') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-sm-12 col-md-1 col-lg-1" style="align-content: center">
                    <div class="mb-1">
                        <input type="number" class="form-control" step="0.01" name="fuerza2d" id="fuerza2d"
                            placeholder="Eje 2 dr" value="{{ old('fuerza2d') }}">
                        @if ($errors->has('fuerza2d'))
                        <span class="error text-danger">{{ $errors->first('fuerza2d') }}</span>
                        @endif
                    </div>
                </div>

                {{-- <div class="col-sm-12 col-md-1 col-lg-1" style="align-content: center">
                                            <div class="mb-1">
                                                <input type="number" class="form-control" step="0.01"
                                                    name="fuerza3i" id="fuerza3i" placeholder="Eje 3 iz"
                                                    value="{{ old('fuerza3i') }}">
                @if ($errors->has('fuerza3i'))
                <span class="error text-danger">{{ $errors->first('fuerza3i') }}</span>
                @endif
            </div>
        </div>
        <div class="col-sm-12 col-md-1 col-lg-1" style="align-content: center">
            <div class="mb-1">
                <input type="number" class="form-control" step="0.01" name="fuerza3d" id="fuerza3d"
                    placeholder="Eje 3 dr" value="{{ old('fuerza3d') }}">
                @if ($errors->has('fuerza3d'))
                <span class="error text-danger">{{ $errors->first('fuerza3d') }}</span>
                @endif
            </div>
        </div>

        <div class="col-sm-12 col-md-1 col-lg-1" style="align-content: center">
            <div class="mb-1">
                <input type="number" class="form-control" step="0.01" name="fuerza4i" id="fuerza4i"
                    placeholder="Eje 4 iz" value="{{ old('fuerza4i') }}">
                @if ($errors->has('fuerza4i'))
                <span class="error text-danger">{{ $errors->first('fuerza4i') }}</span>
                @endif
            </div>
        </div>
        <div class="col-sm-12 col-md-1 col-lg-1" style="align-content: center">
            <div class="mb-1">
                <input type="number" class="form-control" step="0.01" name="fuerza4d" id="fuerza4d"
                    placeholder="Eje 4 dr" value="{{ old('fuerza4d') }}">
                @if ($errors->has('fuerza4d'))
                <span class="error text-danger">{{ $errors->first('fuerza4d') }}</span>
                @endif
            </div>
        </div>

        <div class="col-sm-12 col-md-1 col-lg-1" style="align-content: center">
            <div class="mb-1">
                <input type="number" class="form-control" step="0.01" name="fuerza5i" id="fuerza5i"
                    placeholder="Eje 5 iz" value="{{ old('fuerza5i') }}">
                @if ($errors->has('fuerza5i'))
                <span class="error text-danger">{{ $errors->first('fuerza5i') }}</span>
                @endif
            </div>
        </div>
        <div class="col-sm-12 col-md-1 col-lg-1" style="align-content: center">
            <div class="mb-1">
                <input type="number" class="form-control" step="0.01" name="fuerza5d" id="fuerza5d"
                    placeholder="Eje 5 dr" value="{{ old('fuerza5d') }}">
                @if ($errors->has('fuerza5d'))
                <span class="error text-danger">{{ $errors->first('fuerza5d') }}</span>
                @endif
            </div>
        </div> --}}


        </div>
        <label
            style="color: rgb(0, 4, 255); font-size: 18px; text-align: center; width: 100%; background-color: lightgoldenrodyellow; margin-top: 15px">FUERZAS
            AUXILIARES</label>
        <div style="justify-content: center; display: flex; margin-top: 15px">

            <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                <div class="mb-1">
                    <input type="number" class="form-control" step="0.01" name="fuerzaauxi" id="fuerzaauxi"
                        placeholder="Fuer aux iz" value="{{ old('fuerzaauxi') }}" style="width: 100%">
                    @if ($errors->has('fuerzaauxi'))
                    <span class="error text-danger">{{ $errors->first('fuerzaauxi') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                <div class="mb-1">
                    <input type="number" class="form-control" step="0.01" name="fuerzaauxd" id="fuerzaauxd"
                        placeholder="Fuer aux dr" value="{{ old('fuerzaauxd') }}" style="width: 100%">
                    @if ($errors->has('fuerzaauxd'))
                    <span class="error text-danger">{{ $errors->first('fuerzaauxd') }}</span>
                    @endif
                </div>
            </div>
        </div>
        <label
            style="color: rgb(0, 4, 255); font-size: 18px; text-align: center; width: 100%; background-color: lightgoldenrodyellow; margin-top: 15px">DESEQUILIBRIO
            MOTOCARRO</label>
        <div style="justify-content: center; display: flex; margin-top: 15px">
            {{-- <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                            <div class="mb-1">
                                                <input type="number" class="form-control" step="0.01"
                                                    name="deseje1" id="deseje1" placeholder="Des eje 1"
                                                    value="{{ old('deseje1') }}" disabled>
            <input type="hidden" class="form-control" step="0.01" name="deseje1_" id="deseje1_">
            @if ($errors->has('deseje1'))
            <span class="error text-danger">{{ $errors->first('deseje1') }}</span>
            @endif
        </div>
        </div> --}}
        <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
            <div class="mb-1">
                <input type="number" class="form-control" step="0.01" name="deseje2" id="deseje2"
                    placeholder="Des eje 2" value="{{ old('deseje2') }}" disabled>
                <input type="hidden" class="form-control" step="0.01" name="deseje2_" id="deseje2_">
                @if ($errors->has('deseje2'))
                <span class="error text-danger">{{ $errors->first('deseje2') }}</span>
                @endif
            </div>
        </div>
        {{-- <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                            <div class="mb-1">
                                                <input type="number" class="form-control" step="0.01"
                                                    name="deseje3" id="deseje3" placeholder="Des eje 3"
                                                    value="{{ old('deseje3') }}" disabled>
        <input type="hidden" class="form-control" step="0.01" name="deseje3_" id="deseje3_">
        @if ($errors->has('deseje3'))
        <span class="error text-danger">{{ $errors->first('deseje3') }}</span>
        @endif
        </div>
        </div>
        <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
            <div class="mb-1">
                <input type="number" class="form-control" step="0.01" name="deseje4" id="deseje4"
                    placeholder="Des eje 4" value="{{ old('deseje4') }}" disabled>
                <input type="hidden" class="form-control" step="0.01" name="deseje4_" id="deseje4_">
                @if ($errors->has('deseje4'))
                <span class="error text-danger">{{ $errors->first('deseje4') }}</span>
                @endif
            </div>
        </div>
        <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
            <div class="mb-1">
                <input type="number" class="form-control" step="0.01" name="deseje5" id="deseje5"
                    placeholder="Des eje 5" value="{{ old('deseje5') }}" disabled>
                <input type="hidden" class="form-control" step="0.01" name="deseje5_" id="deseje5_">
                @if ($errors->has('deseje5'))
                <span class="error text-danger">{{ $errors->first('deseje5') }}</span>
                @endif
            </div>
        </div> --}}
        </div>
        <label
            style="color: rgb(0, 4, 255); font-size: 18px; text-align: center; width: 100%; margin-top: 15px; background-color:  lightgoldenrodyellow">EFICACIAS
            MOTOCARRO</label>
        <div style="justify-content: center; display: flex; margin-top: 15px">
            <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                <div class="mb-1">
                    <input type="number" class="form-control" step="0.01" name="efiaux" placeholder="Eficacia auxiliar"
                        id="efiaux" value="{{ old('efiaux') }}" disabled>
                    <input type="hidden" class="form-control" step="0.01" name="efiaux_" id="efiaux_">
                    @if ($errors->has('efiaux'))
                    <span class="error text-danger">{{ $errors->first('efiaux') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                <div class="mb-1">
                    <input type="number" class="form-control" step="0.01" placeholder="Eficacia total" name="efitotal"
                        id="efitotal" value="{{ old('efitotal') }}" disabled>
                    <input type="hidden" class="form-control" step="0.01" name="efitotal_" id="efitotal_">
                    @if ($errors->has('efitotal'))
                    <span class="error text-danger">{{ $errors->first('efitotal') }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div style="justify-content: center; display: flex; margin-top: 15px">
            <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                <button style="width: 100%; height: 55px;" class="btn btn-outline-secondary" id="btn-calcular">Calcular
                    datos</button>
            </div>
            <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                <input type="hidden" name="tipoprueba" id="tipoprueba" value="7">
                <input type="hidden" name="tipopruebaCi2" id="tipopruebaCi2" value="7">
                <input type="hidden" name="prueba" id="prueba" value="Frenos">
                <button style="width: 100%; height: 55px;" class="btn btn-outline-success" id="btn-guardar" disabled
                    type="submit">Guardar</button>
            </div>
        </div>
        </div>
        </div>



        </div>

        </form>

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

    // $(document).ready(function() {
    //     document.getElementById("btn-guardar").disabled = true; // Deshabilitar el botón al cargar la página
    // });

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
        //console.log(placa2);

    });

    $("#btn-calcular").click(function(ev) {
        ev.preventDefault()
        document.getElementById("btn-Guardar").disabled = false;
        var sumFuerza = parseFloat($("#fuerza1d").val()) + parseFloat($(
            "#fuerza2d").val()) + parseFloat($("#fuerza2i").val())
        var peso = parseFloat($("#pesaje1d").val()) + parseFloat($(
            "#pesaje2d").val()) + parseFloat($("#pesaje2i").val())

        var auxiliar = parseFloat($("#fuerzaauxd").val()) + parseFloat($("#fuerzaauxi").val())


        if (parseFloat($("#fuerza2d").val()) > parseFloat($("#fuerza2i").val())) {
            var des2f = parseFloat($("#fuerza2d").val()) - parseFloat($("#fuerza2i").val())
            des2f = ((des2f / parseFloat($("#fuerza2d").val())) * 100);
            $("#deseje2").val(des2f.toFixed(2))
            $("#deseje2_").val(des2f.toFixed(2))
        } else {
            var des2f = parseFloat($("#fuerza2i").val()) - parseFloat($("#fuerza2d").val())
            des2f = ((des2f / parseFloat($("#fuerza2i").val())) * 100);
            //des2f = Math.round((des2f * 100) / 100)
            //.log(des2f)
            $("#deseje2").val(des2f.toFixed(2))
            $("#deseje2_").val(des2f.toFixed(2))

        }



        var eficatotal = ((sumFuerza / peso) * 100)
        eficatotal = ((eficatotal * 100) / 100);
        $("#efitotal").val(eficatotal.toFixed(2));
        $("#efitotal_").val(eficatotal.toFixed(2));
        var efiaux = ((auxiliar / peso) * 100)
        //                        efiaux = efiaux.substring(0, 3);
        efiaux = ((efiaux * 100) / 100);
        $("#efiaux").val(efiaux.toFixed(2));
        $("#efiaux_").val(efiaux.toFixed(2));
    })

    // $("#btn-evento").click(function(ev) {
    //     ev.preventDefault();
    //     document.getElementById("btn-evento").disabled = true; // Deshabilitar el botón al hacer clic
    //     if ($(".Vplaca").val() == null || $(".Vplaca").val() == "") {
    //         Toast.fire({
    //             icon: "error",
    //             title: "Seleccione una placa",
    //             position: "bottom-end"
    //         });
    //         document.getElementById("btn-evento").disabled = false;
    //     } else {
    //         Toast.fire({
    //             icon: "info",
    //             title: "Creando evento...",
    //             timeout: 1000,
    //             position: "bottom-end"
    //         });
    //         $.ajax({
    //             url: 'getevento/',
    //             type: 'post',
    //             dataType: 'json',
    //             data: {
    //                 placa: $(".Vplaca").val(),
    //                 prueba: 'Frenos',
    //                 tipoprueba: '7',
    //                 tipovehiculo: '1',
    //                 tipoevento: '1',
    //                 _token: $("input[name='_token']").val()
    //             },
    //             success: function(data, textStatus, jqXHR) {
    //                 document.getElementById("btn-evento").disabled = false;
    //                 document.getElementById("btn-guardar").disabled = false;
    //                 Swal.close();
    //                 Toast.fire({
    //                     icon: "success",
    //                     title: "Evento creado, tenga en cuenta el tiempo de duracion de la prueba, para enviar los datos.",
    //                     position: "bottom-end",
    //                     timeout: 1000
    //                 });
    //                 iniciarContadorRegresivo();

    //             },
    //             error: function(jqXHR, textStatus, errorThrown) {
    //                 console.log('error')
    //                 console.log(jqXHR.responseText)
    //                 console.log(textStatus)
    //                 console.log(errorThrown)
    //             }
    //         });
    //     }

    // });

    


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
                    idtipo_prueba: 7,
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
                            if (res.observacion.trim() == 'Frenos eje 1 izquierdo' || res
                                .observacion.trim() == 'Frenos eje 1 Izquierdo')
                                $("#fuerza1i").val(res.valor);
                            if (res.observacion.trim() == 'Frenos eje 1 derecho' || res
                                .observacion.trim() == 'Frenos eje 1 Derecho')
                                $("#fuerza1d").val(res.valor);
                            if (res.observacion.trim() == 'Frenos eje 2 izquierdo' || res
                                .observacion.trim() == 'Frenos eje 2 Izquierdo')
                                $("#fuerza2i").val(res.valor);
                            if (res.observacion.trim() == 'Frenos eje 2 derecho' || res
                                .observacion.trim() == 'Frenos eje 2 Derecho')
                                $("#fuerza2d").val(res.valor);
                            if (res.observacion.trim() == 'Frenos eje 3 izquierdo' || res
                                .observacion.trim() == 'Frenos eje 3 Izquierdo')
                                $("#fuerza3i").val(res.valor);
                            if (res.observacion.trim() == 'Frenos eje 3 derecho' || res
                                .observacion.trim() == 'Frenos eje 3 Derecho')
                                $("#fuerza3d").val(res.valor);
                            if (res.observacion.trim() == 'Frenos eje 4 izquierdo' || res
                                .observacion.trim() == 'Frenos eje 4 Izquierdo')
                                $("#fuerza4i").val(res.valor);
                            if (res.observacion.trim() == 'Frenos eje 4 derecho' || res
                                .observacion.trim() == 'Frenos eje 4 Derecho')
                                $("#fuerza4d").val(res.valor);
                            if (res.observacion.trim() == 'Frenos eje 5 izquierdo' || res
                                .observacion.trim() == 'Frenos eje 5 Izquierdo')
                                $("#fuerza5i").val(res.valor);
                            if (res.observacion.trim() == 'Frenos eje 5 derecho' || res
                                .observacion.trim() == 'Frenos eje 5 Derecho')
                                $("#fuerza5d").val(res.valor);


                            if (res.observacion == 'Pesaje eje 1 izquierdo' || res
                                .observacion == 'Pesaje eje 1 Izquierdo')
                                $("#pesaje1i").val(res.valor);
                            if (res.observacion == 'Pesaje eje 1 derecho' || res.observacion ==
                                'Pesaje eje 1 Derecho')
                                $("#pesaje1d").val(res.valor);
                            if (res.observacion == 'Pesaje eje 2 izquierdo' || res
                                .observacion == 'Pesaje eje 2 Izquierdo')
                                $("#pesaje2i").val(res.valor);
                            if (res.observacion == 'Pesaje eje 2 derecho' || res.observacion ==
                                'Pesaje eje 2 Derecho')
                                $("#pesaje2d").val(res.valor);
                            if (res.observacion == 'Pesaje eje 3 izquierdo' || res
                                .observacion == 'Pesaje eje 3 Izquierdo')
                                $("#pesaje3i").val(res.valor);
                            if (res.observacion == 'Pesaje eje 3 derecho' || res.observacion ==
                                'Pesaje eje 3 Derecho')
                                $("#pesaje3d").val(res.valor);
                            if (res.observacion == 'Pesaje eje 4 izquierdo' || res
                                .observacion == 'Pesaje eje 4 Izquierdo')
                                $("#pesaje4i").val(res.valor);
                            if (res.observacion == 'Pesaje eje 4 derecho' || res.observacion ==
                                'Pesaje eje 4 Derecho')
                                $("#pesaje4d").val(res.valor);
                            if (res.observacion == 'Pesaje eje 5 izquierdo' || res
                                .observacion == 'Pesaje eje 5 Izquierdo')
                                $("#pesaje5i").val(res.valor);
                            if (res.observacion == 'Pesaje eje 5 derecho' || res.observacion ==
                                'Pesaje eje 5 Derecho')
                                $("#pesaje5d").val(res.valor);


                            if (res.observacion.trim() == 'FrenoAuxs eje 2 izquierdo' || res
                                .observacion.trim() == 'FrenoAuxs eje 2 Izquierdo' || res
                                .observacion.trim() == 'FrenoAuxs eje 7 izquierdo')
                                $("#fuerzaauxi").val(res.valor);
                            if (res.observacion.trim() == 'FrenoAuxs eje 2 derecho' || res
                                .observacion.trim() == 'FrenoAuxs eje 2 Derecho' || res
                                .observacion.trim() == 'FrenoAuxs eje 7 derecho')
                                $("#fuerzaauxd").val(res.valor);


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
</script>
