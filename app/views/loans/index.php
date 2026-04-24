<section class="mb-4">
    <div class="hero-card">
        <span class="eyebrow mb-3">Vista General</span>
        <h1 class="section-title">Prestamos activos</h1>
        <p class="section-subtitle">Resumen simplificado de los libros actualmente prestados.</p>
    </div>
</section>

<section class="table-card p-4">
    <?php if (empty($loans)): ?>
        <div class="empty-state">
            <p class="mb-0">No hay prestamos registrados.</p>
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
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($loans as $loan): ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($loan['libro']) ?></td>
                            <td><?= htmlspecialchars($loan['estudiante']) ?></td>
                            <td><?= htmlspecialchars($loan['fecha_prestamo']) ?></td>
                            <td><?= htmlspecialchars($loan['fecha_devolucion'] ?? 'Pendiente') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
