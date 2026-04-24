<?php
declare(strict_types=1);

class Router
{
    private array $routes = [];
    private $fallback;

    public function get(string $path, $action): void
    {
        $this->add('GET', $path, $action);
    }

    public function post(string $path, $action): void
    {
        $this->add('POST', $path, $action);
    }

    public function add(string $method, string $path, $action): void
    {
        $path = rtrim($path, '/');
        if ($path === '') {
            $path = '/';
        }

        $this->routes[$method][] = [
            'path' => $path,
            'pattern' => $this->createPattern($path),
            'action' => $action,
        ];
    }

    public function dispatch(string $uri, string $method): void
    {
        $uri = parse_url($uri, PHP_URL_PATH) ?: '/';
        $scriptPath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        $base = rtrim($scriptPath, '/');

        if ($base !== '' && $base !== '/' && str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base));
        }

        $uri = rtrim($uri, '/') ?: '/';

        if (!isset($this->routes[$method])) {
            $this->sendNotFound();
            return;
        }

        foreach ($this->routes[$method] as $route) {
            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->callAction($route['action'], $params);
                return;
            }
        }

        $this->sendNotFound();
    }

    public function fallback(callable $action): void
    {
        $this->fallback = $action;
    }

    private function createPattern(string $path): string
    {
        $regex = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_-]*)\}#', '(?P<$1>[^/]+)', $path);
        return '#^' . $regex . '$#';
    }

    private function callAction($action, array $params): void
    {
        if (is_callable($action)) {
            call_user_func_array($action, $params);
            return;
        }

        if (is_array($action) && count($action) === 2) {
            [$class, $method] = $action;

            if (!RouteProtection::checkAccess($class, $method)) {
                if (!Auth::isAuthenticated()) {
                    redirect_to('login');
                }

                header('HTTP/1.0 403 Forbidden');
                echo '<h1>403 - Acceso denegado</h1>';
                return;
            }

            $controller = new $class();
            call_user_func_array([$controller, $method], $params);
            return;
        }

        throw new RuntimeException('Accion de ruta no valida.');
    }

    private function sendNotFound(): void
    {
        if (is_callable($this->fallback)) {
            call_user_func($this->fallback);
            return;
        }

        header('HTTP/1.0 404 Not Found');
        echo '<h1>404 - Pagina no encontrada</h1>';
    }
}
