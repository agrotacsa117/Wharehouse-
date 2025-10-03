<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Error interno del servidor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8d7da; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .alert { max-width: 500px; margin: auto; }
        .img-error { width: 120px; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="alert alert-danger text-center shadow">
        <img src="{{ asset('imagen/500.png') }}" alt="500" class="img-error">
        <h4 class="alert-heading">Error interno del servidor (500)</h4>
        <p>Ha ocurrido un error inesperado. Por favor, intenta más tarde.</p>
        <hr>
        <a href="{{ url('/') }}" class="btn btn-outline-danger">Volver al inicio</a>
    </div>
</body>
</html>
