<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>No autorizado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #e2e3e5; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .alert { max-width: 500px; margin: auto; }
        .img-error { width: 120px; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="alert alert-secondary text-center shadow">
        <h4 class="alert-heading">No autorizado</h4>
        <p>Debes iniciar sesión para acceder a esta página.</p>
        <img src="{{ asset('imagen/no_autorizado.jpeg') }}" alt="401" class="img-error">
        <hr>
        <a href="{{ url('/') }}" class="btn btn-outline-secondary">Ir al inicio</a>
    </div>
</body>
</html>
