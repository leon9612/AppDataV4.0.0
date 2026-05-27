@include('layout.heder')

<main id="main">
    <section id="visor" class="contact">
        <div class="container">
            <div class="section-title">
                <h2>Luces Mixta</h2>
            </div>

            <div class="row" data-aos="fade-in">

                <div class="col-lg-12 mt-12 mt-lg-12 d-flex align-items-stretch">
                    <form action="{{ url('/lu') }}" method="POST" class="form-control">
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
                            <div class="col-sm-12 col-md-3 col-lg-3" style="align-content: center">
                                <div class="input-group mb-3" style="align-content: center">
                                    <label class="input-group-text" for="inputGroupSelect01">Simultaneas</label>
                                    <select class="form-select" id="inputGroupSelect01" name="selSimultanea"
                                        id="selSimultanea">
                                        <option value="0">No</option>
                                        <option value="1">Si</option>
                                    </select>
                                </div>
                            </div>


                            <div class="container" style=" margin-top: 2%; justify-content: center; display: flex ">
                                <div class="row">
                                    <label
                                        style="color: rgb(0, 4, 255); font-size: 18px; text-align: center; width: 100%; background-color: lightgoldenrodyellow">LUCES
                                        BAJAS</label>
                                    <div style="justify-content: center; display: flex; margin-top: 15px">

                                        <br>
                                        <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                            <div class="input-group mb-3" style="align-content: center">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" name="baja_derecha"
                                                        id="baja_derecha" placeholder="1"
                                                        value="{{ old('baja_derecha') }}">
                                                    <label for="floatingInput">BAJA D</label>
                                                    @if ($errors->has('baja_derecha'))
                                                    <span
                                                        class="error text-danger">{{ $errors->first('baja_derecha') }}</span>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                            <div class="input-group mb-3" style="align-content: center">
                                                <div class="form-floating mb-3">
                                                    <input type="number" class="form-control" step="0.01"
                                                        name="baja_izquierda" id="baja_izquierda" placeholder="1"
                                                        value="{{ old('baja_izquierda') }}">
                                                    <label for="floatingInput">BAJA I</label>
                                                    @if ($errors->has('baja_izquierda'))
                                                    <span
                                                        class="error text-danger">{{ $errors->first('baja_izquierda') }}</span>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                            <div class="input-group mb-3" style="align-content: center">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" name="baja_derecha_1"
                                                        id="baja_derecha_1" placeholder="1"
                                                        value="{{ old('baja_derecha_1') }}">
                                                    <label for="floatingInput">BAJA D-1</label>
                                                    @if ($errors->has('baja_derecha_1'))
                                                    <span
                                                        class="error text-danger">{{ $errors->first('baja_derecha_1') }}</span>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                            <div class="input-group mb-3" style="align-content: center">
                                                <div class="form-floating mb-3">
                                                    <input type="number" class="form-control" step="0.01"
                                                        name="baja_izquierda_1" id="baja_izquierda_1" placeholder="1"
                                                        value="{{ old('baja_izquierda_1') }}">
                                                    <label for="floatingInput">BAJA I-1</label>
                                                    @if ($errors->has('baja_izquierda_1'))
                                                    <span
                                                        class="error text-danger">{{ $errors->first('baja_izquierda_1') }}</span>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                            <div class="input-group mb-3" style="align-content: center">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="sum_bajas">
                                                    <label class="form-check-label" for="flexCheckDefault">
                                                        Sumar
                                                    </label>
                                                </div>

                                            </div>
                                        </div>



                                    </div>
                                    <label
                                        style="color: rgb(0, 4, 255); font-size: 18px; text-align: center; width: 100%; background-color: lightgoldenrodyellow">LUCES
                                        ALTAS</label>
                                    <div style="justify-content: center; display: flex; margin-top: 15px">
                                        <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                            <div class="input-group mb-3" style="align-content: center">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" name="alta_derecha"
                                                        id="alta_derecha" placeholder="1"
                                                        value="{{ old('alta_derecha') }}">
                                                    <label for="floatingInput">ALTA D</label>
                                                    @if ($errors->has('alta_derecha'))
                                                    <span
                                                        class="error text-danger">{{ $errors->first('alta_derecha') }}</span>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                            <div class="input-group mb-3" style="align-content: center">
                                                <div class="form-floating mb-3">
                                                    <input type="number" class="form-control" step="0.01"
                                                        name="alta_izquierda" id="alta_izquierda" placeholder="1"
                                                        value="{{ old('alta_izquierda') }}">
                                                    <label for="floatingInput">ALTA I</label>
                                                    @if ($errors->has('alta_izquierda'))
                                                    <span
                                                        class="error text-danger">{{ $errors->first('alta_izquierda') }}</span>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                            <div class="input-group mb-3" style="align-content: center">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" name="alta_derecha_1"
                                                        id="alta_derecha_1" placeholder="1"
                                                        value="{{ old('alta_derecha_1') }}">
                                                    <label for="floatingInput">ALTA D-1</label>
                                                    @if ($errors->has('alta_derecha_1'))
                                                    <span
                                                        class="error text-danger">{{ $errors->first('alta_derecha_1') }}</span>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                            <div class="input-group mb-3" style="align-content: center">
                                                <div class="form-floating mb-3">
                                                    <input type="number" class="form-control" step="0.01"
                                                        name="alta_izquierda_1" id="alta_izquierda_1" placeholder="1"
                                                        value="{{ old('alta_izquierda_1') }}">
                                                    <label for="floatingInput">ALTA I-1</label>
                                                    @if ($errors->has('alta_izquierda_1'))
                                                    <span
                                                        class="error text-danger">{{ $errors->first('alta_izquierda_1') }}</span>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                            <div class="input-group mb-3" style="align-content: center">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="sum_altas">
                                                    <label class="form-check-label" for="flexCheckDefault">
                                                        Sumar
                                                    </label>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="container" style=" margin-top: 2px; justify-content: center; display: flex ">
                            <div class="row">
                                <label
                                    style="color: rgb(0, 4, 255); font-size: 18px; text-align: center; width: 100%; background-color: lightgoldenrodyellow">ANTINIEBLAS</label>
                                <div style="justify-content: center; display: flex; margin-top: 15px">
                                    <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                        <div class="input-group mb-3" style="align-content: center">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" name="anti_derecha"
                                                    id="anti_derecha" placeholder="1" value="{{ old('anti_derecha') }}">
                                                <label for="floatingInput">ANTI D</label>
                                                @if ($errors->has('anti_derecha'))
                                                <span
                                                    class="error text-danger">{{ $errors->first('anti_derecha') }}</span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                        <div class="input-group mb-3" style="align-content: center">
                                            <div class="form-floating mb-3">
                                                <input type="number" class="form-control" step="0.01"
                                                    name="anti_izquierda" id="anti_izquierda" placeholder="1"
                                                    value="{{ old('anti_izquierda') }}">
                                                <label for="floatingInput">ANTI I</label>
                                                @if ($errors->has('anti_izquierda'))
                                                <span
                                                    class="error text-danger">{{ $errors->first('anti_izquierda') }}</span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                        <div class="input-group mb-3" style="align-content: center">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" name="anti_derecha_1"
                                                    id="anti_derecha_1" placeholder="1"
                                                    value="{{ old('anti_derecha_1') }}">
                                                <label for="floatingInput">ANTI D-1</label>
                                                @if ($errors->has('anti_derecha_1'))
                                                <span
                                                    class="error text-danger">{{ $errors->first('anti_derecha_1') }}</span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                        <div class="input-group mb-3" style="align-content: center">
                                            <div class="form-floating mb-3">
                                                <input type="number" class="form-control" step="0.01"
                                                    name="anti_izquierda_1" id="anti_izquierda_1" placeholder="1"
                                                    value="{{ old('anti_izquierda_1') }}">
                                                <label for="floatingInput">ANTI I-1</label>
                                                @if ($errors->has('anti_izquierda_1'))
                                                <span
                                                    class="error text-danger">{{ $errors->first('anti_izquierda_1') }}</span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                        <div class="input-group mb-3" style="align-content: center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="sum-anti">
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    Sumar
                                                </label>
                                            </div>

                                        </div>
                                    </div>



                                </div>
                                <div style="justify-content: center; display: flex; margin-top: 15px">
                                    <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                        <div class="input-group mb-3" style="align-content: center">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" name="anti_derecha_2"
                                                    id="anti_derecha_2" placeholder="1"
                                                    value="{{ old('anti_derecha_2') }}">
                                                <label for="floatingInput">ANTI D-2</label>
                                                @if ($errors->has('anti_derecha_2'))
                                                <span
                                                    class="error text-danger">{{ $errors->first('anti_derecha_2') }}</span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                        <div class="input-group mb-3" style="align-content: center">
                                            <div class="form-floating mb-3">
                                                <input type="number" class="form-control" step="0.01"
                                                    name="anti_izquierda_2" id="anti_izquierda_2" placeholder="1"
                                                    value="{{ old('anti_izquierda_2') }}">
                                                <label for="floatingInput">ANTI I-2</label>
                                                @if ($errors->has('anti_izquierda_2'))
                                                <span
                                                    class="error text-danger">{{ $errors->first('anti_izquierda_2') }}</span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                        <div class="input-group mb-3" style="align-content: center">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" name="anti_derecha_3"
                                                    id="anti_derecha_3" placeholder="1"
                                                    value="{{ old('anti_derecha_3') }}">
                                                <label for="floatingInput">ANTI D-3</label>
                                                @if ($errors->has('anti_derecha_3'))
                                                <span
                                                    class="error text-danger">{{ $errors->first('anti_derecha_3') }}</span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                        <div class="input-group mb-3" style="align-content: center">
                                            <div class="form-floating mb-3">
                                                <input type="number" class="form-control" step="0.01"
                                                    name="anti_izquierda_3" id="anti_izquierda_3" placeholder="1"
                                                    value="{{ old('anti_izquierda_3') }}">
                                                <label for="floatingInput">ANTI I-3</label>
                                                @if ($errors->has('anti_izquierda_3'))
                                                <span
                                                    class="error text-danger">{{ $errors->first('anti_izquierda_3') }}</span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                        <div class="input-group mb-3" style="align-content: center">


                                        </div>
                                    </div>



                                </div>
                                <label
                                    style="color: rgb(0, 4, 255); font-size: 18px; text-align: center; width: 100%; background-color: lightgoldenrodyellow">INCLINACIONES</label>
                                <div style="justify-content: center; display: flex; margin-top: 15px">

                                    <br>
                                    <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                        <div class="input-group mb-3" style="align-content: center">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" name="incli_derecha"
                                                    id="incli_derecha" placeholder="1"
                                                    value="{{ old('incli_derecha') }}">
                                                <label for="floatingInput">INCLI D</label>
                                                @if ($errors->has('incli_derecha'))
                                                <span
                                                    class="error text-danger">{{ $errors->first('incli_derecha') }}</span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                        <div class="input-group mb-3" style="align-content: center">
                                            <div class="form-floating mb-3">
                                                <input type="number" class="form-control" step="0.01"
                                                    name="incli_izquierda" id="incli_izquierda" placeholder="1"
                                                    value="{{ old('incli_izquierda') }}">
                                                <label for="floatingInput">INCLI I</label>
                                                @if ($errors->has('incli_izquierda'))
                                                <span
                                                    class="error text-danger">{{ $errors->first('incli_izquierda') }}</span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                        <div class="input-group mb-3" style="align-content: center">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" name="incli_derecha_1"
                                                    id="incli_derecha_1" placeholder="1"
                                                    value="{{ old('incli_derecha_1') }}">
                                                <label for="floatingInput">INCLI D-1</label>
                                                @if ($errors->has('incli_derecha_1'))
                                                <span
                                                    class="error text-danger">{{ $errors->first('incli_derecha_1') }}</span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                        <div class="input-group mb-3" style="align-content: center">
                                            <div class="form-floating mb-3">
                                                <input type="number" class="form-control" step="0.01"
                                                    name="incli_izquierda_1" id="incli_izquierda_1" placeholder="1"
                                                    value="{{ old('incli_izquierda_1') }}">
                                                <label for="floatingInput">INCLI I-1</label>
                                                @if ($errors->has('incli_izquierda_1'))
                                                <span
                                                    class="error text-danger">{{ $errors->first('incli_izquierda_1') }}</span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                        <div class="input-group mb-3" style="align-content: center">
                                            <div class="form-floating mb-3">
                                                <input type="hidden" class="form-control" step="0.01"
                                                    name="intensidad_total" id="intensidad_total">
                                                <input type="hidden" class="form-control" step="0.01" name="optLUx"
                                                    id="optLUx">

                                                <label for="floatingInput" id="int_total" style="height: 60px"></label>
                                                @if ($errors->has('intensidad_total'))
                                                <span
                                                    class="error text-danger">{{ $errors->first('intensidad_total') }}</span>
                                                @endif
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

    // $(document).ready(function() {
    //     // document.getElementById("btn-guardar").disabled = true; // Deshabilitar el botón al cargar la página
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
        $("#placa").val(placa2[1]);
        $("#idprueba").val(placa2[0]);
        $("#btn-buscar-placa").click();

    });
    var optLuxV = [0, 0, 0];
    var optLux = "0";
    $('#sum_bajas').change(function() {
        document.getElementById("btn-guardar").disabled = false;
        var sumBajas = document.getElementById('sum_bajas').checked;
        if (sumBajas) {
            optLuxV[0] = 1;
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
            var arr = optLuxV.toString();
            var finalArray = arr.replace(",", "");
            finalArray = finalArray.replace(",", "");
            $("#optLUx").val(finalArray);
            console.log($("#optLUx").val())
        } else {
            optLuxV = [0, 0, 0];
            $("#int_total").html("");
            $("#intensidad_total").val("");
            document.getElementById('sum_altas').checked = false;
            document.getElementById('sum-anti').checked = false;
        }
    });

    $('#sum_altas').change(function() {
        document.getElementById("btn-guardar").disabled = false;
        var sumAltas = document.getElementById('sum_altas').checked;
        if (sumAltas) {
            optLuxV[1] = 1;
            var arr = optLuxV.toString();
            var finalArray = arr.replace(",", "");
            finalArray = finalArray.replace(",", "");
            $("#optLUx").val(finalArray);
            console.log($("#optLUx").val())
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
            optLuxV = [0, 0, 0];
            //optLux = "";
            $("#int_total").html("");
            $("#intensidad_total").val("");
            document.getElementById('sum_bajas').checked = false;
            document.getElementById('sum-anti').checked = false;
        }
    });

    $('#sum-anti').change(function() {
        document.getElementById("btn-guardar").disabled = false;
        var sumAnti = document.getElementById('sum-anti').checked;
        if (sumAnti) {
            optLuxV[2] = 1;
            var arr = optLuxV.toString();
            var finalArray = arr.replace(",", "");
            finalArray = finalArray.replace(",", "");
            $("#optLUx").val(finalArray);
            console.log($("#optLUx").val())
            //            optLux = (optLux + 1);
            //            console.log(optLux)
            //            convertToBinary1(optLux);
            var totalanti = 0;
            var antid = $("#anti_derecha").val() ? $("#anti_derecha").val() : 0;
            var anti1d = $("#anti_derecha_1").val() ? $("#anti_derecha_1").val() : 0;
            var anti2d = $("#anti_derecha_2").val() ? $("#anti_derecha_2").val() : 0;
            var anti3d = $("#anti_derecha_3").val() ? $("#anti_derecha_3").val() : 0;
            var antii = $("#anti_izquierda").val() ? $("#anti_izquierda").val() : 0;
            var anti1i = $("#anti_izquierda_1").val() ? $("#anti_izquierda_1").val() : 0;
            var anti2i = $("#anti_izquierda_2").val() ? $("#anti_izquierda_2").val() : 0;
            var anti3i = $("#anti_izquierda_3").val() ? $("#anti_izquierda_3").val() : 0;
            console.log(antid + " " + anti1d + " " + anti2d + " " + anti3d)
            console.log(antii + " " + anti1i + " " + anti2i + " " + anti3i)
            var totalanti = (parseFloat(antid) + parseFloat(antii) + parseFloat(anti1d) + parseFloat(anti1i) +
                parseFloat(anti2i) + parseFloat(anti2d) + parseFloat(anti3i) + parseFloat(anti3d));
            var total = $("#intensidad_total").val() ? $("#intensidad_total").val() : 0;
            var n = parseFloat(total) + parseFloat(totalanti);
            $("#intensidad_total").val(parseFloat(total) + parseFloat(totalanti));
            $("#int_total").html("Intensidad total: " + n.toFixed(2));
        } else {
            optLuxV = [0, 0, 0];
            $("#int_total").html("");
            $("#intensidad_total").val("");
            document.getElementById('sum_bajas').checked = false;
            document.getElementById('sum_altas').checked = false;

        }
    });

    function convertToBinary1(number) {
        let num = number;
        let binary = (num % 2).toString();
        for (; num > 1;) {
            num = parseInt(num / 2);
            binary = (num % 2) + (binary);
        }
        console.log(binary);
    }





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
                            res.valor = res.valor.replace(",", ".");
                            // console.log(res.observacion + " " + res.valor)
                            if (res.observacion == 'baja_izquierda') {
                                $("#baja_izquierda").val(res.valor);
                                validarRango(res.valor, 'luces', 'baja_izquierda');
                            }
                            if (res.observacion == 'inclinacion_izquierda') {
                                $("#incli_izquierda").val(res.valor);
                                validarRango(res.valor, 'luces', 'incli_izquierda');
                            }
                            if (res.observacion == 'alta_izquierda') {
                                $("#alta_izquierda").val(res.valor);
                                validarRango(res.valor, 'luces', 'alta_izquierda');
                            }
                            if (res.observacion == 'baja_derecha') {
                                $("#baja_derecha").val(res.valor);
                                validarRango(res.valor, 'luces', 'baja_derecha');
                            }

                            if (res.observacion == 'inclinacion_derecha') {
                                $("#incli_derecha").val(res.valor);
                                validarRango(res.valor, 'luces', 'incli_derecha');
                            }
                            if (res.observacion == 'alta_derecha') {
                                $("#alta_derecha").val(res.valor);
                                validarRango(res.valor, 'luces', 'alta_derecha');
                            }
                            if (res.observacion == 'antis_derecha') {
                                $("#anti_derecha").val(res.valor);
                                validarRango(res.valor, 'luces', 'anti_derecha');
                            }
                            if (res.observacion == 'antis_izquierda') {
                                $("#anti_izquierda").val(res.valor);
                                validarRango(res.valor, 'luces', 'anti_izquierda');
                            }



                        });
                    } else {
                        Toast.fire({
                            icon: "success",
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

    $(document).on('keyup', '#baja_izquierda, #baja_derecha, #incli_izquierda, #incli_derecha, #baja_izquierda_1, #baja_derecha_1, #incli_derecha_1, #incli_izquierda_1', function() {
        const valor = $(this).val();
        const idCampo = $(this).attr('id');
        validarRango(valor, 'luces', idCampo);
    });
</script>''