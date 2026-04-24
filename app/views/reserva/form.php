<section class="mb-4">
    <div class="hero-card">
        <span class="eyebrow mb-3">Reservas</span>
        <h1 class="section-title"><?= htmlspecialchars($title ?? 'Reservar libro') ?></h1>
        <p class="section-subtitle">Selecciona un titulo disponible y define hasta cuando se mantendra la reserva activa.</p>
    </div>
</section>

<section class="form-card p-4 p-lg-5">
    <form method="post" action="<?= htmlspecialchars(url('reservas/crear')) ?>" class="needs-validation" novalidate>
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">Libro</label>
                <select name="libro_id" class="form-select form-select-lg" required>
                    <option value="">Selecciona un libro</option>
                    <?php foreach ($libros as $libro): ?>
                        <option value="<?= $libro['id'] ?>"><?= htmlspecialchars($libro['titulo']) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">Selecciona un libro para la reserva.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Estudiante</label>
                <input type="text" class="form-control form-control-lg" value="<?= htmlspecialchars($estudianteActual['nombre'] ?? 'Sin perfil vinculado') ?>" disabled>
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha de expiracion</label>
                <input type="date" name="fecha_expiracion" class="form-control form-control-lg" required>
                <div class="invalid-feedback">Selecciona la fecha de expiracion.</div>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-3 mt-4">
            <button type="submit" class="btn btn-primary btn-lg">Guardar reserva</button>
            <a href="<?= htmlspecialchars(url('reservas')) ?>" class="btn btn-outline-secondary btn-lg">Volver a reservas</a>
        </div>
    </form>
</section>
