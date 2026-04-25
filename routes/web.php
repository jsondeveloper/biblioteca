<?php
declare(strict_types=1);

$router = new Router();
$router->get('/', [HomeController::class, 'index']);
$router->get('/books', [BookController::class, 'index']);
$router->get('/books/{id}', [BookController::class, 'show']);
$router->get('/authors', [AuthorController::class, 'index']);
$router->get('/loans', [LoanController::class, 'index']);

$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/registro/{role}', [AuthController::class, 'registro']);
$router->get('/registro', [AuthController::class, 'registro']);
$router->post('/registro', [AuthController::class, 'registro']);

$router->get('/libros', [LibroController::class, 'listar']);
$router->get('/libros/buscar', [LibroController::class, 'buscar']);
$router->get('/libros/crear', [LibroController::class, 'crear']);
$router->post('/libros/crear', [LibroController::class, 'crear']);
$router->get('/libros/actualizar/{id}', [LibroController::class, 'actualizar']);
$router->get('/libros/editar/{id}', [LibroController::class, 'actualizar']);
$router->post('/libros/actualizar/{id}', [LibroController::class, 'actualizar']);
$router->post('/libros/eliminar/{id}', [LibroController::class, 'eliminar']);

$router->get('/categorias', [CategoriaController::class, 'index']);
$router->get('/categorias/crear', [CategoriaController::class, 'crear']);
$router->post('/categorias/crear', [CategoriaController::class, 'crear']);
$router->get('/categorias/actualizar/{id}', [CategoriaController::class, 'actualizar']);
$router->post('/categorias/actualizar/{id}', [CategoriaController::class, 'actualizar']);
$router->post('/categorias/eliminar/{id}', [CategoriaController::class, 'eliminar']);

$router->get('/prestamos', [PrestamoController::class, 'historial']);
$router->get('/prestamos/crear', [PrestamoController::class, 'registrar']);
$router->post('/prestamos/crear', [PrestamoController::class, 'registrar']);
$router->post('/prestamos/devolver/{id}', [PrestamoController::class, 'devolver']);

$router->get('/reservas', [ReservaController::class, 'index']);
$router->get('/reservas/crear', [ReservaController::class, 'reservar']);
$router->post('/reservas/crear', [ReservaController::class, 'reservar']);
$router->post('/reservas/aprobar/{id}', [ReservaController::class, 'aprobar']);
$router->post('/reservas/cancelar/{id}', [ReservaController::class, 'cancelar']);

$router->fallback(function () {
    header('HTTP/1.0 404 Not Found');
    echo '<h1>404 - Pagina no encontrada</h1>';
});
