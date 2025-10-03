<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página no encontrada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .alert { max-width: 500px; margin: auto; }
        .img-error { width: 120px; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="alert alert-warning text-center shadow">
        
        <h4 class="alert-heading">Página no encontrada (404)</h4>
        <p>La página que buscas no existe o ha sido movida.</p>
        <img src="{{ asset('imagen/NoEncontrada.png') }}" alt="404" class="img-error">
        <hr>
        <a href="{{ url('/') }}" class="btn btn-outline-warning">Volver al inicio</a>
    </div>
</body>
</html>
