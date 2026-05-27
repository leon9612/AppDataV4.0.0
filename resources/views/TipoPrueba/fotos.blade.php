<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Editor de Imágenes</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
        }

        .editor-container {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }

        .canvas-section {
            flex: 1;
            min-width: 300px;
        }

        .controls-section {
            flex: 1;
            min-width: 300px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }

        .canvas-container {
            position: relative;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 15px;
        }

        canvas {
            display: block;
            max-width: 100%;
            height: auto;
            background-color: #f0f0f0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"] {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input:focus {
            border-color: #3498db;
            outline: none;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        button {
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary {
            background-color: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-secondary {
            background-color: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
        }

        .btn-success {
            background-color: #2ecc71;
            color: white;
        }

        .btn-success:hover {
            background-color: #27ae60;
        }

        .btn-small {
            padding: 8px 12px;
            font-size: 14px;
        }

        .base64-section {
            margin-top: 30px;
        }

        .btn-atras {
            background-color: #f39c12;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-atras:hover {
            background-color: #e67e22;
        }

        textarea {
            width: 100%;
            height: 150px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: monospace;
            font-size: 14px;
            resize: vertical;
        }

        .status-message {
            padding: 10px 15px;
            border-radius: 4px;
            margin-top: 15px;
            display: none;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-left-color: #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        .table-container {
            overflow-x: auto;
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: 600;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 768px) {
            .editor-container {
                flex-direction: column;
            }

            .table-container {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header-container">
            <h1>Editor de Imágenes</h1>
            <button id="btnAtras" class="btn-atras">Regresar</button>
        </div>
        <br>

        <div class="editor-container">
            <div class="canvas-section">
                <div class="canvas-container">
                    <canvas id="cFoto1" width="650" height="490"></canvas>
                </div>

                <div class="loading" id="loading">
                    <div class="spinner"></div>
                    <p>Cargando imagen...</p>
                </div>

                <div class="base64-section">
                    <div class="form-group">
                        <label for="base64Output">Base64 de la imagen procesada:</label>
                        <textarea id="base64Output" readonly></textarea>
                    </div>
                    <button id="copyBase64" class="btn-secondary">Copiar Base64</button>
                </div>
            </div>

            <div class="controls-section">
                <div class="form-group">
                    <label for="idhojapruebas">ID Control:</label>
                    <input type="text" id="idhojapruebas" placeholder="Ingrese el ID control" value="575471">
                </div>

                <div class="form-group">
                    <label>Información de Imágenes:</label>
                    <div class="table-container">
                        <table id="tableInfoImagen">
                            <thead>
                                <tr>
                                    <th>ID Hoja Pruebas</th>
                                    <th>ID Prueba</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="bodytableInfoImagen">
                                <!-- Las filas se agregarán aquí dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="button-group">
                    <button id="cargarImagen" class="btn-primary">Buscar Imágenes</button>
                </div>
            </div>

            <div class="controls-section">
                <div class="form-group">
                    <label for="fecha">Fecha:</label>
                    <input type="date" id="fecha">
                </div>

                <div class="form-group">
                    <label for="hora">Hora:</label>
                    <input type="time" id="hora" pattern="[0-9]{2}:[0-9]{2}">
                </div>

                <div class="form-group">
                    <label for="placa">Número de Placa:</label>
                    <input type="text" id="placa" value="AAA001">
                </div>

                <div class="button-group">
                    <button id="actualizarCanvas" class="btn-success">Actualizar Canvas</button>
                </div>

                <div class="form-group">
                    <label for="base64Input">O ingresar Base64 manualmente:</label>
                    <textarea id="base64Input" placeholder="Pegue aquí el Base64 de la imagen"></textarea>
                </div>
                <button id="cargarBase64" class="btn-secondary">Cargar desde Base64</button>

                <div id="statusMessage" class="status-message"></div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // ===== VARIABLES GLOBALES =====
        let canvas, context, base64Output, base64Input, fechaInput, horaInput;
        let placaInput, idpruebaInput, loading, statusMessage;
        let imagenOriginal = null;

        // ===== INICIALIZACIÓN =====
        function inicializarApp() {
            // Obtener elementos del DOM
            canvas = document.getElementById('cFoto1');
            context = canvas.getContext('2d');
            base64Output = document.getElementById('base64Output');
            base64Input = document.getElementById('base64Input');
            fechaInput = document.getElementById('fecha');
            horaInput = document.getElementById('hora');
            placaInput = document.getElementById('placa');
            idpruebaInput = document.getElementById('idhojapruebas');
            loading = document.getElementById('loading');
            statusMessage = document.getElementById('statusMessage');

            // Configurar fecha actual como predeterminada
            const now = new Date();
            fechaInput.value = now.toISOString().split('T')[0];
            //fechaInput.value = "2025-10-31";
            horaInput.value = now.toTimeString().slice(0, 5);



            // Configurar AJAX para incluir el token CSRF
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Asignar event listeners
            document.getElementById('cargarImagen').addEventListener('click', cargarImagenesDesdeServidor);
            document.getElementById('cargarBase64').addEventListener('click', cargarDesdeBase64Manual);
            document.getElementById('actualizarCanvas').addEventListener('click', actualizarCanvas);
            document.getElementById('copyBase64').addEventListener('click', copiarBase64);
        }

        // ===== FUNCIONES DE UTILIDAD =====
        function mostrarMensaje(mensaje, tipo) {
            statusMessage.textContent = mensaje;
            statusMessage.className = 'status-message ' + tipo;
            statusMessage.style.display = 'block';

            setTimeout(() => {
                statusMessage.style.display = 'none';
            }, 5000);
        }

        function mostrarLoading(mostrar) {
            loading.style.display = mostrar ? 'block' : 'none';
        }

        // ===== FUNCIONES DE MANEJO DE IMÁGENES =====
        function procesarImagen(base64Image) {
            mostrarLoading(true);

            const imagen = new Image();
            imagen.src = base64Image;

            imagen.onload = function() {
                // Guardar la imagen original
                imagenOriginal = base64Image;

                // Limpiar canvas completamente
                context.clearRect(0, 0, canvas.width, canvas.height);

                // EXACTAMENTE COMO EL ORIGINAL:
                context.font = "22px Arial";
                context.drawImage(imagen, 0, 0, 650, 490);

                // Dibujar rectángulo negro (MÁS OPACO para cubrir texto anterior)
                context.fillStyle = 'rgba(0, 0, 0, 0.95)'; // Más opaco para cubrir mejor
                context.fillRect(5, 445, 325, 33);

                // Dibujar texto blanco (EXACTAMENTE como el original)
                context.strokeStyle = "#FFFFFF";
                const fecha = fechaInput.value;
                const hora = horaInput.value;
                const placa = placaInput.value;
                context.strokeText(fecha + ", " + placa + " " + hora, 10, 469);
                context.strokeStyle = "#000000";

                // Obtener el base64 actualizado
                const imagenProcesada = canvas.toDataURL('image/jpeg', 0.6);
                base64Output.value = imagenProcesada;

                mostrarLoading(false);
                mostrarMensaje('Imagen procesada correctamente', 'success');
            };

            imagen.onerror = function() {
                mostrarLoading(false);
                mostrarMensaje('Error al cargar la imagen', 'error');
            };
        }

        // ===== FUNCIONES DE CARGA DE DATOS =====
        function cargarImagenesDesdeServidor() {
            const idprueba = idpruebaInput.value.trim();
            console.log('ID Prueba ingresado:', idprueba);

            if (!idprueba) {
                mostrarMensaje('Por favor ingrese un ID de prueba', 'error');
                return;
            }

            mostrarLoading(true);
            // Limpiar tabla antes de agregar nuevos datos
            document.getElementById('bodytableInfoImagen').innerHTML = '';

            $.ajax({
                type: 'POST',
                url: "consultarImagen/",
                mimeType: 'application/json',
                data: {
                    idprueba: idprueba
                },
                success: function(data, textStatus, jqXHR) {

                    if (data && data.length > 0) {
                        console.log('Respuesta del servidor:', data);
                        data.forEach(function(item) {
                            agregarFilaTabla(item);
                        });
                    } else {
                        mostrarMensaje('No se encontraron imágenes para el ID proporcionado', 'error');
                    }

                    mostrarLoading(false);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    mostrarLoading(false);
                    console.error('Error AJAX:', jqXHR.responseText);
                    mostrarMensaje('Error al obtener las imágenes desde el servidor', 'error');
                }
            });
        }

        function agregarFilaTabla(item) {
            const tbody = document.getElementById('bodytableInfoImagen');
            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td>${item.idhojapruebas || 'N/A'}</td>
                <td>${item.idprueba || 'N/A'}</td>
                <td>
                    <button class="btn-primary btn-small" onclick="cargarImagenDesdeTabla('${(item.imagen || '').replace(/'/g, "\\'")}')">
                        Cargar Foto
                    </button>
                </td>
            `;

            tbody.appendChild(tr);
        }

        function cargarImagenDesdeTabla(base64Image) {
            procesarImagen(base64Image);
        }

        // ===== FUNCIONES DE INTERACCIÓN =====
        function cargarDesdeBase64Manual() {
            const base64 = base64Input.value.trim();
            if (base64) {
                procesarImagen(base64);
            } else {
                mostrarMensaje('Por favor ingrese un Base64 válido', 'error');
            }
        }

        function actualizarCanvas() {
            if (imagenOriginal) {
                // Volver a procesar la imagen original con los nuevos datos
                const imagen = new Image();
                imagen.src = imagenOriginal;

                imagen.onload = function() {
                    // Limpiar canvas completamente
                    context.clearRect(0, 0, canvas.width, canvas.height);

                    // Aplicar el mismo proceso exacto
                    context.font = "22px Arial";
                    context.drawImage(imagen, 0, 0, 650, 490);

                    // Dibujar rectángulo negro (MÁS OPACO)
                    context.fillStyle = 'rgba(0, 0, 0, 0.95)';
                    context.fillRect(5, 445, 325, 33);

                    // Dibujar texto blanco
                    context.strokeStyle = "#FFFFFF";
                    const fecha = fechaInput.value;
                    const hora = horaInput.value;
                    const placa = placaInput.value;
                    context.strokeText(fecha + ", " + placa + " " + hora, 10, 469);
                    context.strokeStyle = "#000000";

                    // Obtener el base64 actualizado
                    const imagenProcesada = canvas.toDataURL('image/jpeg', 0.6);
                    base64Output.value = imagenProcesada;

                    mostrarMensaje('Canvas actualizado correctamente', 'success');
                };
            } else {
                mostrarMensaje('No hay imagen para actualizar', 'error');
            }
        }

        function copiarBase64() {
            base64Output.select();
            document.execCommand('copy');
            mostrarMensaje('Base64 copiado al portapapeles', 'success');
        }

        // ===== INICIALIZAR LA APLICACIÓN =====
        document.addEventListener('DOMContentLoaded', inicializarApp);


        $('#btnAtras').on('click', function(ev) {
            ev.preventDefault();
            window.location.href = "{{ url('/cpr') }}";
        });
    </script>
</body>

</html>