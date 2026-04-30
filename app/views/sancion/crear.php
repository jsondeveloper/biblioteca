<section class="mb-4">
    <div class="hero-card">
        <span class="eyebrow mb-3">Sanciones</span>
        <h1 class="section-title"><?= htmlspecialchars($title ?? 'Crear sanción') ?></h1>
        <p class="section-subtitle">Registra una sanción para un estudiante desde el rol bibliotecario.</p>
    </div>
</section>

<section class="form-card p-4 p-lg-5">
    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-<?= htmlspecialchars($tipo === 'success' ? 'success' : 'danger') ?> border-0 shadow-sm mb-4">
            <?= htmlspecialchars($mensaje) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($errores)): ?>
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            <ul class="mb-0 mt-2">
                <?php foreach ($errores as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= htmlspecialchars(url('sanciones/crear')) ?>" class="needs-validation" novalidate>
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">Estudiante</label>
                <select name="estudiante_id" class="form-select form-select-lg" required>
                    <option value="">Selecciona un estudiante</option>
                    <?php foreach ($estudiantes as $estudiante): ?>
                        <option value="<?= $estudiante['id'] ?>" <?= (int) ($selectedEstudianteId ?? 0) === (int) $estudiante['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($estudiante['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">Selecciona un estudiante.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Razón</label>
                <input type="text" name="razon" class="form-control form-control-lg" value="<?= htmlspecialchars($_POST['razon'] ?? '') ?>" required>
                <div class="invalid-feedback">Ingresa la razón de la sanción.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha de inicio</label>
                <input type="date" name="fecha_inicio" class="form-control form-control-lg" value="<?= htmlspecialchars($_POST['fecha_inicio'] ?? '') ?>" required>
                <div class="invalid-feedback">Selecciona la fecha de inicio.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha de fin</label>
                <input type="date" name="fecha_fin" class="form-control form-control-lg" value="<?= htmlspecialchars($_POST['fecha_fin'] ?? '') ?>" required>
                <div class="invalid-feedback">Selecciona la fecha de fin.</div>
            </div>
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="activa" id="activa" <?= isset($_POST['activa']) ? 'checked' : '' ?> >
                    <label class="form-check-label" for="activa">Sanción activa</label>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-3 mt-4">
            <button type="submit" class="btn btn-danger btn-lg">Registrar sanción</button>
            <a href="<?= htmlspecialchars(url('sanciones')) ?>" class="btn btn-outline-secondary btn-lg">Volver al listado de sanciones</a>
        </div>
    </form>
</section>
