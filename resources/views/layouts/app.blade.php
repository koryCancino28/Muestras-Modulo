<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Muestras</title>
<link rel="shortcut icon" href="{{ asset('imgs/favicon.ico') }}" type="image/x-icon" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />

<!-- Bootstrap Icons (Bootstrap 5 oficial) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />

<!-- Font Awesome (opcional, solo si lo usas) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Tus estilos personalizados -->
<link href="{{ asset('css/muestras/home.css') }}" rel="stylesheet" />

<!-- jQuery (requerido para Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap 5 Bundle JS (incluye Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- DataTables CSS y JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
</head>

<body>
    <h1 class="text-center mt-5 mb-5 fw-bold">  </h1>
    
    <div class="container">
       
    @include('messages')
           
            <div class="col-md-12">
                {{-- Si no hay contenido en la sección 'content', se incluirá la lista de empleados por defecto --}}
                @if (empty(trim($__env->yieldContent('content'))))
                <h1 class="text-center"> Muestras Registradas <hr></h1>
                    @include('muestras')
                @else
                    @yield('content')
                @endif

            </div>
        </div>
    </div>
    <h1 class="text-center mt-5 mb-5 fw-bold">  </h1>
</body>

</html>