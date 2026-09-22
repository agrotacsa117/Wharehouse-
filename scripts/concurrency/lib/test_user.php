<?php

declare(strict_types=1);

/**
 * Crea (o reutiliza) el usuario de prueba compartido por todos los seeds de
 * concurrencia. Idempotente: se puede llamar tantas veces como se quiera sin
 * duplicar el usuario ni fallar por unique constraint en el email.
 *
 * @return array{email: string, password: string, userId: int}
 */
function ensureTestUser(PDO $pdo): array
{
    $testEmail = 'k6.concurrency@test.local';
    $testPassword = 'K6Concurrency!2026';

    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$testEmail]);
    $userId = $stmt->fetchColumn();

    if (! $userId) {
        $pdo->prepare('
            INSERT INTO users (name, email, password, activo, rol, rol_id, created_at, updated_at)
            VALUES (?, ?, ?, 1, ?, ?, NOW(), NOW())
        ')->execute([
            'k6 Concurrency Tester',
            $testEmail,
            password_hash($testPassword, PASSWORD_BCRYPT),
            'Operador',
            4,
        ]);
        $userId = (int) $pdo->lastInsertId();
    }

    return ['email' => $testEmail, 'password' => $testPassword, 'userId' => (int) $userId];
}
