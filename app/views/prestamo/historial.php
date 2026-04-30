<section class="mb-4">
    <div class="hero-card">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <span class="eyebrow mb-3">Prestamos</span>
                <h1 class="section-title"><?= htmlspecialchars($title ?? 'Prestamos y historial') ?></h1>
                <p class="section-subtitle">Consulta prestamos activos y el historial completo de movimientos.</p>
            </div>
            <?php if ($isBibliotecario ?? false): ?>
                <a class="btn btn-success" href="<?= htmlspecialchars(url('prestamos/crear')) ?>">Registrar prestamo</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if (isset($mensaje) && !empty($mensaje)): ?>
    <div class="alert alert-<?= htmlspecialchars($tipo === 'success' ? 'success' : 'danger') ?> border-0 shadow-sm mb-4">
        <?= htmlspecialchars($mensaje) ?>
    </div>
<?php endif; ?>

<!-- Prestamos Activos -->
<section class="mb-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h2 class="h4 mb-0">Prestamos activos</h2>
        <span class="badge badge-soft-primary fs-6"><?= count($prestamosActivos ?? []) ?> activos</span>
    </div>

    <div class="table-card p-4">
        <?php if (empty($prestamosActivos ?? [])): ?>
            <div class="empty-state">
                <h3 class="h5 mb-2">Sin prestamos activos</h3>
                <p class="text-secondary mb-0">No hay libros prestados actualmente.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Libro</th>
                            <th>Estudiante</th>
                            <th>Fecha de prestamo</th>
                            <th>Fecha de devolucion</th>
                            <th>Estado</th>
                            <th>Observaciones</th>
                            <?php if ($isBibliotecario ?? false): ?>
                                <th class="text-end">Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prestamosActivos as $prestamo): ?>
                            <tr>
                                <td class="fw-semibold"><?= htmlspecialchars($prestamo['libro']) ?></td>
                                <td><?= htmlspecialchars($prestamo['estudiante']) ?></td>
                                <td><?= htmlspecialchars($prestamo['fecha_prestamo']) ?></td>
                                <td><?= htmlspecialchars($prestamo['fecha_devolucion']) ?></td>
                                <td><span class="badge <?= htmlspecialchars(status_badge_class($prestamo['estado'])) ?>"><?= htmlspecialchars($prestamo['estado']) ?></span></td>
                                <td>
                                    <?php if (!empty($prestamo['observaciones'])): ?>
                                        <small class="text-muted"><?= htmlspecialchars($prestamo['observaciones']) ?></small>
                                    <?php else: ?>
                                        <small class="text-muted">-</small>
                                    <?php endif; ?>
                                </td>
                                <?php if ($isBibliotecario ?? false): ?>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-outline-success" href="<?= htmlspecialchars(url('prestamos/devolver/' . $prestamo['id'])) ?>">Marcar devolucion</a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Historial Completo -->
<section>
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h2 class="h4 mb-0">Historial completo</h2>
        <span class="badge badge-soft-secondary fs-6"><?= count($historial ?? []) ?> total</span>
    </div>

    <div class="table-card p-4">
        <?php if (empty($historial ?? [])): ?>
            <div class="empty-state">
                <h3 class="h5 mb-2">Sin historial</h3>
                <p class="text-secondary mb-0">No hay movimientos registrados en el historial.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Libro</th>
                            <th>Estudiante</th>
                            <th>Bibliotecario</th>
                            <th>Prestamo</th>
                            <th>Devolucion</th>
                            <th>Entrega</th>
                            <th>Estado</th>
                            <th>Observaciones</th>
                            <th>Comentarios</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historial as $prestamo): ?>
                            <tr>
                                <td class="fw-semibold"><?= htmlspecialchars($prestamo['libro']) ?></td>
                                <td><?= htmlspecialchars($prestamo['estudiante']) ?></td>
                                <td><?= htmlspecialchars($prestamo['bibliotecario']) ?></td>
                                <td><?= htmlspecialchars($prestamo['fecha_prestamo']) ?></td>
                                <td><?= htmlspecialchars($prestamo['fecha_devolucion'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($prestamo['fecha_entrega'] ?? '-') ?></td>
                                <td><span class="badge <?= htmlspecialchars(status_badge_class($prestamo['estado'])) ?>"><?= htmlspecialchars($prestamo['estado']) ?></span></td>
                                <td>
                                    <?php if (!empty($prestamo['observaciones'])): ?>
                                        <small class="text-muted"><?= htmlspecialchars($prestamo['observaciones']) ?></small>
                                    <?php else: ?>
                                        <small class="text-muted">-</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($prestamo['comentarios_historial'])): ?>
                                        <small class="text-muted" title="<?= htmlspecialchars($prestamo['comentarios_historial']) ?>">
                                            <?= htmlspecialchars(strlen($prestamo['comentarios_historial']) > 50 ? substr($prestamo['comentarios_historial'], 0, 50) . '...' : $prestamo['comentarios_historial']) ?>
                                        </small>
                                    <?php else: ?>
                                        <small class="text-muted">-</small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>
