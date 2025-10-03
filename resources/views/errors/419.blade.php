<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sesión expirada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fff3cd; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .alert { max-width: 500px; margin: auto; }
    </style>
</head>
<body>
    <div class="alert alert-warning text-center shadow">
        <h4 class="alert-heading">¡Sesión expirada!</h4>
        <p>Por seguridad, tu sesión ha expirado o el formulario ya fue enviado.<br>
        Por favor, vuelve a iniciar sesión o navega normalmente.</p>
        <hr>
        <a href="{{ url('/') }}" class="btn btn-outline-warning">Ir al inicio</a>
    </div>
</body>
</html>
