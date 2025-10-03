<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso denegado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fff3cd; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .alert { max-width: 500px; margin: auto; }
        .img-error { width: 120px; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="alert alert-danger text-center shadow">
        <img src="{{ asset('imagen/sinPermisos.jpg') }}" alt="403" class="img-error">
        <h4 class="alert-heading">Acceso denegado</h4>
        <p>No tienes permisos para acceder a esta página.</p>
        <hr>
        <a href="{{ url('/') }}" class="btn btn-outline-danger">Volver al inicio</a>
    </div>
</body>
</html>
