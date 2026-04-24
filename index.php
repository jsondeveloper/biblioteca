<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap/app.php';

$router->dispatch($_SERVER['REQUEST_URI'] ?? '/', $_SERVER['REQUEST_METHOD'] ?? 'GET');
