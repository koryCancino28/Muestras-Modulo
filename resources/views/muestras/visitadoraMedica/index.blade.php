<div class="d-flex justify-content-center mb-3">
    <a href="{{ route('muestras.create') }}" class="btn btn-primary me-3">
        <i class="bi bi-plus-circle"></i> Agregar Muestra
    </a>
    <input type="text" id="searchInput" class="form-control w-75" placeholder="Buscar por las primeras 5 letras del nombre de la Muestra" onkeyup="filterTable()">
</div>


<div class="table-responsive">
    <table class="table table-hover" id="table_muestras">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre de la Muestra</th>
                <th scope="col">Clasificación</th>
                <th scope="col">Unidad de<br> Medida</th>
                <th scope="col">Tipo de Muestra</th> <!-- Nueva columna -->
                <th scope="col">Cantidad</th>
                <th scope="col">Observaciones</th>
                <th scope="col">Fecha/hora<br>Registrada</th> <!-- Nueva columna para la fecha -->
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($muestras as $index => $muestra)
                <tr id="muestra_{{ $muestra->id }}">
                    <td>{{ $index + 1 }}</td>
                    <td class="observaciones">{{ $muestra->nombre_muestra }}</td>
                    <td>{{ $muestra->clasificacion ? $muestra->clasificacion->nombre_clasificacion : 'Sin clasificación' }}</td>
                    <td>{{ $muestra->unidadDeMedida->nombre_unidad_de_medida ?? 'No asignada' }}</td>
                    <td>{{ $muestra->tipo_muestra ?? 'No asignado' }}</td> <!-- Mostrar el tipo de muestra -->
                    <td>{{ $muestra->cantidad_de_muestra }}</td>
                    <td class="observaciones">{{ $muestra->observacion }}</td>
                    <td>
                    {{ $muestra->updated_at ? $muestra->updated_at->format('Y-m-d') : $muestra->created_at->format('Y-m-d') }} <br>
                    {{ $muestra->updated_at ? $muestra->updated_at->format('H:i:s') : $muestra->created_at->format('H:i:s') }}
                    </td>
                    <td>
                        <ul class="flex_acciones">
                            <li>
                                <a title="Ver detalles de la muestra" href="{{ route('muestras.show', $muestra->id) }}" class="btn btn-success">
                                    <i class="bi bi-binoculars"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('muestras.edit', $muestra->id) }}" class="btn btn-primary">
                                    <i class="bi bi-pencil-square"></i>   
                                </a>
                            </li>
                            <li>
                                <form action="{{ route('muestras.destroy', $muestra->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Desea eliminar esta muestra?');">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
    function filterTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toUpperCase();
        const table = document.getElementById('table_muestras');
        const tr = table.getElementsByTagName('tr');
        
        for (let i = 1; i < tr.length; i++) {
            const td = tr[i].getElementsByTagName('td')[1]; // Nombre de la muestra está en la columna 2 (índice 1)
            if (td) {
                const txtValue = td.textContent || td.innerText;
                // Compara las primeras 5 letras del nombre con el filtro
                if (txtValue.substring(0, 5).toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>

</div>
