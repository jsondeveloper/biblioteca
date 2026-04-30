<section class="auth-shell">
    <div class="row g-4 w-100 align-items-stretch">
        <div class="col-lg-4">
            <div class="auth-panel__side">
                <span class="eyebrow mb-3 bg-white text-dark">Nuevo Usuario</span>
                <h2 class="mb-3">Crea tu acceso</h2>
                <p class="mb-4">Registra estudiantes o bibliotecarios desde un formulario mas claro y ordenado.</p>
                <ul class="mb-0">
                    <li>Datos personales y academicos</li>
                    <li>Alta de roles con campos relevantes</li>
                    <li>Interfaz guiada con validaciones</li>
                </ul>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card auth-panel border-0 shadow-sm">
                <div class="card-body p-lg-4">
                    <h1 class="h2 mb-2">Registro de usuario</h1>
                    <p class="text-secondary mb-4">Completa la informacion para crear una nueva cuenta.</p>
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger border-0 shadow-sm"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <form method="post" action="<?= htmlspecialchars(url('registro')) ?>" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <div class="col-md-6">
                               
                                <input placeholder="Usuario" type="text" name="username" value="<?= htmlspecialchars($input['username'] ?? '') ?>" class="form-control" required minlength="4">
                                <div class="invalid-feedback">El usuario debe tener al menos 4 caracteres.</div>
                            </div>
                            <div class="col-md-6">
                                
                                <input placeholder="Email" type="email" name="email" value="<?= htmlspecialchars($input['email'] ?? '') ?>" class="form-control" required>
                                <div class="invalid-feedback">Ingresa un correo valido.</div>
                            </div>
                            <div class="col-md-6">
                                
                                <input placeholder="Contraseña" type="password" name="password" class="form-control" required minlength="6">
                                <div class="invalid-feedback">La contrasena debe tener al menos 6 caracteres.</div>
                            </div>
                            <div class="col-md-6">
                                <select name="role" class="form-select" required>
                                    <option value="" disabled <?= empty($input['role']) ? 'selected' : '' ?>>Selecciona tu rol</option>
                                    <option value="estudiante" <?= (isset($input['role']) && $input['role'] === 'estudiante') ? 'selected' : '' ?>>Estudiante</option>
                                    <option value="bibliotecario" <?= (isset($input['role']) && $input['role'] === 'bibliotecario') ? 'selected' : '' ?>>Bibliotecario</option>
                                </select>
                                <div class="invalid-feedback">Selecciona un rol para tu cuenta.</div>
                            </div>
                            <div class="col-md-6">
                                <input placeholder="Nombre completo" type="text" name="nombre" value="<?= htmlspecialchars($input['nombre'] ?? '') ?>" class="form-control" required>
                                <div class="invalid-feedback">Ingresa el nombre completo.</div>
                            </div>
                            <div class="col-md-6">
                               
                                <input placeholder="Telefono"  required type="text" name="telefono" value="<?= htmlspecialchars($input['telefono'] ?? '') ?>" class="form-control" placeholder="Ej. 555-0101">
                                <div class="invalid-feedback">Ingresa un numero de telefono valido.</div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-3 mt-4">
                            <button type="submit" class="btn btn-success btn-lg">Registrar usuario</button>
                            <a href="<?= htmlspecialchars(url('login')) ?>" class="btn btn-outline-secondary btn-lg">Ya tengo cuenta</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>