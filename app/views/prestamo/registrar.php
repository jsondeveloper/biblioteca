<section class="mb-4">
    <div class="hero-card">
        <span class="eyebrow mb-3">Prestamos</span>
        <h1 class="section-title"><?= htmlspecialchars($title ?? 'Registrar prestamo') ?></h1>
        <p class="section-subtitle">Asocia un libro disponible con un estudiante y define su fecha estimada de devolucion.</p>
    </div>
</section>

<section class="form-card p-4 p-lg-5">
    <?php if (!empty($errores)): ?>
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            <strong>No pudimos registrar el prestamo.</strong>
            <ul class="mb-0 mt-2">
                <?php foreach ($errores as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= htmlspecialchars(url('prestamos/crear')) ?>" class="needs-validation" novalidate>
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">Libro disponible</label>
                <select name="libro_id" class="form-select form-select-lg" required>
                    <option value="">Selecciona un libro</option>
                    <?php foreach ($libros as $libro): ?>
                        <option value="<?= $libro['id'] ?>" <?= (int) ($selectedLibroId ?? 0) === (int) $libro['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($libro['titulo']) ?> (<?= htmlspecialchars($libro['estado']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">Selecciona un libro disponible.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Estudiante</label>
                <select name="estudiante_id" class="form-select form-select-lg" required>
                    <option value="">Selecciona un estudiante</option>
                    <?php foreach ($estudiantes as $estudiante): ?>
                        <option value="<?= $estudiante['id'] ?>"><?= htmlspecialchars($estudiante['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">Selecciona un estudiante.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Bibliotecario responsable</label>
                <input type="text" class="form-control form-control-lg" value="<?= htmlspecialchars($bibliotecarioActual['nombre'] ?? 'Sin asignar') ?>" disabled>
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha de devolucion</label>
                <input type="date" name="fecha_devolucion" class="form-control form-control-lg" required>
                <div class="invalid-feedback">Selecciona una fecha de devolucion.</div>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-3 mt-4">
            <button type="submit" class="btn btn-primary btn-lg">Guardar prestamo</button>
            <a href="<?= htmlspecialchars(url('prestamos')) ?>" class="btn btn-outline-secondary btn-lg">Volver al historial</a>
        </div>
    </form>
</section>
