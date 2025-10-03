<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sitio en mantenimiento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #e2e3e5; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .alert { max-width: 500px; margin: auto; }
        .img-mantenimiento { width: 120px; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="alert alert-secondary text-center shadow">
        <!-- Imagen personalizada de mantenimiento -->
        <h4 class="alert-heading">Sitio en mantenimiento</h4>
        <p>Estamos realizando tareas de mantenimiento.<br>
        Por favor, vuelve a intentarlo en unos minutos.</p>
        <img src="{{ asset('imagen/4.png') }}" alt="Mantenimiento" class="img-mantenimiento">
        <hr>
        <span class="text-muted">Gracias por tu paciencia.</span>
    </div>
</body>
</html>
