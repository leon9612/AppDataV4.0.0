@include('layout.heder')

<main id="main">
    <section id="visor" class="contact">
        <div class="container">
            <div class="section-title">
                <h2>Actualizar sistema</h2>
            </div>

            <div class="row" data-aos="fade-in">

                <div class="col-lg-12 mt-12 mt-lg-12 d-flex align-items-stretch">
                    <form action="{{ url('/update') }}" method="POST" class="form-control">
                        @csrf
                        <div style="margin-top: 15px">
                            <div class="row">


                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <div class="input-group mb-3" style="align-content: center;">
                                        <label class="input-group-text" for="inputGroupSelect01">Seleccione la
                                            version</label>
                                        <select class="form-select selVersion" id="inputGroupSelect01"
                                            style="height: 58px" >
                                            

                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <div style="text-align: center">
                                        <button style="height: 55px; width: 150px" class="btn btn-outline-success"
                                            id="btn-descargar">Descargar</button>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="margin-top: 15px">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12" id="descipcionActualizacion">
                                    
                                    
                                </div>
                                
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

    $(document).ready(function() {
        $.ajax({
            url: 'https://appdataingeniersoftware.com/appdatacontrol/index.php/Cdispositivo/getActualizaciones',
            type: 'get',
            dataType: 'json',
            success: function(data, textStatus, jqXHR) {
                console.log(data)
                $(".selVersion").empty();
                
                $(".selVersion").append($('<option>', {
                    value: '',
                    text: 'Seleccione'
                }));
                $.each(data, function(index, value) {
                    $(".selVersion").append($('<option>', {
                        value: value.file,
                        text: value.file
                    }));
                });

            },
            error: function(jqXHR, textStatus, errorThrown) {

            }
        });

    });

    $(".selVersion").change(function(e) {
        e.preventDefault();
        // console.log($('.selVersion option:selected').attr('value'))
        // $('.selVersion option:selected').attr('value');
        $.ajax({
            url: 'https://appdataingeniersoftware.com/appdatacontrol/index.php/Cdispositivo/getActualizacionesFile',
            type: 'post',
            dataType: 'json',
            data:{
                file: $('.selVersion option:selected').attr('value'),
            },
            success: function(data, textStatus, jqXHR) {
                console.log({data})
                $("#descipcionActualizacion").html('');
                $("#descipcionActualizacion").html(data[0].descripcion);

            },
            error: function(jqXHR, textStatus, errorThrown) {

            }
        });


    });


    $("#btn-descargar").click(function(ev) {
        ev.preventDefault();
        const downloadingAlert = Swal.fire({
            title: 'Descargando...',
            text: 'La descarga de la actualización está en proceso.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        $.ajax({
            url: 'getActualizacion/',
            type: 'post',
            dataType: 'json',
            data: {

                file: $(".selVersion option:selected").attr('value'),
                _token: $("input[name='_token']").val()
            },
            success: function(data, textStatus, jqXHR) {
                // console.log(data)
                downloadingAlert.close();
                if (data.success) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Descarga exitosa'
                    });
                    // window.location.href = ;
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Error al descargar la actualización'
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


    });
</script>
