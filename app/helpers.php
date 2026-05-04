<?php
declare(strict_types=1);

function app_base_url(): string
{
    static $base = null;

    if ($base !== null) {
        return $base;
    }

    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $scriptDir = rtrim($scriptDir, '/');
    $base = $scriptDir === '' || $scriptDir === '.' ? '' : $scriptDir;

    return $base;
}

function public_base_url(): string
{
    static $publicBase = null;

    if ($publicBase !== null) {
        return $publicBase;
    }

    $base = app_base_url();
    $publicBase = str_ends_with($base, '/public') ? $base : ($base === '' ? '/public' : $base . '/public');

    return $publicBase;
}

function url(string $path = ''): string
{
    $base = app_base_url();
    $path = trim($path, '/');

    if ($path === '') {
        return $base === '' ? '/' : $base . '/';
    }

    return ($base === '' ? '' : $base) . '/' . $path;
}

function asset_url(string $path): string
{
    return public_base_url() . '/' . ltrim($path, '/');
}

function redirect_to(string $path = ''): void
{
    header('Location: ' . url($path));
    exit;
}

function status_badge_class(?string $status): string
{
    return match ($status) {
        'Disponible', 'Activa', 'Activo' => 'badge-soft-success',
        'Reservado' => 'badge-soft-warning',
        'Prestado', 'Cumplida' => 'badge-soft-primary',
        'Mantenimiento', 'Cancelada' => 'badge-soft-secondary',
        'Devuelto' => 'badge-soft-info',
        'Devolucion Retrasada' => 'badge-soft-danger',
        'Retrasado' => 'badge-soft-danger',
        default => 'badge-soft-dark',
    };
}

function role_badge_class(?string $role): string
{
    return match ($role) {
        'bibliotecario' => 'badge-soft-primary',
        'estudiante' => 'badge-soft-success',
        default => 'badge-soft-dark',
    };
}

function query_alerts(): array
{
    $alerts = [];

    if (!empty($_GET['success'])) {
        $alerts[] = [
            'type' => 'success',
            'message' => is_string($_GET['success']) && $_GET['success'] !== '1'
                ? $_GET['success']
                : 'Operacion completada correctamente.',
        ];
    }

    if (!empty($_GET['error'])) {
        $alerts[] = [
            'type' => 'danger',
            'message' => (string) $_GET['error'],
        ];
    }

    if (!empty($_GET['info'])) {
        $alerts[] = [
            'type' => 'info',
            'message' => (string) $_GET['info'],
        ];
    }

    return $alerts;
}
