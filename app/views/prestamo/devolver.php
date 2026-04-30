<section class="mb-4">
    <div class="hero-card">
        <span class="eyebrow mb-3">Devolución</span>
        <h1 class="section-title"><?= htmlspecialchars($title ?? 'Registrar devolución') ?></h1>
        <p class="section-subtitle">Registra la devolución del libro, agrega un comentario y aplica una sanción si es necesario.</p>
    </div>
</section>

<section class="form-card p-4 p-lg-5">
    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-<?= htmlspecialchars($tipo === 'success' ? 'success' : 'danger') ?> border-0 shadow-sm mb-4">
            <?= htmlspecialchars($mensaje) ?>
        </div>
    <?php endif; ?>

    <div class="mb-4">
        <h2 class="h5">Información del préstamo</h2>
        <div class="row g-3">
            <div class="col-md-4">
                <strong>Libro</strong>
                <p class="mb-0"><?= htmlspecialchars($prestamo['libro']) ?></p>
            </div>
            <div class="col-md-4">
                <strong>Estudiante</strong>
                <p class="mb-0"><?= htmlspecialchars($prestamo['estudiante']) ?></p>
            </div>
            <div class="col-md-4">
                <strong>Fecha devolución</strong>
                <p class="mb-0"><?= htmlspecialchars($prestamo['fecha_devolucion']) ?></p>
            </div>
        </div>
    </div>

    <form method="post" action="<?= htmlspecialchars(url('prestamos/devolver/' . $prestamo['id'])) ?>" class="needs-validation" novalidate>
        <div class="mb-4">
            <label class="form-label">Comentario de devolución</label>
            <textarea name="comentario" class="form-control" rows="4" placeholder="Agrega un comentario opcional acerca de la devolución..."><?= htmlspecialchars($_POST['comentario'] ?? '') ?></textarea>
        </div>

        <div class="mb-4">
            <h2 class="h5">Sanción</h2>
            <p class="text-muted mb-3">Registra una sanción para el estudiante sólo si aplica.</p>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Razón</label>
                    <input type="text" name="sancion_razon" class="form-control" value="<?= htmlspecialchars($_POST['sancion_razon'] ?? '') ?>" placeholder="Ej. Retraso en devolución">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Inicio</label>
                    <input type="date" name="sancion_fecha_inicio" class="form-control" value="<?= htmlspecialchars($_POST['sancion_fecha_inicio'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fin</label>
                    <input type="date" name="sancion_fecha_fin" class="form-control" value="<?= htmlspecialchars($_POST['sancion_fecha_fin'] ?? '') ?>">
                </div>
            </div>
            <div class="form-check form-switch mt-3">
                <input class="form-check-input" type="checkbox" role="switch" id="sancion_activa" name="sancion_activa" <?= isset($_POST['sancion_activa']) ? 'checked' : '' ?> />
                <label class="form-check-label" for="sancion_activa">Sanción activa</label>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-3">
            <button type="submit" class="btn btn-success btn-lg">Confirmar devolución</button>
            <a href="<?= htmlspecialchars(url('prestamos')) ?>" class="btn btn-outline-secondary btn-lg">Cancelar</a>
        </div>
    </form>
</section>
