<?php
declare(strict_types=1);

class RouteProtection
{
    private static array $protected = [
        'BookController' => [
            'index' => ['bibliotecario', 'estudiante'],
            'show' => ['bibliotecario', 'estudiante'],
        ],
        'CategoriaController' => [
            'index' => ['bibliotecario'],
            'crear' => ['bibliotecario'],
            'actualizar' => ['bibliotecario'],
            'eliminar' => ['bibliotecario'],
        ],
        'LibroController' => [
            'listar' => ['bibliotecario', 'estudiante'],
            'buscar' => ['bibliotecario', 'estudiante'],
            'crear' => ['bibliotecario'],
            'actualizar' => ['bibliotecario'],
            'eliminar' => ['bibliotecario'],
            'historial' => ['bibliotecario'],
        ],
        'PrestamoController' => [
            'registrar' => ['bibliotecario'],
            'devolver' => ['bibliotecario'],
            'historial' => ['bibliotecario', 'estudiante'],
        ],
        'ReservaController' => [
            'reservar' => ['estudiante'],
            'cancelar' => ['estudiante', 'bibliotecario'],
            'index' => ['estudiante', 'bibliotecario'],
            'aprobar' => ['bibliotecario'],
        ],
        'SancionController' => [
            'index' => ['bibliotecario'],
            'crear' => ['bibliotecario'],
            'actualizar' => ['bibliotecario'],
            'activar' => ['bibliotecario'],
            'desactivar' => ['bibliotecario'],
        ],
    ];

    private static array $public = [
        'AuthController' => ['login', 'logout', 'registro'],
        'HomeController' => ['index'],
    ];

    public static function checkAccess(string $controllerName, string $action): bool
    {
        if (self::isPublic($controllerName, $action)) {
            return true;
        }

        if (!isset(self::$protected[$controllerName])) {
            return true;
        }

        if (!isset(self::$protected[$controllerName][$action])) {
            return true;
        }

        $allowedRoles = self::$protected[$controllerName][$action];

        if (in_array('*', $allowedRoles, true)) {
            return true;
        }

        if (!Auth::isAuthenticated()) {
            return false;
        }

        return Auth::hasAnyRole($allowedRoles);
    }

    private static function isPublic(string $controllerName, string $action): bool
    {
        return isset(self::$public[$controllerName]) && in_array($action, self::$public[$controllerName], true);
    }
}
