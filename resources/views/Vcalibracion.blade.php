@include('layout.heder')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<main id="main">
    <section id="visor" class="contact">
        <div class="container">
            <div class="section-title">
                <h2>Calibracion de analizadores</h2>
            </div>

            <div class="row" data-aos="fade-in">

                <div class="col-lg-12 mt-12 mt-lg-12 d-flex align-items-stretch">
                    <form action="{{ url('/cal') }}" method="POST" class="form-control" id="form-calibracion">
                        @csrf

                        <div style="margin-top: 15px">


                            <div class="row">

                                <div class="col-sm-12 col-md-4 col-lg-4" style="align-content: center">
                                    <div class="input-group mb-3" style="align-content: center">
                                        <label class="input-group-text" for="inputGroupSelect01">Usuarios</label>
                                        <select class="form-select" name="selUsuario" id="selUsuario">
                                            @foreach ($usuarios as $us)
                                                <option value="{{ $us->IdUsuario }}">{{ $us->nombre }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-4" style="align-content: center">
                                    <div class="input-group mb-3" style="align-content: center">
                                        <label class="input-group-text" for="inputGroupSelect01">Maquinas</label>
                                        <select class="form-select" name="selMaquina" id="selMaquina">
                                            @foreach ($maquinas as $ma)
                                                <option value="{{ $ma->idmaquina }}">{{ $ma->maquina }} </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('selMaquina'))
                                            <span class="error text-danger">{{ $errors->first('selMaquina') }}</span>
                                        @endif

                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-4" style="align-content: center">
                                    <div class="input-group mb-3" style="align-content: center">
                                        <label class="input-group-text" for="inputGroupSelect01">Sistema gases</label>
                                        <select class="form-select" name="tipo" id="tipo">
                                            <option value="0">Android </option>
                                            <option value="1">PC </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-2 col-lg-2" style="align-content: center">
                                    <div class="input-group mb-3" style="align-content: center">
                                        <label class="input-group-text" for="inputGroupSelect01">PEF</label>
                                        <input type="number" step="0.001" class="form-control" name="PEF"
                                            id="PEF" value="{{ old('PEF') }}" placeholder="PEF">


                                    </div>
                                </div>
                            </div>
                            <div class="container" style="margin-top: 2%;">
                                <div class="row">
                                    <label
                                        style="color: rgb(0, 4, 255); font-size: 18px; text-align: center; width: 100%; background-color: lightgoldenrodyellow">
                                        Calibración de Analizador de Gases
                                    </label>

                                    <div class="row mt-3">
                                        <!-- Sección SPAN Bajo -->
                                        <div class="col-md-6">
                                            <div class="card p-3">
                                                <strong class="text-primary">SPAN Bajo</strong>

                                                <div class="row mt-2">
                                                    <div class="col-12 mb-2">
                                                        <label style="font-size: 14px;">HC (ppm)</label>
                                                        <input type="number" step="1" min="0"
                                                            name="span_bajo_hc" class="form-control form-control-sm"
                                                            placeholder="Ingrese valor HC" id="span_bajo_hc" />
                                                    </div>

                                                    <div class="col-12 mb-2">
                                                        <label style="font-size: 14px;">CO (%)</label>
                                                        <input type="number" step="0.01" min="0"
                                                            name="span_bajo_co" class="form-control form-control-sm"
                                                            placeholder="Ingrese valor CO" />
                                                    </div>

                                                    <div class="col-12 mb-2">
                                                        <label style="font-size: 14px;">CO₂ (%)</label>
                                                        <input type="number" step="0.01" min="0"
                                                            name="span_bajo_co2" class="form-control form-control-sm"
                                                            placeholder="Ingrese valor CO₂" />
                                                    </div>

                                                    <div class="col-12 mb-2">
                                                        <label style="font-size: 14px;">O₂ (%)</label>
                                                        <input type="number" step="0.01" min="0"
                                                            name="span_bajo_o2" class="form-control form-control-sm"
                                                            placeholder="Ingrese valor O₂" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Sección SPAN Alto -->
                                        <div class="col-md-6">
                                            <div class="card p-3">
                                                <strong class="text-danger">SPAN Alto</strong>

                                                <div class="row mt-2">
                                                    <div class="col-12 mb-2">
                                                        <label style="font-size: 14px;">HC (ppm)</label>
                                                        <input type="number" step="1" min="0"
                                                            name="span_alto_hc" class="form-control form-control-sm"
                                                            placeholder="Ingrese valor HC" />
                                                    </div>

                                                    <div class="col-12 mb-2">
                                                        <label style="font-size: 14px;">CO (%)</label>
                                                        <input type="number" step="0.01" min="0"
                                                            name="span_alto_co" class="form-control form-control-sm"
                                                            placeholder="Ingrese valor CO" />
                                                    </div>

                                                    <div class="col-12 mb-2">
                                                        <label style="font-size: 14px;">CO₂ (%)</label>
                                                        <input type="number" step="0.01" min="0"
                                                            name="span_alto_co2" class="form-control form-control-sm"
                                                            placeholder="Ingrese valor CO₂" />
                                                    </div>

                                                    <div class="col-12 mb-2">
                                                        <label style="font-size: 14px;">O₂ (%)</label>
                                                        <input type="number" step="0.01" min="0"
                                                            name="span_alto_o2" class="form-control form-control-sm"
                                                            placeholder="Ingrese valor O₂" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div style="text-align: center">
                                    <button style="height: 55px; width: 150px" class="btn btn-outline-success"
                                        type="submit">Guardar</button>

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

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


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
        // Guardar valores cuando cambian
        $('input[type="number"], select').on('change', function() {

            if (this.name) {
                localStorage.setItem(this.name, $(this).val());
            }
        });

        // Cargar valores guardados al iniciar
        $('input[type="number"], select').each(function() {
            const valorGuardado = localStorage.getItem(this.name);
            if (valorGuardado !== null) {
                $(this).val(valorGuardado);
            }
        });
    });


    $('#form-calibracion').on('submit', function(e) {
        e.preventDefault(); // Prevenir envío normal

        // Objeto para almacenar los datos

        let formData = {
            _token: $('input[name="_token"]').val(),
            usuario: $('#selUsuario option:selected').attr('value'),
            tipo: $('#tipo option:selected').attr('value'),
            maquina: $('#selMaquina').val(),
            pef: $('#PEF').val(),
            span_bajo: {},
            span_alto: {}
        };

        let camposFaltantes = [];
        let formularioValido = true;

        // Validar campos principales
        if (!formData.pef || isNaN(formData.pef)) {
            camposFaltantes.push('PEF');
            $('#PEF').addClass('is-invalid');
            formularioValido = false;
        } else {
            $('#PEF').removeClass('is-invalid');
        }

        // Validar y capturar SPAN BAJO
        $('[name^="span_bajo_"]').each(function() {
            let $input = $(this);
            let nombre = $input.attr('name').replace('span_bajo_', '');
            let valor = $input.val().trim();

            if (!valor || isNaN(valor)) {
                camposFaltantes.push('SPAN Bajo - ' + $input.attr('placeholder'));
                $input.addClass('is-invalid');
                formularioValido = false;
            } else {
                $input.removeClass('is-invalid');
                formData.span_bajo[nombre] = valor;
            }
        });

        // Validar y capturar SPAN ALTO
        $('[name^="span_alto_"]').each(function() {
            let $input = $(this);
            let nombre = $input.attr('name').replace('span_alto_', '');
            let valor = $input.val().trim();

            if (!valor || isNaN(valor)) {
                camposFaltantes.push('SPAN Alto - ' + $input.attr('placeholder'));
                $input.addClass('is-invalid');
                formularioValido = false;
            } else {
                $input.removeClass('is-invalid');
                formData.span_alto[nombre] = valor;
            }
        });

        // Mostrar alerta si hay campos faltantes

        console.log(formData)
        if (!formularioValido) {
            Swal.fire({
                icon: 'error',
                title: 'Campos incompletos',
                html: 'Los siguientes campos son requeridos:<br><br>' +
                    camposFaltantes.map(campo => `${campo}`).join('<br>'),
                confirmButtonText: 'Entendido'
            });
            return;
        }

        // Confirmación antes de enviar
        Swal.fire({
            title: '¿Confirmar calibración?',
            text: "¿Estás seguro de que los datos son correctos?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Revisar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar carga
                Swal.fire({
                    title: 'Guardando calibración',
                    html: 'Por favor espere...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Enviar datos por AJAX
                $.ajax({
                    url: 'getCalibracion/',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        console.log(response)
                        Swal.fire({
                            icon: 'success',
                            title: '¡Calibración guardada!',
                            text: 'Los datos se han registrado correctamente',
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            // Redirigir o limpiar formulario
                            // window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al guardar la calibración';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg += ': ' + xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMsg
                        });
                    }
                });
            }
        });
    });

    // Limpiar errores al empezar a escribir
    $('input, select').on('input change', function() {
        $(this).removeClass('is-invalid');
    });









    async function loadDefects() {
        $.ajax({
            url: 'getDefectos/',
            type: 'post',
            dataType: 'json',
            data: {
                idprueba: $("#idprueba").val(),
                _token: $("input[name='_token']").val()
            },
            success: function(data) {
                initializeSelect2(data.defectos);
                $("#tableResultsDefectos tbody").empty();
                if (data.resultados.length == 0) {
                    $("#tableResultsDefectos tbody").append(
                        `<tr>
                            <td colspan="5">No se encontraron defectos</td>
                        </tr>`
                    );
                    Swal.close();
                    return;
                }
                data.resultados.forEach(function(index, value) {
                    // console.log(index);
                    if (index.tiporesultado == 'defecto') {
                        $("#tableResultsDefectos tbody").append(
                            `<tr>
                                <td>${index.valor}</td>
                                <td>${index.tipo}</td>
                                <td>${index.descripcion}</td>
                                
                                <td>
                                    <button class="btn btn-danger btn-sm" onclick="event.preventDefault(); removeDefect(${index.idresultados});">Eliminar</button>
                                </td>
                            </tr>`
                        );
                    } else if (index.observacion == "OBSERVACIONLABRADO") {
                        // console.log(index.tiporesultado);
                        $("#" + index.tiporesultado)
                            .val(index.valor)
                            .attr('idresultados', index.idresultados);
                    }
                });

                // Cerrar SweetAlert de carga
                Swal.close();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudieron cargar los defectos. ' + jqXHR.responseText
                });

            }
        });
    }
</script>
