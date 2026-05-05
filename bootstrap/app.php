<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
date_default_timezone_set('America/Bogota');

require_once BASE_PATH . '/app/helpers.php';

spl_autoload_register(function (string $class): void {
    $paths = [
        BASE_PATH . '/app/',
        BASE_PATH . '/app/controllers/',
        BASE_PATH . '/app/models/',
    ];

    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/routes/web.php';
