@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Listado de Proveedores</h1>
    <div class="mb-3">
        <a href="{{ route('proveedores.create') }}" class="btn btn_crear"><i class="fa-solid fa-square-plus"></i>Nuevo Proveedor</a>
    </div>

    <table class="table table-striped table-responsive">
        <thead>
            <tr>
                <th>Razón Social</th>
                <th>RUC</th>
                <th>Teléfono</th>
                <th>Correo CPE</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($proveedores as $proveedor)
            <tr>
                <td class="observaciones">{{ $proveedor->razon_social }}</td>
                <td>{{ $proveedor->ruc }}</td>
                <td>{{ $proveedor->telefono_1 }}</td>
                <td>{{ $proveedor->correo_cpe }}</td>
                <td>
                    <span class="badge bg-{{ $proveedor->estado == 'activo' ? 'success' : 'secondary' }}">
                        {{ ucfirst($proveedor->estado) }}
                    </span>
                </td>
                <td>
                    <div class="w">
                        <a href="{{ route('proveedores.show', $proveedor->id) }}" class="btn btn-info btn-sm" style="background-color: #17a2b8; border-color: #17a2b8; color: white;"><i class="fa-regular fa-eye"></i>Ver</a>
                        <a href="{{ route('proveedores.edit', $proveedor->id) }}" class="btn btn-warning btn-sm" style="background-color: #ffc107; border-color: #ffc107; color: white;"><i class="fa-solid fa-pen"></i>Editar</a>
                        <form action="{{ route('proveedores.destroy', $proveedor->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro que deseas eliminar este proveedor?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i>Eliminar</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $proveedores->links() }}
</div>
 <style>
        .btn-sm {
        font-size: 1rem; 
        padding: 8px 14px; 
        border-radius: 8px;
        display: flex; 
        align-items: center; 
        }

        .btn-sm i {
            margin-right: 4px; /* Espaciado entre el icono y el texto */
        }
         .btn_crear i {
            margin-right: 4px; /* Espaciado entre el icono y el texto */
        }
        .w {
            display: flex;
            justify-content: center;
            gap: 5px;
        }

        table thead th {
            background-color: #fe495f;
            color: white;
        }

        table tbody td {
            background-color: rgb(255, 249, 249);
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }

        .table-bordered {
            border-color: #fe495f;
        }
        table th, table td {
            text-align: center;
        }
        td {
            width: 1%;  
            white-space: nowrap; 
        }
    </style>
@endsection