<section class="auth-shell">
    <div class="row g-4" style="display: flex; align-items: center; justify-content: center;">
        <div class="col-lg-7">
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
        <div class="col-lg-5">
            <div class="card auth-panel border-0 shadow-sm">
                <div class="card-body p-lg-4">
                    <h2 style="text-align: center; margin: .8rem;" class="h3 mb-3">Iniciar sesión</h2>
                    
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger border-0 shadow-sm"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <form method="post" action="<?= htmlspecialchars(url('login')) ?>" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <div class="col-12">
                                
                                <input  type="text" placeholder="Usuario" name="username" value="<?= htmlspecialchars($username ?? '') ?>" class="form-control form-control-lg" required>
                                
                            </div>
                            <div class="col-12">
                                
                                <input  type="password" placeholder="Contraseña" name="password" class="form-control form-control-lg" required minlength="6">
                                
                            </div>
                        </div>
                        <div class="">
                            <button style="width: 100%; margin-top: 1rem;" type="submit" class="btn btn-primary btn-lg">Ingresar</button>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
