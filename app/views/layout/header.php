<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= isset($title) ? htmlspecialchars($title) . ' | Biblioteca' : 'Biblioteca Universitaria' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('css/style.css')) ?>">
</head>
<body>
<?php Auth::start(); $isAuth = Auth::isAuthenticated(); $role = Auth::getRole(); $alerts = query_alerts(); ?>
<div class="app-shell">
    <header class="site-topbar">
        <nav class="navbar navbar-expand-lg navbar-dark py-3">
            <div class="container">
                <a class="navbar-brand brand-mark" href="<?= htmlspecialchars(url()) ?>">
                    <span class="brand-mark__icon">B</span>
                    <span>
                        <strong>Biblioteca</strong>
                        <small>Universitaria</small>
                    </span>
                </a>

               <div class="nav-shell mx-auto">
                        <ul class="navbar-nav nav-links-row">
                           
                            <?php if ($isAuth): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= htmlspecialchars(url('categorias')) ?>">Categorias</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= htmlspecialchars(url('libros')) ?>">Libros</a>
                                </li>
                            <?php endif; ?>
                            <?php if ($isAuth && $role === 'bibliotecario'): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= htmlspecialchars(url('prestamos')) ?>">Prestamos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= htmlspecialchars(url('reservas')) ?>">Reservas</a>
                                </li>
                                
                            <?php endif; ?>
                            <?php if ($isAuth && $role === 'estudiante'): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= htmlspecialchars(url('reservas')) ?>">Reservas</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                <div class="collapse navbar-collapse" id="navbarMain">
                    

                    <div class="nav-actions ms-lg-auto">
                        <?php if ($isAuth): ?>
                            
                            <span class="user-pill">
                               <?= htmlspecialchars(Auth::getUserName() ?? Auth::getUser()['username']) ?>                                
                            </span>
                                                     
                            
                            <a class="btn" href="<?= htmlspecialchars(url('logout')) ?>">Cerrar sesion</a>
                        <?php else: ?>
                            <a class="btn btn-secondary btn-lg" href="<?= htmlspecialchars(url('login')) ?>">Entrar al sistema</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
        <div class="hero-stripe"></div>
    </header>

    <main class="page-frame">
        <div class="container py-3 py-lg-4">
            <?php if (!empty($alerts)): ?>
                <div class="alerts-stack mb-4">
                    <?php foreach ($alerts as $alert): ?>
                        <div class="alert alert-<?= htmlspecialchars($alert['type']) ?> alert-dismissible fade show shadow-sm border-0" role="alert">
                            <?= htmlspecialchars($alert['message']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
