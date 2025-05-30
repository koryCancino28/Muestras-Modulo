<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aprobacion Coordinadora</title>
    <link rel="shortcut icon" href="{{ asset('imgs/favicon.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('css/muestras/labora.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
</head>

<body>
    <h1 class="text-center mt-5 mb-5 fw-bold"></h1>
    
    <div class="container">
    @include('messages')
        <h1 class="flex-grow-1 text-center">Estado de las Muestras<hr></h1>
        <div class="header-tools">
            <a title="Ver detalles" href="{{ route('muestras.createCO') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Agregar Muestra
            </a>
        </div> 
        <div class="table-responsive">
            <table class="table table-hover" id="table_muestras">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre de la Muestra</th>
                        <th scope="col">Clasificación</th>
                        <th scope="col">Tipo de Muestra</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col" class="th-small">Aprobado<br> J. Comercial</th>
                        <th scope="col" class="th-small">Aprobado<br> Coordinadora</th>
                        <th>Creado por</th>
                        <th>Doctor</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Fecha/hora Entrega</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($muestras as $index => $muestra)
                        <tr id="muestra_{{ $muestra->id }}">
                            <td>{{ $index + 1 }}</td>
                            <td class="observaciones">{{ $muestra->nombre_muestra }}</td>
                            <td>{{ $muestra->clasificacion ? $muestra->clasificacion->nombre_clasificacion : 'Sin clasificación' }}</td>
                            <td>{{ $muestra->tipo_muestra ?? 'No asignado' }}</td>
                            <td>{{ $muestra->cantidad_de_muestra }}</td>
                            <td>
                                <input type="checkbox" class="aprobacion-jefe" disabled {{ $muestra->aprobado_jefe_comercial ? 'checked' : '' }}>
                            </td>
                            <td>
                                <input type="checkbox" class="aprobado-coordinadora" data-id="{{ $muestra->id }}" {{ $muestra->aprobado_coordinadora ? 'checked' : '' }} {{ !$muestra->aprobado_jefe_comercial ? 'disabled' : '' }}>
                            </td>
                            <td>{{ $muestra->creator ? $muestra->creator->name : 'Desconocido' }}</td>
                            <td class="observaciones">{{ $muestra->name_doctor }}</td>
                            <td>
                                <span class="badge" style="background-color: {{ $muestra->estado == 'Pendiente' ? 'red' : 'green' }}; color: white; padding: 5px;">
                                    {{ $muestra->estado }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('muestras.actualizarFechaEntrega', $muestra->id) }}" method="POST" id="fecha_form_{{ $muestra->id }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="datetime-local" name="fecha_hora_entrega" class="form-control"
                                        value="{{ old('fecha_hora_entrega', $muestra->fecha_hora_entrega ? \Carbon\Carbon::parse($muestra->fecha_hora_entrega)->format('Y-m-d\TH:i') : '') }}"
                                        onchange="document.getElementById('fecha_form_{{ $muestra->id }}').submit();"
                                        id="fecha_{{ $muestra->id }}"
                                        min="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}">
                                </form>
                            </td>
                            <td>
                            <div class="d-flex gap-2">
                                <a title="Ver detalles" href="{{ route('muestras.showCo', $muestra->id) }}" class="btn btn-success btn-sm">
                                    <i class="bi bi-binoculars"></i>
                                </a>
                                <a href="{{ route('muestras.editCO', $muestra->id) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-pencil-square"></i>   
                                </a>
                                    <form action="{{ route('muestras.destroyCO', $muestra->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Desea eliminar esta muestra?');">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!!$muestras->appends(request()->except('page'))->links()!!}
        </div>
    </div>
    <h1 class="text-center mt-5 mb-5 fw-bold"></h1>

    <script>
       // Función para manejar la aprobación de coordinadora
function actualizarAprobacion(id, field, value) {
    $.ajax({
        url: '/muestras/' + id + '/actualizar-aprobacion',
        type: 'PUT',
        data: {
            _token: '{{ csrf_token() }}',
            field: field,
            value: value
        },
        success: function(response) {
            console.log(response.message);
        },
        error: function(xhr) {
            alert('Error al actualizar la aprobación.');
        }
    });
}

// Función para manejar el hover y tooltips
function setupCheckboxHover() {
    $('.aprobado-coordinadora').closest('td').hover(function() {
        var checkbox = $(this).find('.aprobado-coordinadora');
        if (checkbox.prop('disabled')) {
            $(this).attr('title', '⚠ Solo se puede marcar si el Jefe Comercial ha aprobado');
        } else {
            $(this).removeAttr('title');
        }
    });

    $('.aprobacion-jefe').closest('td').hover(function() {
        var checkbox = $(this).find('.aprobacion-jefe');
        if (checkbox.prop('disabled')) {
            $(this).attr('title', '⚠ Solo el Jefe Comercial puede marcar');
        } else {
            $(this).removeAttr('title');
        }
    });
}

// Función para manejar el cambio en checkboxes de jefe comercial
function setupJefeCheckboxChange() {
    $('.aprobacion-jefe').off('change').on('change', function() {
        var isChecked = $(this).is(':checked');
        var id = $(this).closest('tr').find('.aprobado-coordinadora').data('id');
        $('.aprobado-coordinadora[data-id="' + id + '"]').prop('disabled', !isChecked);
    });
}

// Función para manejar el click/touch en checkboxes
function setupCheckboxClickHandlers() {
    $('.aprobado-coordinadora, .aprobacion-jefe').off('click touchstart').on('click touchstart', function(e) {
        if ($(this).prop('disabled')) {
            e.preventDefault();
            alert('⚠ ' + ($(this).hasClass('aprobado-coordinadora') 
                ? 'Solo se puede marcar si el Jefe Comercial ha aprobado' 
                : 'Solo el Jefe Comercial puede marcar'));
        }
    });
}
function setupCoordinadoraCheckboxChange() {
    $('.aprobado-coordinadora').off('change').on('change', function() {
        // Si ya está marcado, no permitir desmarcarlo
        if ($(this).is(':checked')) {
            var id = $(this).data('id');
            var value = 1;
            actualizarAprobacion(id, 'aprobado_coordinadora', value);
        } else {
            // Si intenta desmarcar, restaurarlo a marcado
            $(this).prop('checked', true);
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
    setupCheckboxClickHandlers();
    setupJefeCheckboxChange();
    setupCoordinadoraCheckboxChange();
    setupCheckboxHover();
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
</body>

</html>
