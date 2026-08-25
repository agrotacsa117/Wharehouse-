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
        td.blank { height: 22px; }
    </style>
</head>
<body>
    <h1>LISTA DE CONTEO FÍSICO</h1>
    <p>Producto: {{ $productName }} &middot; Código: {{ $productId }} &middot; Bodega: {{ $warehouseName }}</p>
    <p>Generado: {{ now()->format('Y-m-d') }}</p>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Bodega</th>
                <th>Ubicación</th>
                <th>Descripción</th>
                <th>Sistema</th>
                <th>Conteo</th>
                <th>Diferencia</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $row['productCode'] }}</td>
                    <td>{{ $row['warehouseName'] }}</td>
                    <td>{{ $row['location'] }}</td>
                    <td>{{ $row['productName'] }}</td>
                    <td>{{ $row['systemQuantity'] }}</td>
                    <td class="blank">&nbsp;</td>
                    <td class="blank">&nbsp;</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Sin existencias registradas</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
