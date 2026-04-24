<section class="mb-4">
    <div class="hero-card">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <span class="eyebrow mb-3">Prestamos</span>
                <h1 class="section-title"><?= htmlspecialchars($title ?? 'Historial de prestamos') ?></h1>
                <p class="section-subtitle">Consulta el historial completo de prestamos, detecta retrasos y procesa devoluciones con claridad.</p>
            </div>
            <?php if (Auth::hasRole('bibliotecario')): ?>
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

<section class="table-card p-4">
    <?php if (empty($historial)): ?>
        <div class="empty-state">
            <h2 class="h4 mb-2">Sin prestamos para mostrar</h2>
            <p class="text-secondary mb-0">Cuando existan movimientos activos o historicos apareceran en esta tabla.</p>
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
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
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
                            <td><span class="badge <?= htmlspecialchars(status_badge_class($prestamo['estado'])) ?>"><?= htmlspecialchars($prestamo['estado']) ?></span></td>
                            <td class="text-end">
                                <?php if ($prestamo['estado'] === 'Activo' && Auth::hasRole('bibliotecario')): ?>
                                    <form method="post" action="<?= htmlspecialchars(url('prestamos/devolver/' . $prestamo['id'])) ?>" class="d-inline-block">
                                        <button type="submit" class="btn btn-sm btn-outline-success">Marcar devolucion</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-secondary small">Sin acciones</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
