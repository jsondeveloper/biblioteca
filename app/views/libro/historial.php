<?php Auth::start(); ?>
<section class="mb-4">
    <div class="hero-card">
        <span class="eyebrow mb-3">Hoja de vida</span>
        <h1 class="section-title"><?= htmlspecialchars($title ?? 'Historial del libro') ?></h1>
        <p class="section-subtitle">Revisa el historial de préstamos de este libro.</p>
    </div>
</section>

<section class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="table-card p-4 h-100">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Titulo</label>
                    <div class="form-control bg-light-subtle"><?= htmlspecialchars($libro['titulo']) ?></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Autor</label>
                    <div class="form-control bg-light-subtle"><?= htmlspecialchars($libro['autor']) ?></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">ISBN</label>
                    <div class="form-control bg-light-subtle"><?= htmlspecialchars($libro['isbn']) ?></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Estado</label>
                    <div><span class="badge <?= htmlspecialchars(status_badge_class($libro['estado'] ?? 'Disponible')) ?>"><?= htmlspecialchars($libro['estado'] ?? 'Disponible') ?></span></div>
                </div>
                <div class="col-12">
                    <label class="form-label">Descripcion</label>
                    <div class="form-control bg-light-subtle"><?= htmlspecialchars($libro['descripcion'] ?? 'Sin descripcion disponible.') ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel-card p-4 h-100">
            <h2 class="h5 mb-3">Acciones</h2>
            <div class="d-grid gap-3">
                <a class="btn btn-outline-primary" href="<?= htmlspecialchars(url('libros')) ?>">Volver al catálogo</a>
                <a class="btn btn-outline-secondary" href="<?= htmlspecialchars(url('prestamos')) ?>">Ver préstamos activos</a>
            </div>
        </div>
    </div>
</section>

<section class="table-card p-4">
    <h2 class="h5 mb-3">Historial de préstamos</h2>

    <?php if (empty($prestamos)): ?>
        <div class="empty-state">
            <h2 class="h4 mb-2">Sin historial disponible</h2>
            <p class="text-secondary mb-0">Aún no se han registrado préstamos para este libro.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Prestamo</th>
                        <th>Devolucion</th>
                        <th>Entrega</th>
                        <th>Estado</th>
                        <th>Bibliotecario</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prestamos as $prestamo): ?>
                        <tr>
                            <td><?= htmlspecialchars($prestamo['estudiante']) ?></td>
                            <td><?= htmlspecialchars($prestamo['fecha_prestamo']) ?></td>
                            <td><?= htmlspecialchars($prestamo['fecha_devolucion']) ?></td>
                            <td><?= htmlspecialchars($prestamo['fecha_entrega'] ?? 'No entregado') ?></td>
                            <td><span class="badge <?= htmlspecialchars(status_badge_class($prestamo['estado'])) ?>"><?= htmlspecialchars($prestamo['estado']) ?></span></td>
                            <td><?= htmlspecialchars($prestamo['bibliotecario']) ?></td>
                            <td><?= htmlspecialchars($prestamo['observaciones'] ?? 'Sin observaciones') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
