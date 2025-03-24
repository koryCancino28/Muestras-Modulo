<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aprobacion Jefe Comercial</title>
    <link rel="shortcut icon" href="{{ asset('imgs/favicon.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
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
                            <td>{{ $muestra->nombre_muestra }}</td>
                            <td>{{ $muestra->clasificacion ? $muestra->clasificacion->nombre_clasificacion : 'Sin clasificación' }}</td>
                            <td>{{ $muestra->tipo_muestra ?? 'No asignado' }}</td> <!-- Mostrar el tipo de muestra -->
                            <td>{{ $muestra->unidadDeMedida->nombre_unidad_de_medida }}</td>
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
            $(document).ready(function() {
        // Deshabilitar siempre los checkboxes de la coordinadora
        $(".aprobado_coordinadora").prop("disabled", true);

        // Mensaje cuando se pasa el mouse sobre la columna de la coordinadora
        $('.aprobado_coordinadora').closest('td').hover(function() {
            var checkbox = $(this).find('.aprobado_coordinadora');
            if (checkbox.prop('disabled')) {
                $(this).attr('title', '⚠ Solo la coordinadora puede marcar');
            } else {
                $(this).removeAttr('title');
            }
        });

        // Evento para manejar cambios en los checkboxes
        $("input[type='checkbox']").on("change", function() {
            var id = $(this).data("id");
            var value = $(this).is(":checked") ? 1 : 0;

            // Enviar actualización por AJAX (solo si es el jefe comercial)
            if ($(this).hasClass("aprobacion-jefe")) {
                $.ajax({
                    url: "/muestras/" + id + "/actualizar-aprobacion",
                    type: "PUT",
                    data: { _token: "{{ csrf_token() }}", field: "aprobado_jefe_comercial", value: value },
                    success: function(response) {
                        console.log(response.message);
                    },
                    error: function() {
                        alert("Error al actualizar la aprobación.");
                    }
                });
            }
        });
    });

              // Configuración de Pusher-----------------------------
Pusher.logToConsole = true;

var pusher = new Pusher('f0c10c06466015ef4767', {
    cluster: 'us2'
});

var channel = pusher.subscribe('muestras');

// Función para guardar y mostrar notificaciones persistentes
function showPersistentNotification(type, title, message) {
    // Obtener notificaciones existentes o crear un array vacío
    var notifications = JSON.parse(localStorage.getItem('persistentNotifications') || '[]');
    
    // Crear nueva notificación con timestamp
    var newNotification = {
        id: Date.now(), // ID único basado en timestamp
        type: type,
        title: title,
        message: message,
        timestamp: new Date().toLocaleString()
    };
    
    // Agregar la nueva notificación al array
    notifications.push(newNotification);
    
    // Guardar en localStorage
    localStorage.setItem('persistentNotifications', JSON.stringify(notifications));
    
    // Mostrar la notificación actual
    showToastrNotification(newNotification);
}

// Función para mostrar notificación con toastr
function showToastrNotification(notification) {
    toastr[notification.type](notification.message, notification.title, {
        closeButton: true,
        progressBar: true,
        timeOut: 0, // No desaparecer automáticamente
        extendedTimeOut: 0,
        positionClass: 'toast-top-right',
        enableHtml: true,
        onHidden: function() {
            // Cuando el usuario cierra la notificación, eliminarla del almacenamiento
            removeNotification(notification.id);
        }
    });
}

// Función para eliminar una notificación
function removeNotification(id) {
    var notifications = JSON.parse(localStorage.getItem('persistentNotifications') || '[]');
    notifications = notifications.filter(notif => notif.id !== id);
    localStorage.setItem('persistentNotifications', JSON.stringify(notifications));
}

// Escuchar el evento cuando se crea una nueva muestra
channel.bind('muestra.creada', function(data) {
    console.log('Nueva muestra creada:', data);
    var muestra = data.muestra;
    
    // Buscar la última fila visible de la tabla
    var lastRow = $('#table_muestras tbody tr').last();
    var nuevaFilaIndex = lastRow.length > 0 ? parseInt(lastRow.find('td:first').text()) + 1 : 1;
    
    // Crear y mostrar notificación persistente
    showPersistentNotification(
        'success', 
        'Se ha creado una nueva Muestra', 
        `<strong>Muestra #${nuevaFilaIndex} Creada:</strong> ${muestra.nombre_muestra} <br> <strong>Fecha:</strong> ${muestra.fecha_creacion}`
    );
    
    // Redirigir después de 1 segundo
    setTimeout(function() {
        window.location.href = '/muestras/jefe-comercial';
    }, 1000);
});

// Escuchar el evento cuando una muestra es actualizada
channel.bind('muestra.actualizada', function(data) {
    console.log('Muestra actualizada:', data);
    var muestra = data.muestra;
    var row = $('#muestra_' + muestra.id);
    
    if (row.length > 0) {
        var index = $('#table_muestras tbody tr').index(row) + 1;
        var fechaActualizacion = new Date(muestra.fecha_actualizacion).toLocaleString();
        
        // Crear y mostrar notificación persistente
        showPersistentNotification(
            'info', 
            'Se ha actualizado una Muestra', 
            `<strong>Muestra #${index} Actualizada:</strong> ${muestra.nombre_muestra} <br> <strong>Fecha de actualización:</strong> ${fechaActualizacion}`
        );
    }
    
    // Redirigir después de 1 segundo
    setTimeout(function() {
        window.location.href = '/muestras/jefe-comercial';
    }, 1000);
});

$(document).ready(function() {
    // Cargar y mostrar todas las notificaciones persistentes al cargar la página
    var notifications = JSON.parse(localStorage.getItem('persistentNotifications') || '[]');
    
    notifications.forEach(function(notification) {
        showToastrNotification(notification);
    });
});
        </script>
</html>
