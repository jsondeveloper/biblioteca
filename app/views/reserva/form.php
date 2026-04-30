<section class="mb-4">
    <div class="hero-card">
        <span class="eyebrow mb-3">Reservas</span>
        <h1 class="section-title"><?= htmlspecialchars($title ?? 'Reservar libro') ?></h1>
        <p class="section-subtitle">Selecciona un titulo disponible. La reserva expirara automaticamente en 24 horas.</p>
    </div>
</section>

<section class="form-card p-4 p-lg-5">
    <form method="post" action="<?= htmlspecialchars(url('reservas/crear')) ?>" class="needs-validation" novalidate>
        <div class="row g-4">
            <div class="col-md-8">
                <label class="form-label">Libro</label>
                <select name="libro_id" class="form-select form-select-lg" required>
                    <option value="">Selecciona un libro</option>
                    <?php foreach ($libros as $libro): ?>
                        <option value="<?= $libro['id'] ?>" <?= isset($selectedBookId) && $selectedBookId === (int) $libro['id'] ? 'selected' : '' ?>><?= htmlspecialchars($libro['titulo']) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">Selecciona un libro para la reserva.</div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Expiracion</label>
                <div class="form-control form-control-lg bg-light">
                    <strong>24 horas</strong> desde la creacion
                </div>
                <div class="form-text">La reserva se cancelara automaticamente si no es aprobada.</div>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-3 mt-4">
            <button type="submit" class="btn btn-primary btn-lg">Crear reserva</button>
            <a href="<?= htmlspecialchars(url('reservas')) ?>" class="btn btn-outline-secondary btn-lg">Volver a reservas</a>
        </div>
    </form>
</section>
