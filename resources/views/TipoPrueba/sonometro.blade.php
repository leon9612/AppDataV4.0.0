@include('layout.heder')
<style>
    #btn-buscar-placa {
        display: none;
    }
</style>
<main id="main">
    <section id="visor" class="contact">
        <div class="container">

            <div class="section-title">
                <h2>Sonometro</h2>
            </div>

            <div class="row" data-aos="fade-in">

                <div class="col-lg-12 mt-12 mt-lg-12 d-flex align-items-stretch">
                    <form action="{{ url('/so') }}" method="POST" class="form-control">
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
                                        <input type="number" class="form-control" step="0.01"
                                            name="valson" id="valson" placeholder="1" value="{{ old('valson') }}">
                                        <label for="floatingInput">Valor</label>
                                        @if ($errors->has('valson'))
                                        <span class="error text-danger">{{ $errors->first('valson') }}</span>
                                        @endif
                                    </div>

                                </div>
                            </div>

                            <div class="col-sm-12 col-md-2 col-lg-2">
                                <input type="hidden" name="tipoprueba" id="tipoprueba" value="4">
                                <input type="hidden" name="tipopruebaCi2" id="tipopruebaCi2" value="12">
                                <input type="hidden" name="prueba" id="prueba" value="Sonometro">
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
        $("#placa").val(placa2[1]);
        $("#idprueba").val(placa2[0]);
        console.log(placa2);

    });


    $(document).on('keyup', '#valson', function() {
        const valor = $(this).val();
        const idCampo = $(this).attr('id');
        validarRango(valor, 'sonometro', idCampo);
    });
</script>