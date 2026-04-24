<?php
declare(strict_types=1);

class Usuario extends Model
{
    protected static function table(): string
    {
        return 'usuarios';
    }

    protected static function fillable(): array
    {
        return ['username', 'password', 'email', 'role'];
    }

    public static function findByUsername(string $username): ?array
    {
        $stmt = self::query('SELECT * FROM usuarios WHERE username = :username LIMIT 1', ['username' => $username]);
        return $stmt->fetch() ?: null;
    }

    public static function authenticate(string $username, string $password): ?array
    {
        $user = self::findByUsername($username);
        if ($user === null) {
            return null;
        }

        return password_verify($password, $user['password']) ? $user : null;
    }
}
