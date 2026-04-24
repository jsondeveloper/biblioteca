<section class="auth-shell">
    <div class="row g-4 w-100 align-items-stretch">
        <div class="col-lg-5">
            <div class="auth-panel__side">
                <span class="eyebrow mb-3 bg-white text-dark">Acceso Seguro</span>
                <h2 class="mb-3">Bienvenido de nuevo</h2>
                <p class="mb-4">Ingresa para consultar el catalogo, administrar movimientos y revisar tus operaciones recientes.</p>
                <ul class="mb-0">
                    <li>Panel diferenciado por rol</li>
                    <li>Prestamos y reservas con estados visibles</li>
                    <li>Navegacion simple y enfocada</li>
                </ul>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card auth-panel border-0 shadow-sm">
                <div class="card-body p-lg-4">
                    <h1 class="h2 mb-2">Iniciar sesion</h1>
                    <p class="text-secondary mb-4">Completa tus credenciales para entrar al sistema.</p>
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger border-0 shadow-sm"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <form method="post" action="<?= htmlspecialchars(url('login')) ?>" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Usuario</label>
                                <input type="text" name="username" value="<?= htmlspecialchars($username ?? '') ?>" class="form-control form-control-lg" required>
                                <div class="invalid-feedback">Ingresa tu nombre de usuario.</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Contrasena</label>
                                <input type="password" name="password" class="form-control form-control-lg" required minlength="6">
                                <div class="invalid-feedback">Ingresa una contrasena valida.</div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-3 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Entrar</button>
                            <a href="<?= htmlspecialchars(url('registro')) ?>" class="btn btn-outline-secondary btn-lg">Crear cuenta</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
