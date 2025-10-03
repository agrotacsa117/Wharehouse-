<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Error de conexión a la base de datos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8d7da;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .alert {
            max-width: 500px;
            margin: auto;
        }
        .icon {
            font-size: 3rem;
            color: #842029;
        }
        .img-error {
            width: 120px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="alert alert-danger text-center shadow">
        <!-- Ejemplo de imagen personalizada -->
        <div class="icon mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                <path d="M7.938 2.016a.13.13 0 0 1 .125 0l6.857 11.856c.06.104.01.228-.104.228H1.184a.13.13 0 0 1-.104-.228L7.938 2.016zm.862-1.757a1.13 1.13 0 0 0-1.6 0L.04 12.115C-.417 12.89.146 14 1.184 14h13.632c1.038 0 1.601-1.11 1.144-1.885L8.8.259z"/>
                <path d="M7.002 11a1 1 0 1 0 2 0 1 1 0 0 0-2 0zm.93-2.481a.5.5 0 0 0 .992 0l.07-4a.5.5 0 0 0-.992 0l-.07 4z"/>
            </svg>
        </div>
        <h4 class="alert-heading">¡Error a la  conexion de la base de datos!</h4>
        <p>Por favor, verifica la configuración o contacta al administrador del sistema.</p>
        <img src="{{ asset('imagen/desconexionsql.png') }}" alt="Error DB" class="img-error">
        <hr>
        <p class="mb-0"><a href="{{ url('/') }}" class="btn btn-outline-danger">Volver al inicio</a></p>
    </div>
</body>
</html>
