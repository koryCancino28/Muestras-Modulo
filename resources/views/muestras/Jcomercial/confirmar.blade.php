<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aprobacion Jefe Comercial</title>
    <link rel="shortcut icon" href="{{ asset('imgs/favicon.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('css/muestras/aprobacion.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</head>

<body>
    <h1 class="text-center mt-5 mb-5 fw-bold"></h1>
    
    <div class="container">
        @section('title')
            | Laboratorio - Muestras
        @endsection

        <h1 class="text-center"> Estado de las Muestras<hr></h1>
        <div class="table-responsive">
            <table class="table table-hover" id="table_muestras">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre de la Muestra</th>
                        <th scope="col">Clasificación</th>
                        <th scope="col">Tipo de Muestra</th> <!-- Nueva columna -->
                        <th scope="col" class="th-small">Unidad <br> de Medida</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col" class="th-small">Aprobado por <br> Jefe Comercial</th>
                        <th scope="col" class="th-small">Aprobado por<br> Coordinadora</th>
                        <th scope="col">Observaciones</th>
                        <th scope="col">Fecha/hora Recibida</th>
                        <th scope="col">Acciones</th> <!-- Columna para mostrar el estado con color -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($muestras as $index => $muestra)
                        <tr id="muestra_{{ $muestra->id }}">
                            <td>{{ $index + 1 }}</td>
                            <td class="observaciones">{{ $muestra->nombre_muestra }}</td>
                            <td>{{ $muestra->clasificacion ? $muestra->clasificacion->nombre_clasificacion : 'Sin clasificación' }}</td>
                            <td>{{ $muestra->tipo_muestra ?? 'No asignado' }}</td> <!-- Mostrar el tipo de muestra -->
                            <td>
                                @if($muestra->clasificacion && $muestra->clasificacion->unidadMedida)
                                    {{ $muestra->clasificacion->unidadMedida->nombre_unidad_de_medida }}
                                @else
                                    No asignada
                                @endif
                            </td>
                            <td>{{ $muestra->cantidad_de_muestra }}</td>
                            <td>
                                <input type="checkbox" class="aprobacion-jefe" data-id="{{ $muestra->id }}" {{ $muestra->aprobado_jefe_comercial ? 'checked' : '' }}>
                            </td>  
                            <td>
                                <input type="checkbox" class="aprobado_coordinadora" data-id="{{ $muestra->id }}" {{ $muestra->aprobado_coordinadora ? 'checked' : '' }}>
                            </td>
                            <td class="observaciones">{{ $muestra->observacion }}</td>
                            <td>
                            {{ $muestra->updated_at ? $muestra->updated_at->format('Y-m-d') : $muestra->created_at->format('Y-m-d') }} <br>
                            {{ $muestra->updated_at ? $muestra->updated_at->format('H:i:s') : $muestra->created_at->format('H:i:s') }}</td>
                            <td>
                                <!-- Cambiar la lógica para mostrar el estado con color -->
                                <span class="badge" 
                                    style="background-color: {{ $muestra->estado == 'Pendiente' ? 'red' : 'green' }}; color: white; padding: 5px;">
                                    {{ $muestra->estado }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</body>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
                // Función para configurar los checkboxes de coordinadora
            function setupCoordinadoraCheckboxes() {
                // Deshabilitar siempre los checkboxes de la coordinadora
                $(".aprobado_coordinadora").prop("disabled", true);

                // Mensaje hover para coordinadora
                $('.aprobado_coordinadora').closest('td').hover(function() {
                    var checkbox = $(this).find('.aprobado_coordinadora');
                    $(this).attr('title', checkbox.prop('disabled') ? '⚠ Solo la coordinadora puede marcar' : '');
                });
            }

            // Función para manejar cambios en checkboxes
            function setupCheckboxChanges() {
                $("input[type='checkbox']").off('change').on("change", function() {
                    var id = $(this).data("id");
                    var value = $(this).is(":checked") ? 1 : 0;

                    if ($(this).hasClass("aprobacion-jefe")) {
                        $.ajax({
                            url: "/muestras/" + id + "/actualizar-aprobacion",
                            type: "PUT",
                            data: { 
                                _token: "{{ csrf_token() }}", 
                                field: "aprobado_jefe_comercial", 
                                value: value 
                            },
                            success: function(response) {
                                console.log(response.message);
                            },
                            error: function(xhr) {
                                console.error("Error al actualizar:", xhr.responseText);
                                toastr.error("Error al actualizar la aprobación");
                                // Revertir el cambio en caso de error
                                $(this).prop('checked', !$(this).prop('checked'));
                            }
                        });
                    }
                });
            }

                        // Configuración de Pusher
            Pusher.logToConsole = true;
            var pusher = new Pusher('260bec4d6a6754941503', { cluster: 'us2' });
            var channel = pusher.subscribe('muestras');

            // Configuración de notificaciones
            var MAX_NOTIFICATIONS = 4;
            var STORAGE_KEY = 'persistentNotificationsQueue';

            // Función para actualizar la tabla via AJAX
            function refreshTable() {
                $.ajax({
                    url: window.location.href,
                    type: 'GET',
                    success: function(data) {
                        var newTable = $(data).find('#table_muestras').html();
                        $('#table_muestras').html(newTable);
                        
                        // Adjuntar manejadores de eventos después de refrescar
                        attachEventHandlers();

                    }
                });
            }

            // Función para adjuntar los manejadores de eventos
            function attachEventHandlers() {
                setupCoordinadoraCheckboxes();
                setupCheckboxChanges();
            }
            // Función para manejar la cola de notificaciones
            function manageNotificationQueue(type, title, message) {
                // Obtener cola actual de localStorage
                var notificationsQueue = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
                
                // Crear ID único para la notificación
                var notificationId = type + '-' + title + '-' + message;
                
                // Verificar si ya existe en la cola
                var exists = notificationsQueue.some(n => n.id === notificationId);
                if (exists) return;
                
                // Agregar nueva notificación
                notificationsQueue.push({
                    id: notificationId,
                    type: type,
                    title: title,
                    message: message,
                    timestamp: new Date().getTime()
                });
                
                // Limpiar notificaciones antiguas si excedemos el máximo
                if (notificationsQueue.length > MAX_NOTIFICATIONS) {
                    // Eliminar la más antigua (FIFO)
                    notificationsQueue.shift();
                }
                
                // Guardar en localStorage
                localStorage.setItem(STORAGE_KEY, JSON.stringify(notificationsQueue));
                
                // Mostrar todas las notificaciones en cola
                displayNotificationQueue();
            }

            // Función para mostrar la cola de notificaciones
            function displayNotificationQueue() {
                // Limpiar notificaciones actuales
                toastr.clear();
                
                // Obtener cola de notificaciones
                var notificationsQueue = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
                
                // Mostrar cada notificación
                notificationsQueue.forEach(notification => {
                    toastr[notification.type](notification.message, notification.title, {
                        closeButton: true,
                        progressBar: true,
                        timeOut: 0,
                        extendedTimeOut: 0,
                        positionClass: 'toast-top-right',
                        enableHtml: true,
                        onHidden: function() {
                            // Al cerrar una notificación, eliminarla de la cola
                            removeNotificationFromQueue(notification.id);
                        }
                    });
                });
            }


            // Función para eliminar una notificación de la cola
            function removeNotificationFromQueue(notificationId) {
                var notificationsQueue = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
                notificationsQueue = notificationsQueue.filter(n => n.id !== notificationId);
                localStorage.setItem(STORAGE_KEY, JSON.stringify(notificationsQueue));
            }

            // Cargar notificaciones al iniciar
            function loadPersistentNotifications() {
                displayNotificationQueue();
            }

            // Eventos de Pusher
            channel.bind('muestra.creada', function(data) {
                console.log('Nueva muestra creada:', data);
                var muestra = data.muestra;
                
                refreshTable();
                
                setTimeout(function() {
                    var lastRow = $('#table_muestras tbody tr').last();
                    var nuevaFilaIndex = lastRow.length > 0 ? parseInt(lastRow.find('td:first').text()) : 1;
                    
                    manageNotificationQueue(
                        'success', 
                        'Nueva Muestra Creada', 
                        `<strong>Muestra #${nuevaFilaIndex}</strong><br>Nombre: <strong>${muestra.nombre_muestra}</strong><br><small><strong>Fecha de creación:</strong> ${muestra.fecha_creacion}</small>`
                    );
                }, 500);
            });

            channel.bind('muestra.actualizada', function(data) {
                console.log('Muestra actualizada:', data);
                var muestra = data.muestra;
                
                refreshTable();
                
                setTimeout(function() {
                    var row = $('#muestra_' + muestra.id);
                    if (row.length > 0) {
                        var index = $('#table_muestras tbody tr').index(row) + 1;
                        var fechaActualizacion = new Date(muestra.fecha_actualizacion).toLocaleString();
                        
                        manageNotificationQueue(
                            'info', 
                            'Muestra Actualizada', 
                            `<strong>Muestra #${index}</strong><br>Nombre: <strong>${muestra.nombre_muestra}</strong><br><small><strong>Fecha de creación: </strong>${fechaActualizacion}</small>`
                        );
                    }
                }, 500);
            });

            $(document).ready(function() {
                // Limpiar notificaciones existentes al cargar
                toastr.clear();
                
                // Cargar notificaciones persistentes
                loadPersistentNotifications();
                
                // Adjuntar manejadores de eventos
                attachEventHandlers();
                
            });
        </script>
</html>
