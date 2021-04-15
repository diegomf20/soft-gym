<table>
    <thead>
        <tr>
            <th>Código</th>
            <th>Descripción</th>
            <th>Marca</th>
            <th>Precio</th>
            <th>Stock</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($productos as $key=>$producto)
            <tr>
                <td>{{ $producto->codigo }}</td>
                <td>{{ $producto->descripcion }}</td>
                <td>{{ $producto->marca }}</td>
                <td>{{ $producto->precio }}</td>
                <td>{{ $producto->stock }}</td>
            </tr>
        @endforeach
    </tbody>
</table>