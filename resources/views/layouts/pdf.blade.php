<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('titulo', 'Documento PDF')</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
            font-size: 13px;
            color: #222;
            background: #f8fafc;
            margin: 0;
            padding: 0 10px 10px 10px;
        }
        h1, h2, h3, h4, h5 {
            color: #1a237e;
            margin-bottom: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        h1 {
            font-size: 1.7em;
            border-bottom: 2px solid #1976d2;
            padding-bottom: 6px;
            margin-bottom: 18px;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 18px;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 6px #0001;
        }
        th, td {
            border: 1px solid #d1d5db;
            padding: 7px 10px;
            text-align: left;
        }
        th {
            background: #e3f2fd;
            color: #0d47a1;
            font-weight: 600;
        }
        tr:nth-child(even) td {
            background: #f5faff;
        }
        tr:last-child td {
            border-bottom: 2px solid #1976d2;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        .mb-2 { margin-bottom: 10px; }
        .mb-3 { margin-bottom: 20px; }
        .mt-2 { margin-top: 10px; }
        .mt-3 { margin-top: 20px; }
        .small { font-size: 11px; color: #666; }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            font-size: 11px;
            color: #fff;
            background: #1976d2;
            border-radius: 12px;
            margin-right: 4px;
        }
        .box {
            background: #e3f2fd;
            border-radius: 8px;
            padding: 10px 15px;
            margin-bottom: 15px;
            border-left: 4px solid #1976d2;
        }
    </style>
</head>
<body>
    @yield('contenido')
</body>
</html>
