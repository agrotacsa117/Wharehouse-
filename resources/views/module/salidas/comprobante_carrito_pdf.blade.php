<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de Salida de Almacén</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 13px;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        .container {
            width: 90%;
            margin: 0 auto;
        }
        .titulo {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin: 20px 0 10px 0;
        }
        .info {
            margin-bottom: 15px;
        }
        .info label {
            font-weight: bold;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .table th, .table td {
            border: 1px solid #222;
            padding: 6px 4px;
            text-align: center;
        }
        .table th {
            background: #f2f2f2;
        }
        .firmas {
            margin-top: 40px;
            width: 100%;
            display: flex;
            justify-content: space-between;
        }
        .firma {
            width: 30%;
            text-align: center;
        }
        .firma .linea {
            border-top: 1.5px solid #000;
            width: 80%;
            margin: 40px auto 0 auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="titulo">Ticket de Salida de Almacén</div>
        <div class="info">
            <label>Usuario:</label> <span>{{ $usuario->name }}</span><br>
            <label>Fecha de salida:</label> <span>
                @php
                    $fechaSalida = isset($carrito) && count($carrito) > 0 && isset($carrito[0]['created_at']) ? $carrito[0]['created_at'] : (isset($carrito[0]['fecha_salida']) ? $carrito[0]['fecha_salida'] : null);
                @endphp
                {{ $fechaSalida ? \Carbon\Carbon::parse($fechaSalida)->format('d/m/Y') : now()->format('d/m/Y') }}
            </span><br>
            <label>Bodega de salida:</label> <span>
                @php
                    $rolUsuario = strtolower($usuario->rol);
                    $bodegas = collect($carrito)->pluck('rol')->unique()->filter()->map(function($b){ return ucfirst(str_replace('_', ' ', $b)); })->implode(', ');
                @endphp
                @if(in_array($rolUsuario, ['bodega tapachula', 'bodega_dorado', 'bodega tapachula', 'bodega_dorado']))
                    {{ ucfirst(str_replace('_', ' ', $rolUsuario)) }}
                @else
                    {{ $bodegas ?: '-' }}
                @endif
            </span><br>
            <label>Bodega destino:</label> <span>{{ !empty($bodegaDestino) ? ucfirst($bodegaDestino) : 'No especificada' }}</span><br>
            <label>Comunidad/Institución:</label> <span>{{ !empty($donadoA) ? $donadoA : '-' }}</span>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Clave</th>
                    <th>Categoría</th>
                    <th>Cantidad</th>
                    <th>Rack/Aduana</th>
                </tr>
            </thead>
            <tbody>
                @forelse($carrito as $id => $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><code>{{ $item['clave'] }}</code></td>
                    <td>{{ $item['nombre'] }}</td>
                    <td style="font-weight:bold;">{{ $item['cantidad'] }}</td>
                    <td>{{ $item['colocado'] ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; color:#888; font-style:italic;">No hay productos en el carrito de salida.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="firmas">
            <div class="firma">
                <span>Autorizo</span>
                <div class="linea"></div>
            </div>
            <div class="firma">
                <span>Recibido</span>
                <div class="linea"></div>
            </div>
            <div class="firma">
                <span>Autorizado</span>
                <div class="linea"></div>
            </div>
        </div>
    </div>
</body>
</html>
