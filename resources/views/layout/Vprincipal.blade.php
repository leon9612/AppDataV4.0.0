@include('layout.heder')

<main id="main">
    <section id="visor" class="contact">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="section-title text-center mb-5">
                        <h2 class="fw-bold">Módulo Principal</h2>
                        <div class="border-top border-primary w-25 mx-auto my-3"></div>
                        <p class="lead">Software AppData</p>
                    </div>

                    <div class="card shadow-lg mb-5">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">Descripción del Sistema</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <h6 class="alert-heading"><i class="bi bi-info-circle-fill"></i> Funcionalidad Principal</h6>
                                        <p class="mb-0">Solución especializada con encriptación de datos avanzada para evitar generación de alertas en sistemas TECMMAS y SICOV.</p>
                                    </div>
                                    
                                    <div class="alert alert-warning mt-3">
                                        <h6 class="alert-heading"><i class="bi bi-exclamation-triangle-fill"></i> Consideraciones para el envio de las pruebas</h6>
                                        <p class="mb-0">Es crítico monitorear el tiempo de duración de la prueba posterior a la creación del evento inicial.</p>
                                    </div>
                                    
                                    <div class="alert alert-danger mt-3">
                                        <h6 class="alert-heading"><i class="bi bi-shield-lock-fill"></i> Confidencialidad</h6>
                                        <p class="mb-0">Software de uso estrictamente restringido. Requiere discreción absoluta durante su operación y manejo.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-header bg-success text-white">
                                    <h5 class="card-title mb-0">Cobertura de Licencia</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><i class="bi bi-check-circle-fill text-success"></i> Actualizaciones periódicas de llaves de encriptación</li>
                                        <li class="list-group-item"><i class="bi bi-check-circle-fill text-success"></i> Soporte continuo del servicio</li>
                                        <li class="list-group-item"><i class="bi bi-check-circle-fill text-success"></i> Mejoras y actualizaciones funcionales</li>
                                        <li class="list-group-item"><i class="bi bi-calendar-check-fill text-primary"></i> Vigencia: 1 año a partir de la fecha de instalación</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-header bg-info text-white">
                                    <h5 class="card-title mb-0">Soporte Técnico</h5>
                                </div>
                                <div class="card-body">
                                    <p><i class="bi bi-headset"></i> Asistencia continua para resolución de fallas o ajustes requeridos</p>
                                    
                                    <div class="mt-4">
                                        <h6><i class="bi bi-gear-fill"></i> Requisitos de Instalación</h6>
                                        <ul>
                                            <li>Equipo dedicado (PC) sin acceso a personal no autorizado</li>
                                            <li>Instalación obligatoria de <a href="https://rustdesk.com/es/" target="_blank">RustDesk</a> para gestión remota de soporte</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mt-4">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="card-title mb-0">Proceso de Implementación</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Enviar:</h6>
                                    <ul class="list-group">
                                        <li class="list-group-item">Nombre del sistema SICOV</li>
                                        <li class="list-group-item">Software de operación utilizado (incluyendo versión)</li>
                                    </ul>
                                </div>
                                <div class="col-md-6 mt-3 mt-md-0">
                                    <h6>Contacto:</h6>
                                    <div class="list-group">
                                        <a href="mailto:ingenierodesoftware0507@gmail.com" class="list-group-item list-group-item-action">
                                            <i class="bi bi-envelope-fill"></i> ingenierodesoftware0507@gmail.com
                                        </a>
                                        <a href="https://wa.me/573232517006" class="list-group-item list-group-item-action">
                                            <i class="bi bi-whatsapp"></i> +57 322 9065874 (WhatsApp/Telegram)
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-warning mt-4">
                                <h6 class="alert-heading"><i class="bi bi-exclamation-diamond-fill"></i> Nota Importante</h6>
                                <p class="mb-0">En caso de detección o bloqueo del software por mal manejo operativo, el reprocesamiento de llaves de encriptación y reprogramación de formularios generará costos adicionales.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@include('layout.footer')

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
<style>
    .card {
        border-radius: 10px;
        transition: transform 0.3s;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .list-group-item {
        padding: 0.75rem 1.25rem;
    }
    .alert {
        border-left: 5px solid;
    }
</style>