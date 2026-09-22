<?php

declare(strict_types=1);

/**
 * Conexion PDO directa, sin pasar por el bootstrap de Laravel/artisan
 * (el CLI de PHP en esta maquina falla al cargar sqlsrv, ver README de esta carpeta).
 * Lee las credenciales del .env de la raiz del proyecto.
 */
function envValue(string $key, string $envPath): ?string
{
    static $cache = null;

    if ($cache === null) {
        $cache = [];
        foreach (file($envPath, FILE_IGNORE_NEW_LINES) as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
                continue;
            }
            [$k, $v] = explode('=', $line, 2);
            $cache[trim($k)] = trim($v);
        }
    }

    return $cache[$key] ?? null;
}

function makePdo(): PDO
{
    $envPath = dirname(__DIR__, 2).'/.env';
    $host = envValue('DB_HOST', $envPath) ?? '127.0.0.1';
    $port = envValue('DB_PORT', $envPath) ?? '3306';
    $database = envValue('DB_DATABASE', $envPath);
    $username = envValue('DB_USERNAME', $envPath);
    $password = envValue('DB_PASSWORD', $envPath);

    if (strtolower((string) envValue('APP_ENV', $envPath)) === 'production') {
        fwrite(STDERR, "ABORTADO: APP_ENV=production, estos scripts son solo para bases de prueba.\n");
        exit(1);
    }

    $pdo = new PDO(
        "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4",
        $username,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;
}
