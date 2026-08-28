<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        h1 { font-size: 14px; margin-bottom: 2px; }
        p { margin-top: 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 4px 6px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    @if ($isMultiProduct)
        <h1>Lotes &mdash; Todos los productos</h1>
        <p>Bodega: {{ $warehouseName }}</p>
    @else
        <h1>Lotes de {{ $productName }}</h1>
        <p>Código: {{ $productId }} &middot; Bodega: {{ $warehouseName }}</p>
    @endif
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Producto</th>
                <th>#</th>
                <th>Lote</th>
                <th>Ubicación</th>
                <th>Cantidad</th>
                <th>Fecha Caducidad</th>
                <th>Días Restantes</th>
                <th>Obsolescencia</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $row['productCode'] }}</td>
                    <td>{{ $row['productName'] }}</td>
                    <td>{{ $row['number'] }}</td>
                    <td>{{ $row['lotNumber'] }}</td>
                    <td>{{ $row['location'] }}</td>
                    <td>{{ $row['quantity'] }}</td>
                    <td>{{ $row['expirationDate'] }}</td>
                    <td>{{ $row['remainingDaysLabel'] }}</td>
                    <td>{{ $row['obsolescence'] }}</td>
                    <td>{{ $row['status'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">Sin lotes registrados</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>