<section class="mb-4">
    <div class="hero-card">
        <span class="eyebrow mb-3">Prestamos</span>
        <h1 class="section-title"><?= htmlspecialchars($title ?? 'Prestamos activos') ?></h1>
        <p class="section-subtitle">Monitorea prestamos activos, fechas de devolucion y acciones disponibles por usuario.</p>
        <?php if ($isBibliotecario ?? false): ?>
            <a class="btn btn-success" href="<?= htmlspecialchars(url('prestamos/crear')) ?>">Nuevo prestamo</a>
        <?php endif; ?>
    </div>
</section>

<section class="table-card p-4">
    <?php if (empty($loans)): ?>
        <div class="empty-state">
            <h2 class="h4 mb-2">Sin prestamos activos</h2>
            <p class="text-secondary mb-0">Los prestamos activos apareceran aqui junto con su estado.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Libro</th>
                        <th>Prestado a</th>
                        <th>Fecha de prestamo</th>
                        <th>Fecha de devolucion</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($loans as $loan): ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($loan['libro']) ?></td>
                            <td><?= htmlspecialchars($loan['estudiante']) ?></td>
                            <td><?= htmlspecialchars($loan['fecha_prestamo']) ?></td>
                            <td><?= htmlspecialchars($loan['fecha_devolucion']) ?></td>
                            <td><span class="badge <?= htmlspecialchars(status_badge_class($loan['estado'])) ?>"><?= htmlspecialchars($loan['estado']) ?></span></td>
                            <td>
                                <?php if (($isBibliotecario ?? false) && $loan['estado'] === 'Activo'): ?>
                                    <a class="btn btn-sm btn-outline-danger me-2" href="<?= htmlspecialchars(url('prestamos/devolver/' . $loan['id'])) ?>">Devolver</a>
                                    <a class="btn btn-sm btn-outline-warning" href="<?= htmlspecialchars(url('sanciones/crear?estudiante_id=' . $loan['estudiante_id'])) ?>">Sancionar</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
