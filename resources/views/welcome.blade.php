<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Fuente Nunito -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <style>
        /* Estilos generales de la página */
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }
        .full-height { height: 100vh; }
        .flex-center { align-items: center; display: flex; justify-content: center; }
        .position-ref { position: relative; }
        .top-right { position: absolute; right: 10px; top: 18px; }
        .content { text-align: center; }
        .title { font-size: 84px; }
        .m-b-md { margin-bottom: 30px; }
    </style>
</head>
<body>
<!-- Contenedor principal centrado -->
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <!-- Enlaces de autenticación en la esquina superior derecha -->
        <div class="top-right links">
            @auth
                <a href="{{ url('/home') }}" class="btn btn-primary me-2">Inicio</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Iniciar sesión</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-outline-secondary">Registrarse</a>
                @endif
            @endauth
        </div>
    @endif
    <div class="content">
        <!-- Título principal -->
        <div class="title m-b-md">
            Laravel
        </div>
        <!-- Enlaces a recursos de Laravel -->
        <div class="row justify-content-center g-3">
            <div class="col-auto">
                <a href="https://laravel.com/docs" class="btn btn-link">Documentación</a>
            </div>
            <div class="col-auto">
                <a href="https://laracasts.com" class="btn btn-link">Laracasts</a>
            </div>
            <div class="col-auto">
                <a href="https://laravel-news.com" class="btn btn-link">Noticias</a>
            </div>
            <div class="col-auto">
                <a href="https://blog.laravel.com" class="btn btn-link">Blog</a>
            </div>
            <div class="col-auto">
                <a href="https://nova.laravel.com" class="btn btn-link">Nova</a>
            </div>
            <div class="col-auto">
                <a href="https://forge.laravel.com" class="btn btn-link">Forge</a>
            </div>
            <div class="col-auto">
                <a href="https://vapor.laravel.com" class="btn btn-link">Vapor</a>
            </div>
            <div class="col-auto">
                <a href="https://github.com/laravel/laravel" class="btn btn-link">GitHub</a>
            </div>
        </div>
    </div>
</div>
<!-- Scripts de Bootstrap 5 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
