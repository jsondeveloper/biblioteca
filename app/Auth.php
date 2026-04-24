<?php
declare(strict_types=1);

class Auth
{
    public static function start(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public static function login(array $user): void
    {
        self::start();
        $_SESSION['user'] = $user;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
    }

    public static function logout(): void
    {
        self::start();
        unset($_SESSION['user']);
        unset($_SESSION['user_id']);
        unset($_SESSION['role']);
        session_destroy();
    }

    public static function isAuthenticated(): bool
    {
        self::start();
        return isset($_SESSION['user'], $_SESSION['user_id']);
    }

    public static function getUser(): ?array
    {
        self::start();
        return $_SESSION['user'] ?? null;
    }

    public static function getUserId(): ?int
    {
        self::start();
        return $_SESSION['user_id'] ?? null;
    }

    public static function getRole(): ?string
    {
        self::start();
        return $_SESSION['role'] ?? null;
    }

    public static function hasRole(string $role): bool
    {
        return self::getRole() === $role;
    }

    public static function hasAnyRole(array $roles): bool
    {
        $userRole = self::getRole();
        return $userRole !== null && in_array($userRole, $roles, true);
    }

    public static function requireAuth(?array $allowedRoles = null): void
    {
        if (!self::isAuthenticated()) {
            redirect_to('login');
        }

        if ($allowedRoles !== null && !self::hasAnyRole($allowedRoles)) {
            header('HTTP/1.0 403 Forbidden');
            echo '<h1>403 - Acceso denegado</h1>';
            exit;
        }
    }
}
