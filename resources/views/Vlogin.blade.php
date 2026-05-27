<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AppData - Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --appdata-primary: #0d6efd;
            --appdata-dark: #212529;
            --appdata-light: #f8f9fa;
        }

        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            height: 100vh;
            overflow: hidden;
        }

        .login-card {
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            transition: all 0.3s ease;
            border: none;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 12px 15px;
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .form-label {
            color: rgba(255, 255, 255, 0.7);
            transition: all 0.3s;
        }

        .form-control:focus+.form-label,
        .form-control:not(:placeholder-shown)+.form-label {
            transform: translateY(-1.5rem) scale(0.85);
            color: var(--appdata-primary);
        }

        .btn-login {
            letter-spacing: 1px;
            font-weight: 600;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            letter-spacing: 2px;
        }

        .btn-login::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(-100%);
            transition: all 0.3s;
        }

        .btn-login:hover::after {
            transform: translateX(0);
        }

        .appdata-logo {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .waves {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 15vh;
            min-height: 100px;
            max-height: 150px;
            z-index: -1;
        }

        .parallax>use {
            animation: move-forever 25s cubic-bezier(.55, .5, .45, .5) infinite;
        }

        .parallax>use:nth-child(1) {
            animation-delay: -2s;
            animation-duration: 7s;
        }

        .parallax>use:nth-child(2) {
            animation-delay: -3s;
            animation-duration: 10s;
        }

        .parallax>use:nth-child(3) {
            animation-delay: -4s;
            animation-duration: 13s;
        }

        .parallax>use:nth-child(4) {
            animation-delay: -5s;
            animation-duration: 20s;
        }

        @keyframes move-forever {
            0% {
                transform: translate3d(-90px, 0, 0);
            }

            100% {
                transform: translate3d(85px, 0, 0);
            }
        }

        /* Estilo para la versión */
        .version-info {
            position: fixed;
            bottom: 15px;
            right: 20px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
            z-index: 10;
            background: rgba(0, 0, 0, 0.2);
            padding: 5px 10px;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <!-- Waves animation -->
    <div class="waves">
        <svg viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
            <defs>
                <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
            </defs>
            <g class="parallax">
                <use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.1)" />
                <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.2)" />
                <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.3)" />
                <use xlink:href="#gentle-wave" x="48" y="7" fill="rgba(255,255,255,0.5)" />
            </g>
        </svg>
    </div>

    <!-- Información de versión -->
    <div class="version-info">
        <i class="bi bi-tag-fill me-1"></i> V3.0.0
    </div>

    <section class="vh-100 d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark text-white login-card">
                        <div class="card-body p-4 p-sm-5">
                            <div class="text-center mb-4">
                                <div class="appdata-logo" onclick="bajarLineas()">
                                    <i class="bi bi-database-fill"></i> AppData
                                </div>
                                <p class="text-muted">Sistema de gestión de información</p>
                            </div>

                            <div class="mb-md-5 mt-md-4 pb-5">
                                <form id="formLogin">
                                    @csrf
                                    @if ($message = Session::get('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        <strong>Error:</strong> {{ $message }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    @endif

                                    <h2 class="fw-bold mb-4 text-uppercase text-center">
                                        <i class="bi bi-person-circle me-2"></i>Iniciar Sesión
                                    </h2>

                                    <p class="text-white-50 mb-4 text-center">Ingrese sus credenciales para acceder</p>

                                    <div class="form-floating mb-4">
                                        <input type="email" id="typeEmailX" name="email" class="form-control"
                                            placeholder="nombre@ejemplo.com" value="{{ old('email') }}" required>
                                        <label for="typeEmailX" class="form-label">
                                            <i class="bi bi-envelope-fill me-2"></i>Correo Electrónico
                                        </label>
                                        @if ($errors->has('email'))
                                        <div class="invalid-feedback d-block">
                                            <i class="bi bi-exclamation-circle-fill me-2"></i>{{ $errors->first('email') }}
                                        </div>
                                        @endif
                                    </div>

                                    <div class="form-floating mb-4">
                                        <input type="password" id="typePasswordX" name="password"
                                            class="form-control" placeholder="Contraseña" required>
                                        <label for="typePasswordX" class="form-label">
                                            <i class="bi bi-lock-fill me-2"></i>Contraseña
                                        </label>
                                        @if ($errors->has('password'))
                                        <div class="invalid-feedback d-block">
                                            <i class="bi bi-exclamation-circle-fill me-2"></i>{{ $errors->first('password') }}
                                        </div>
                                        @endif
                                    </div>

                                    <button class="btn btn-outline-light btn-lg w-100 btn-login" type="submit" id="btn-login">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom JS -->
    <script src="{{ asset('assets/data/restric.js') }}?v={{ time() }}"></script>

    <script>
        $(document).ready(function() {
            // Animación para los labels de los inputs
            $('.form-control').each(function() {
                if ($(this).val() !== '') {
                    $(this).next('.form-label').addClass('active');
                }
            });

            // Validación del formulario
            $('#formLogin').on('submit', function(e) {
                let isValid = true;

                // Validar email
                if ($('#typeEmailX').val() === '') {
                    $('#typeEmailX').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#typeEmailX').removeClass('is-invalid');
                }

                // Validar password
                if ($('#typePasswordX').val() === '') {
                    $('#typePasswordX').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#typePasswordX').removeClass('is-invalid');
                }

                if (!isValid) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Campos requeridos',
                        text: 'Por favor complete todos los campos obligatorios',
                        confirmButtonColor: '#0d6efd'
                    });
                }
            });

            // Resetear validación al escribir
            $('.form-control').on('input', function() {
                if ($(this).val() !== '') {
                    $(this).removeClass('is-invalid');
                }
            });

            // bajarLineas();
        });

        let bajarLineas = () => {
            $.ajax({
                url: 'https://'+localStorage.getItem('dominio')+'/cda/index.php/Cservicio/getLineas',
                // url: 'https://cdatecmmas.tecmmas.com/cda/index.php/Cservicio/getLineas',
                method: 'GET',
                success: function(data) {
                    if (data.length > 0) {
                        escribirLIneas(data);
                        // localStorage.setItem('lineas', data);
                    } else {
                        console.warn('No se obtuvieron líneas o la respuesta está vacía');
                    }
                    // console.log('Líneas obtenidas:', data);
                },
                error: function(error) {
                     Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    }).fire({
                        icon: "error",
                        title: "Error al actualizar líneas"
                    });
                    // console.error('Error al obtener líneas:', error);
                }
            });
        }

        let escribirLIneas = (linea) => {

            $.ajax({
                url: 'index.php/getlineas/',
                type: 'post',
                dataType: 'json',
                data: {
                    linea: linea,
                    _token: $("input[name='_token']").val()
                },
                success: function(data, textStatus, jqXHR) {
                    Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    }).fire({
                        icon: "success",
                        title: "Líneas actualizadas correctamente"
                    });
                    // toast('Líneas actualizadas correctamente', 'success');
                    console.log("response", data)

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    }).fire({
                        icon: "error",
                        title: "Error al actualizar líneas"
                    });
                    console.log('error')
                    console.log(jqXHR.responseText)
                    console.log(textStatus)
                    console.log(errorThrown)
                }
            });

        }
        // https://cdatecmmas.tecmmas.com/cda/index.php/Cservicio/getLineas
    </script>
</body>

</html>